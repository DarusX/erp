<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ComprasSolicitudes extends Model
{
    protected $table = "compras_solicitudes";

    protected $primaryKey = "id_solicitud";

    protected $fillable = [
        "id_producto",
        "id_almacen",
        "id_sucursal",
        "id_unidad_medida",
        "id_usuario",
        "cantidad",
        "cantidad_nueva",
        "fecha_solicitud",
        "justificacion",
        "fecha_limite",
        "fecha_autorizacion",
        "responsable",
        "id_proveedor",
        "estatus",
        "dias_analisis",
        "rotacion",
        "transferencia_automatica",
        "tipo_oc"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "compras_solicitudes.id_producto");
        $query->leftJoin("productos_sucursales_costos as pc", function ($join){
            $join->on("pc.id_producto", "=", "compras_solicitudes.id_producto")
                ->on("pc.id_sucursal", "=", "compras_solicitudes.id_sucursal")
                ->where("pc.estatus", "=", "actual");
        });
        $query->leftJoin("productos_sucursales_precio_base as pb", function ($join){
            $join->on("pb.id_producto", "=", "compras_solicitudes.id_producto")
                ->on("pb.id_sucursal", "=", "compras_solicitudes.id_sucursal");
        });
        $query->leftJoin("cat_sucursales as cs", "cs.id_sucursal", "=", "compras_solicitudes.id_sucursal");
        $query->leftJoin("cat_proveedores as pv", "pv.id_proveedor", "=", "compras_solicitudes.id_proveedor");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "compras_solicitudes.id_almacen");
        $query->leftJoin("iva as iva", "iva.id_iva", "=", "p.id_iva");

        $query->leftJoin("productos_unidades_medida as um", "um.id_unidad_medida", "=", "p.unidad_compra");
        $query->leftJoin("cat_sucursales_logistica as sl", function ($join){
            $join->on("sl.id_sucursal_destino", "=", "compras_solicitudes.id_sucursal")
                ->where("sl.estatus_logistica_sucursales", "=", "activo");
        });
        $query->leftJoin("cat_sucursales as so", "so.id_sucursal", "=", "sl.id_sucursal_origen");

        $select = [
            "compras_solicitudes.*",
            "cs.id_sucursal", "cs.nombre as sucursal",
            "pv.nombre as proveedor",
            "p.codigo_producto",
            "p.descripcion",
            "pc.costo_actual",
            \DB::raw("p_costo(compras_solicitudes.id_producto) as precio"),
            \DB::raw("ifnull(p.peso,0) as peso"),
            "a.almacen",
            "iva.porcentaje",
            "p.unidad_compra as id_unidad_medida",
            "um.unidad_medida",
            "sl.id_sucursal_origen",
            "so.nombre as sucursal_origen"
        ];

        $query->select($select);


        if (!empty($datos["id_familia"])) {
            $query->whereIn("p.id_familia", $datos["id_familia"]);
        }
        if (!empty($datos["id_linea"])) {
            $query->whereIn("p.id_linea", $datos["id_linea"]);
        }
        if (!empty($datos["id_proveedor"])) {
            $query->where("compras_solicitudes.id_proveedor", $datos["id_proveedor"]);
        }
        if (!empty($datos["estatus"])) {
            $query->where("compras_solicitudes.estatus", $datos["estatus"]);
        }
        if (!empty($datos["id_solicitud"])){
            $query->where("compras_solicitudes.id_solicitud", $datos["id_solicitud"]);
        }
        if (!empty($datos["id_sucursal"])) {
            if (count($datos["id_sucursal"]) > 1) {
                $query->whereIn("compras_solicitudes.id_sucursal", $datos["id_sucursal"]);
            } else {
                $query->where("compras_solicitudes.id_sucursal", $datos["id_sucursal"]);
            }
        }

        if (!empty($datos["proveedor"])){
            $query->whereNotNull("compras_solicitudes.id_proveedor");
        }

        if (!empty($datos["first"])){
            return $query->first();
        }

        //$query->orderBy("compras_solicitudes.tipo_oc", "desc");

        $query->groupBy("compras_solicitudes.id_solicitud");

        //dd($query->toSql());

        return $query->get();
    }

    public function obtenerSolicitudes($id_producto, $id_almacen)
    {

        $query = $this->from("compras_solicitudes as cs");

        $query->select(
            \DB::raw("ifnull(sum(cantidad),0) as solicitudes")
        );

        $query->where("cs.id_producto", $id_producto);
        $query->where("cs.id_almacen", $id_almacen);
        $query->whereIn("cs.estatus", ["ps"]);

        return $query->first();

    }

}
