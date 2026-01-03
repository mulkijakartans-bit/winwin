<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    protected $fillable = [
        'name', 'default_price', 'description', 'is_active', 'order'
    ];

    protected $casts = [
        'default_price' => 'decimal:2',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope a query to only include active add-ons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
