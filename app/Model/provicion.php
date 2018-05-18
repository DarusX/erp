<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class provicion extends Model
{
    //
    protected $table = "compras_provisiones";
    protected $primaryKey = "id_provision";

    public function buscar($datos)
    {
        $query = $this->leftJoin("cat_proveedores as cp", "cp.id_proveedor","=", "compras_provisiones.id_proveedor");
        if (!empty($datos["id_proveedor"])) {
            $query->where("cp.id_proveedor","=", $datos["id_proveedor"]);
        }

        $query->select(
            "compras_provisiones.*",
            "cp.nombre as proveedor"
        );

        return $query->get();

    }
}
