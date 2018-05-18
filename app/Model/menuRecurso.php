<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class menuRecurso extends Model
{
    //
    protected $table = "acl_menu_recurso";

    protected $fillable = [
        "menu_id",
        "recurso_id"
    ];

    public function buscar($datos)
    {
        $query = $this->join('acl_recurso as r', 'acl_menu_recurso.recurso_id', '=', 'r.id');
        if (isset($datos["menu_id"])) {
            $query->where('menu_id', "=", $datos["menu_id"]);
        }
        if (isset($datos['rol_id'])) {
            $query->leftJoin("acl_rol_recurso as rr", "rr.recurso_id", "=", "r.id");
            $query->where("rr.rol_id", "=", $datos["rol_id"]);
        }
//        dd($query->toSql());

        return $query->get();
    }
}
