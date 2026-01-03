<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MuaPortfolio extends Model
{
    protected $fillable = [
        'mua_profile_id', 'image', 'description', 'order', 'is_featured',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the MUA profile that owns the portfolio.
     */
    public function muaProfile()
    {
        return $this->belongsTo(MuaProfile::class);
    }
}

