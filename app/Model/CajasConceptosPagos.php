<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CajasConceptosPagos extends Model
{
    protected $table = "cajas_conceptos_pagos";

    protected $primaryKey = "id_salida_caja_concepto";

    protected $fillable = [
        "concepto",
        "clave_fiscal",
        "comision",
        "tasa_iva"
    ];

    public function buscar($datos)
    {

        $query = $this->select(
            "cajas_conceptos_pagos.*"
        );

        if (!empty($datos["concepto"])) {
            $query->where("concepto", 'LIKE', '%' . $datos["concepto"] . '%');
        }

        if (!empty($datos["clave_fiscal"])) {
            $query->where("clave_fiscal", $datos["clave_fiscal"]);
            return $query->first();
        }

        return $query->get();

    }
}
