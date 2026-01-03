<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'booking_id', 'customer_id', 'rating',
        'comment', 'images', 'is_visible',
    ];

    protected $casts = [
        'rating' => 'integer',
        'images' => 'array',
        'is_visible' => 'boolean',
    ];

    /**
     * Get the booking that owns the review.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the customer who made the review.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the MUA profile (always WINWIN Makeup).
     */
    public function muaProfile()
    {
        return MuaProfile::getWinwinProfile();
    }

    /**
     * Scope a query to only include visible reviews.
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Update MUA profile rating after review is created/updated.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($review) {
            $review->updateMuaRating();
        });

        static::deleted(function ($review) {
            $review->updateMuaRating();
        });
    }

    /**
     * Update the MUA profile rating.
     */
    public function updateMuaRating()
    {
        $muaProfile = MuaProfile::getWinwinProfile();
        $reviews = Review::where('is_visible', true)->get();
        $totalReviews = $reviews->count();
        $averageRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;

        $muaProfile->update([
            'rating' => round($averageRating, 2),
            'total_reviews' => $totalReviews,
        ]);
    }
}
