<?php

namespace App\Repositories;

use App\Models\professor_user;
use InfyOm\Generator\Common\BaseRepository;

class professor_userRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'name',
        'email',
        'password'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return professor_user::class;
    }
}
