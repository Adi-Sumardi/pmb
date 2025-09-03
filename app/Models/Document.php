<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id',
        'document_type',
        'document_name',
        'file_path',
        'file_size',
        'mime_type',
        'description',
        'is_verified'
    ];

    /**
     * Get the pendaftar that owns the document.
     */
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    /**
     * Check if the document is of a certain type
     *
     * @param string $type
     * @return bool
     */
    public function isType(string $type): bool
    {
        return $this->document_type === $type;
    }

    /**
     * Get formatted file size in KB, MB etc.
     *
     * @return string
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $size = $this->file_size;

        if ($size < 1024) {
            return $size . ' bytes';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return round($size / 1048576, 2) . ' MB';
        }
    }

    /**
     * Get URL for the document file
     *
     * @return string
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Get appropriate icon class based on mime type
     *
     * @return string
     */
    public function getIconClassAttribute(): string
    {
        if (strpos($this->mime_type, 'pdf') !== false) {
            return 'bi bi-file-earmark-pdf';
        } elseif (strpos($this->mime_type, 'image') !== false) {
            return 'bi bi-file-earmark-image';
        } elseif (strpos($this->mime_type, 'word') !== false || strpos($this->mime_type, 'document') !== false) {
            return 'bi bi-file-earmark-word';
        } elseif (strpos($this->mime_type, 'excel') !== false || strpos($this->mime_type, 'spreadsheet') !== false) {
            return 'bi bi-file-earmark-excel';
        } else {
            return 'bi bi-file-earmark-text';
        }
    }
}
