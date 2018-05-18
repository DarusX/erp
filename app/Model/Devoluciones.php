<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Devoluciones extends Model
{

    protected $table = "devoluciones";

    protected $primaryKey = "id_devolucion";

    public $timestamps = false;

    public function buscar($datos)
    {

        $query = $this->leftJoin("ventas as v", "v.id_venta", "=", "devoluciones.id_venta");

        $query->select(
            "devoluciones.*"
        );

        if (!empty($datos["id_venta"])){

            $query->where("devoluciones.id_venta", $datos["id_venta"]);

        }

        if (!empty($datos["first"])){

            return $query->first();

        }

        return $query->get();

    }

}
