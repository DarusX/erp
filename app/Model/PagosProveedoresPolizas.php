<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PagosProveedoresPolizas extends Model
{
    protected $table = "compras_ordenes_facturas_pagos_proveedores_polizas";
    protected $primaryKey = "id_proveedor_pago_poliza";
    public $timestamps = false;
}
