<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ComprasOrdenesVenta extends Model
{

    protected $table = "compras_ordenes_ventas";

    protected $fillable = [
        "id_orden",
        "id_orden_descripcion",
        "id_venta",
        "id_venta_descripcion",
        "producto_id",
        "cantidad"
    ];

    public function buscar($parametros)
    {
        $query = $this;

        if (isset($parametros['id_orden']) && $parametros['id_orden'] != "") {
            $query = $query->where('id_orden', $parametros['id_orden']);
        }

        return $query->groupBy('id_venta')
            ->get();
    }

}