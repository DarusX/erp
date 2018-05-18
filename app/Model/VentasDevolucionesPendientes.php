<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VentasDevolucionesPendientes extends Model
{

    protected $table = "ventas_devoluciones_pendientes";

    protected $fillable = [
        "id_venta",
        "id_devolucion",
        "fecha_venta",
        "fecha_devolucion",
        "fecha_venta_movido",
        "fecha_devolucion_movido",
        "id_empleado_registra",
        "estatus"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("ventas as v", "v.id_venta", "=", "ventas_devoluciones_pendientes.id_venta");

        $query->select(
            "ventas_devoluciones_pendientes.*"
        );

        if (!empty($datos["id_venta"])){

            $query->where("ventas_devoluciones_pendientes.id_venta", $datos["id_venta"]);

        }

        if (!empty($datos["first"])){

            return $query->first();

        }

        return $query->get();

    }

}
