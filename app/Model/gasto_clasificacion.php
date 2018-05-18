<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class gasto_clasificacion extends Model
{
    //
    protected $table = "gastosClasificacion";

    public function buscar($datos)
    {

        $query = $this->from("gastosClasificacion as gc")->select("gc.*");

        if (!empty($datos["id_clasificacion"])){

            $query->where("id_gasto_clasificacion", $datos["id_clasificacion"]);

            return $query->first();

        }

        return $query->get();

    }

}
