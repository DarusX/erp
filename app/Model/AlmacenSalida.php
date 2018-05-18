<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenSalida extends Model
{
    protected $table = 'almacen_salida';
    public $primaryKey = 'id_almacen_salida';
    public $timestamps = false;
}