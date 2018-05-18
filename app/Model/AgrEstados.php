<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgrEstados extends Model
{
    protected $table = 'agr_estados';

    protected $fillable = [
        'id',
        'estado'
    ];
}
