<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Vacante extends Model
{
    protected $table = 'vacantes';
    protected $primaryKey = 'id_vacante';
    protected $fillable = [
        'id_puesto_sucursal'
    ];
}
