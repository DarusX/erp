<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminValidacionesDescuentosRolesPreceden extends Model
{

    protected $table = "administrador_validaciones_ventas_descuentos_roles_preceden";

    public $timestamps = false;

    public function checarRol ($datos)
    {

        $query = $this->from("administrador_validaciones_ventas_descuentos_roles_preceden as avdp")
            ->select("avdp.*")
            ->where("avdp.rol_id", $datos["rol_id"]);

        return $query->first();

    }

}
