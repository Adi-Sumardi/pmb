<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id',
        'external_id',
        'invoice_id',
        'invoice_url',
        'amount',
        'status',
        'paid_at',
        'xendit_response',
    ];

    protected $casts = [
        'xendit_response' => 'array',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
