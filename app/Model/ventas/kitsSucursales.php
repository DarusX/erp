<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class kitsSucursales extends Model
{
    protected $table = 'ventas_paquetes_sucursales';

    protected $fillable = ['paquete_id', 'sucursal_id','precio_total','diferencia','precio_anterior'];



    public function buscar($datos){
        $query = $this->leftJoin('ventas_paquetes as p','p.id', '=', 'ventas_paquetes_sucursales.paquete_id');
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "ventas_paquetes_sucursales.sucursal_id");

        $query->select(
            'ventas_paquetes_sucursales.*',
            's.nombre',
            'p.codigo AS paquete',
            's.nombre'
            //'s.id_sucursal'

        );

        if(!empty($datos['sucursal_id'])){
            $query->where('ventas_paquetes_sucursales.sucursal_id', $datos['sucursal_id']);
        }
        if(!empty($datos['paquete_id'])){
            $query->where('ventas_paquetes_sucursales.paquete_id', $datos['paquete_id']);
        }


        if (!empty($datos['sucursal_id'])) {
            $query->where('ventas_paquetes_sucursales.sucursal_id', $datos['sucursal_id']);
        }
        if (!empty($datos['paquete_id'])) {
            $query->where('ventas_paquetes_sucursales.paquete_id', $datos['paquete_id']);
        }

        if (!empty($datos['activo'])) {
            $query->where('p.activo', $datos['activo']);
        }

        if(!empty($datos['first'])){
            return $query->first();
        }

        return $query->get();
    }
}
