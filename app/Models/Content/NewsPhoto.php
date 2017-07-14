<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class NewsPhoto extends Model
{

    protected $fillable = [
        'news_id', 'file', 'subtitle', 'featured', 'order'
    ];

    /**
     * Scopes!
     */
    public function scopeFromNews($query, $id)
    {
        $query->where('news_id', $id);
    }
}
