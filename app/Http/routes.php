<?php

use App\Venta;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



Route::get('/', 'rh\VacantesController@index');
Route::get('rh/vacantes/json', 'rh\VacantesController@json');
Route::post('rh/vacantes/crear', 'rh\VacantesController@crear');
Route::post('rh/vacantes/eliminar', 'rh\VacantesController@eliminar');

Route::get('/reportes/ventas/lineas', 'reportesGral\VentasLineasController@index');
Route::post('/reportes/ventas/lineas/buscar', 'reportesGral\VentasLineasController@buscar');
Route::get('/reportes/empleados', 'reportesGral\PlantillaController@index');

