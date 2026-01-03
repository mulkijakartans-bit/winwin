<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MakeupPackage extends Model
{
    protected $fillable = [
        'mua_profile_id', 'name', 'description', 'price', 
        'duration', 'includes', 'images', 'is_active', 'order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer',
        'is_active' => 'boolean',
        'order' => 'integer',
        'images' => 'array',
    ];

    /**
     * Get the MUA profile that owns the package.
     */
    public function muaProfile()
    {
        return $this->belongsTo(MuaProfile::class);
    }

    /**
     * Get all categories for this package.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'package_categories');
    }

    /**
     * Get all bookings for this package.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all add-ons for this package.
     */
    public function addOns()
    {
        return $this->hasMany(PackageAddOn::class)->ordered();
    }

    /**
     * Get active add-ons for this package.
     */
    public function activeAddOns()
    {
        return $this->hasMany(PackageAddOn::class)->active()->ordered();
    }
}

