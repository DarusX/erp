<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosFactorConversion extends Model
{

    protected $table = "productos_factor_conversion";

    protected $fillable = [
        "producto_id",
        "proveedor_id",
        "factor_conversion",
        "empleado_captura_id",
        "fecha_captura",
        "estatus"
    ];

    public function buscar($datos){

        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "productos_factor_conversion.producto_id");
        $query->leftJoin("cat_proveedores as prov", "prov.id_proveedor", "=", "productos_factor_conversion.proveedor_id");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "productos_factor_conversion.empleado_captura_id");

        $query->select(
            "productos_factor_conversion.*",
            "p.codigo_producto",
            "p.descripcion",
            "prov.nombre",
            \DB::raw("ifnull(concat(e.nombre, ' ', e.apaterno, ' ', e.amaterno), '-') as empleado"),
            \DB::raw("ifnull(productos_factor_conversion.fecha_captura,'-') as fecha_captura")
        );

        if(!empty($datos["producto_id"])){
            $query->where("producto_id", $datos["producto_id"]);
        }

        if (!empty($datos["proveedor_id"])){
            $query->where("proveedor_id", $datos["proveedor_id"]);
        }

        if (!empty($datos["first"])){
            return $query->first();
        }

        if (!empty($datos["estatus"])){
            $query->where("productos_factor_conversion.estatus", $datos["estatus"]);
        }

        $query->orderBy("estatus", "desc");

        return $query->get();

    }

}
