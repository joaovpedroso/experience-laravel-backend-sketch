<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    protected $fillable = [
        'name', 'zip_code', 'address', 'number', 'complement', 'phone',
        'cell_phone', 'facebook'
    ];
}
