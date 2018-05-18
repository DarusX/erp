<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ModificacionCostoProducto extends Model
{

    protected $table = "modificacion_costo_producto";

    protected $fillable = [
        "producto_id",
        "costo_anterior",
        "costo_nuevo",
        "porcentaje_aumento",
        "cantidad_aumento",
        "usuario_captura_id",
        "fecha_captura",
        "estado"
    ];

    public function buscar($datos) {

        $query = $this->from("modificacion_costo_producto as mcp");
        $query->leftJoin("productos as p", "p.id_producto", "=", "mcp.producto_id");

        $select = [
            "mcp.*",
            \DB::raw("ifnull(obtenerEmpleadoNombre(mcp.usuario_captura_id),'') as usuario_captura_nombre")
        ];

        $query->select($select);

        if (!empty($datos["id_producto"])) {

            $query->where("mcp.producto_id", $datos["id_producto"]);

        }

        if (!empty($datos["modificacion_id"])) {

            $query->where("mcp.id", $datos["modificacion_id"]);

        }

        if (!empty($datos["first"])) {

            return $query->first();

        }

        return $query->get();

    }

}
