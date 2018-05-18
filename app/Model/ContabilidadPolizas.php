<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ContabilidadPolizas extends Model
{
    protected $table = "contabilidad_polizas";
    protected $primaryKey = "id_poliza";
    public $timestamps = false;

}
