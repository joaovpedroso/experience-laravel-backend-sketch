<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configurate extends Model
{
    protected $fillable = [
        'banner_largura_max_recomendada', 'banner_altura_max_recomendada', 'noticia_fotos_destaque', 'noticia_categoria'
    ];
}
