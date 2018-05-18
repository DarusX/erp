<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrdenesCompraSolicitudes extends Model
{

    protected $table = "compras_ordenes_solicitudes";

    protected $fillable = [
        "id_orden",
        "id_orden_descripcion",
        "id_solicitud"
    ];

    public function buscar($datos)
    {

        $query = $this->from("compras_ordenes_solicitudes as cos");
        $query->leftJoin("compras_ordenes as co", "co.id_orden", "=", "cos.id_orden");
        //$query->leftJoin("compras_ordenes_descripcion as cod", "cod.id_orden_descripcion", "=", "cos.id_orden_descripcion");
        $query->leftJoin("compras_solicitudes as cs", "cs.id_solicitud", "=", "cos.id_solicitud");
        $query->leftJoin("productos as p", "p.id_producto", "=", "cs.id_producto");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "cs.id_almacen");
        $query->leftJoin("transferencias_compras_solicitudes as tcs", "tcs.id_solicitud", "=", "cs.id_solicitud");
        $query->leftJoin("transferencias_ordenes_descripcion as tod", "tod.id_transferencia", "=", "tcs.id_transferencia");

        $query->select(
            "cs.*",
            \DB::raw("ifnull(cs.cantidad_nueva,'') as cantidad_nueva"),
            "p.codigo_producto",
            "p.descripcion",
            "p.peso",
            "a.almacen",
            \DB::raw("ifnull(tod.id_transferencia_orden,'N/A') as id_transferencia_orden")
        );

        if (!empty($datos["id_orden"])){
            
            $query->where("cos.id_orden", $datos["id_orden"]);
            
        }

        if (!empty($datos["id_orden_descripcion"])){

            $query->where("cos.id_orden_descripcion", $datos["id_orden_descripcion"]);

        }

        if (!empty($datos["id_orden_descripcion"])){

            $query->where("cos.id_orden_descripcion", $datos["id_orden_descripcion"]);

        }

        if (!empty($datos["first"])){

            return $query->get();

        }

        return $query->get();

    }

}
