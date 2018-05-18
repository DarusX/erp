<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class producto_utilidad extends Model
{
    //
    protected $table = "productos_porcentaje_utilidad";
    protected $fillable = [
        'producto_id',
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

        $query = $this->from('productos_porcentaje_utilidad AS ppu')
            ->select(
                "ppu.*"
            )
            ->where('ppu.sucursal_id', $datos['sucursal_id'])
            ->where('ppu.producto_id', $datos['producto_id'])
            ->where('ppu.tipo_venta_id', $datos['tipo_venta_id']);

        if (isset($datos['first'])) {
            return $query->first();
        }

        return $query->get();

        /*$query = $this->leftJoin("productos as p", "p.id_producto", "=", "productos_porcentaje_utilidad.producto_id");
        $query->leftJoin("cat_sucursales as cs", "cs.id_sucursal", "=", "productos_porcentaje_utilidad.sucursal_id");
        if (!empty($datos["producto_id"]))
            $query->where("productos_porcentaje_utilidad.producto_id", $datos["producto_id"]);
        if (!empty($datos['tipo_venta_id'])) {
            $query = $query->where('productos_porcentaje_utilidad.tipo_venta_id', $datos["tipo_venta_id"]);
        }
        $select = [
            "productos_porcentaje_utilidad.*",
            "p.codigo_producto",
            "p.descripcion",
            "cs.nombre as sucursal"
        ];
        $query->select($select);
        return $query->get();*/
    }

    public function buscarDatos($datos)
    {

        $query = $this->from('productos_porcentaje_utilidad AS ppu')
            ->select(
                "ppu.*",
                \DB::raw("ifnull(ppu.porcentaje,0) as porcentaje")
            )
            ->where('ppu.sucursal_id', $datos['sucursal_id'])
            ->where('ppu.producto_id', $datos['producto_id'])
            ->where('ppu.tipo_precio_id', $datos['tipo_precio_id']);

        if (isset($datos['first'])) {
            return $query->first();
        }

        return $query->get();
    }

}
