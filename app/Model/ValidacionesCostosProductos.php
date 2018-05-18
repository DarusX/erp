<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ValidacionesCostosProductos extends Model
{

    protected $table = "validaciones_costos_productos";

    protected $fillable = [
        "modificacion_costo_producto_id",
        "administrador_validaciones_id",
        "orden",
        "estado"
    ];

    public function buscar ($datos)
    {

        $query = $this->from("validaciones_costos_productos as vcp");
        $query->leftJoin("modificacion_costo_producto as mcp", "mcp.id", "=", "vcp.modificacion_costo_producto_id");
        $query->leftJoin("administrador_validaciones as av", "av.id", "=", "vcp.administrador_validaciones_id");

        $select = [
            "vcp.*",
            "mcp.producto_id",
            "av.rol_id",
            "mcp.costo_anterior",
            "mcp.costo_nuevo",
            "mcp.porcentaje_aumento",
            "mcp.cantidad_aumento",
            "mcp.fecha_captura",
            \DB::raw("ifnull(obtenerEmpleadoNombre(mcp.usuario_captura_id),'') as usuario_captura_nombre")
        ];

        $query->select($select);

        if (!empty($datos["producto_id"])) {

            $query->where("mcp.producto_id", $datos["producto_id"]);

        }

        if (!empty($datos["rol_id"])) {

            $query->where("av.rol_id", $datos["rol_id"]);

        }

        if (!empty($datos["orden"])) {

            $query->where("vcp.orden", $datos["orden"]);

        }

        if (!empty($datos["validacion_id"])) {

            $query->where("vcp.id", $datos["validacion_id"]);

        }

        if (!empty($datos["estado"])) {

            $query->where("vcp.estado", $datos["estado"]);

        }

        if (!empty($datos["first"])) {

            return $query->first();

        }

        $query->orderBy("vcp.orden", "asc");

        return $query->get();

    }

}
