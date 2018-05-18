<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProveedoresEmpleados extends Model
{

    protected $table = "proveedores_empleados";

    protected $fillable = [
        "id_proveedor",
        "id_empleado",
        "estatus"
    ];

    public function buscar($datos){

        $query = $this->leftJoin("cat_proveedores as p", "p.id_proveedor", "=", "proveedores_empleados.id_proveedor");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "proveedores_empleados.id_empleado");

        $query->select(
            "proveedores_empleados.*",
            "p.nombre",
            \DB::raw("ifnull(concat(e.nombre, ' ', e.apaterno, ' ', e.amaterno),'S/R') as nombre_completo")
        );

        if (!empty($datos["id_proveedor"])){
            $query->where("proveedores_empleados.id_proveedor", $datos["id_proveedor"]);
        }

        if (!empty($datos["id_empleado"])){
            $query->where("proveedores_empleado.id_empleado", $datos["id_empleado"]);
        }

        if (!empty($datos["estatus"])){
            $query->where("proveedores_empleados.estatus", $datos["estatus"]);
        }

        return $query->get();

    }

}
