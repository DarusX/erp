<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Familias extends Model
{
    protected $table = "productos_familias";

    protected $primaryKey = "id_familia";

    protected $fillable = [
        'familia',
        'pagina',
        'estatus_utilidad',

    ];

    public function buscar($datos)
    {
        $query = $this->select(
            "productos_familias.*",
            \DB::raw('(SELECT COUNT(*) FROM productos_familias_porcentaje_utilidad AS pfpu LEFT JOIN familias_porcentajes_utilidades_ediciones AS fpue ON fpue.producto_familia_porcentaje_utilidad_id = pfpu.id WHERE pfpu.familia_id = productos_familias.id_familia AND fpue.estado = "Pendiente") AS utilidades_pendientes'),
            \DB::raw('(SELECT COUNT(*) FROM productos_familias_porcentaje_utilidad AS pfpu LEFT JOIN familias_porcentajes_utilidades_ediciones AS fpue ON fpue.producto_familia_porcentaje_utilidad_id = pfpu.id WHERE pfpu.familia_id = productos_familias.id_familia AND fpue.estado = "Validado") AS utilidades_validadas')
        );

        if (!empty($datos['familia'])) {
            $query->where("familia", "like", "%" . $datos['familia'] . "%");
        }
        if (!empty($datos["familia_id"])) {
            $query->where("id_familia", $datos["familia_id"]);
        }
        if (!empty($datos["first"]))
            return $query->first();

        return $query->get();
    }
}
