<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class provision extends Model
{
    //
    protected $table = "compras_provisiones";
    protected $primaryKey = "id_provision";

    protected $fillable = [
        "fecha",
        "id_usuario",
        "usuario",
        "id_proveedor",
        "proveedor_nombre",
        "clasificacion",
        "id_gasto_clasificacion",
        "estatus",
        "estatus_pronto_pago"
    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("compras_provisiones_descripcion as cpd", "cpd.id_provision", "=", "compras_provisiones.id_provision");

        $campos = [
            "compras_provisiones.*",
            "cpd.folio_fiscal_digital",
            "cpd.subtotal",
            "cpd.descuento",
            "cpd.retenciones",
            "cpd.total",
//            "cpd.iva_retenido",
//            "cpd.isr_retenido"

        ];
        $query->select($campos);


        if (!empty($datos["id_provision"])) {
            $query->where("compras_provisiones.id_provision", "=", $datos["id_provision"]);
            return $query->first();
        }

        return $query->get();
    }
}
