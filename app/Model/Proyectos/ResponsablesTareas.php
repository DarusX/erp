<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;
use DB;

class ResponsablesTareas extends Model
{
    public $table = 'proyectos_tareas_responsables';
    public $fillable = ['id', 'usuario_id', 'tarea_id', 'created_at', 'updated_at',];

    public function buscar($datos)
    {
        $query = $this->leftJoin("proyectos_tareas_asignadas as t", "t.id", "=", "proyectos_tareas_responsables.tarea_id");
        $query->leftJoin("proyectos_tareas as ta", "ta.id", "=", "t.tarea_id");
        $query->leftJoin("usuarios as u", "u.id_usuario", "=", "proyectos_tareas_responsables.usuario_id");
        $query->leftJoin('acl_rol as rol_n', 'rol_n.id', '=', 'u.rol_id');
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "u.id_empleado");
        $query->leftJoin("proyectos_etapas as ep", "ep.id", "=", "t.etapa_id");
        $query->leftJoin("proyectos_proyectos as p", "p.id", "=", "ep.proyecto_id");
        $query->select(
            "proyectos_tareas_responsables.*",
            "u.id_usuario",
            "ta.titulo as tarea",
            \DB::raw("ifnull(rol_n.rol, 'S/R') as rol_nuevo"),
            \DB::raw('CONCAT(e.nombre, " ", e.apaterno, " ", e.amaterno) as nombre_empleado'),
            "ep.id as etapa_id",
            "ep.titulo as etapa",
            "p.id as proyecto_id",
            "p.nombre as proyecto",
            "e.email_empresa as email_empresa",
            "t.fecha_inicio as fecha_inicio",
            "t.fecha_final as fecha_final"
        );
        if (!empty($datos['tarea_id'])) {
            $query->where("proyectos_tareas_responsables.tarea_id", $datos['tarea_id']);
        }
        if (!empty($datos['etapa_id'])) {
            $query->where("ep.id", $datos['etapa_id']);
        }
        if (!empty($datos['proyecto_id'])) {
            $query->where("p.id", $datos['proyecto_id']);
        }

        $query->groupBy("proyectos_tareas_responsables.id");


        return $query->get();
    }

    public function buscarResponsables($datos)
    {
        $query = $this->leftJoin("proyectos_tareas_asignadas as t", "t.id", "=", "proyectos_tareas_responsables.tarea_id");
        $query->leftJoin("usuarios as u", "u.id_usuario", "=", "proyectos_tareas_responsables.usuario_id");
        $query->leftJoin('acl_rol as rol_n', 'rol_n.id', '=', 'u.rol_id');
        $query->select(
            \DB::raw("u.id_usuario"),
            \DB::raw("ifnull(rol_n.rol, 'S/R') as rol_nuevo")
        );
        if (!empty($datos['id'])) {
            $query->where("proyectos_tareas_responsables.tarea_id", $datos['id']);
        }
        /*if(!empty($datos['tarea_id'])){
            $query->where("proyectos_tareas_responsables.tarea_id", $datos['tarea_id']);
        }*/
        $query->groupBy("proyectos_tareas_responsables.id");

        return $query->get();
    }

    public function buscarTareasAsignadas($datos)
    {

        $query = $this->from("proyectos_tareas_responsables as r");
        $query->leftJoin("proyectos_tareas_asignadas as t", "t.id", "=", "r.tarea_id");
        $query->leftJoin("proyectos_etapas as e", "e.id", "=", "t.etapa_id");
        $query->leftJoin("proyectos_proyectos as p", "p.id", "=", "e.proyecto_id");
        $query->leftJoin("usuarios as u", "u.id_usuario", "=", "r.usuario_id");

        $query->select(
            \DB::raw("ifnull(count(r.id),0) as totalTareas")
        );

        $query->where("p.estado", "=", "Autorizado");
        $query->where("t.fecha_inicio", ">=", $datos["fecha_ini"]);
        $query->where("t.fecha_inicio", "<=", $datos["fecha_fin"]);
        $query->where("u.id_empleado", $datos["id_empleado"]);

        if (!empty($datos["estatus"])){

            $query->where("t.estatus", $datos["estatus"]);

        } else {

            $query->where("t.estatus", "<>", "Rechazada");

        }

        //dd($query->toSql());

        return $query->first();

    }

}


