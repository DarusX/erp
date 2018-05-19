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



Route::get('/', function () {
    return view('welcome');
});

Route::get('/reportes/ventas/lineas', 'reportesGral\VentasLineasController@index');
Route::post('/reportes/ventas/lineas/buscar', 'reportesGral\VentasLineasController@buscar');
Route::get('/reportes/empleados', 'reportesGral\PlantillaController@index');

Route::get('/{year}', function ($year) {
    $diasEntregaPromedioSqlStr = "(SELECT 
    ROUND(SUM(dias) / COUNT(dias), 1)
    FROM
    (SELECT 
        ROUND((DATEDIFF(aeo.fecha_entrada, co.fecha_autorizacion))) AS dias,
            cod.id_producto
    FROM
        compras_ordenes_descripcion AS cod
    LEFT JOIN compras_ordenes AS co ON co.id_orden = cod.id_orden
    LEFT JOIN almacenes_entradas_ordenes AS aeo ON aeo.id_orden = co.id_orden
    WHERE
        aeo.id_orden IS NOT NULL
            AND YEAR(co.fecha_autorizacion) = @anio
    ORDER BY co.id_orden DESC) AS dias
    WHERE
    dias.id_producto = vd.id_producto) AS 'dias_entrega_promedio'";
    $cantidadSqlStr = "SUM(vd.cantidad) AS cantidad";
    $montoVentaSqlStr = "ROUND(SUM(vd.cantidad * vd.precio) , 2) AS 'monto_venta'";
    $precioSqlStr = "ROUND(SUM(vd.precio) , 2) AS precio";
    $montoCostoSqlStr = "ROUND(SUM(vd.costo * vd.cantidad) , 2) AS 'monto_costo'";
    $costoSqlStr = "ROUND(SUM(vd.costo) , 2) AS costo";
    $promTicketSqlStr = "ROUND((SELECT ROUND(SUM(vd3.cantidad * vd3.precio) , 2) FROM ventas_descripcion AS vd3 WHERE vd3.id_venta = v.id_venta AND YEAR(vd3.fecha) = @anio )/(SELECT COUNT(*) FROM ventas_descripcion AS vd4 WHERE vd4.id_venta = v.id_venta AND YEAR(vd4.fecha) = @anio), 2) AS 'prom_ticket'";
    $skuSqlStr = "(SELECT 
    COUNT(*)
    FROM
        ventas_descripcion AS vd2
    WHERE
        vd2.id_venta = v.id_venta
            AND YEAR(vd2.fecha) = @anio) AS 'skus'";
    $promedioSqlStr = "(SELECT 
    ROUND(SUM(vd10.cantidad) / COUNT(*), 2)
    FROM
        ventas_descripcion AS vd10
    WHERE
        vd10.id_producto = vd.id_producto
            AND YEAR(vd10.fecha) = @anio) AS 'promedio'";
    $modaSqlStr = "IFNULL((SELECT 
            vd5.cantidad
        FROM
            ventas_descripcion AS vd5
        WHERE
            vd5.id_producto = vd.id_producto
                AND YEAR(vd5.fecha) = @anio
        GROUP BY vd5.cantidad
        HAVING COUNT(*) > 1
        ORDER BY COUNT(*) DESC
        LIMIT 1),
    0) AS 'moda'";
    $numModaSqlStr = "IFNULL((SELECT 
    COUNT(*)
    FROM
        ventas_descripcion AS vd6
    WHERE
        vd6.id_producto = vd.id_producto
            AND YEAR(vd6.fecha) = @anio
    GROUP BY vd6.cantidad
    HAVING COUNT(*) > 1
    ORDER BY COUNT(*) DESC
    LIMIT 1),
    0) AS 'num_moda'";
    $minSqlStr = "(SELECT 
    MIN(vd7.cantidad)
    FROM
        ventas_descripcion AS vd7
    WHERE
        vd7.id_producto = vd.id_producto
            AND YEAR(vd7.fecha) = @anio
    LIMIT 1) AS 'min'";
    $maxSqlStr = "(SELECT 
        MAX(vd8.cantidad)
    FROM
        ventas_descripcion AS vd8
    WHERE
        vd8.id_producto = vd.id_producto
            AND YEAR(vd8.fecha) = @anio
    LIMIT 1) AS 'max'";
    $ticketsTotalesSqlStr = "(SELECT 
        COUNT(*)
    FROM
        ventas_descripcion AS vd9
    WHERE
        vd9.id_producto = vd.id_producto
            AND YEAR(vd9.fecha) = @anio) AS 'tickets_totales'";
    DB::statement("SET @anio=".$year);

    $productos = DB::table('ventas_descripcion AS vd')
    ->leftJoin('ventas AS v', 'v.id_venta', '=', 'vd.id_venta')
    ->leftJoin('productos AS p', 'p.id_producto', '=', 'vd.id_producto')
    ->leftJoin('productos_familias AS pf', 'pf.id_familia', '=', 'p.id_familia')
    ->leftJoin('productos_lineas AS pl', 'pl.id_linea', '=', 'p.id_familia')
    ->select(
        DB::raw($minSqlStr),
        DB::raw($maxSqlStr), 
        DB::raw($precioSqlStr), 
        DB::raw($ticketsTotalesSqlStr), 
        DB::raw($numModaSqlStr), 
        DB::raw($modaSqlStr), 
        DB::raw($promedioSqlStr), 
        DB::raw($skuSqlStr), 
        DB::raw($diasEntregaPromedioSqlStr), 
        DB::raw($promTicketSqlStr), 
        DB::raw($montoCostoSqlStr), 
        DB::raw($costoSqlStr), 
        DB::raw($cantidadSqlStr), 
        DB::raw($montoVentaSqlStr), 
        'vd.id_producto', 
        'p.factor_conversion', 
        'p.codigo_producto', 
        'p.descripcion', 
        'pf.familia', 
        'pl.linea')
    ->whereYear('vd.fecha', '=', $year)
    ->groupBy('vd.id_producto')
    ->simplePaginate(500);
    return view('reporte')->with([
        'productos' => $productos
    ]);
});
