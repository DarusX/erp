<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Tests\RequestContentProxy;

class Contratos extends Model
{

    protected $table = "contratos";

    protected $fillable = [
        "id_solicitante",
        "folio_contrato",
        "tonelaje",
        "precio",
        "empleado_captura_id",
        "fecha_captura",
        "empleado_edita_id",
        "fecha_edita",
        "empleado_autoriza_id",
        "fecha_autoriza",
        "id_solicitante_respaldo",
        "folio_contrato_respaldo",
        "tonelaje_respaldo",
        "precio_respaldo",
        "estatus",
        "tipo"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("contratos_solicitantes as cs", "cs.id", "=", "contratos.id_solicitante");
        $query->leftJoin("contratos_solicitantes as csr", "csr.id", "=", "contratos.id_solicitante_respaldo");
        $query->leftJoin("rh_empleados as ec", "ec.id_empleado", "=", "contratos.empleado_captura_id");
        $query->leftJoin("rh_empleados as ee", "ee.id_empleado", "=", "contratos.empleado_edita_id");
        $query->leftJoin("rh_empleados as ea", "ea.id_empleado", "=", "contratos.empleado_autoriza_id");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "cs.id_sucursal");

        $query->select(
            "contratos.*",
            \DB::raw("ifnull(cs.folio_solicitante,'S/R') as folio_solicitante"),
            \DB::raw("ifnull(contratos.folio_contrato,'S/R') as folio_contrato"),
            \DB::raw("ifnull(contratos.folio_contrato_respaldo,'S/R') as folio_contrato_respaldo"),
            \DB::raw("ifnull(s.nombre,'S/R') as sucursal"),
            \DB::raw("ifnull((contratos.tonelaje / 1000),0) as tonelaje"),
            \DB::raw("ifnull(csr.folio_solicitante,'S/R') as folio_solicitante_respaldo"),
            \DB::raw("ifnull(contratos.precio,0) as precio"),
            \DB::raw("ifnull(contratos.tonelaje_respaldo,0) as tonelaje_respaldo"),
            \DB::raw("ifnull(contratos.tipo,'') as tipo"),
            \DB::raw("ifnull(contratos.precio_respaldo,0) as precio_respaldo"),
            \DB::raw("ifnull(concat(ec.nombre, ' ', ec.apaterno, ' ', ec.amaterno),'S/R') as empleado_captura"),
            \DB::raw("ifnull(concat(ee.nombre, ' ', ee.apaterno, ' ', ee.amaterno),'S/R') as empleado_edita"),
            \DB::raw("ifnull(concat(ea.nombre, ' ', ea.apaterno, ' ', ea.amaterno),'S/R') as empleado_autoriza"),
            \DB::raw("ocupadoContrato(contratos.id) as ocupado")
        );

        if (!empty($datos["id_contrato"])){

            $query->where("contratos.id", $datos["id_contrato"]);

        }

        if (!empty($datos["id_solicitante"])){

            $query->where("contratos.id_solicitante", $datos["id_solicitante"]);

        }

        if (!empty($datos["id_sucursal"])){

            $query->whereIn("cs.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["estatus"])){

            $query->where("contratos.estatus", $datos["estatus"]);

        }

        if (!empty($datos["first"])){

            //dd($query->toSql());
            return $query->first();

        }

        //dd($query->toSql());
        return $query->get();

    }

    public function buscarRemisiones($datos)
    {

        $query = $this->leftJoin("remisiones_ordenes_compra as r", "r.id_contrato", "=", "contratos.id");
        $query->leftJoin("compras_ordenes as co", "co.id_orden", "=", "r.id_orden");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "co.id_sucursal");
        $query->leftJoin("compras_ordenes_descripcion as cod", "cod.id_orden_descripcion", "=", "r.id_orden_descripcion");
        $query->leftJoin("vehiculos_viajes_ordenes_detalles as vvd", "vvd.id_orden_descripcion", "=", "cod.id_orden_descripcion");
        $query->leftJoin("vehiculos_viajes_ordenes as vo", "vo.id_vehiculo_viaje_orden", "=", "vvd.id_vehiculo_viaje_orden");
        $query->leftJoin("rh_empleados as chofer", "chofer.id_empleado", "=", "vo.id_chofer");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "cod.id_almacen");
        $query->leftJoin("productos as p", "p.id_producto", "=", "cod.id_producto");
        $query->leftJoin("productos_unidades_medida as puc", "puc.id_unidad_medida", "=", "p.unidad_compra");
        $query->leftJoin("productos_unidades_medida as puv", "puv.id_unidad_medida", "=", "p.unidad_venta");
        $query->leftJoin("contratos_solicitantes as cs", "cs.id", "=", "contratos.id_solicitante");
        $query->leftJoin("cat_sucursales as css", "css.id_sucursal", "=", "cs.id_sucursal");
        
        $query->select(
            "contratos.*",
            "r.id_orden",
            "r.id as id_remision",
            \DB::raw("ifnull(r.remision,'') as remision"),
            \DB::raw("ifnull(r.pedido,'') as pedido"),
            \DB::raw("ifnull(r.factura,'') as factura"),
            \DB::raw("ifnull(r.fecha,'') as fecha"),
            \DB::raw("ifnull(r.monto,'') as monto"),
            "r.cantidad as cantidad_remision",
            "r.estatus_remision",
            \DB::raw("ifnull(r.tonelaje,0) as tonelaje_remision"),
            //"co.id_orden",
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
            \DB::raw("ifnull(concat(chofer.nombre, ' ', chofer.apaterno, ' ', chofer.amaterno),'') as nombre_chofer")
        );

        if (!empty($datos["id_contrato"])){

            $query->where("contratos.id", $datos["id_contrato"]);

        }

        if (!empty($datos["id_sucursal"])){

            $query->whereIn("co.id_sucursal", $datos["id_sucursal"]);

        }

        $query->groupBy("r.id");

        //dd($query->toSql());

        return $query->get();

    }

}