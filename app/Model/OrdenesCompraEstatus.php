<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrdenesCompraEstatus extends Model
{

    protected $table = "ordenes_compra_estatus";

    protected $fillable = [
        "orden_id",
        "admin_id",
        "rol_id",
        "jerarquia",
        "estado"
    ];

    public function buscar($datos)
    {

        $query = $this->from("ordenes_compra_estatus as oce");
        $query->leftJoin("compras_ordenes as co", "co.id_orden", "=", "oce.orden_id");
        $query->leftJoin("administrador_ordenes_compra as aoc", "aoc.id", "=", "oce.admin_id");
        $query->leftJoin("acl_rol as r", "r.id", "=", "oce.rol_id");

        $query->select(
            "oce.*",
            "aoc.monto_minimo",
            "r.rol"
        );

        if (!empty($datos["id_orden"])) {

            $query->where("oce.orden_id", $datos["id_orden"]);

        }

        if (!empty($datos["rol_id"])) {

            $query->where("oce.rol_id", $datos["rol_id"]);

        }

        if (!empty($datos["estado"])) {

            $query->where("oce.estado", $datos["estado"]);

        }

        if (!empty($datos["jerarquia"])) {
            
            $query = $query->where('oce.jerarquia', $datos['jerarquia']);
            
        }
        
        if (!empty($datos["id"])){
            
            $query = $query->where("oce.id", $datos["id"]);
            
        }

        if (!empty($datos["admin_id"])){

            $query = $query->where("oce.admin_id", $datos["admin_id"]);

        }

        if (!empty($datos["first"])) {

            return $query->first();

        }

        $query->orderBy("oce.jerarquia", "asc");

        //dd($query->toSql());

        return $query->get();

    }

}
