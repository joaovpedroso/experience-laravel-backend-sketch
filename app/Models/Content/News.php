<?php

namespace App\Models\Content;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class News extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'news';

    protected $fillable = [
        'slug', 'date', 'title',  'text',
        'source', 'video', 'audio', 'happened', 'featured', 'status',
        'comment_photo', 'comment_title', 'comment_text', 'comment_description','editorial_id'
    ];

    protected $dates = [
        'deleted_at', 'happened', 'date'
    ];

    /**
     * Mutators!
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat('d/m/Y H:i', $value)
            ->format('Y-m-d H:i:s');
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y H:i');
    }

    /**
     * Scopes!
     */
    public function scopeSlug($query, $slug = null)
    {
        if ( ! is_null($slug)) {
            $query->where('slug', $slug);
        }
    }

    public function scopeDateFromTo($query, $start_date = null, $end_date = null)
    {
        if ( ! is_null($start_date)) {
            $start_date = Carbon::createFromFormat('d/m/Y', $start_date)->format('Y-m-d');

            $query->where('created_at', '>=', $start_date);
        }

        if ( ! is_null($end_date)) {
            $end_date = Carbon::createFromFormat('d/m/Y', $end_date)->format('Y-m-d');

            $query->where('created_at', '<=', $end_date);
        }
    }

    public function scopeEditorial($query, $editorial = null)
    {
        if ( ! is_null($editorial)) {
            $query->where('editorial_id', $editorial);
        }
    }

    public function scopeKeywords($query, $keywords = null)
    {

        if ( ! is_null($keywords)) {
            $keywords = str_replace(' ', '%', $keywords);

            return $query->where('title', 'like', "%$keywords%");
        }
    }

    public function scopeOwn($query, $own = null)
    {
        if ( ! is_null($own)) {
            $query->where('own', $own);
        }
    }

    /**
     * Foreign Keys!
     */
    public function photos()
    {
        return $this->hasMany('App\Models\Content\NewsPhoto');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Content\NewsTag', 'news_tags_relation', 'news_id', 'news_tag_id');
    }

    /**
     * Functions!
     */
    public function getLog()
    {
        $logs = [];

        foreach ($this->activity as $log) {
            $logs[] = trans('logs.news', [
                'description' => trans('logs.'.$log->description),
                'causer' => $log->causer->name,
                'date' => $log->created_at->format('d/m/Y \à\s H:i')
            ]);
        }

        return implode('<br/>', $logs);
    }
}
