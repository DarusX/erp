<?php

namespace App\Http\Controllers\reportesGral;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Empleados;
use DB;

class PlantillaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reportesGral.plantilla')->with([
            'empleados' => DB::table('rh_empleados AS re')
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 're.id_sucursal_fiscal')
            ->leftJoin('rh_puestos_sucursales AS rps', 'rps.id_puesto_sucursal', '=', 're.id_puesto_sucursal')
            ->leftJoin('rh_puestos AS rp', 'rp.id_puesto', '=', 'rps.id_puesto')
            ->select(DB::raw('CONCAT(re.nombre, " ", re.apaterno, " ", re.amaterno) AS nombre'), DB::raw('cs.nombre AS sucursal'), 'rp.puesto')
            ->where('estatus', '=', 'activo')
            ->whereNotNull('rp.id_puesto')
            ->orderBy(DB::raw('sucursal, rp.puesto'))
            ->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
