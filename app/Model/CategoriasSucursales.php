<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoriasSucursales extends Model
{
    protected $table = "categorias_sucursales";

    protected $fillable = [
        "nombre",
        "descripcion"
    ];

    public function buscar($datos)
    {

        //dd($datos);

        $query = $this->select(
            "categorias_sucursales.*"
        );

        if(!empty($datos["id_categoria"])){
            $query->where("id", "=", $datos["id_categoria"]);
            if(!empty($datos["first"])){
                return $query->first();
            }
        }

        if(!empty($datos["nombre"])){
            $query->where("nombre", "like", "%".$datos["nombre"]."%");
        }

        if(!empty($datos["descripcion"])){
            $query->where("descripcion", "like", "%".$datos["descripcion"]."%");
        }

        //dd($query->toSql());

        return $query->get();

    }
}
