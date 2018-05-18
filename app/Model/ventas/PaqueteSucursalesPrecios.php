<?php

namespace App\Model\ventas;

use App\Model\sucursal;
use Illuminate\Database\Eloquent\Model;

class PaqueteSucursalesPrecios extends Model
{
    protected $table = 'ventas_paquetes_productos_sucursales_precios';
    protected $fillable = ['paquete_id', 'producto_id', 'sucursal_id', 'precio', 'descuento', 'precio_final', 'utilidad', 'precio_anterior', 'utilidad_anterior', 'costo'];

    public function buscar($datos)
    {
        $query = $this->leftJoin('ventas_paquetes as vp', 'vp.id', '=', 'ventas_paquetes_productos_sucursales_precios.paquete_id');
        $query = $query->leftJoin('cat_sucursales as s', 's.id_sucursal', '=', 'ventas_paquetes_productos_sucursales_precios.sucursal_id');
        $query = $query->leftJoin('productos as p', 'p.id_producto', '=', 'ventas_paquetes_productos_sucursales_precios.producto_id');
        //$query = $query->leftJoin('ventas_paquetes_productos as vpp', 'vpp.paquete_id','=','ventas_paquetes_productos_sucursales_precios.paquete_id');
        //$query->leftJoin('cat_sucursales as s', 's.id_sucursal', '=', 'ventas_paquetes_productos_sucursales_precios.sucursal_id');
        //$query->leftJoin('productos as p', 'p.id_producto', '=', 'ventas_paquetes_productos_sucursales_precios.producto_id');
        $query->leftJoin("ventas_paquetes_productos as vpp", function ($join) {
            $join->on("vpp.paquete_id", "=", "ventas_paquetes_productos_sucursales_precios.paquete_id")
                ->on("vpp.producto_id", "=", "ventas_paquetes_productos_sucursales_precios.producto_id");
        });


        $query = $query->select([
            \DB::raw('CONCAT(p.codigo_producto, " - ",p.descripcion) as codigo_descripcion'),
            'vp.*',
            'ventas_paquetes_productos_sucursales_precios.*',
            's.id_sucursal as sucursal',
            's.nombre as sucursal_texto',
            \DB::raw("2 as bandera"),
            'vpp.cantidad',
            'vpp.id as producto_paquete_id',
            'p.peso as peso',
            'p.codigo_producto',
            'p.descripcion'
        ]);


        if(!empty($datos['paquete_id'])){
            $query = $query->where('ventas_paquetes_productos_sucursales_precios.paquete_id', $datos['paquete_id']);
        }

        if(!empty($datos['producto_id'])){
           $query = $query->where('ventas_paquetes_productos_sucursales_precios.producto_id', $datos['producto_id']);
        }
        if(!empty($datos['sucursal_id'])){
            $query = $query->where('ventas_paquetes_productos_sucursales_precios.sucursal_id', $datos['sucursal_id']);
        }


        if (!empty($datos['paquete_id'])) {
            $query->where('ventas_paquetes_productos_sucursales_precios.paquete_id', $datos['paquete_id']);
        }

        if (!empty($datos['producto_id'])) {
            $query->where('ventas_paquetes_productos_sucursales_precios.producto_id', $datos['producto_id']);
        }
        if (!empty($datos['sucursal_id'])) {
            $query->where('ventas_paquetes_productos_sucursales_precios.sucursal_id', $datos['sucursal_id']);
        }

        if(!empty($datos['first'])){

            return $query->first();
        }


        $query = $query->groupBy('ventas_paquetes_productos_sucursales_precios.id');
        $query = $query->orderBy('ventas_paquetes_productos_sucursales_precios.sucursal_id');

        return $query->get();

    }

    public function checarKits($datos)
    {

        $query = $this->from("ventas_paquetes_productos_sucursales_precios as vpp");
        $query->leftJoin("ventas_paquetes as vp", "vp.id", "=", "vpp.paquete_id");

        $query->select("vp.id as paquete_id");

        if (!empty($datos["producto_id"])) {

            $query->where("vpp.producto_id", $datos["producto_id"]);

        }

        if (!empty($datos["sucursal_id"])) {

            $query->where("vpp.sucursal_id", $datos["sucursal_id"]);

        }

        $query->where("vp.activo", "=", "si");

        return $query->get();

    }

    public function obtenerTotales($paquete, $sucursal)
    {

        $query = $this->from("ventas_paquetes_productos_sucursales_precios as vpsp");

        $query->leftJoin("productos as p", "p.id_producto", "=", "vpsp.producto_id");
        $query->leftJoin("ventas_paquetes_productos as vpp", function ($join){
           $join->on("vpp.paquete_id", "=", "vpsp.paquete_id")
               ->on("vpp.producto_id", "=", "vpsp.producto_id");
        });

        $query->select(
            \DB::raw("SUM(vpp.cantidad * vpsp.precio) AS subtotal"),
            \DB::raw("SUM(vpp.cantidad * vpsp.precio_final) AS total"),
            \DB::raw("SUM((vpp.cantidad * vpsp.precio_final) - (vpp.cantidad * vpsp.precio)) AS iva"),
            \DB::raw("SUM(vpp.cantidad * p.peso) AS total_peso")
        );

        $query->where("vpsp.paquete_id", $paquete)
            ->where("vpsp.sucursal_id", $sucursal);

        //dd($query->toSql());
        return $query->first();

    }

}
