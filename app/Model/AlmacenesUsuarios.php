<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenesUsuarios extends Model
{
    protected $table = 'almacenes_usuarios';

    protected $fillable = [
        'id_almacen',
        'id_usuario',
        'almacen_respaldo',
        'fecha_vencimiento',
        'usuario_captura_id'
    ];

    public function buscar($datos){
        $query = $this->leftJoin('almacenes as a', 'a.id_almacen', '=', 'almacenes_usuarios.id_almacen');
        $query->leftJoin('cat_sucursales as s', 's.id_sucursal', '=', 'a.id_sucursal');
        $query->leftJoin('usuarios as ua', 'ua.id_usuario', '=', 'almacenes_usuarios.id_usuario');
        $query->leftJoin('rh_empleados as ea', 'ea.id_empleado', '=', 'ua.id_empleado');
        $query->leftJoin('usuarios as uc', 'uc.id_usuario', '=', 'almacenes_usuarios.usuario_captura_id');

        $query->select(
            'almacenes_usuarios.*',
            'a.almacen',
            'a.id_sucursal',
            's.nombre',
            'ua.usuario',
            'ua.id_empleado',
            \DB::raw('CONCAT(ea.nombre, " ", ea.apaterno, " ", ea.amaterno) AS empleado_asignado'),
            'uc.nombre as usuario_captura'
        );

        if(!empty($datos['almacen'])){
            $query->where('a.almacen', 'like', '%'.$datos['almacen'].'%');
        }

        //dd($query->toSql());
        return $query->get();
    }
}
