<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class VentasCotizacionesKits extends Model
{

    protected $table = "ventas_cotizaciones_kits";

    protected $fillable = [
        "cantidad",
        "cotizacion_id",
        "kit_id",
        "precio_unitario",
        "subtotal",
        "iva",
        "importe",
        "tiempo_entrega"
    ];

    public function buscar ($datos) {

        $query = $this->from("ventas_cotizaciones_kits as vck");
        $query->leftJoin("ventas_paquetes as vp", "vp.id", "=", "vck.kit_id");
        $query->leftJoin("ventas_paquetes_sucursales as vps", function ($join) use ($datos){
            $join->on("vps.paquete_id", "=", "vp.id")
                ->where("vps.sucursal_id", "=", $datos["sucursal_id"]);
        });

        $query->select(
            "vck.kit_id",
            "vck.cantidad",
            "vp.codigo",
            "vp.descripcion",
            "vck.subtotal",
            "vck.iva",
            "vck.importe",
            "vck.importe",
            \DB::raw("ifnull(vck.tiempo_entrega,'') as tiempo_entrega"),
            "vps.precio_total as precio_unitario",
            //\DB::raw("ifnull(vp.precio,0) as precio"),
            \DB::raw("obtenerSubtotalKit(vck.kit_id,". $datos["sucursal_id"] .") as subtotal_kit")
        );

        if (!empty($datos["cotizacion_id"])) {

            $query->where("vck.cotizacion_id", $datos["cotizacion_id"]);

        }

        return $query->get();

    }

}
