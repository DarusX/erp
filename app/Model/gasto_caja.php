<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class gasto_caja extends Model
{
    //
    protected $table = "compras_gastos_sucursales";

    public function buscar($datos){
        $query = $this->leftJoin("cat_sucursales as s","s.id_sucursal","=","compras_gastos_sucursales.id_sucursal");
        $query->leftJoin("cat_sucursales as s2","s2.id_sucursal","=","compras_gastos_sucursales.id_sucursal_asignada");
        $query->select(
            "compras_gastos_sucursales.*",
            "s.nombre as sucursal_cobrado","s2.nombre as sucursal_asignado"
        );

        if($datos["id_sucursal"]){
            $query->where("id_sucursal_asignada","=",$datos["id_sucursal"]);
        }
        if($datos["fecha_mes"]){
            $query->whereRaw("(DATE_FORMAT(compras_gastos_sucursales.fecha, '%Y-%m-01')) = '" . $datos["fecha_mes"] . "'");
        }
        if($datos["estatus_not"]){
            $query->whereNotIn("compras_gastos_sucursales.estatus",$datos["estatus_not"]);
        }
        return $query->get();
    }


}
