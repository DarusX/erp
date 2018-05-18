<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TrabajosRecurrentes extends Model
{
    protected $table = 'agr_trabajos_recurrentes';

    protected $fillable = [
        'id',
        'trabajo',
        'frecuencia_id',
        'fecha_ini',
        'fecha_fin',
        'responsable_id'
    ];
}
