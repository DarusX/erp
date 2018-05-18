<?php

namespace App\Model\Acciones;

use Illuminate\Database\Eloquent\Model;

class Traslado extends Model
{
    protected $table = 'agr_traslado';

    protected $fillable = [
        'id',
        'usuario_captura_id',
        'fecha_captura',
        'fecha_traslado',
        'rancho_origen_id',
        'rancho_destino_id',
        'estatus'
    ];

    public function reporte($datos){

        $query = $this->leftJoin('usuarios as u', 'u.id_usuario', '=', 'agr_traslado.usuario_captura_id');
        $query->leftJoin('agr_rancho as ro', 'ro.id', '=', 'agr_traslado.rancho_origen_id');
        $query->leftJoin('agr_rancho as rd', 'rd.id', '=', 'agr_traslado.rancho_destino_id');

        $query->select(
            'agr_traslado.*',
            'u.usuario',
            'ro.rancho as rancho_origen',
            'rd.rancho as rancho_destino'
        );

        if(!empty($datos['traslado_id'])){
            $query->where('agr_traslado.id', $datos['traslado_id']);
        }
        if(!empty($datos['rancho_origen_id'])){
            $query->where('rancho_origen_id', $datos['rancho_origen_id']);
        }
        if(!empty($datos['rancho_destino_id'])){
            $query->where('rancho_destino_id', $datos['rancho_destino_id']);
        }
        if (!empty($datos['fecha_ini'])) {
            $query->where('fecha_traslado', '>=', $datos['fecha_ini']);
        }
        if (!empty($datos['fecha_fin'])) {
            $query->where('fecha_traslado', '<=', $datos['fecha_fin']);
        }
        if (!empty($datos["first"])){
            return $query->first();
        }

        return $query->get();

    }
}
