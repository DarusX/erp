<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class radioSucursal extends Model
{
    protected $table = 'ventas_areas_entregas';
    protected $fillable = ['radio', 'sucursal_id', 'created_at', 'updated_at'];

    public function buscar($datos){
        $query = $this->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "ventas_areas_entregas.sucursal_id");

        $query->select(
            'ventas_areas_entregas.*',
            's.nombre'
        );

        if(!empty($datos['sucursal_id'])){
            $query->where('ventas_areas_entregas.sucursal_id', $datos['sucursal_id']);
        }

        if(!empty($datos['first'])){
            return $query->first();
        }
    }
}
