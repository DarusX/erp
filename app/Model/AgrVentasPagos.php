<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AgrVentasPagos extends Model
{

    protected $table = "agr_ventas_pagos";

    protected $fillable = [
        "venta_id",
        "clave_fiscal"
    ];

}
