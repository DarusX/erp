<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BotonRol extends Model
{
    //
    protected $table = "acl_boton_rol";

    public function buscar($datos)
    {
        $query = $this->leftJoin("acl_boton as b", "b.id", "=", "acl_boton_rol.boton_id");
        $query->leftJoin("acl_recurso as r", "r.id", "=", "b.recurso_id");

        if (isset($datos["rol_id"]))
            $query->where("acl_boton_rol.rol_id", "=", $datos["rol_id"]);
        if (isset($datos['pathname']))
            $query->where("r.ruta", "=", $datos["pathname"]);

        $query->select("b.id as id", "r.ruta as ruta","b.nombre");


        return $query->lists("b.nombre", "id");
    }
}
