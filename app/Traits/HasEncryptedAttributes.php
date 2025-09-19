<?php

namespace App\Traits;

use App\Services\DataEncryptionService;

trait HasEncryptedAttributes
{
    private DataEncryptionService $encryptionService;

    public function initializeHasEncryptedAttributes()
    {
        $this->encryptionService = app(DataEncryptionService::class);
    }

    /**
     * Get the list of encrypted attributes for this model
     */
    protected function getEncryptedAttributes(): array
    {
        return property_exists($this, 'encrypted') ? $this->encrypted : [];
    }

    /**
     * Encrypt attributes before saving
     */
    protected function encryptAttributes()
    {
        $encryptedAttributes = $this->getEncryptedAttributes();

        foreach ($encryptedAttributes as $attribute) {
            if (isset($this->attributes[$attribute]) && !empty($this->attributes[$attribute])) {
                $this->attributes[$attribute] = $this->encryptionService->encryptField($this->attributes[$attribute]);
            }
        }
    }

    /**
     * Decrypt attributes after retrieving
     */
    protected function decryptAttributes()
    {
        $encryptedAttributes = $this->getEncryptedAttributes();

        foreach ($encryptedAttributes as $attribute) {
            if (isset($this->attributes[$attribute]) && !empty($this->attributes[$attribute])) {
                $this->attributes[$attribute] = $this->encryptionService->decryptField($this->attributes[$attribute]);
            }
        }
    }

    /**
     * Override getAttribute to decrypt on access
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->getEncryptedAttributes()) && !empty($value)) {
            return $this->encryptionService->decryptField($value);
        }

        return $value;
    }

    /**
     * Override setAttribute to encrypt on assignment
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->getEncryptedAttributes()) && !empty($value)) {
            $value = $this->encryptionService->encryptField($value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Get raw encrypted value without decryption
     */
    public function getRawAttribute($key)
    {
        return parent::getAttribute($key);
    }

    /**
     * Get decrypted attributes for display
     */
    public function getDecryptedAttributes(): array
    {
        $attributes = $this->toArray();
        $decrypted = [];

        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->getEncryptedAttributes()) && !empty($value)) {
                $decrypted[$key] = $this->encryptionService->decryptField($value);
            } else {
                $decrypted[$key] = $value;
            }
        }

        return $decrypted;
    }
}
