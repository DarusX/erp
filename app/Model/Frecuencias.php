<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Frecuencias extends Model
{
    protected $table = 'agr_frecuencias_trabajos';

    protected $fillable = [
        'id',
        'frecuencia',
        'dias_suma',
        'estatus'
    ];

}
