<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosPrecioBaseLog extends Model
{
    protected $table = "productos_precio_base_log";

    protected $fillable = [
        "id_producto",
        "id_empleado",
        "precio_anterior",
        "precio_actual",
        "fecha_actualizacion"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "productos_precio_base_log.id_producto");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "productos_precio_base_log.id_empleado");

        $query->select(
            "productos_precio_base_log.*",
            "p.descripcion",
            "p.codigo_producto",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as nombre_completo")
        );

        if(!empty($datos['id_producto'])){
            $query->where("id_producto", $datos['id_producto']);
        }
        if(!empty($datos['id_empleado'])){
            $query->where("id_empleado", $datos['id_empleado']);
        }
        if(!empty($datos['id_familia'])){
            $query->where("p.id_familia", $datos['id_familia']);
        }
        if(!empty($datos['id_linea'])){
            $query->where("p.id_linea", $datos['id_linea']);
        }
        if(!empty($datos['id_categoria'])){
            $query->where("p.id_categoria", $datos['id_categoria']);
        }
        if(!empty($datos['fecha_ini'])){
            $query->where("fecha_actualizacion", ">=", $datos['fecha_ini']);
        }
        if(!empty($datos['fecha_fin'])){
            $query->where("fecha_actualizacion", "<=", $datos['fecha_fin']);
        }

        return $query->get();

    }
}