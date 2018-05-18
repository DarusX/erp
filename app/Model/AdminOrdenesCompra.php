<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminOrdenesCompra extends Model
{

    protected $table = "administrador_ordenes_compra";

    protected $fillable = [
        "rol_id",
        "r.rol",
        "monto_minimo",
        "monto_minimo_costo",
        "monto_maximo_costo",
        "estado",
        "aplica_validacion_costo"
    ];

    public function buscar($datos)
    {

        $query = $this->from("administrador_ordenes_compra as aod");
        $query->leftJoin("acl_rol as r", "r.id", "=", "aod.rol_id");
        //$query->leftJoin("usuarios as u", "u.rol_id", "=", "aod.rol_id");
        //$query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "u.id_empleado");

        $query->select(
            "aod.*",
            "r.rol"
            //"e.email_empresa"
        );

        if (!empty($datos["estado"])) {
            $query->where("aod.estado", "=", $datos["estado"]);
        }

        if (!empty($datos["id"])) {
            $query->where("aod.id", "=", $datos["id"]);
        }

        if (!empty($datos["monto"])) {
            $query->where("aod.monto_minimo", "<=", $datos["monto"]);
        }

        if (isset($datos["producto_nuevo"])) {
            $query = $query->where('aod.producto_nuevo', 1);
        } else {
            $query = $query->where('aod.producto_nuevo', 0);
        }

        if (!empty($datos['rol_id']))
            $query->where("rol_id", $datos['rol_id']);

        if (isset($datos["ordenar"])) {

            $query->orderBy("jerarquia_oc", "asc");

        } else {

            $query->orderBy("monto_minimo", "asc");

        }

        if (!empty($datos["first"])) {
            return $query->first();
        }

        return $query->get();
    }

}