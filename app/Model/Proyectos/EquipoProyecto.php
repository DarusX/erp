<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;
use DB;

class EquipoProyecto extends Model
{
    protected $table = 'proyectos_equipos';
    protected $fillable = ['usuario_id', 'proyecto_id', 'created_at', 'updated_at'];

    public function equipos()
    {
        return $this->belongsToMany('App\Model\Proyectos\proyecto', 'proyectos_equipos');
    }

    public function buscar($datos)
    {
        $query = $this->leftJoin("proyectos_proyectos as p", "p.id", "=", "proyectos_equipos.proyecto_id");
        $query->leftJoin("usuarios as u", "u.id_usuario", "=", "proyectos_equipos.usuario_id");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "u.id_empleado");
        $query->leftJoin('acl_rol as rol_n', 'rol_n.id', '=', 'u.rol_id');
        $query->select(
            'proyectos_equipos.*',
            \DB::raw("ifnull(rol_n.rol, 'S/R') as rol_nuevo"),
            \DB::raw('CONCAT(e.nombre, " ",e.apaterno, " ", e.amaterno) as nombre_empleado')
        );
        if (!empty($datos['proyecto_id'])) {
            $query->where("proyectos_equipos.proyecto_id", $datos['proyecto_id']);
        }
        $query->groupBy("proyectos_equipos.id");
        //dd($query->toSql());
        return $query->get();
    }
}
