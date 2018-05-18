<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClasificacionesAnimales extends Model
{
    protected $table = "agr_clasificaciones_animales";

    protected $fillable = [
        "categoria_id",
        "raza_id",
        "genero",
        "clasificacion_id",
        "clasificacion",
        "dias_inicio",
        "dias_final",
        "precio_estandar"
    ];

    public function buscar($datos){

        $query = $this->leftJoin("agr_categorias_razas as cr", "cr.id", "=", "agr_clasificaciones_animales.categoria_id");
        $query->leftJoin("agr_razas as r", "r.id", "=", "agr_clasificaciones_animales.raza_id");
        $query->leftJoin("agr_cat_clasificaciones as cca", "cca.id", "=", "agr_clasificaciones_animales.clasificacion_id");

        $query->select(
            "agr_clasificaciones_animales.*",
            "cr.nombre",
            "r.raza",
            \DB::raw("ifnull(agr_clasificaciones_animales.precio_estandar,0) as precio_estandar"),
            \DB::raw("ifnull(cca.clasificacion,'') as clasificacion")
        );

        if(!empty($datos["id"])){
            $query->where("agr_clasificaciones_animales.id", $datos["id"]);
            if(!empty($datos["first"])){
                return $query->first();
            }
        }
        if(!empty($datos["categoria_id"])){
            $query->where("agr_clasificaciones_animales.categoria_id", $datos["categoria_id"]);
        }
        if(!empty($datos["raza_id"])){
            if(count($datos["raza_id"]) > 1) {
                $query->whereIn("agr_clasificaciones_animales.raza_id", $datos["raza_id"]);
            }else{
                $query->where("agr_clasificaciones_animales.raza_id", $datos["raza_id"]);
            }
        }
        if(!empty($datos["genero"])){
            $query->where("agr_clasificaciones_animales.genero", $datos["genero"]);
        }

        return $query->get();

    }
}
