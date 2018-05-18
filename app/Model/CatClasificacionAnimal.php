<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CatClasificacionAnimal extends Model
{
    protected $table = "agr_cat_clasificaciones";

    protected $fillable = [
        "clasificacion",
        "genero"
    ];

    public function buscar($datos)
    {

        $query = $this->select(
            "agr_cat_clasificaciones.*"
        );

        if(!empty($datos["clasificacion"])){
            $query->where("clasificacion", "like", "%".$datos["clasificacion"]."%");
        }

        if(!empty($datos["id"])){
            $query->where("id", $datos["id"]);
            if(!empty($datos["first"])) {
                return $query->first();
            }
        }

        return $query->get();

    }
}
