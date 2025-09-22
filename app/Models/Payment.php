<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Payment extends Model
{
    use HasFactory;

    // Payment status constants
    const STATUS_PENDING = 'PENDING';
    const STATUS_PAID = 'PAID';
    const STATUS_EXPIRED = 'EXPIRED';
    const STATUS_FAILED = 'FAILED';
    const STATUS_CANCELLED = 'CANCELLED';

    protected $fillable = [
        'pendaftar_id',
        'external_id',
        'invoice_id',
        'invoice_url',
        'amount',
        'status',
        'expires_at',
        'paid_at',
        'expired_at',
        'failed_at',
        'xendit_response',
        'metadata',
    ];

    protected $casts = [
        'xendit_response' => 'array',
        'metadata' => 'array',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'failed_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    protected $dates = [
        'expires_at',
        'paid_at',
        'expired_at',
        'failed_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Relationship with Pendaftar
     */
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    /**
     * Check if payment is expired
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    /**
     * Check if payment is paid
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if payment is failed
     */
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Check if payment is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Check if payment is still active (pending and not expired)
     */
    public function isActive(): bool
    {
        return $this->isPending() && !$this->isExpired();
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            self::STATUS_PENDING => 'bg-warning text-dark',
            self::STATUS_PAID => 'bg-success',
            self::STATUS_EXPIRED => 'bg-secondary',
            self::STATUS_FAILED => 'bg-danger',
            self::STATUS_CANCELLED => 'bg-dark'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    /**
     * Get status label for UI
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_PAID => 'Berhasil',
            self::STATUS_EXPIRED => 'Kadaluarsa',
            self::STATUS_FAILED => 'Gagal',
            self::STATUS_CANCELLED => 'Dibatalkan'
        ];

        return $labels[$this->status] ?? 'Tidak Diketahui';
    }

    /**
     * Get status icon for UI
     */
    public function getStatusIconAttribute(): string
    {
        $icons = [
            self::STATUS_PENDING => 'bi-clock-history',
            self::STATUS_PAID => 'bi-check-circle-fill',
            self::STATUS_EXPIRED => 'bi-x-circle-fill',
            self::STATUS_FAILED => 'bi-exclamation-triangle-fill',
            self::STATUS_CANCELLED => 'bi-dash-circle-fill'
        ];

        return $icons[$this->status] ?? 'bi-question-circle';
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        if ($this->amount === null) {
            return 'Rp 0';
        }

        return 'Rp ' . number_format((float)$this->amount, 0, ',', '.');
    }

    /**
     * Get remaining time before expiry
     */
    public function getRemainingTimeAttribute(): ?string
    {
        if (!$this->expires_at || $this->isExpired()) {
            return null;
        }

        return $this->expires_at->diffForHumans();
    }

    /**
     * Get time until expiry in minutes
     */
    public function getMinutesUntilExpiryAttribute(): ?int
    {
        if (!$this->expires_at || $this->isExpired()) {
            return null;
        }

        return now()->diffInMinutes($this->expires_at);
    }

    /**
     * Check if payment is about to expire (less than 10 minutes)
     */
    public function isAboutToExpire(): bool
    {
        $minutes = $this->minutes_until_expiry;
        return $minutes !== null && $minutes <= 10;
    }

    /**
     * Get payment method from xendit response
     */
    public function getPaymentMethodAttribute(): ?string
    {
        if (!$this->xendit_response) {
            return null;
        }

        return $this->xendit_response['payment_method'] ??
               $this->xendit_response['payment_channel'] ??
               null;
    }

    /**
     * Get payment channel from xendit response
     */
    public function getPaymentChannelAttribute(): ?string
    {
        if (!$this->xendit_response) {
            return null;
        }

        return $this->xendit_response['payment_channel'] ?? null;
    }

    /**
     * Mark payment as expired
     */
    public function markAsExpired(): bool
    {
        return $this->update([
            'status' => self::STATUS_EXPIRED,
            'expired_at' => now()
        ]);
    }

    /**
     * Mark payment as paid
     */
    public function markAsPaid(): bool
    {
        return $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now()
        ]);
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed(): bool
    {
        return $this->update([
            'status' => self::STATUS_FAILED,
            'failed_at' => now()
        ]);
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for paid payments
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Scope for expired payments
     */
    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    /**
     * Scope for failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Scope for active payments (pending and not expired)
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_PENDING)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope for payments that should be expired
     */
    public function scopeShouldBeExpired($query)
    {
        return $query->where('status', self::STATUS_PENDING)
                    ->where('expires_at', '<=', now());
    }

    /**
     * Scope for recent payments
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for payments by pendaftar
     */
    public function scopeByPendaftar($query, $pendaftarId)
    {
        return $query->where('pendaftar_id', $pendaftarId);
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto set expires_at when creating new payment
        static::creating(function ($payment) {
            if (!$payment->expires_at) {
                $payment->expires_at = now()->addHour();
            }
        });

        // Log status changes
        static::updating(function ($payment) {
            if ($payment->isDirty('status')) {
                Log::info('Payment status changed', [
                    'payment_id' => $payment->id,
                    'external_id' => $payment->external_id,
                    'old_status' => $payment->getOriginal('status'),
                    'new_status' => $payment->status,
                ]);
            }
        });
    }

    /**
     * Convert model to array with additional computed fields
     */
    public function toArray()
    {
        $array = parent::toArray();

        // Add computed attributes
        $array['status_badge'] = $this->status_badge;
        $array['status_label'] = $this->status_label;
        $array['status_icon'] = $this->status_icon;
        $array['formatted_amount'] = $this->formatted_amount;
        $array['remaining_time'] = $this->remaining_time;
        $array['is_expired'] = $this->isExpired();
        $array['is_active'] = $this->isActive();
        $array['is_about_to_expire'] = $this->isAboutToExpire();

        return $array;
    }
}
