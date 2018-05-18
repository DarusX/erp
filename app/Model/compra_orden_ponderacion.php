<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class compra_orden_ponderacion extends Model
{
    //
    protected $table = "compras_ordenes_ponderacion";
    protected $fillable = [
        'orden_compra_id',
        'sucursal_id',
        'porcentaje',
        'provision_ponderacion_id'


    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("cat_sucursales as s", "sucursal_id", "=", "id_sucursal");
        if (!empty($datos["orden_compra_id"])) {
            $query->where("orden_compra_id", $datos["orden_compra_id"]);
        }

        $select = [
            \DB::raw("(obtenerTotalOC(compras_ordenes_ponderacion.orden_compra_id) *(compras_ordenes_ponderacion.porcentaje/100) )as porcentaje_dinero"),
            "compras_ordenes_ponderacion.*",
            "s.nombre as sucursal", "s.id_sucursal"
        ];
        $query->select($select);


        return $query->get();
    }
}
