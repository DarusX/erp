<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class VentasCotizacionesDescripcion extends Model
{

    protected $table = "ventas_cotizaciones_descripcion";

    protected $fillable = [
        "cotizacion_id",
        "producto_id",
        "cantidad",
        "precio_unitario",
        "precio_anterior",
        "subtotal",
        "iva",
        "importe",
        "kit_id",
        "texto_libre",
        "codigo_producto_libre",
        "descripcion_libre",
        "variacion_utilidad",
        "utilidad",
        "utilidad_anterior",
        "utilidad_porcentaje",
        "utilidad_anterior_porcentaje",
        "tiempo_entrega"
    ];

    public function buscar ($datos) {

        $query = $this->from("ventas_cotizaciones_descripcion as vcd");

        $query->leftJoin("productos as p", "p.id_producto", "=", "vcd.producto_id");
        $query->leftJoin("iva as i", "i.id_iva", "=", "p.id_iva");
        $query->leftJoin("productos_familias as f", "f.id_familia", "=", "p.id_familia");
        $query->leftJoin("productos_lineas as l", "l.id_linea", "=", "p.id_linea");

        $query->select(
            "vcd.*",
            "p.codigo_producto",
            "p.descripcion",
            "p.peso",
            \DB::raw("p_costo(p.id_producto) as ultimoCosto"),
            "f.familia",
            "l.linea",
            "vcd.cantidad",
            "p.peso",
            \DB::raw("ifnull(vcd.precio_unitario,0) as precio_unitario"),
            \DB::raw("ifnull(vcd.precio_anterior,0) as precio_anterior"),
            \DB::raw("if(p.id_iva = 1,round(vcd.precio_unitario * ((i.porcentaje / 100)+1),2),vcd.precio_unitario) as precio_unitario_iva"),
            \DB::raw("if(p.id_iva = 1,round(vcd.precio_anterior * ((i.porcentaje / 100)+1),2),vcd.precio_anterior) as precio_anterior_iva"),
            \DB::raw("ifnull(vcd.iva,0) as iva"),
            \DB::raw("ifnull(vcd.importe,0) as importe"),
            //\DB::raw("ifnull(vcd.kit_id,'') as kit_id"),
            \DB::raw("ifnull(vcd.utilidad,'') as utilidad"),
            \DB::raw("ifnull(vcd.utilidad_anterior,'') as utilidad_anterior"),
            \DB::raw("ifnull(vcd.tiempo_entrega,'') as tiempo_entrega"),
            \DB::raw("ifnull(vcd.utilidad_porcentaje,'') as utilidad_porcentaje"),
            \DB::raw("ifnull(vcd.utilidad_anterior_porcentaje,'') as utilidad_anterior_porcentaje")
        );

        if (!empty($datos["cotizacion_id"])) {

            $query->where("vcd.cotizacion_id", $datos["cotizacion_id"]);

        }

        if (!empty($datos["kits"])) {

            $query->whereNotNull("vcd.kit_id");
            $query->orderBy("vcd.kit_id", "asc");

        }

        if (!empty($datos["libres"])) {

            $query->where("texto_libre", "=", "si");

        }

        return $query->get();

    }

}
