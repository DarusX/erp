<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenConteo extends Model
{
    protected $table = 'almacenes_conteos';
    protected $primaryKey = 'id_conteo';
    public $timestamps = false;

    public function buscarConteo($datos)
    {
        $query = $this->select(
            'a.id_almacen',
            'almacenes_conteos.id_sucursal',
            'p.id_producto',
            'almacenes_conteos.cantidad',
            'almacenes_conteos.cantidad_real'
        )
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'almacenes_conteos.id_producto')
            ->leftJoin('almacenes AS a', 'a.id_almacen', '=', 'almacenes_conteos.id_almacen')
            ->whereIn('almacenes_conteos.estatus', ['VALIDADO', 'VALIDACION'])
            //->where('almacenes_conteos.id_sucursal', $datos['sucursal_id']);
            ->where('almacenes_conteos.id_almacen', $datos['almacen_id']);
        if (!empty($datos['producto_id'])) {
            $query = $query->whereIn('p.codigo_producto', $datos['producto_id']);
        }

        return $query->get();
    }

    /*
     * FUNCIÓN PARA ELIMINAR CONTEOS EN UNA SUCURSAL
     * Y CODIGOS DE PRODUCTOS SELECCIONADOS
     * */
    /**
     * @param $datos
     */
    public function eliminarConteo($datos)
    {

        $query = $this->leftJoin('productos AS p', 'p.id_producto', '=', 'almacenes_conteos.id_producto')
            ->leftJoin('almacenes AS a', 'a.id_almacen', '=', 'almacenes_conteos.id_almacen')
            ->whereIn('almacenes_conteos.estatus', ['VALIDADO', 'VALIDACION'])
            ->where('almacenes_conteos.id_almacen', $datos['almacen_id']);
        if (!empty($datos['producto_id'])) {
            $query = $query->whereIn('p.codigo_producto', $datos['producto_id']);
        }

        $query->delete();
    }
}
