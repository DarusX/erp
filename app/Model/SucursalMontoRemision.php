<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SucursalMontoRemision extends Model
{

    protected $table = "sucursales_monto_remision";

    protected $fillable = [
        "monto_remision_id",
        "sucursal_id",
        "monto_sucursal"
    ];

}
