<?php

namespace App\Repositories;

use App\Models\Usuarios;
use InfyOm\Generator\Common\BaseRepository;

class UsuariosRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email',
        'password',
        'remember_token'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Usuarios::class;
    }
}
