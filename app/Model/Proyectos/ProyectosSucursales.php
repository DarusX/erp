<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class ProyectosSucursales extends Model
{
    protected $table='proyectos_sucursales';
    protected $fillable =['sucursales_id','proyecto_id', 'rancho_id', 'inmueble_id', 'proyecto_inmobiliaria_id'];


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

    public function buscarRanchos($datos){
        $query = $this->leftJoin("proyectos_proyectos as p", "p.id", "=", "proyectos_sucursales.proyecto_id");
        $query->leftJoin("agr_rancho as r", "r.id", "=", "proyectos_sucursales.rancho_id");

        $query->select(
            "r.id as rancho_id",
            "r.*"
        );

        if(!empty($datos['proyecto_id'])){

            $query->where("proyectos_sucursales.proyecto_id", $datos['proyecto_id']);
        }
        return $query->get();
    }

    public function buscarInmueble($datos){
        $query = $this->leftJoin("proyectos_proyectos as p", "p.id", "=", "proyectos_sucursales.proyecto_id");
        $query->leftJoin("inmuebles_inmobiliaria as i", "i.id", "=", "proyectos_sucursales.inmueble_id");
        $query->leftJoin("catalogos_proyectos_inmobiliaria as c", "c.id", "=", "proyectos_sucursales.proyecto_inmobiliaria_id");

        $query->select(
            "i.nombre as inmueble",
            "c.nombre as proyecto_inmobiliaria",
            "proyectos_sucursales.*",
            "p.nombre as proyecto"
        );


        if(!empty($datos['proyecto_id'])){
            $query->where("proyectos_sucursales.proyecto_id", $datos['proyecto_id']);
        }

        return $query->get();
    }

}
