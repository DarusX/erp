<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenAjusteDescripcion extends Model
{
    protected $table = 'almacenes_ajustes_descripcion';
    public $timestamps = false;
    protected $primaryKey = 'id_ajuste_descripcion';
}
