<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VehiculosGastosSucursal extends Model
{
    protected $table = "vehiculos_gastos_sucursales";

    protected $primarykey = "id_gasto_vehiculo";

    protected $fillable = [
        'id_vehiculo',
        'id_gasto_sucursal',
        'id_orden'
    ];
}
