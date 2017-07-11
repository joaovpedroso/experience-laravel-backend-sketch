<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CursoVideo extends Model
{
    protected $fillable = [
        'link', 'titulo', 'descricao', 'curso_id'
    ];
}
