<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageAddOn extends Model
{
    protected $fillable = [
        'package_id', 'name', 'description', 'price', 'is_active', 'order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the package that owns this add-on.
     */
    public function package()
    {
        return $this->belongsTo(MakeupPackage::class);
    }

    /**
     * Scope a query to only include active add-ons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order add-ons.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }
}
