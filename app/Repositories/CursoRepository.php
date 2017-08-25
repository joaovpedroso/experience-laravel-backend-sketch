<?php

namespace App\Repositories;

use App\Models\Curso;
use InfyOm\Generator\Common\BaseRepository;

class CursoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'file'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Curso::class;
    }
}
