<?php

namespace App\Model\ventas;

use App\User;
use Illuminate\Database\Eloquent\Model;

class VentasCotizaciones extends Model
{

    protected $table = "ventas_cotizaciones";

    protected $fillable = [
        "sucursal_id",
        "cliente_id",
        "usuario_id",
        "venta_descuento",
        "subtotal",
        "iva",
        "total",
        "estatus_validacion",
        "venta_especial",
        "tiempo_entrega",
        "fecha_compromiso",
        "url_pdf",
        "envio",
        "utilidad_real",
        "utilidad_nueva",
        "utilidad_real_porcentaje",
        "utilidad_nueva_porcentaje"
    ];

    public function buscar($datos)
    {

        $query = $this->from("ventas_cotizaciones as vc");

        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "vc.sucursal_id");
        $query->leftJoin("clientes as c", "c.id_cliente", "=", "vc.cliente_id");
        $query->leftJoin("usuarios as u", "u.id_usuario", "=", "vc.usuario_id");

        $select = [
            "vc.*",
            "vc.cliente_id",
            "vc.url_pdf",
            "vc.sucursal_id",
            "vc.estatus_validacion",
            "vc.envio",
            "s.nombre as sucursal",
            "c.nombre as cliente",
            "u.nombre as usuario",
            \DB::raw("ifnull(vc.subtotal,0) as subtotal"),
            \DB::raw("ifnull(vc.iva,0) as iva"),
            \DB::raw("ifnull(vc.total,0) as total"),
            "vc.created_at as fecha",
            \DB::raw("ventaTieneDescuento(vc.id) as validaciones"),
            \DB::raw("obtenerTotalPartidasCotizacion(vc.id) as totalPartidas"),
            \DB::raw("obtenerTotalPartidasCotizacionDescuento(vc.id) as totalPartidasDescuento"),
            \DB::raw("ifnull(vc.utilidad_real,'') as utilidad_real"),
            \DB::raw("ifnull(vc.utilidad_nueva,'') as utilidad_nueva"),
            \DB::raw("ifnull(vc.utilidad_real_porcentaje,'') as utilidad_real_porcentaje"),
            \DB::raw("ifnull(vc.utilidad_nueva_porcentaje,'') as utilidad_nueva_porcentaje"),
            \DB::raw("ifnull((SELECT r.rol FROM ventas_cotizaciones_validaciones_descuentos AS vd LEFT JOIN acl_rol AS r ON vd.rol_id = r.id WHERE vd.cotizacion_id = vc.id ORDER BY orden DESC LIMIT 1),'') as ultimaValidacion")
        ];

        if (!empty($datos['cliente'])) {
            $query->where('c.nombre', 'LIKE', '%' . $datos['cliente'] . '%');
        }

        if (!empty($datos['fecha_inicial'])) {
            $query->where(\DB::raw('DATE(vc.created_at)'), '>=', $datos['fecha_inicial']);
        }

        if (!empty($datos['fecha_final'])) {
            $query->where(\DB::raw('DATE(vc.created_at)'), '<=', $datos['fecha_final']);
        }

        if (!empty($datos["id"])) {
            $query = $query->where("vc.id", $datos["id"]);
        }

        if (!empty($datos["cliente"])) {

            $query->where("c.nombre", "like", "%" . $datos["cliente"] . "%");

        }

        if (!empty($datos["fecha_inicio"])) {

            $query->where("vc.created_at", ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_fin"])) {

            $query->where("vc.created_at", "<=", $datos["fecha_fin"]);

        }


        if (!empty($datos["limite"])) {
            $query->limit($datos["limite"]);
        }


        if (!empty($datos["venta_descuento"])) {

            $query->where("venta_descuento", $datos["venta_descuento"]);

        } /*else {

            $query->where("venta_descuento", "no");

        }*/

        if (!empty($datos["validacion_pendiente"])) {

            if ($datos["validacion_pendiente"] == "si") {

                $query->havingRaw("validaciones > 0");

            } else {

                $query->havingRaw("validaciones = 0");

            }

        }

        if (!empty($datos["pendientes_rol"])) {

            $query->whereRaw("ventaDescuentoPendienteRol(vc.id, " . $datos["pendientes_rol"] . ") > 0");

        }

        if (!empty($datos["rol_id"])) {

            $select[] = \DB::raw("(select if((select count(vv.id) from ventas_cotizaciones_validaciones_descuentos as vv where vv.cotizacion_id = vc.id and vv.rol_id = ". $datos['rol_id'] ." and vv.estado = 'Pendiente') > 0,'si','no')) as validacionRol");
            $query->having("validacionRol", "=", "si");

        }

        if (!empty($datos['sucursal_id'])) {
            $query = $query->where('vc.sucursal_id', $datos['sucursal_id']);
        }

        $query->select($select);

        if (!empty($datos["limite"])) {
            $query->limit($datos["limite"]);
        }

        if (!empty($datos["first"])) {
            return $query->first();
        }

        return $query->get();

    }

    public function flete()
    {
        return $this->hasOne(entregasFletes::class, 'cotizacion_id');
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
