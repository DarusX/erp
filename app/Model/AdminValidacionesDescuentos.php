<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminValidacionesDescuentos extends Model
{

    protected $table = "administrador_validaciones_ventas_descuentos";

    public function buscarTodos($datos)
    {

        $query = $this->from("administrador_validaciones_ventas_descuentos as a");
        $query->leftJoin("acl_roles as r", "r.id_rol", "=", "a.id_rol");
        $query->leftJoin("acl_rol as rol", "rol.id", "=", "a.rol_id");

        $query->select(
            "a.*",
            "r.mascara_rol",
            "rol.rol"
        );

        $query->where("a.estado", "activo");

        return $query->get();

    }

    public function buscarValidaciones($datos)
    {

        $query = $this->from("administrador_validaciones_ventas_descuentos as a");
        $query->leftJoin("acl_roles as r", "r.id_rol", "=", "a.id_rol");

        $query->select(
            "a.*",
            "r.mascara_rol"
        );

        $query->where("a.estado", "activo");

        if (isset($datos["utilidad"])) {

        $query->whereRaw($datos["utilidad"] . " BETWEEN a.porcentaje_utilidad_hasta AND a.porcentaje_utilidad_de");

        }

        if (!empty($datos["id"])) {

            $query->where("a.id", "<=", $datos["id"]);

        }

        if (!empty($datos["jerarquia_validacion"])) {

            $query->where("a.jerarquia_validacion", "<=", $datos["jerarquia_validacion"]);

        }

        if (!empty($datos["rol_id"])) {

            $query->where("a.rol_id", "<=", $datos["rol_id"]);

        }

        if (!empty($datos["first"])) {

            return $query->first();

        }

        $query->orderBy("a.jerarquia_validacion", "asc");

        return $query->get();

    }

}
