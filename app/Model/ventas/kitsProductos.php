<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class kitsProductos extends Model
{
    protected $table = 'ventas_paquetes_productos';
    protected $fillable = ['paquete_id', 'producto_id', 'cantidad', 'created_at', 'updated_at','precio', 'codigo_descripcion', 'descuento', 'utilidad', 'utilidad_anterior', 'precio_anterior'];

    public function buscar($datos)
    {
        $query = $this->leftJoin('ventas_paquetes as vp', 'vp.id', '=', 'ventas_paquetes_productos.paquete_id');
        $query->leftJoin('productos as p', 'p.id_producto', '=', 'ventas_paquetes_productos.producto_id');

        $query->select(
            'ventas_paquetes_productos.*',
            \DB::raw('ifnull (p_costo(p.id_producto),0) as costo'),
            \DB::raw('2 as bandera')

        );

        if(!empty($datos['paquete_id'])){
            $query->where('ventas_paquetes_productos.paquete_id', $datos['paquete_id']);
        }

        if (!empty($datos['producto_id'])) {
            $query->where('ventas_paquetes_productos.producto_id', $datos['producto_id']);
        }

        return $query->get();
    }

    public function checarKits($datos)
    {

        $query = $this->from("ventas_paquetes_productos as vpp");
        $query->leftJoin("ventas_paquetes as vp", "vp.id", "=", "vpp.paquete_id");
        $query->leftJoin("ventas_paquetes_sucursales as vps", "vps.paquete_id", "=", "vp.id");

        $query->select("vp.id as paquete_id");

        if (!empty($datos["producto_id"])) {

            $query->where("vpp.producto_id", $datos["producto_id"]);

        }

        if (!empty($datos["sucursal_id"])) {

            $query->where("vps.sucursal_id", $datos["sucursal_id"]);

        }

        $query->where("vp.activo", "=", "si");

        return $query->get();

    }

    public function buscarProductosPaquetes($datos)
    {

        $query = $this->from("ventas_paquetes_productos as vpp");
        $query->leftJoin("productos as p", "p.id_producto", "=", "vpp.producto_id");

        $query->select(
            "p.id_producto",
            "p.codigo_producto",
            "p.descripcion",
            "vpp.cantidad",
            "vpp.precio"
        );

        if (!empty($datos["paquete_id"])) {

            $query->where("vpp.paquete_id", $datos["paquete_id"]);

        }

        return $query->get();
    }
}
