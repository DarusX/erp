<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Hash;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $fillable = [
        'id',
        'usuario',
        'nombre',
        'email',
        'password',
        'rol_id',
        'imagen',
        'estatus',
        'cambiar-pass'
    ];

    public function setPasswordAttribute($password){

        $this->attributes['password'] = Hash::make($password);

    }

    public  function buscar($datos){
        $query = $this->leftJoin('acl_rol as rol', 'rol.id', '=', 'usuarios.rol_id');

        $query->select(
            'usuarios.*',
            'rol.rol',
            \DB::raw('fechaCheque(curdate()) as fecha'),
            \DB::raw('obtenerCajaNombre(usuarios.id_caja) as cajaNombre')
        );

        if(!empty($datos['nombre'])){
            $query->where('nombre', 'LIKE', '%'.$datos['nombre'].'%');
        }
        if(!empty($datos['id'])){
            $query->where('usuarios.id', $datos['id']);
            return $query->first();
        }

        return $query->get();
    }
}
