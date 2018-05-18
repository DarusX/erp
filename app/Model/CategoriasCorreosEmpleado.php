<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoriasCorreosEmpleado extends Model
{
    protected $table = "categorias_correos_empleados";

    protected $fillable = [
        "id_categoria_correo",
        "id_empleado"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("categorias_correos as cc", "cc.id", "=", "categorias_correos_empleados.id_categoria_correo");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "categorias_correos_empleados.id_empleado");

        $query->select(
            "categorias_correos_empleados.*",
            "cc.nombre_categoria",
            "e.email_empresa",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as nombre_completo")
        );

        if(!empty($datos['id_categoria'])){
            $query->where("categorias_correos_empleados.id_categoria_correo", $datos['id_categoria']);
        }
        if(!empty($datos['id_empleado'])){
            $query->where("categorias_correos_empleados.id_empleado", $datos['id_empleado']);
        }

        return $query->get();

    }
}
