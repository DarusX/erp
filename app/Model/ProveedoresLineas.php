<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProveedoresLineas extends Model
{

    protected $table = "proveedores_lineas";

    protected $fillable = [
        "id_proveedor",
        "id_linea"
    ];

    protected $primaryKey = "id_proveedor_linea";

    public function buscar ($datos){

        $query = $this->leftJoin("cat_proveedores as p", "p.id_proveedor", "=", "proveedores_lineas.id_proveedor");
        $query->leftJoin("productos_lineas as l", "l.id_linea", "=", "proveedores_lineas.id_linea");

        $query->select(
            "proveedores_lineas.*",
            "p.nombre as nombre_proveedor",
            "l.linea"
        );

        if (!empty($datos["id_proveedor"])){

            $query->where("proveedores_lineas.id_proveedor", $datos["id_proveedor"]);

        }

        //dd($query->toSql());

        return $query->get();

    }

    public function obtenerDiasProveedor($id_linea)
    {

        $query = $this->from("proveedores_lineas as pl");
        $query->leftJoin("cat_proveedores as cp", "cp.id_proveedor", "=", "pl.id_proveedor");

        $query->select(
            \DB::raw("ifnull(avg(cp.tiempo_entrega),0) as dias")
        );

        $query->where("pl.id_linea", "=", $id_linea);

        $query->whereNotNull("cp.tiempo_entrega");

        return $query->first();

    }

}
