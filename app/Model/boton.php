<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class boton extends Model
{
    //
    protected $table = "acl_boton";

    protected $fillable = [
        'recurso_id',
        'descripcion',
        'nombre'
    ];

    public function buscarPorRol($datos)
    {
        $query = $this->leftJoin("acl_boton_rol as b", function ($join) use ($datos) {
            $join->on("b.boton_id", "=", "acl_boton.id")
                ->where("b.rol_id", "=", $datos["rol_id"]);
        });
        if (!empty($datos["recurso_id"])) {
            $query->where("acl_boton.recurso_id", "=", $datos["recurso_id"]);
        }

        $query->select(
            "acl_boton.*",
            "b.id as boton_rol_id",
            \DB::raw("if(ifnull(b.id,0)>0,'SI','NO') as asignado")
        );

        return $query->get();
    }

    public function buscar($datos)
    {

        $query = $this->leftJoin('acl_recurso as r', 'r.id', '=', 'acl_boton.recurso_id');

        $query->select(
            'acl_boton.*',
            'r.ruta',
            'r.label'
        );

        if (!empty($datos['nombre'])) {
            $query->where('acl_boton.nombre', $datos['nombre']);
        }

        return $query->get();

    }

}
