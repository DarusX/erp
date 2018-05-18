<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cajas extends Model
{
    protected $table = 'cajas';
    protected $fillable = [
        'id_caja',
        'id_sucursal',
        'nombre',
        'referencia_bancaria',
        'referencia_poliza',
        'id_usuario_creo',
        'fecha_creacion',
        'id_usuario_modifico',
        'fecha_modificacion',
        'sufijo_cuenta_poliza'
    ];

    public function buscar($datos){

        $query = $this->select(
            'cajas.*',

            \DB::raw("obtenerSucursalNombre(cajas.id_sucursal) as sucursalNombre")
        );

        if(!empty($datos['id_sucursal'])){
            $query->where('id_sucursal', $datos['id_sucursal']);
        }

        return $query->get();

    }
}
