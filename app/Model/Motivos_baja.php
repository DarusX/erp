<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Motivos_baja extends Model
{
    protected $table = 'agr_motivo_baja';

    protected $fillable = [
        'id',
        'motivo'
    ];
}
