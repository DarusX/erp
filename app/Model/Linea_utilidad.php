<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Linea_utilidad extends Model
{
    //
    protected $table = "productos_lineas_porcentaje_utilidad";
    protected $fillable = [
        'linea_id',
        'sucursal_id',
        'porcentaje',
        'nuevo_porcentaje',
        'tipo_venta_id',
        'tipo_precio_id',
        'empleado_captura_utilidad_id',
        'empleado_valida_utilidad_id',
        'empleado_autoriza_utilidad_id'
    ];

    public function buscar($datos)
    {

        $query = $this->from('productos_lineas_porcentaje_utilidad AS plpu')
            ->select(
                "plpu.*"
            )
            ->where('plpu.sucursal_id', $datos['sucursal_id'])
            ->where('plpu.linea_id', $datos['linea_id'])
            ->where('plpu.tipo_venta_id', $datos['tipo_venta_id']);

        if (isset($datos['first'])) {
            return $query->first();
        }

        return $query->get();

        /*$query = $this->leftJoin("cat_sucursales as cs", "cs.id_sucursal","=", "productos_lineas_porcentaje_utilidad.sucursal_id");
        $query->leftJoin("productos_lineas as l", "l.id_linea","=","productos_lineas_porcentaje_utilidad.linea_id");

        if(!empty($datos["linea_id"]))
            $query->where("l.id_linea","=",$datos["linea_id"]);

        if (!empty($datos['tipo_venta_id'])) {
            $query = $query->where('productos_lineas_porcentaje_utilidad.tipo_venta_id', $datos["tipo_venta_id"]);
        }

        $select = [
            "productos_lineas_porcentaje_utilidad.*",
            "cs.nombre as sucursal",
            "l.linea"
        ];
        $query->select($select);


        return $query->get();*/
    }

    public function buscarDatos($datos)
    {

        $query = $this->from('productos_lineas_porcentaje_utilidad AS plpu')
            ->select(
                "plpu.*"
            )
            ->where('plpu.sucursal_id', $datos['sucursal_id'])
            ->where('plpu.linea_id', $datos['linea_id'])
            ->where('plpu.tipo_precio_id', $datos['tipo_precio_id']);

        if (isset($datos['first'])) {
            return $query->first();
        }

        return $query->get();

    }

}


