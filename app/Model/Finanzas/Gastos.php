<?php

namespace App\Model\Finanzas;

use Illuminate\Database\Eloquent\Model;

class Gastos extends Model
{
    protected $table = 'agr_gastos';

    protected $fillable = [
        'id',
        'fecha',
        'rancho_id',
        'tipo',
        'proveedor',
        'proveedor_id',
        'descripcion',
        'importe_total',
        'estatus',
        'usuario_captura_id',
        'usuario_valida_id',
        'usuario_autoriza_id',
        'usuario_cancela_id',
        'fecha_captura',
        'fecha_valida',
        'fecha_autoriza',
        'fecha_cancela',
    ];

    public function buscar ($datos)
    {

        $query = $this->leftJoin('agr_rancho as r', 'r.id', '=', 'agr_gastos.rancho_id');
        $query->leftJoin('agr_proveedor as p', 'p.id', '=', 'agr_gastos.proveedor');
        $query->leftJoin('agr_estados as e', 'e.id', '=', 'p.estado_id');
        $query->leftJoin('agr_gasto as g', 'g.id', '=', 'agr_gastos.tipo');
        $query->leftJoin('usuarios as ucap', 'ucap.id_usuario', '=', 'agr_gastos.usuario_captura_id');
        $query->leftJoin('usuarios as uval', 'uval.id_usuario', '=', 'agr_gastos.usuario_valida_id');
        $query->leftJoin('usuarios as uaut', 'uaut.id_usuario', '=', 'agr_gastos.usuario_autoriza_id');
        $query->leftJoin('usuarios as ucan', 'ucan.id_usuario', '=', 'agr_gastos.usuario_cancela_id');

        $query->select(
            'agr_gastos.*',
            'r.rancho',
            'p.proveedor',
            \DB::raw('ifnull(p.rfc,"S/R") as rfc'),
            \DB::raw('ifnull(p.domicilio,"S/R") as domicilio'),
            \DB::raw('ifnull(p.colonia,"S/R") as colonia'),
            \DB::raw('ifnull(e.estado,"S/R") as estado'),
            \DB::raw('ifnull(p.cp,"S/R") as cp'),
            \DB::raw('ifnull(p.correo,"S/R") as correo'),
            \DB::raw('ifnull(g.gasto,"S/R") as gasto'),
            \DB::raw('ifnull(ucap.nombre,"S/R") as usuario_captura'),
            \DB::raw('ifnull(uval.nombre,"S/R") as usuario_valida'),
            \DB::raw('ifnull(uaut.nombre,"S/R") as usuario_autoriza'),
            \DB::raw('ifnull(ucan.nombre,"S/R") as usuario_cancela')
        );

        if(!empty($datos['gasto_id'])){
            $query->where('agr_gastos.id', $datos['gasto_id']);
        }
        if(!empty($datos['tipo'])){
            $query->where('agr_gastos.tipo', $datos['tipo']);
        }
        if(!empty($datos['rancho_id'])){
            $query->where('agr_gastos.rancho_id', $datos['rancho_id']);
        }
        if(!empty($datos['proveedor_id'])){
            $query->where('agr_gastos.proveedor_id', $datos['proveedor_id']);
        }
        if(!empty($datos['estatus'])){
            $query->where('agr_gastos.estatus', $datos['estatus']);
        }
        if (!empty($datos['fecha_ini'])) {
            $query->where('fecha', '>=', $datos['fecha_ini']);
        }
        if (!empty($datos['fecha_fin'])) {
            $query->where('fecha', '<=', $datos['fecha_fin']);
        }

        //dd($query->toSql());
        return $query->get();

    }

    public function obtenerGasto($datos)
    {

        $query = $this->leftJoin("agr_rancho as r", "r.id", "=", "agr_gastos.rancho_id");

        $query->select(
            "r.id as rancho_id",
            "r.rancho",
            \DB::raw("sum(agr_gastos.importe_total) as total_rancho")
        );

        $query->where("agr_gastos.fecha", ">=", $datos["fecha_ini"]);
        $query->where("agr_gastos.fecha", "<=", $datos["fecha_fin"]);

        $query->groupBy("r.id");

        return $query->get();

    }

}
