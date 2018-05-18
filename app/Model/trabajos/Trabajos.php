<?php

namespace App\Model\trabajos;

use Illuminate\Database\Eloquent\Model;

class
Trabajos extends Model
{
    protected $table = 'agr_trabajos';

    protected $fillable = [
        "trabajo",
        "responsable_id",
        "rancho_id",
        "sucursal_id",
        "proyecto_id",
        "inmueble_id",
        "usuario_captura_id",
        "fecha_programacion",
        "fecha_inicio",
        "fecha_termino",
        "fecha_inicializacion",
        "fecha_terminacion",
        "responsable_inicia_id",
        "responsable_termina_id",
        "fecha_realizacion",
        "comentarios",
        "foto",
        "tipo",
        "status",
        "observacion_auditoria",
        "cumplido_satisfactoriamente"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin('usuarios as ur', 'ur.id_usuario', '=', 'agr_trabajos.responsable_id');
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "ur.id_empleado");
        $query->leftJoin("rh_puestos_sucursales as ps", "ps.id_puesto_sucursal", "=", "e.id_puesto_sucursal");
        $query->leftJoin("rh_puestos as p", "ps.id_puesto", "=", "p.id_puesto");
        $query->leftJoin('usuarios as uc', 'uc.id_usuario', '=', 'agr_trabajos.usuario_captura_id');
        $query->leftJoin('agr_trabajos_recurrentes_detalles as trd', 'trd.trabajo_id', '=', 'agr_trabajos.id');
        $query->leftJoin('agr_trabajos_recurrentes as tr', 'tr.id', '=', 'trd.trabajo_recurrente_id');
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "agr_trabajos.sucursal_id");
        $query->leftJoin("catalogos_proyectos_inmobiliaria as pro", "pro.id", "=", "agr_trabajos.proyecto_id");
        $query->leftJoin("inmuebles_inmobiliaria as i", "i.id", "=", "agr_trabajos.inmueble_id");
        $query->leftJoin("agr_rancho as r", "r.id", "=", "agr_trabajos.rancho_id");

        $query->select(
            'agr_trabajos.*',
            'ur.nombre as responsable',
            \DB::raw("ifnull(agr_trabajos.rancho_id,'') as rancho_id"),
            'uc.nombre as usuario_captura',
            'agr_trabajos.trabajo as title',
            'agr_trabajos.fecha_inicio as start',
            'agr_trabajos.fecha_termino as end',
            \DB::raw("ifnull(agr_trabajos.fecha_inicializacion,'') as fecha_inicializacion"),
            \DB::raw("ifnull(agr_trabajos.fecha_terminacion,'') as fecha_terminacion"),
            \DB::raw("ifnull(agr_trabajos.observacion_auditoria,'') as observacion_auditoria"),
            \DB::raw("ifnull(agr_trabajos.cumplido_satisfactoriamente,'') as cumplido_satisfactoriamente"),
            'tr.id as recurrente',
            \DB::raw('if(agr_trabajos.status = "Realizado", "#378006", if(agr_trabajos.status = "Proceso", "#DBB100", if(agr_trabajos.status = "Auditado", "#7a04b1", "#0489B1"))) as color'),
            \DB::raw("ifnull(s.nombre,'S/R') as sucursal"),
            \DB::raw("ifnull(pro.nombre,'S/R') as proyecto"),
            \DB::raw("ifnull(i.nombre,'S/R') as inmueble"),
            \DB::raw("ifnull(r.rancho,'S/R') as rancho"),
            "p.departamento"
        //\DB::raw('"true" as allDay')
        );

        if (!empty($datos['responsable_id'])) {
            $query->where('agr_trabajos.responsable_id', $datos['responsable_id']);
        }
        if (!empty($datos['fecha_ini'])) {
            $query->where('fecha_inicio', '>=', $datos['fecha_ini']);
        }
        if (!empty($datos['fecha_fin'])) {
            $query->where('fecha_termino', '<=', $datos['fecha_fin']);
        }
        if (!empty($datos['trabajo_id'])) {
            $query->where('agr_trabajos.id', $datos['trabajo_id']);
            return $query->first();
        }
        if (!empty($datos['tipo_id'])) {
            $query->where('agr_trabajos.tipo', $datos['tipo_id']);
        }
        if (!empty($datos['estatus'])) {
            $query->where('agr_trabajos.status', $datos['estatus']);
        }
        if (!empty($datos['trabajo'])) {
            $query->where('agr_trabajos.trabajo', 'LIKE', '%' . $datos['trabajo'] . '%');
        }
        if (isset($datos["rancho"])){
            if (!empty($datos["rancho_id"])){
                $query->where("agr_trabajos.rancho_id", $datos["rancho_id"]);
            } else {
                $query->where("agr_trabajos.rancho_id", "<>", "");
            }
        }
        if (isset($datos["sucursal"])){
            if (!empty($datos["sucursal_id"])){
                $query->where("agr_trabajos.sucursal_id", $datos["sucursal_id"]);
            } else {
                $query->where("agr_trabajos.sucursal_id", "<>", "");
            }
        }
        if (isset($datos["proyecto"])){
            if (!empty($datos["proyecto_id"])){
                $query->where("agr_trabajos.proyecto_id", $datos["proyecto_id"]);
                if (!empty($datos["inmueble_id"])){
                    $query->where("agr_trabajos.inmueble_id", $datos["inmueble_id"]);
                }
            } else {
                $query->where("agr_trabajos.proyecto_id", "<>", "");
            }
        }
        if (isset($datos["departamento"])){
            $query->where("p.departamento", $datos["departamento"]);
        }
        if (!empty($datos["activos"])){
            $query->where("agr_trabajos.status", "<>", "Cancelado");
        }

        //dd($query->toSql());
        return $query->get();

    }

    public function trabajosBono($datos)
    {

        $query = $this->leftJoin("usuarios as u", "u.id_usuario", "=", "agr_trabajos.responsable_id");

        $query->select(
            \DB::raw("count(agr_trabajos.id) as trabajos")
        );

        $query->where("fecha_programacion", ">=", $datos["fecha_ini"]);
        $query->where("fecha_programacion", "<=", $datos["fecha_fin"]);

        if(!empty($datos["auditado"])){
            $query->where("status", "Auditado");
            $query->where("cumplido_satisfactoriamente", "si");
        }

        if(!empty($datos["empleado"])){
            $query->where("u.id_empleado", $datos["empleado"]);
        }

        return $query->get();

    }

    public function trabajosRancho($datos)
    {

        $query = $this->leftJoin("agr_rancho as r", "r.id", "=", "agr_trabajos.rancho_id");
        $query->leftJoin("usuarios as u", "u.id_usuario", "=", "agr_trabajos.responsable_id");

        $query->select(
            "r.id as rancho_id",
            "r.rancho",
            \DB::raw("count(agr_trabajos.id) as totales"),
            \DB::raw("sum(if(agr_trabajos.cumplido_satisfactoriamente = 'si',1,0)) as cumplidos")
        );

        $query->where("agr_trabajos.fecha_programacion", ">=", $datos["fecha_ini"]);
        $query->where("agr_trabajos.fecha_programacion", "<=", $datos["fecha_fin"]);

        if(!empty($datos["empleado"])){
            $query->where("u.id_empleado", $datos["empleado"]);
        }

        $query->groupBy("r.id");

        return $query->get();

    }

}
