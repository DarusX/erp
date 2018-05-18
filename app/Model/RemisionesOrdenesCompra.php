<?php

namespace App\Model;

use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;

class RemisionesOrdenesCompra extends Model
{

    protected $table = "remisiones_ordenes_compra";

    protected $fillable = [
        "remision",
        "pedido",
        "factura",
        "fecha",
        "monto",
        "cantidad",
        "tonelaje",
        "id_orden",
        "id_orden_descripcion",
        "id_contrato",
        "estatus_remision"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("compras_ordenes as oc", "oc.id_orden", "=", "remisiones_ordenes_compra.id_orden");
        $query->leftJoin("compras_ordenes_descripcion as ocd", "ocd.id_orden_descripcion", "=", "remisiones_ordenes_compra.id_orden_descripcion");
        $query->leftJoin("contratos as c", "c.id", "=", "remisiones_ordenes_compra.id_contrato");
        $query->leftJoin("contratos_solicitantes as s", "s.id", "=", "c.id_solicitante");

        $query->select(
            "remisiones_ordenes_compra.*",
            \DB::raw("ifnull(remisiones_ordenes_compra.remision,'') as remision"),
            \DB::raw("ifnull(remisiones_ordenes_compra.pedido,'') as pedido"),
            \DB::raw("ifnull(remisiones_ordenes_compra.factura,'') as factura"),
            \DB::raw("ifnull(remisiones_ordenes_compra.fecha,'') as fecha"),
            \DB::raw("ifnull(remisiones_ordenes_compra.monto,0) as monto"),
            "c.folio_contrato",
            "c.precio",
            "s.folio_solicitante"
        );

        if (!empty($datos["id_orden"])){

            $query->where("remisiones_ordenes_compra.id_orden", $datos["id_orden"]);

        }

        if (!empty($datos["id_orden_descripcion"])){

            $query->where("remisiones_ordenes_compra.id_orden_descripcion", $datos["id_orden_descripcion"]);

        }

        if (!empty($datos["estatus"])){

            $query->where("remisiones_ordenes_compra.estatus_remision", $datos["estatus"]);

        }

        if (!empty($datos["id_sucursal"])){

            $query->whereIn("s.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_contrato"])){

            if (count($datos["id_contrato"]) > 1){

                $query->whereIn("c.id", $datos["id_contrato"]);

            } else {

                $query->where("c.id", $datos["id_contrato"]);

            }

        }

        return $query->get();

    }
    
    public function buscarRemisiones($datos){

        $query = $this->leftJoin("contratos as c", "c.id", "=", "remisiones_ordenes_compra.id_contrato");
        $query->leftJoin("compras_ordenes as co", "co.id_orden", "=", "remisiones_ordenes_compra.id_orden");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "co.id_sucursal");
        $query->leftJoin("compras_ordenes_descripcion as cod", "cod.id_orden_descripcion", "=", "remisiones_ordenes_compra.id_orden_descripcion");
        $query->leftJoin("vehiculos_viajes_ordenes_detalles as vvd", "vvd.id_orden_descripcion", "=", "cod.id_orden_descripcion");
        $query->leftJoin("vehiculos_viajes_ordenes as vo", "vo.id_vehiculo_viaje_orden", "=", "vvd.id_vehiculo_viaje_orden");
        $query->leftJoin("rh_empleados as chofer", "chofer.id_empleado", "=", "vo.id_chofer");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "cod.id_almacen");
        $query->leftJoin("productos as p", "p.id_producto", "=", "cod.id_producto");
        $query->leftJoin("productos_unidades_medida as puc", "puc.id_unidad_medida", "=", "p.unidad_compra");
        $query->leftJoin("productos_unidades_medida as puv", "puv.id_unidad_medida", "=", "p.unidad_venta");
        $query->leftJoin("contratos_solicitantes as cs", "cs.id", "=", "c.id_solicitante");
        $query->leftJoin("cat_sucursales as css", "css.id_sucursal", "=", "cs.id_sucursal");

        $query->select(
            \DB::raw("ifnull(c.folio_contrato,'S/R') as folio_contrato"),
            "remisiones_ordenes_compra.*",
            \DB::raw("ifnull(remisiones_ordenes_compra.remision,'') as remision"),
            \DB::raw("ifnull(remisiones_ordenes_compra.pedido,'') as pedido"),
            \DB::raw("ifnull(remisiones_ordenes_compra.factura,'') as factura"),
            \DB::raw("ifnull(remisiones_ordenes_compra.fecha,'') as fecha"),
            \DB::raw("ifnull(remisiones_ordenes_compra.monto,'') as monto"),
            \DB::raw("ifnull(remisiones_ordenes_compra.tonelaje,0) as tonelaje_remision"),
            "co.id_orden",
            "co.estatus as estatus_orden",
            "co.id_sucursal",
            "s.nombre as sucursal",
            "cod.cantidad",
            "cod.id_almacen",
            "a.almacen",
            "cs.folio_solicitante",
            \DB::raw("ifnull(cs.id_sucursal,'') as id_sucursal_solicitante"),
            \DB::raw("ifnull(css.nombre,'') as sucursal_solicitante"),
            "p.codigo_producto",
            "p.descripcion",
            "p.peso",
            "puc.unidad_medida as unidad_compra",
            "puv.unidad_medida as unidad_venta",
            \DB::raw("ifnull(vvd.id_vehiculo_viaje_orden,'') as id_viaje"),
            \DB::raw("ifnull(concat(chofer.nombre, ' ', chofer.apaterno, ' ', chofer.amaterno),'') as nombre_chofer"),
            "remisiones_ordenes_compra.estatus_remision"
        );

        if (!empty($datos["id_contrato"])){

            if (count($datos["id_contrato"]) > 1){

                $query->whereIn("c.id", $datos["id_contrato"]);

            } else {

                $query->where("c.id", $datos["id_contrato"]);

            }

        }

        if (!empty($datos["id_sucursal"])){

            $query->whereIn("co.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_orden"])){

            $query->where("remisiones_ordenes_compra.id_orden", $datos["id_orden"]);

        }

        if (!empty($datos["id_vehiculo"])){

            $query->whereIn("vo.id_vehiculo", $datos["id_vehiculo"]);

        }

        if (!empty($datos["chofer"])){

            $query->where("chofer.nombre", "like", "%". $datos["chofer"] ."%");

        }

        if (!empty($datos["estatus_remision"])) {

            $query->where("remisiones_ordenes_compra.estatus_remision", "!=", "cancelado");

        }

        //dd($query->toSql());

        return $query->get();
        
    }

    public function buscarOrdenes($datos)
    {

        $query = $this->from("remisiones_ordenes_compra as r");
        $query->leftJoin("compras_ordenes as oc", "oc.id_orden", "=", "r.id_orden");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "oc.id_sucursal");

        $query->select(
            "r.id_orden",
            "oc.id_sucursal",
            "s.nombre as sucursal",
            \DB::raw("ifnull(SUM(r.tonelaje),0) as tonelaje_oc")
        );

        if (!empty($datos["id_contrato"])){
            $query->where("r.id_contrato", $datos["id_contrato"]);
        }

        $query->where("r.estatus_remision", "!=", "cancelado");

        $query->groupBy("r.id_orden");

        return $query->get();

    }

}
