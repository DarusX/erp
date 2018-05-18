<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class profecion extends Model
{
    //
    protected $table = "rh_profecion";
    protected $primaryKey = "id_profecion";
    protected $fillable = [
        "profecion"
    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_profecion_puesto as pp", function ($join) use ($datos) {
            $join->on("rh_profecion.id_profecion", "=", "pp.id_profecion")
                ->where("pp.id_puesto", "=", $datos["id_puesto"])
                ->where("pp.estatus", "=", "activo");

        });
        $query->select(
            "rh_profecion.*", "pp.id_puesto",
            \DB::raw("ifnull(id_profecion_puesto,0) as id_profecion_puesto"),
            \DB::raw("ifnull(id_profecion_puesto,'NO') as asignado")
        );
        if(!empty($datos["profesion"]))
            $query->where("rh_profecion.profecion","like","%".$datos["profesion"]."%");
        return $query->get();
    }
    public function buscarProfecion($datos){
        $query = $this;
        if(!empty($datos["profesion"]))
            $query = $query->where("rh_profecion.profecion","like","%".$datos["profesion"]."%");
        return $query->get();
    }

    public function buscarPorPuesto($datos)
    {
        $query = $this->leftJoin("rh_profecion_puesto as pp", "rh_profecion.id_profecion", "=", "pp.id_profecion");

        $query->where("pp.id_perfil", "=", $datos["id_perfil"]);
        $query->select(
            "rh_profecion.profecion",
            "pp.*"

        );

        return $query->get();
    }
}
