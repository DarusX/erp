<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EquiposComputo extends Model
{

    protected $table = "equipo_computo";

    protected $fillable = [
        "nombre",
        "codigo",
        "id_categoria",
        "id_sucursal",
        "descripcion",
        "estado",
        "ip_equipo",
        "mac_equipo"
    ];

    protected $primaryKey = "id_equipo";

    public function buscar($datos)
    {

        $query = $this->from("equipo_computo as e");
        $query->leftJoin("cat_categoria_computo as cc", "cc.id_categoria_equipo", "=", "e.id_categoria");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "e.id_sucursal");

        $query->select(
            "e.*",
            "cc.categoria_equipo",
            "s.nombre as sucursal",
            \DB::raw("ifnull(e.ip_equipo,'') as ip_equipo"),
            \DB::raw("ifnull(e.mac_equipo,'') as mac_equipo")
        );

        if (!empty($datos["id_equipo"])){
            $query->where("e.id_equipo", $datos["id_equipo"]);
        }

        if (!empty($datos["codigo"])){
            $query->where("e.codigo", "like", "%". $datos["codigo"] ."%");
        }

        if (!empty($datos["id_sucursal"])){
            if (count($datos["id_sucursal"]) > 1){
                $query->whereIn("s.id_sucursal", $datos["id_sucursal"]);
            } else {
                $query->where("s.id_sucursal", $datos["id_sucursal"]);
            }
        }

        if (!empty($datos["id_categoria"])){
            if (count($datos["id_categoria"]) > 1){
                $query->whereIn("s.id_categoria", $datos["id_categoria"]);
            } else {
                $query->where("s.id_categoria", $datos["id_categoria"]);
            }
        }

        if (!empty($datos["ip_equipo"])){
            $query->where("e.ip_equipo", "like", "%". $datos["ip_equipo"] ."%");
        }

        if (!empty($datos["estado"])){
            $query->where("e.estado", $datos["estado"]);
        }

        if (!empty($datos["ultimo"])){
            $query->orderBy("id_equipo", "desc");
        }

        if (!empty($datos["first"])){
            return $query->first();
        }

        return $query->get();

    }

}
