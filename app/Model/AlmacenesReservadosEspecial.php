<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenesReservadosEspecial extends Model
{

    protected $table = "almacenes_reservados_especial";

    protected $primaryKey = "id_reservado_especial";
    public $timestamps = false;

    public function buscar($datos)
    {

        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "almacenes_reservados_especial.id_producto");
        $query->leftJoin("ventas as v", "v.id_venta", "=", "almacenes_reservados_especial.id_venta");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "almacenes_reservados_especial.id_almacen");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "a.id_sucursal");
        $query->leftJoin("clientes as c", "c.id_cliente", "=", "v.id_cliente");

        $query->select(
            "almacenes_reservados_especial.*",
            "c.nombre as cliente",
            \DB::raw("DATEDIFF(CURDATE(), v.fecha) AS dias_transcurridos"),
            \DB::raw("ifnull(v.fecha_compromiso,'S/R') as fecha_compromiso"),
            \DB::raw("(SELECT SUM(ax.existencia) FROM almacenes_existencias AS ax WHERE ax.id_almacen = almacenes_reservados_especial.id_almacen AND ax.id_producto = almacenes_reservados_especial.id_producto) AS existencia"),
            "p.codigo_producto",
            "p.descripcion",
            "s.nombre as sucursal",
            "a.almacen"
        );

        if (!empty($datos["id_sucursal"])){

            $query->where("a.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_almacen"])){

            $query->where("almacenes_reservados_especial.id_almacen", $datos["id_almacen"]);

        }

        if (!empty($datos["codigo_producto"])){

            $query->where("p.codigo_producto", "like", "%". $datos["codigo_producto"] ."%");

        }

        if (!empty($datos["cliente"])){

            $query->where("c.nombre", "like", "%". $datos["cliente"] ."%");

        }

        //dd($query->toSql());
        return $query->get();

    }
}
