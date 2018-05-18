<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class provisionPonderacion extends Model
{
    //
    protected $table = "compras_provisiones_ponderaciones";
    protected $primaryKey = "id_ponderacion";
    protected $fillable = [
        'id_provision',
        'id_sucursal',
        'importe_sin_impuesto',
        'importe',
        'porcentaje'
    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("compras_provisiones as cp", "cp.id_provision", "=", "compras_provisiones_ponderaciones.id_provision");

        $campos = [
            "compras_provisiones_ponderaciones.*"
        ];
        $query->select($campos);

        if (!empty($datos["id_provision"])) {
            $query->where("compras_provisiones_ponderaciones.id_provision", "=", $datos["id_provision"]);
        }

        return $query->get();

    }

}
