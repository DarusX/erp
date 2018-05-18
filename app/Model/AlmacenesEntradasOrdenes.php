<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenesEntradasOrdenes extends Model
{

    protected $table = "almacenes_entradas_ordenes";

    protected $primaryKey = "id_entrada_orden";

    public function tiempoEntrega($datos)
    {

        $query = $this->from("almacenes_entradas_ordenes as aeo");
        $query->leftJoin("compras_ordenes as co", "co.id_orden", "=", "aeo.id_orden");
        $query->leftJoin("cat_proveedores as cp", "cp.id_proveedor", "=", "co.id_proveedor");
        $query->leftJoin("cat_sucursales as cs", "cs.id_sucursal", "=", "co.id_sucursal");

        $select = [
            \DB::raw("avg(DATEDIFF(aeo.fecha_entrada, IFNULL(co.fecha_autorizacion, co.fecha_orden))) AS dias_entrega"),
            "cp.nombre",
            "cp.id_proveedor",
            "cs.nombre as sucursal"
        ];

        $query->select($select);

        if (!empty($datos["id_sucursal"])){

            $query->where("co.id_sucursal", "=", $datos["id_sucursal"]);

        }

        if (!empty($datos["fecha_inicio"])){

            $query->where(\DB::raw("date(aeo.fecha_entrada)"), ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])){

            $query->where(\DB::raw("date(aeo.fecha_entrada)"), "<=", $datos["fecha_final"]);

        }

        if (isset($datos["individual"])){

            $query->groupBy("co.id_proveedor");

        } else {

            $query->groupBy("aeo.id_orden");

        }

        //dd($query->toSql());
        return $query->get();

    }

}
