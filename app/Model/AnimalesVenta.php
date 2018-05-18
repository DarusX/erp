<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AnimalesVenta extends Model
{
    protected $table = "agr_animales_venta";

    protected $fillable = [
        "animal_id",
        "comentarios",
        "estatus"
    ];


    public function buscar($datos)
    {

        $query = $this->leftJoin("agr_animal as a", "a.id", "=", "agr_animales_venta.animal_id");

        $query->select(
            "agr_animales_venta.*",
            "a.numero",
            "a.raza",
            "a.genero"
        );

        if(!empty($datos["animal_id"])){
            $query->where("animal_id", $datos["animal_id"]);
        }

        return $query->get();

    }
}
