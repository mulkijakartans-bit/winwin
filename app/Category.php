<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'icon', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all packages in this category.
     */
    public function packages()
    {
        return $this->belongsToMany(MakeupPackage::class, 'package_categories');
    }
}

