<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MuaProfile extends Model
{
    protected $fillable = [
        'name', 'bio', 'experience_years', 'specialization', 
        'rating', 'total_reviews', 'instagram', 
        'facebook', 'whatsapp', 'email', 'phone', 'cover_photo', 'hero_image', 'login_background',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'total_reviews' => 'integer',
        'experience_years' => 'integer',
    ];

    /**
     * Get all portfolios for this MUA.
     */
    public function portfolios()
    {
        return $this->hasMany(MuaPortfolio::class);
    }

    /**
     * Get all packages for this MUA.
     */
    public function packages()
    {
        return $this->hasMany(MakeupPackage::class);
    }

    /**
     * Get all bookings for this MUA.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all reviews for this MUA.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all schedules for this MUA.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get featured portfolios.
     */
    public function featuredPortfolios()
    {
        return $this->portfolios()->where('is_featured', true);
    }

    /**
     * Get active packages.
     */
    public function activePackages()
    {
        return $this->packages()->where('is_active', true);
    }

    /**
     * Get the single MUA profile instance (WINWIN Makeup).
     */
    public static function getWinwinProfile()
    {
        return static::firstOrCreate(['id' => 1], [
            'name' => 'WINWIN Makeup',
        ]);
    }
}
