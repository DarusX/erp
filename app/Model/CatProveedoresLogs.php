<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CatProveedoresLogs extends Model
{

    protected $table = "cat_proveedores_logs";

    protected $fillable = [
        "proveedor_id",
        "empleado_id",
        "comentario"
    ];

    public function buscar($datos)
    {

        $query = $this->from("cat_proveedores_logs as cpl");
        $query->leftJoin("cat_proveedores as p", "p.id_proveedor", "=", "cpl.proveedor_id");
        $query->leftJoin("rh_empleados as r", "r.id_empleado", "=", "cpl.empleado_id");

        $query->select(
            "cpl.*",
            "p.nombre as proveedor",
            \DB::raw("concat(r.nombre, ' ', r.apaterno, ' ', r.amaterno) as empleado")
        );

        if (!empty($datos["proveedor_id"])){

            $query->where("cpl.proveedor_id", $datos["proveedor_id"]);

        }

        //dd($query->toSql());
        return $query->get();

    }

}
