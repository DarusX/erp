<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoriasCorreos extends Model
{
    protected $table = "categorias_correos";

    protected $fillable = [
        "nombre_categoria"
    ];

    public function buscar($datos){

        $query = $this->select(
            "categorias_correos.*"
        );

        if(!empty($datos['id'])){
            if(!empty($datos['first'])){
                return $query->first();
            }
        }
        if(!empty($datos['nombre_categoria'])){
            $query->where("nombre_categoria", "like", "%".$datos['nombre_categoria']."%");
        }

        return $query->get();

    }
}
