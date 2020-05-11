<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class path extends Model
{
    protected $fillable = [
        'lati1',
        'long1',
        'lati2',
        'long2'
    ];
}
