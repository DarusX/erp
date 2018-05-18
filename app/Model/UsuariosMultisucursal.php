<?php

namespace App\Model;

use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;

class UsuariosMultisucursal extends Model
{
    protected $table = "usuariosMultisucursal";

    public function buscarCorreos ($datos)
    {

        $query = $this->from("usuariosMultisucursal as ums");
        $query->leftJoin("usuarios as u", "u.id_usuario", "=", "ums.id_usuario");

        $query->select("ums.*");

        $query->where("u.activo", "=", "si");

        if (!empty($datos["rol_id"])) {

            $query->where("u.rol_id", $datos["rol_id"]);

        }

        if (!empty($datos["id_sucursal"])) {

            $query->where("ums.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["first"])) {

            return $query->first();

        }

        return $query->get();

    }

}
