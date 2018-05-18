<?php

namespace App\Model\Acciones;

use Illuminate\Database\Eloquent\Model;

class Palpacion extends Model
{
    protected $table = 'agr_palpacion';

    protected $fillable = [
        'id',
        'animal_id',
        'estado',
        'dias_gestacion',
        'fecha',
        'estatus',
        'condicion',
        'cria',
        'fecha_probable',
        'comentario',
        'status'
    ];

    public function buscar($datos){

        $query = $this->leftJoin('agr_animal as a', 'a.id', '=', 'agr_palpacion.animal_id');
        $query->leftJoin('agr_potrero as p', 'p.id', '=', 'a.potrero_id');
        $query->leftJoin('agr_rancho as r', 'r.id', '=', 'p.rancho_id');

        $query->select(
            'agr_palpacion.*', 
            'a.numero',
            'p.potrero',
            'r.rancho',
            \DB::raw('ifnull(agr_palpacion.estatus,"No aplica") as estatus'),
            \DB::raw('ifnull(dias_gestacion,"No aplica") as dias_gestacion1'),
            \DB::raw('ifnull(fecha_probable,"No aplica") as fecha_probable'),
            \DB::raw('ifnull(DATEDIFF(fecha_probable, NOW()),0) AS diferencia')
        );

        //$query->where("agr_palpacion.status", "activo");

        if(!empty($datos['animal_id'])){
            $query->where('agr_palpacion.animal_id', $datos['animal_id']);
        }
        if(!empty($datos['estado'])){
            $query->where('agr_palpacion.estado', $datos['estado']);
        }
        if(!empty($datos['estatus'])){
            $query->where('agr_palpacion.estatus', $datos['estatus']);
        }
        if(!empty($datos['rancho_id'])){
            $query->where('p.rancho_id', $datos['rancho_id']);
        }
        if(!empty($datos['potrero_id'])){
            $query->where('a.potrero_id', $datos['potrero_id']);
        }
        if(!empty($datos['condicion'])){
            $query->where('agr_palpacion.condicion', $datos['condicion']);
        }
        if (!empty($datos['fecha_ini'])) {
            $query->where('fecha', '>=', $datos['fecha_ini']);
        }
        if (!empty($datos['fecha_fin'])) {
            $query->where('fecha', '<=', $datos['fecha_fin']);
        }
        if(!empty($datos['status'])){
            $query->where('agr_palpacion.status', $datos['status']);
        }
        if(!empty($datos['animal_status']) && $datos['animal_status'] == 1){
            $query->where('a.status', 'Activo');
        }
        if(!empty($datos["fecha_probable_ini"])){
            $query->where("fecha_probable", ">=", $datos["fecha_probable_ini"]);
        }
        if(!empty($datos["fecha_probable_fin"])){
            $query->where("fecha_probable", "<=", $datos["fecha_probable_fin"]);
        }
        if(!empty($datos["ordenar"])){
            $query->orderBy("fecha_probable", "asc");
        }

        //dd($query->toSql());
        return $query->get();

    }

    public function buscarProbables($datos)
    {

        $query = $this->leftJoin("agr_animal as a", "a.id", "=", "agr_palpacion.animal_id");
        $query->leftJoin("agr_potrero as p", "p.id", "=", "a.potrero_id");
        $query->leftJoin("agr_rancho as r", "r.id", "=", "p.rancho_id");

        $query->select(
            "r.id as rancho_id",
            "r.rancho",
            \DB::raw("count(agr_palpacion.id) as probables")
        );

        $query->where("agr_palpacion.fecha_probable", ">=", $datos["fecha_ini"]);
        $query->where("agr_palpacion.fecha_probable", "<=", $datos["fecha_fin"]);
        $query->groupBy("r.id");

        //dd($query->toSql());
        return $query->get();

    }

}
