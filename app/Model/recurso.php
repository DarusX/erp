<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class recurso extends Model
{
    //
    protected $table = "acl_recurso";
    protected $fillable = [
        "metodo",
        "variables",
        "ruta",
        "controlador",
        "accion",
        "grupo_id",
        "label"
    ];

    public function buscar($datos)
    {


        if (isset($datos["rol_id"])) {
            $query = $this->leftJoin("acl_rol_recurso as r", "r.recurso_id", "=", "acl_recurso.id");
            $query->where("r.rol_id", "=", $datos["rol_id"]);
            if ($datos["id"])
                $query->where("acl_recurso.id", "=", $datos["id"]);

            $query->select("acl_recurso.*");
            //dd($query->toSql());
            $resultado = $query->get();
            if (count($resultado) > 0) {
                return $resultado->toArray();
            } else {
                return array();
            }


        } else {
            return $this::all()->toArray();

        }
    }

    public function buscarRecurso($datos)
    {
        $query = $this->leftJoin("acl_grupo as g", "g.id", "=", "acl_recurso.grupo_id");
        $query->leftJoin("acl_modulo as m", "m.id", "=", "g.modulo_id");
        $query->select(
            "acl_recurso.*",
            "m.modulo",
            "g.grupo"
        );

        if (!empty($datos['ruta'])) {
            $query->where('ruta', 'like', '%' . $datos['ruta'] . "%");
        }

        return $query->get();
    }

    public function buscarRecursoRol($datos)
    {
        $query = $this->leftJoin("acl_grupo as g", "g.id", "=", "acl_recurso.grupo_id");
        $query->leftJoin("acl_modulo as m", "m.id", "=", "g.modulo_id");
        $query->leftJoin("acl_rol_recurso as rr", function ($join) use ($datos) {
            $join->on("rr.recurso_id", "=", "acl_recurso.id")
                ->where("rr.rol_id", "=", $datos["rol_id"]);
        });

        $query->select(
            "acl_recurso.*",
            "m.modulo", "m.id as modulo_id",
            "g.grupo", "rr.id as recurso_id",
            \DB::raw("if(ifnull(rr.id,0)>0,'SI','NO') as asignado")
        );
        return $query->get();
    }

    public function buscarRecursoMenu($datos)
    {
        $query = $this->leftJoin('acl_grupo as g', 'g.id', '=', 'acl_recurso.grupo_id');
        $query->leftJoin('acl_modulo as m', 'm.id', '=', 'g.modulo_id');
        $query->leftJoin('acl_menu_recurso as mr', function ($join) use ($datos) {
            $join->on('mr.recurso_id', '=', 'acl_recurso.id')
                ->where('mr.menu_id', '=', $datos['menu_id']);
        });

        $query->select(
            'acl_recurso.*',
            'm.modulo',
            'm.id as modulo_id',
            'g.grupo',
            'mr.id as recurso_id',
            \DB::raw("if(ifnull(mr.id,0)>0,'SI','NO') as asignado")
        )
            ->where('acl_recurso.label', '<>', '');

        return $query->get();
    }
}
