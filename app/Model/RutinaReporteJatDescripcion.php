<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class RutinaReporteJatDescripcion extends Model
{
    protected $table = "rutina_jat_reporte_descripcion";

    public function obtenerRegistrosPorRotacion($params)
    {
        $query = $this->from("rutina_jat_reporte_descripcion as rd");
        $query->leftJoin('rutina_jat_reporte as r', 'rd.rutina_reporte_id', '=', 'r.id');
        $query->leftJoin('productos as p', 'rd.producto_id', '=', 'p.id_producto');
        $query->leftJoin('productos_lineas as l', 'p.id_linea', '=', 'l.id_linea');

        $query->select(

            \DB::raw("COUNT(*) as total")

        );

        if (!empty($params["id_sucursal"]))
            $query->where('sucursal_id', $params['id_sucursal']);
        if (!empty($params["rotacion"]))
            $query->where('rotacion', $params['rotacion']);
        if (!empty($params["filtro"]))
            $query->where('filtro', $params['filtro']);
        if (!empty($params["fecha"]))
            $query->where(DB::raw('DATE(r.created_at)'), $params['fecha']);
        if (!empty($params["id_linea"]))
            $query->where('p.id_linea', $params['id_linea']);

        return $query->first();

    }

    public function obtenerRegistrosLineaGeneral($params){
        $query = $this->from("rutina_jat_reporte_descripcion as rd");
        $query->leftJoin('rutina_jat_reporte as r', 'rd.rutina_reporte_id', '=', 'r.id');
        $query->leftJoin('productos as p', 'rd.producto_id', '=', 'p.id_producto');
        $query->leftJoin('productos_lineas as l', 'p.id_linea', '=', 'l.id_linea');

        $query->select(

            'p.id_linea','l.linea'

        );

        if (!empty($params["id_sucursal"]))
            $query->where('sucursal_id',$params['id_sucursal']);
        if (!empty($params["rotacion"]))
            $query->where('rotacion',$params['rotacion']);
        if (!empty($params["filtro"]))
            $query->where('filtro',$params['filtro']);
        if (!empty($params["fecha"]))
            $query->where(DB::raw('DATE(r.created_at)'),$params['fecha']);

        $query->groupBy('p.id_linea');
        return $query->get();

    }

    public function obtenerRegistrosProductoGeneral($params)
    {
        $query = $this->from("rutina_jat_reporte_descripcion as rd");
        $query->leftJoin('rutina_jat_reporte as r', 'rd.rutina_reporte_id', '=', 'r.id');
        $query->leftJoin('productos as p', 'rd.producto_id', '=', 'p.id_producto');
        $query->leftJoin('productos_lineas as l', 'p.id_linea', '=', 'l.id_linea');

        $query->select(

            'rd.producto_id','rd.codigo_producto','rd.descripcion','l.linea',
            \DB::raw("COUNT(*) as total")

        );

        if (!empty($params["id_sucursal"]))
            $query->where('sucursal_id', $params['id_sucursal']);
        if (!empty($params["rotacion"]))/*ROTACION (ALTA, MEDIA, BAJA)*/
            $query->where('rotacion', $params['rotacion']);
        if (!empty($params["filtro"]))/*FILTRO (EXISTENCIA CERO, FALTANTE, SOBRANTE)*/
            $query->where('filtro', $params['filtro']);
        if (!empty($params["fecha"]))
            $query->where(DB::raw('DATE(r.created_at)'), $params['fecha']);
        if (!empty($params["id_linea"]))
            $query->where('p.id_linea', $params['id_linea']);

        $query->groupBy('rd.producto_id');
        return $query->get();

    }

    public function obtenerLineaCantidad($params)
    {
        $query = $this->from("rutina_jat_reporte_descripcion as rd");
        $query->leftJoin('rutina_jat_reporte as r', 'rd.rutina_reporte_id', '=', 'r.id');
        $query->leftJoin('productos as p', 'rd.producto_id', '=', 'p.id_producto');
        $query->leftJoin('productos_lineas as l', 'p.id_linea', '=', 'l.id_linea');

        $query->select(

            'l.id_linea','l.linea',
            \DB::raw("COUNT(*) as total")

        );

        if (!empty($params["id_sucursal"]))
            $query->where('sucursal_id', $params['id_sucursal']);
        if (!empty($params["rotacion"]))/*ROTACION (ALTA, MEDIA, BAJA)*/
            $query->where('rotacion', $params['rotacion']);
        if (!empty($params["filtro"]))/*FILTRO (EXISTENCIA CERO, FALTANTE, SOBRANTE)*/
            $query->where('filtro', $params['filtro']);
        if (!empty($params["fecha"]))
            $query->where(DB::raw('DATE(r.created_at)'), $params['fecha']);
        if (!empty($params["id_linea"]))
            $query->where('p.id_linea', $params['id_linea']);

        $query->groupBy('p.id_linea');
//        $query->distinct('p.id_producto');
        return $query->get();

    }

    public function obtenerSucursalCantidad($params)
    {
        $query = $this->from("rutina_jat_reporte_descripcion as rd");
        $query->leftJoin('rutina_jat_reporte as r', 'rd.rutina_reporte_id', '=', 'r.id');
        $query->leftJoin('productos as p', 'rd.producto_id', '=', 'p.id_producto');
        $query->leftJoin('productos_lineas as l', 'p.id_linea', '=', 'l.id_linea');

        $query->select(

            'rd.sucursal_id as id_sucursal','rd.sucursal_nombre as sucursal',
            \DB::raw("COUNT(*) as total")

        );

        if (!empty($params["id_sucursal"]))
            $query->where('sucursal_id', $params['id_sucursal']);
        if (!empty($params["rotacion"]))/*ROTACION (ALTA, MEDIA, BAJA)*/
            $query->where('rotacion', $params['rotacion']);
        if (!empty($params["filtro"]))/*FILTRO (EXISTENCIA CERO, FALTANTE, SOBRANTE)*/
            $query->where('filtro', $params['filtro']);
        if (!empty($params["fecha"]))
            $query->where(DB::raw('DATE(r.created_at)'), $params['fecha']);
        if (!empty($params["id_linea"]))
            $query->where('p.id_linea', $params['id_linea']);

        $query->groupBy('rd.sucursal_id');
        return $query->get();

    }

    public function obtenerProductosLineaGeneral($params){
        $query = $this->from("rutina_jat_reporte_descripcion as rd");
        $query->leftJoin('rutina_jat_reporte as r', 'rd.rutina_reporte_id', '=', 'r.id');
        $query->leftJoin('productos as p', 'rd.producto_id', '=', 'p.id_producto');
        $query->leftJoin('productos_lineas as l', 'p.id_linea', '=', 'l.id_linea');

        $query->select(

            \DB::raw("COUNT(*) as total")

        );

        if (!empty($params["id_sucursal"]))
            $query->where('sucursal_id', $params['id_sucursal']);
        if (!empty($params["rotacion"]))/*ROTACION (ALTA, MEDIA, BAJA)*/
            $query->where('rotacion', $params['rotacion']);
        if (!empty($params["filtro"]))/*FILTRO (EXISTENCIA CERO, FALTANTE, SOBRANTE)*/
            $query->where('filtro', $params['filtro']);
        if (!empty($params["fecha"]))
            $query->where(DB::raw('DATE(r.created_at)'), $params['fecha']);
        if (!empty($params["id_linea"]))
            $query->where('p.id_linea', $params['id_linea']);

//        $query->distinct('p.id_producto');
        return $query->get();
    }
}
