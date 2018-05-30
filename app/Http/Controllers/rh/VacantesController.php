<?php

namespace App\Http\Controllers\rh;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\Model\Vacante;

class VacantesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('rh.vacantes');
    }

    public function json()
    {
        return DB::table('rh_empleados AS re')
        ->leftJoin('rh_puestos_sucursales AS rps', 'rps.id_puesto_sucursal', '=', 're.id_puesto_sucursal')
        ->leftJoin('rh_puestos AS rp', 'rp.id_puesto', '=', 'rps.id_puesto')
        ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 're.id_sucursal')
        ->leftJoin('vacantes AS va', 'rps.id_puesto_sucursal', '=', 'va.id_puesto_sucursal')
        ->select(DB::raw('COUNT(*) AS cantidad'),'cs.nombre AS sucursal', 'cs.id_sucursal', 'rp.id_puesto', 'rp.puesto', 'rps.maximo', 'va.id_vacante', 'rps.id_puesto_sucursal')
        ->where('re.estatus', '=', 'activo')
        ->groupBy('re.id_puesto_sucursal')
        ->orderBy('sucursal')
        ->get();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function crear(Request $request)
    {
        Vacante::create($request->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function eliminar(Request $request)
    {
        Vacante::destroy($request->id_vacante);
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
