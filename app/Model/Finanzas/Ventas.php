<?php

namespace App\Model\Finanzas;

use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    protected $table = 'agr_ventas';

    protected $fillable = [
        'id',
        'cliente_id',
        'tipo_venta_id',
        'fecha',
        'concepto',
        'total_cantidad',
        'peso_total',
        'importe_total',
        'formaPago',
        'metodoPago',
        'NumCtaPago',
        'estatus',
        'venta_tipo',
        'usuario_captura_id',
        'usuario_valida_id'
    ];

    public function reporte($datos){

        $query = $this->leftJoin('agr_cliente as c', 'c.id', '=', 'agr_ventas.cliente_id');

        $query->select(
            'agr_ventas.*',
            'c.nombre',
            'c.rfc',
            'c.calle',
            'c.numero_ext',
            'c.colonia',
            'c.ciudad',
            'c.municipio',
            'c.estado',
            'c.cp',
            'c.email'
        );

        if(!empty($datos['venta_id'])){
            $query->where('agr_ventas.id', $datos['venta_id']);
            return $query->first();
        }
        if(!empty($datos['nombre_cliente'])){
            $query->where('c.nombre', 'LIKE', '%'.$datos['nombre_cliente'].'%');
        }
        if (!empty($datos['fecha_ini'])) {
            $query->where('fecha', '>=', $datos['fecha_ini']);
        }
        if (!empty($datos['fecha_fin'])) {
            $query->where('fecha', '<=', $datos['fecha_fin']);
        }
        if (!empty($datos['venta_tipo'])) {
            $query->where('venta_tipo', '=', $datos['venta_tipo']);
        }

        return $query->get();

    }

}
