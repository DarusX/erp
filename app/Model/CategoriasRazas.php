<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoriasRazas extends Model
{
    protected $table = "agr_categorias_razas";

    protected $fillable = [
        "nombre",
        "descripcion"
    ];

    public function buscar($datos)
    {

        $query = $this->select(
            "agr_categorias_razas.*"
        );

        if(!empty($datos["nombre"])){
            $query->where("nombre", "like", "%".$datos["nombre"]."%");
        }

        if(!empty($datos["id"])){
            $query->where("id", $datos["id"]);
            if(!empty($datos["first"])){
                return $query->first();
            }
        }

        return $query->get();

    }
}
