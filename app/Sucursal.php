<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table='proyectos_sucursales';
    protected $fillable =['sucursales_id','proyecto_id'];


    public function buscarDatos($datos){

        $query = $this->leftJoin("proyectos_proyectos as p", "p.id", "=", "proyectos_sucursales.proyecto_id");
        $query ->leftJoin("cat_sucursales", "cat_sucursales.id_sucursal", "=", "proyectos_sucursales.sucursales_id");
        $query ->leftJoin("cat_estados as e", "e.id_estado", "=", "cat_sucursales.id_estado");

        $query->select(
            "cat_sucursales.*",
            \DB::raw("ifnull(cat_sucursales.direccion,'') as direccion"),
            \DB::raw("ifnull(cat_sucursales.colonia,'') as colonia"),
            \DB::raw("ifnull(cat_sucursales.ciudad,'') as ciudad"),
            \DB::raw("ifnull(cat_sucursales.logo_ticket,'') as logo_ticket"),
            \DB::raw("ifnull(cat_sucursales.mapa,'') as mapa"),
            \DB::raw("ifnull(cat_sucursales.telefono,'000 0000000') as telefono"),
            \DB::raw("ifnull(cat_sucursales.email,'') as email"),
            \DB::raw("ifnull(e.estado,'') as estado")
        );
        if (!empty($datos['proyecto_id'])){
            $query->where("proyectos_sucursales.proyecto_id", $datos['proyecto_id']);
        }
        return $query->get();
    }
    

}
