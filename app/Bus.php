<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'matricule',
        'nmbrPlace',
        'nmbrPlaceDebout',
        'code_line'
    ];
}
