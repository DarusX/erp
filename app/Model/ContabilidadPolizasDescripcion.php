<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ContabilidadPolizasDescripcion extends Model
{
    protected $table = "contabilidad_polizas_descripcion";
    protected $primaryKey = "id_poliza";
    public $timestamps = false;
}
