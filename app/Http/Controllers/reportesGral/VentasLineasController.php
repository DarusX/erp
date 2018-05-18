<?php

namespace App\Http\Controllers\reportesGral;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Lineas;
use DB;

class VentasLineasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reportesGral.ventasLineas')->with([
            'lineas' => Lineas::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buscar(Request $request)
    {
        return DB::table('ventas AS v')
        ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'v.id_sucursal')
        ->leftJoin('ventas_descripcion AS vd', 'vd.id_venta', '=', 'v.id_venta')
        ->leftJoin('productos AS p', 'p.id_producto', '=', 'vd.id_producto')
        ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'v.id_usuario')
        ->select('cs.nombre AS sucursal', 'p.codigo_producto', 'p.descripcion', DB::raw('SUM(vd.cantidad) AS cantidad'), DB::raw('SUM(vd.precio) AS monto'), 'u.nombre AS vendedor')
        ->whereBetween(DB::raw('DATE(v.fecha)'), [$request->fecha_inicio, $request->fecha_termino])
        ->where('p.id_linea', '=', $request->id_linea)
        ->groupBy('v.id_usuario', 'vd.id_producto')
        ->get(); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
