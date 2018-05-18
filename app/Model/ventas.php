<?php

namespace App\Model;

use App\Model\ventas\Clientes;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ventas extends Model
{
    protected $table = "ventas";
    protected $primaryKey = "id_venta";
    public $timestamps = false;

    public function buscar($datos)
    {
        $query = $this->leftJoin("clientes as c", "c.id_cliente", "=", "ventas.id_cliente");
        $query->leftJoin("ventas_tipos as vt", "vt.id_tipo_venta", "=", "ventas.id_tipo_venta");
        $query->leftJoin("cat_sucursales AS cs", "cs.id_sucursal", "=", "ventas.id_sucursal");
        $query->select(
            "ventas.*",
            "c.nombre",
            "c.rfc",
            "vt.tipo as tipo_venta",
            "cs.nombre AS sucursal"
        );

        if (!empty($datos["id_venta"])) {
            $query->where("ventas.id_venta", $datos["id_venta"]);
            if (!empty($datos["first"])) {
                return $query->first();
            }
        }

        return $query->get();
    }

    public function buscarDatos($datos)
    {
        $query = $this->leftJoin("clientes as c", "c.id_cliente", "=", "ventas.id_cliente");
        $query->leftJoin("ventas_tipos as vt", "vt.id_tipo_venta", "=", "ventas.id_tipo_venta");
        $query->leftJoin("ventas_pagos as vp", "vp.id_venta", "=", "ventas.id_venta");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "ventas.id_sucursal");
        $query->leftJoin("ventas_devoluciones_pendientes as vdp", "vdp.id_venta", "=", "ventas.id_venta");
        $query->leftJoin("devoluciones as d", "d.id_venta", "=", "ventas.id_venta");

        $query->select(
            "ventas.*",
            \DB::raw("ifnull(d.id_devolucion,'') as id_devolucion"),
            "c.nombre as cliente",
            "s.nombre as sucursal",
            "c.rfc",
            "vt.tipo as tipo_venta",
            "vp.tipo as tipo_pago",
            \DB::raw("ifnull(vdp.estatus,'') as estatus_devolucion_pendiente"),
            \DB::raw("ifnull(d.fecha,'') as fecha_devolucion")
        );

        if (!empty($datos["id_venta"])) {
            $query->where("ventas.id_venta", $datos["id_venta"]);
            if (!empty($datos["first"])) {
                return $query->first();
            }
        }
        return $query->get();
    }

    public function totalVentas($parametros)
    {
        $query = $this->from("ventas as v")
            ->select(
                \DB::raw("ifnull(sum(v.subtotal),0) as total_ventas"),
                'v.id_sucursal'
            );

        $query = $query->whereRaw("!ventaTieneDevolucion(v.id_venta)")
            ->whereBetween(\DB::raw('DATE(v.fecha)'), [$parametros['fecha_inicio'], $parametros['fecha_fin']])
            ->whereRaw('!(SELECT COUNT(*) FROM tae_detalles WHERE tae_detalles.id_venta = v.id_venta)')
            ->where('v.id_sucursal', 3);

        if (isset($parametros['sucursal_id'])) {
            return $query->where('v.id_sucursal', $parametros['sucursal_id'])
                ->first();
        } else {
            $query = $query->groupBy('v.id_sucursal');
        }
        return $query->get();
    }

    public function descuentos($datos)
    {

        $query = $this->from("ventas as v")
            ->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "v.id_sucursal")
            ->leftJoin("clientes as c", "c.id_cliente", "=", "v.id_cliente")
            ->leftJoin("ventas_tipos as t", "t.id_tipo_venta", "=", "v.id_tipo_venta");

        $select = [
            "s.nombre as sucursal",
            "v.id_venta",
            "v.fecha",
            "c.nombre as cliente",
            "v.subtotal",
            "v.iva",
            "v.total",
            "t.tipo",
            \DB::raw("obtenerTotalVentaPVigente (v.id_venta) AS subtotal_precio_vigente"),
            \DB::raw("obtenerCostoVenta (v.id_venta) AS costo_venta"),
            \DB::raw("(((v.subtotal - obtenerTotalVentaPVigente (v.id_venta)) / obtenerTotalVentaPVigente (v.id_venta)) * 100) AS porcentaje_descuento"),
            \DB::raw("(v.subtotal - obtenerCostoVenta (v.id_venta)) AS utilidad_dinero"),
            \DB::raw("(obtenerTotalVentaPVigente (v.id_venta) - obtenerCostoVenta (v.id_venta)) AS utilidad_dinero_vigente"),
            \DB::raw("(((v.subtotal - obtenerCostoVenta (v.id_venta)) / obtenerCostoVenta (v.id_venta)) * 100) AS utilidad_porcentaje"),
            \DB::raw("(((obtenerTotalVentaPVigente (v.id_venta) - obtenerCostoVenta (v.id_venta)) / obtenerCostoVenta (v.id_venta)) * 100) AS utilidad_porcentaje_vigente"),
            \DB::raw("ifnull(obtenerUltimaValidacionDescuento(v.id_venta),'') as ultima_validacion"),
            \DB::raw("ifnull(obtenerUltimaValidacionDescuentoID(v.id_venta),'') as ultima_validacion_id")
        ];

        $query->select($select);

        $query->where("v.estatus", "=", "Pagada");
        $query->whereRaw("ventaTieneDevolucion (v.id_venta) = 0");
        $query->whereRaw("ventaTienesRecarga (v.id_venta) = 0");
        $query->whereRaw("checarDiferenciasPreciosVenta (v.id_venta) > 0");

        if (!empty($datos["id_sucursal"])) {

            if (count($datos["id_sucursal"]) > 1) {

                $query->whereIn("v.id_sucursal", $datos["id_sucursal"]);

            } else {

                $query->where("v.id_sucursal", $datos["id_sucursal"]);

            }

        }

        if (!empty($datos["fecha_inicio"])) {

            $query->where("v.fecha", ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])) {

            $query->where("v.fecha", "<=", $datos["fecha_final"]);

        }

        $query->groupBy("v.id_venta");

        if (!empty($datos["id_rol"])) {

            $query->having("ultima_validacion_id", "=", $datos["id_rol"]);

        }

        $query->orderBy("v.id_sucursal", "asc");

        //dd($query->toSql());

        return $query->get();

    }

    public function totalVenta($datos)
    {

        $query = $this->from("ventas as v");

        $query->select(
            \DB::raw("sum(v.total) as subtotal")
        );

        $query->where("v.estatus", "=", "Pagada");
        $query->where("v.fecha", ">=", $datos["fecha_inicio"]);
        $query->where("v.fecha", "<=", $datos["fecha_final"]);
        $query->whereRaw("!ventaTieneDevolucion(v.id_venta)");
        $query->whereRaw('!(SELECT COUNT(*) FROM tae_detalles WHERE tae_detalles.id_venta = v.id_venta)');

        //dd($query->toSql());

        return $query->first();

    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'id_cliente');
    }

    public function partidas(){
        return $this->hasMany(venta_descripcion::class, 'id_venta');
    }

}
