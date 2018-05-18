<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Lineas extends Model
{
    protected $table = "productos_lineas";

    protected $primaryKey = "id_linea";

    protected $fillable = [
        "linea",
        "promedio_entrega",
        "estatus_utilidad",
        "dias_inventario_alta",
        "dias_inventario_media",
        "dias_inventario_baja",
        "transferencias_modificables"
    ];

    public function buscar($datos)
    {
        $query = $this->select(
            "productos_lineas.*",
            \DB::raw('(SELECT COUNT(*) FROM productos_lineas_porcentaje_utilidad AS pfpu LEFT JOIN lineas_porcentajes_utilidades_ediciones AS fpue ON fpue.producto_linea_porcentaje_utilidad_id = pfpu.id WHERE pfpu.linea_id = productos_lineas.id_linea AND fpue.estado = "Pendiente") AS utilidades_pendientes'),
            \DB::raw('(SELECT COUNT(*) FROM productos_lineas_porcentaje_utilidad AS pfpu LEFT JOIN lineas_porcentajes_utilidades_ediciones AS fpue ON fpue.producto_linea_porcentaje_utilidad_id = pfpu.id WHERE pfpu.linea_id = productos_lineas.id_linea AND fpue.estado = "Validado") AS utilidades_validadas'),
            \DB::raw("ifnull(productos_lineas.dias_inventario_alta,'') as dias_inventario_alta"),
            \DB::raw("ifnull(productos_lineas.dias_inventario_media,'') as dias_inventario_media"),
            \DB::raw("ifnull(productos_lineas.dias_inventario_baja,'') as dias_inventario_baja")
        );
        //$select = ["productos_lineas.*"];

        if (!empty($datos["id_proveedor"])) {

            $query = $this->leftJoin("proveedores_lineas as pl", "pl.id_linea", "=", "productos_lineas.id_linea");
            $query->where("pl.id_proveedor", "=", $datos["id_proveedor"]);

        }

        //$query = $query->select($select);

        if (!empty($datos['linea'])) {
            $query->where("linea", "like", "%" . $datos['linea'] . "%");
        }

        if (!empty($datos["id_linea"])){
            $query->where("id_linea", $datos["id_linea"]);
            return $query->first();
        }

        return $query->get();
    }
}
