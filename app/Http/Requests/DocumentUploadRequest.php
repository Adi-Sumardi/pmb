<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

class DocumentUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'document_type' => 'required|string|max:100',
            'document_name' => 'required|string|max:255',
            'file' => [
                'required',
                'file',
                File::types(['pdf', 'jpg', 'jpeg', 'png'])
                    ->max(2048) // 2MB max
                    ->rules(['mimes:pdf,jpg,jpeg,png']),
            ],
            'description' => 'nullable|string|max:500'
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'file.required' => 'File dokumen wajib diupload.',
            'file.file' => 'File yang diupload tidak valid.',
            'file.mimes' => 'File harus berformat PDF, JPG, JPEG, atau PNG.',
            'file.max' => 'Ukuran file maksimal 2MB.',
            'document_type.required' => 'Jenis dokumen wajib dipilih.',
            'document_name.required' => 'Nama dokumen wajib diisi.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('file')) {
                $file = $this->file('file');

                // Additional security checks
                if (!$this->isValidFileContent($file)) {
                    $validator->errors()->add('file', 'File tidak valid atau mengandung konten berbahaya.');
                }

                if (!$this->isValidFileSize($file)) {
                    $validator->errors()->add('file', 'Ukuran file terlalu besar.');
                }
            }
        });
    }

    /**
     * Validate file content for security
     */
    private function isValidFileContent($file): bool
    {
        $filePath = $file->getPathname();
        $mimeType = mime_content_type($filePath);

        // Check MIME type
        $allowedMimes = [
            'application/pdf',
            'image/jpeg',
            'image/jpg',
            'image/png'
        ];

        if (!in_array($mimeType, $allowedMimes)) {
            return false;
        }

        // Check file signature/magic bytes
        $fileHandle = fopen($filePath, 'rb');
        if (!$fileHandle) {
            return false;
        }

        $header = fread($fileHandle, 10);
        fclose($fileHandle);

        // PDF signature
        if ($mimeType === 'application/pdf') {
            return strpos($header, '%PDF') === 0;
        }

        // JPEG signatures
        if (in_array($mimeType, ['image/jpeg', 'image/jpg'])) {
            return (bin2hex(substr($header, 0, 3)) === 'ffd8ff');
        }

        // PNG signature
        if ($mimeType === 'image/png') {
            return (bin2hex(substr($header, 0, 8)) === '89504e470d0a1a0a');
        }

        return true;
    }

    /**
     * Additional file size validation
     */
    private function isValidFileSize($file): bool
    {
        // 2MB limit
        return $file->getSize() <= 2097152;
    }
}