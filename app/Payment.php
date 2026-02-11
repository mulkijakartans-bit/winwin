<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    protected $fillable = [
        'booking_id', 'payment_code', 'payment_method', 'amount',
        'payment_proof', 'status', 'rejection_reason', 'verified_by',
        'verified_at', 'notes', 'external_id', 'checkout_link',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_code)) {
                $payment->payment_code = 'PAY' . strtoupper(Str::random(8));
            }
        });
    }

    /**
     * Get the booking that owns the payment.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who verified this payment.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope a query to only include pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include verified payments.
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }
}

