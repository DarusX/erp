<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosCostos extends Model
{
    protected $table = "productos_sucursales_costos";

    protected $fillable = [
        'id_producto',
        'id_sucursal',
        'id_empleado',
        'costo_anterior',
        'costo_actual',
        'estatus'
    ];


    public function buscar($datos){

        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "productos_sucursales_costos.id_producto");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "productos_sucursales_costos.id_sucursal");

        $query->select(
            "productos_sucursales_costos.*",
            "p.descripcion",
            "p.codigo_producto",
            "s.nombre as sucursal",
            \DB::raw("ifnull(productos_sucursales_costos.costo_anterior,0) as costo_anterior")
        );

        if($datos['id_producto']){
            $query->where("p.id_producto", $datos['id_producto']);
        }
        if($datos['id_sucursal']){
            $query->where("s.id_sucursal", $datos['id_sucursal']);
        }

        return $query->get();

    }

    public function buscar_todo($datos){

        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "productos_sucursales_costos.id_producto");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "productos_sucursales_costos.id_empleado");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "productos_sucursales_costos.id_sucursal");

        $query->select(
            "productos_sucursales_costos.*",
            "p.descripcion",
            "p.codigo_producto",
            "s.nombre as sucursal",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as nombre_completo"),
            \DB::raw("ifnull(productos_sucursales_costos.costo_anterior,0) as costo_anterior")
        );

        if(!empty($datos['id_producto'])){
            $query->where("p.id_producto", $datos['id_producto']);
        }
        if(!empty($datos['id_sucursal'])){
            $query->where("s.id_sucursal", $datos['id_sucursal']);
        }
        if(!empty($datos['id_empleado'])){
            $query->where("e.id_empleado", $datos['id_empleado']);
        }
        if(!empty($datos['id_familia'])){
            $query->where("p.id_familia", $datos['id_familia']);
        }
        if(!empty($datos['id_categoria'])){
            $query->where("p.id_categoria", $datos['id_categoria']);
        }
        if(!empty($datos['id_linea'])){
            $query->where("p.id_linea", $datos['id_linea']);
        }
        if(!empty($datos['fecha_ini'])){
            $query->where("productos_sucursales_costos.created_at", ">=", $datos['fecha_ini']." 00:00:00");
        }
        if(!empty($datos['fecha_fin'])){
            $query->where("productos_sucursales_costos.created_at", "<=", $datos['fecha_fin']." 23:59:59");
        }

        return $query->get();

    }
}
