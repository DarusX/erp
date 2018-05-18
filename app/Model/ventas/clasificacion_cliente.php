<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class clasificacion_cliente extends Model
{
    protected $table = 'ventas_clasificacion_clientes';
    protected $fillable = ['clasificacion', 'created_at', 'updated_at'];

    public function buscar($datos){

        $query = $this;

        $query =  $query->select(
            'ventas_clasificacion_clientes.*'
        );

        if(!empty($datos['clasificacion'])){
            $query->where('ventas_clasificacion_clientes.clasificacion','like', '%' . $datos['clasificacion'] . '%');
        }


        return $query->get();
    }
}
