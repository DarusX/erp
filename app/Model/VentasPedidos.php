<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VentasPedidos extends Model
{
    protected $table = "ventas_pedidos";

    protected $primaryKey = "id_pedido";

    public function buscar($datos)
    {

        $query = $this->from("ventas_pedidos as vp");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "vp.id_sucursal");
        $query->leftJoin("clientes as c", "c.id_cliente", "=", "vp.id_cliente");
        $query->leftJoin("usuarios as u", "u.id_usuario", "=", "vp.id_usuario");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "u.id_empleado");

        $query->select(
            "vp.*",
            "s.nombre as sucursal",
            "c.nombre as cliente",
            \DB::raw("ifnull(concat(e.nombre, ' ', e.apaterno, ' ', e.amaterno), '') as usuario")
        );

        if (!empty($datos["id_pedido"])){

            $query->where("vp.id_pedido", $datos["id_pedido"]);

        }

        if (!empty($datos["first"])){

            return $query->first();

        }

        return $query->get();

    }

}
