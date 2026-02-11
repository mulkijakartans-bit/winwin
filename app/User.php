<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'address', 'avatar',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all bookings as customer.
     */
    public function customerBookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    /**
     * Get all reviews as customer.
     */
    public function customerReviews()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    /**
     * Get all payments verified by this user.
     */
    public function verifiedPayments()
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is customer.
     */
    public function isCustomer()
    {
        return $this->role === 'customer';
    }
}
