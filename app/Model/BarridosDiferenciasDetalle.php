<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BarridosDiferenciasDetalle extends Model
{
    public function buscar($datos)
    {
        $query = $this->from('barridos_diferencias_detalles AS bdd')
            ->select(
                'bdd.*',
                'p.codigo_producto',
                'p.descripcion',
                'a.almacen'
            )
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'bdd.producto_id')
            ->leftJoin('almacenes AS a', 'a.id_almacen', '=', 'bdd.almacen_id');

        if (!empty($datos['almacen_id'])) {
            $query = $query->where('bdd.almacen_id', $datos['almacen_id']);
        }

        if (!empty($datos['producto_id'])) {
            $query = $query->whereIn('bdd.producto_id', $datos['producto_id']);
        }

        return $query->where('bdd.barrido_diferencia_id', $datos['barrido_diferencia_id'])
            ->get();
    }
}