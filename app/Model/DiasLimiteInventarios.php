<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DiasLimiteInventarios extends Model
{

    protected $table = "dias_limite_inventario";

    protected $fillable = [
        "rotacion",
        "dias_inventario",
        "dias_inventario_maximo"
    ];

    public function buscar($datos)
    {

        $query = $this->from("dias_limite_inventario as dli");

        $query->select("dli.*", \DB::raw("ifnull(dli.dias_inventario_maximo,'') as dias_inventario_maximo"));

        if (!empty($datos["rotacion"])){

            $query->where("dli.rotacion", $datos["rotacion"]);
            return $query->first();

        }

        if (!empty($datos["id"])){

            $query->where("dli.id", $datos["id"]);
            return $query->first();

        }

        return $query->get();

    }

}
