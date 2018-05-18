<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BarridosDiferencia extends Model
{
    public function buscar($datos)
    {
        $query = $this->from('barridos_diferencias AS bd')
            ->select(
                'bd.*',
                'cs.nombre AS sucursal'
            )
            ->leftJoin('barridos_diferencias_detalles AS bdd', 'bdd.barrido_diferencia_id', '=', 'bd.id')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'bd.sucursal_id');

        if ($datos['sucursal_id'] != 0) {
            $query = $query->where('bd.sucursal_id', $datos['sucursal_id']);
        }

        if (!empty($datos['almacen_id'])) {
            $query = $query->whereIn('bdd.almacen_id', $datos['almacen_id']);
        }

        if (!empty($datos['producto_id'])) {
            $query = $query->whereIn('bdd.producto_id', $datos['producto_id']);
        }

        return $query->groupBy('bd.id')
            ->get();
    }
}