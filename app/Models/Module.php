<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['name', 'url', 'position', 'icon' , 'slug', 'status'];

    public function submodules()
    {
        return $this->hasMany('App\Models\SubModule')
            ->where('status', '=', 'Ativo')
            ->orderBy('position', 'asc');
    }
}
