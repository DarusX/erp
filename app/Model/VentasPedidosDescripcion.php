<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VentasPedidosDescripcion extends Model
{

    protected $table = "ventas_pedidos_descripcion";

    protected $primaryKey = "id_pedido_descripcion";

    protected $fillable = [
        "id_pedido",
        "id_producto",
        "cantidad",
        "precio",
        "id_almacen",
        "iva",
        "porcentaje_envio",
        "porcentaje_iva"

    ];

    public function buscar($datos)
    {

        $query = $this->from("ventas_pedidos_descripcion as vpd");
        $query->leftJoin("ventas_pedidos as vp", "vp.id_pedido", "=", "vpd.id_pedido");
        $query->leftJoin("productos as p", "p.id_producto", "=", "vpd.id_producto");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "vpd.id_almacen");

        $query->select(
            "vpd.*",
            "p.codigo_producto",
            "p.descripcion",
            "a.almacen",
            \DB::raw("ifnull((vpd.cantidad * vpd.precio),0) as subtotal"),
            \DB::raw("ifnull(((vpd.cantidad * vpd.precio) * vpd.porcentaje_iva),0) as iva")
        );
        
        $query->where("vpd.id_pedido", $datos["id_pedido"]);
        
        //dd($query->toSql());
        
        return $query->get();

    }

}
