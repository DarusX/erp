<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class videos extends Model
{
    //
    protected $table = "rh_videos";
    protected $fillable = [];

    public function buscar($datos)
    {

        $query = $this->where("rh_videos.estatus", "=", "activo");


        if (!empty($datos["actividad_id"])) {
            $query->leftJoin("procedimiento_actividad_video as pv", function ($join) use ($datos) {
                $join->on("rh_id_video", "=", "pv.video_id")
                    ->where("pv.actividad_id", "=", $datos["actividad_id"]);

            });

            $query->select("*",
                \DB::raw("if(pv.id is null,'NO','SI') as asignado")
            );
            if (!empty($datos["asignado"])) {
                $query->whereNotNull("pv.id");
            }

        } else {

            $query->select("*",
                \DB::raw("'NO' as asignado")
            );
        }

        return $query->get();

    }
}
