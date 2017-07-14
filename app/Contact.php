<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'email', 'phone', 'city_id', 'message'
    ];

    /**
     * The modules that belong to the user.
     */
    public function departments()
    {
        return $this->belongsTo('App\Department');
    }

    /**
     * The modules that belong to the user.
     */
    public function cities()
    {
        return $this->belongsTo('App\City');
    }

    public function scopeEmail($query, $email = null)
    {
        if (!is_null($email)) {
            return $query->where('email', 'like', "%$email%");
        }
    }


    public function scopeName($query, $name = null)
    {
        if (!is_null($name)) {
            $name = str_replace(' ', '%', $name);

            return $query->where('name', 'like', "%$name%");
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

}
