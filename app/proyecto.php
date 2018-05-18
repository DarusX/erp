<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class proyecto extends Model
{
    //Variables de la base de datos
    protected $table = 'proyectos_proyectos';
    protected $fillable = ['nombre', 'categoria_id', 'lider_id', 'admin_id', 'estado', 'observador_id', 'created_at', 'updated_at', 'creador_id', 'fecha_val','val_id', 'fecha_aut','aut_id','fecha_rechazo','rechazo_id','porcentaje','costo_total','dias_totales'];

    //Declaración de relaciones
    public function categoria()
    {
        return $this->hasOne('App\categoria');
    }
    public function materiale()
    {
        return $this->belongsTo('App\materiale');
    }
    public function usuario()
    {
        return $this->hasOne('App\usuario','id_usuario', 'lider_id');
    }
    public function usuario2()
    {
        return $this->hasOne('App\usuario','id_usuario', 'admin_id');
    }
    public function usuario3(){
        return $this->belongsToMany('App\usuario','proyectos_observadores','proyecto_id','usuario_id');
    }
    public function usuario4(){
        return $this->hasOne('App\usuario', 'id_usuario','creador_id');
    }
    public function usuario5(){
        return $this->hasOne('App\usuario', 'id_usuario', 'val_id');
    }
    public function usuario6(){
        return $this->hasOne('App\usuario','id_usuario', 'aut_id');
    }
    public function usuario7(){
        return $this->hasOne('App\usuario', 'id_usuario', 'rechazo_id');
    }
    public function usuarios(){
        return $this->belongsToMany('App\User', 'proyectos_observadores', 'proyecto_id', 'usuario_id');
    }
    public function sucursales(){
        return $this->belongsToMany('App\Sucursales', 'proyectos_sucursales', 'proyecto_id','sucursales_id');
    }
    public function etapas(){
        return $this->hasMany('App\etapa', 'proyectos_etapas');
    }

    //fin de declaración de relaciones

    //Método para consultar los datos de la tabla.
    public function buscar($datos){
        $query = $this->leftJoin("proyectos_categorias as c", "c.id", "=", "proyectos_proyectos.categoria_id");
            $query->leftJoin("usuarios as u", "u.id_usuario", "=", "proyectos_proyectos.lider_id");
            $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "u.id_empleado");
            $query->leftJoin("usuarios as u1", "u1.id_usuario", "=", "proyectos_proyectos.admin_id");
            $query->leftJoin("rh_empleados as e1", "e1.id_empleado", "=", "u1.id_empleado");
            $query->leftJoin("usuarios as u2", "u2.id_usuario", "=", "proyectos_proyectos.creador_id");
            $query->leftJoin("rh_empleados as e2", "e2.id_empleado", "=", "u2.id_empleado");
            $query->leftJoin("usuarios as u3", "u3.id_usuario", "=", "proyectos_proyectos.val_id");
            $query->leftJoin("rh_empleados as e3", "e3.id_empleado", "=", "u3.id_empleado");
            $query->leftJoin("usuarios as u4", "u4.id_usuario", "=", "proyectos_proyectos.aut_id");
            $query->leftJoin("rh_empleados as e4", "e4.id_empleado", "=", "u4.id_empleado");
            $query->leftJoin("proyectos_sucursales as s", "s.proyecto_id", "=", "proyectos_proyectos.id");

        $query->select(
            'proyectos_proyectos.*',
            'c.nombre as categoria',
            'proyectos_proyectos.nombre as proyecto',
            'proyectos_proyectos.id as id_proyecto',
            \DB::raw('CONCAT(e.nombre, " ", e.apaterno, " ", e.amaterno) as nombre_lider'),
            \DB::raw('CONCAT(e1.nombre, " ", e1.apaterno, " ", e1.amaterno) as nombre_admin'),
            \DB::raw('CONCAT(e2.nombre, " ", e2.apaterno, " ", e2.amaterno) as nombre_creador'),
            \DB::raw('CONCAT(e3.nombre, " ", e3.apaterno, " ", e3.amaterno) as nombre_val'),
            \DB::raw('CONCAT(e4.nombre, " ", e4.apaterno, " ", e4.amaterno) as nombre_aut')


        );
        //condiciones para filtrar los datos según los parametros de busqueda.
        if(!empty($datos['proyecto'])){
            $query->where("proyectos_proyectos.id", $datos['proyecto']);
        }
        if(!empty($datos['categoria'])){
            $query->where("proyectos_proyectos.categoria_id", $datos['categoria']);
        }
        if(!empty($datos['sucursal'])){
            $query->whereIn("s.sucursales_id", $datos['sucursal']);
        }

        if(!empty($datos['estatus'])){
            $query->where("proyectos_proyectos.estado", $datos['estatus']);
        }
        if(!empty($datos['tipo_fecha']) && !empty($datos['fecha']) && !empty($datos['fecha2'])){
           switch($datos['tipo_fecha']){
               case "creacion":
                   $fecha_inicio=$datos['fecha'];
                   $fecha_final = $datos['fecha2'];
                   $query->whereRaw("proyectos_proyectos.created_at >= ? AND proyectos_proyectos.created_at <= ?", array($fecha_inicio." 00:00:00", $fecha_final." 23:59:59"));
                   break;
               case "autorizacion":
                   $query->where(DB::raw("DATE(proyectos_proyectos.fecha_aut)"),'>=', $datos['fecha'])
                       ->where(DB::raw("DATE(proyectos_proyectos.fecha_aut)"),'<=', $datos['fecha2']);
                   break;
               case "validacion":
                   $query->where(DB::raw("DATE(proyectos_proyectos.fecha_val)"), '>=', $datos['fecha'])
                   ->where(DB::raw("DATE(proyectos_proyectos.fecha_val)"),'<=', $datos['fecha2']);
                   break;
               case "rechazo":
                   $query->where(DB::raw("DATE(proyectos_proyectos.fecha_rechazo)"),'>=', $datos['fecha'])
                   ->where(DB::raw("DATE(proyectos_proyectos.fecha_rechazo)"),'<=', $datos['fecha2']);
                   break;
           }
        }
        //Evita que se dupliquen los resultados del filtrado
        $query->groupBy("proyectos_proyectos.id");

        return $query->get();
    }


}
