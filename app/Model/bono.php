<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class bono extends Model
{
    //
    protected $table = "rh_bono";
    protected $primaryKey = "id_bono";
    protected $fillable = [
        "bono",
        "descripcion",
        "tipo"
    ];

    public function buscarBonoPerfil($datos)
    {
        $query = $this->leftJoin("calidad_perfil_bono as pb", function ($join) use ($datos) {
            $join->on("rh_bono.id_bono", "=", "pb.bono_id")
                ->where("pb.puesto_id", "=", $datos["id_puesto"])
                ->where("pb.estatus", "=", "activo");
        });
        if (!empty($datos["id_puesto_perfil"]))
            $query->where("pb.perfil_id", "=", $datos["id_puesto_perfil"]);
        $query->select(
            "rh_bono.*",
            \DB::raw("ifnull(pb.id,0) as perfil_bono_id"),
            \DB::raw("ifnull(pb.id,'NO') as asignado")

        );

        return $query->get();

    }

    public function buscar($datos)
    {
        $query = $this;
        if (!empty($datos["id_bono"]))
            return $query->where("id_bono", "=", $datos["id_bono"])->first();

        return $query->get();
    }

    public function buscarAsignado($datos)
    {
        $query = $this->leftJoin("rh_bono_puesto as bp", function ($join) use ($datos) {
            $join->on("bp.id_bono", "=", "rh_bono.id_bono")
                ->where("bp.id_puesto", "=", $datos["id_puesto"]);
        });
        $select = [
            "rh_bono.*",
            "bp.minimo", "bp.porcentaje", "bp.minimo_respaldo", "bp.porcentaje_respaldo", "bp.tipo",
            \DB::raw("ifnull(bp.id_bono_puesto,0) as id_bono_puesto"),
            \DB::raw("if(ifnull(bp.id_bono_puesto,0) = 0,'NO','SI') as asignado")

        ];
        if (!empty($datos["asignado"])) {
            $query->whereNotNull("bp.id_bono_puesto");
        }
        $query->select($select);
        //dd($query->toSql());

        return $query->get();


    }

}