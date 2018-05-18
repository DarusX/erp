<?php

namespace App\Model\Acciones;

use Illuminate\Database\Eloquent\Model;

class Bajas extends Model
{
    protected $table = 'agr_animal_baja';

    protected $fillable = [
        'id',
        'animal_id',
        'motivo_id',
        'causa_muerte_id',
        'comentario',
        'usuario_captura_id',
        'usuario_valida_id',
        'usuario_autoriza_id',
        'usuario_cancela_id',
        'fecha_captura',
        'fecha_valida',
        'fecha_autoriza',
        'fecha_cancela',
        'estatus',
    ];

    public function reporte($datos){

        $query = $this->leftJoin('agr_animal as a', 'a.id', '=', 'agr_animal_baja.animal_id');
        $query->leftJoin('agr_motivo_baja as m', 'm.id', '=', 'agr_animal_baja.motivo_id');
        $query->leftJoin('usuarios as ucap', 'ucap.id_usuario', '=', 'agr_animal_baja.usuario_captura_id');
        $query->leftJoin('usuarios as uval', 'uval.id_usuario', '=', 'agr_animal_baja.usuario_valida_id');
        $query->leftJoin('usuarios as uaut', 'uaut.id_usuario', '=', 'agr_animal_baja.usuario_autoriza_id');
        $query->leftJoin('usuarios as ucan', 'ucan.id_usuario', '=', 'agr_animal_baja.usuario_cancela_id');
        $query->leftJoin('agr_potrero as p', 'p.id', '=', 'a.potrero_id');
        $query->leftJoin('agr_rancho as r', 'r.id', '=', 'p.rancho_id');
        $query->leftJoin('agr_ventas_descripcion as vd', 'vd.animal_id', '=', 'agr_animal_baja.animal_id');

        $query->select(
            'agr_animal_baja.*',
            'a.numero',
            'a.potrero_id',
            'ucap.usuario',
            'uval.usuario',
            'uaut.usuario',
            'ucan.usuario',
            'r.rancho',
            'p.potrero',
            'm.motivo',
            'vd.venta_id',
            'a.imagen_baja'
        );

        if(!empty($datos['animal_id'])){
            $query->where('agr_animal_baja.animal_id', $datos['animal_id']);
            if (!empty($datos["first"])){
                return $query->first();
            }
        }
        if(!empty($datos['motivo_id'])){
            $query->where('motivo_id', $datos['motivo_id']);
        }
        if(!empty($datos['rancho_id'])){
            $query->where('r.id', $datos['rancho_id']);
        }
        if(!empty($datos['potrero_id'])){
            $query->where('p.id', $datos['potrero_id']);
        }
        if(!empty($datos['estatus'])){
            $query->where('agr_animal_baja.estatus', $datos['estatus']);
        }
        if (!empty($datos['fecha_ini'])) {
            $query->where('fecha_captura', '>=', $datos['fecha_ini']);
        }
        if (!empty($datos['fecha_fin'])) {
            $query->where('fecha_captura', '<=', $datos['fecha_fin']);
        }
        if (!empty($datos["diferente"])){
            $query->where("estatus", "<>", "cancelado");
        }

        //dd($query->toSql());
        return $query->get();

    }
}
