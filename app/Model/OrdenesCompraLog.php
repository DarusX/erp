<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrdenesCompraLog extends Model
{
    protected $table = "compras_ordenes_log";

    protected $primarykey = "id_orden_log";

    protected $fillable = [
        'id_orden',
        'log_fecha',
        'observacion'
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("compras_ordenes as oc", "oc.id_orden", "=", "compras_ordenes_log.id_orden");

        $query->select(
            "compras_ordenes_log.*"
        );

        if (!empty($datos["id_orden"])){

            $query->where("compras_ordenes_log.id_orden", $datos["id_orden"]);

        }

        return $query->get();

    }
}
