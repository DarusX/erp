<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class provisionDescripcion extends Model
{
    //
    protected $table = "compras_provisiones_descripcion";
    protected $primaryKey = "id_provision_descripcion";

    public function buscar($datos)
    {
        $query = $this->leftJoin("compras_provisiones as p", "p.id_provision", "=", "compras_provisiones_descripcion.id_provision");
        $query->leftJoin("cat_proveedores as cp", "cp.id_proveedor", "=", "p.id_proveedor");
        $query->leftJoin("compras_ordenes_provision as cop","compras_provisiones_descripcion.id_provision","=","cop.provision_id");

        if (!empty($datos["id_proveedor"])) {
            $query->where("cp.id_proveedor", "=", $datos["id_proveedor"]);
        }
        if (!empty($datos["estatus"])) {
            $query->where("p.estatus", "=", $datos["estatus"]);
            $query->whereNull("cop.provision_id");
        }

        $query->select(
            "p.*",
            "compras_provisiones_descripcion.*",
            "cp.nombre as proveedor"
        );

        return $query->get();

    }
}
