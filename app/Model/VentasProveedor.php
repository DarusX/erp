<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VentasProveedor extends Model
{
    protected $table = 'ventas_proveedores';
    public $timestamps = false;
    protected $primaryKey = "id_venta_proveedor";
}