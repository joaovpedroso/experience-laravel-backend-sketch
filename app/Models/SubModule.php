<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubModule extends Model
{
    protected $fillable = ['name','module_id','position','status', 'url'];
}
