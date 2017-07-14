<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    protected $fillable = [
        'name', 'slug'
    ];

    /**
     * Scopes!
     */
    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
}
