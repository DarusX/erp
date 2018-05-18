<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class bonoPuesto extends Model
{
    //
    protected $table = "rh_bono_puesto";
    protected $primaryKey = "id_bono_puesto";
    protected $fillable = [
        'id_bono',
        'id_puesto',
        'porcentaje',
        'minimo',
        'porcentaje_respaldo',
        'minimo_respaldo',
        'tipo',
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("rh_bono as b", "b.id_bono", "=", "rh_bono_puesto.id_bono");

        $query->select(
            "rh_bono_puesto.*",
            "b.tipo",
            "b.bono"
        );

        $query->where("id_puesto", $datos["id_puesto"]);
        $query->where("rh_bono_puesto.tipo", "actual");

        //dd($query->toSql());

        return $query->get();

    }
}
