<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenesReservados extends Model
{

    protected $table = "almacenes_reservados";

    protected $primaryKey = "id_reservado";

    public $timestamps = false;

    public function buscarVentas($datos)
    {

        $query = $this->leftJoin("ventas_descripcion as vd", "vd.id_venta", "=", "almacenes_reservados.id_venta");
        $query->leftJoin("ventas as v", "v.id_venta", "=", "vd.id_venta");
        $query->leftJoin("clientes as c", "c.id_cliente", "=", "v.id_cliente");

        $query->select(
            "v.id_venta",
            "c.nombre",
            "almacenes_reservados.reservado"
        );

        if (!empty($datos["id_sucursal"])){

            $query->where("almacenes_reservados.id_sucursal", $datos["id_sucursal"]);

        }
        if (!empty($datos["id_almacen"])){

            $query->where("almacenes_reservados.id_almacen", $datos["id_almacen"]);

        }
        if (!empty($datos["id_producto"])){

            $query->where("almacenes_reservados.id_producto", $datos["id_producto"]);

        }

        $query->where("almacenes_reservados.reservado", ">", "0");

        $query->groupBy("v.id_venta");

        //dd($query->toSql());
        return $query->get();

    }

    public function reporte($datos)
    {

        $query= $this->leftJoin("productos as p", "p.id_producto", "=", "almacenes_reservados.id_producto");
        $query->leftJoin("ventas as v", "v.id_venta", "=", "almacenes_reservados.id_venta");
        $query->leftJoin("ventas_descripcion as vd", function ($join){
            $join->on("vd.id_venta", "=", "almacenes_reservados.id_venta")
                ->on("almacenes_reservados.id_producto", "=", "vd.id_producto");
        });
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "almacenes_reservados.id_almacen");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "a.id_sucursal");
        $query->leftJoin("clientes as c", "c.id_cliente", "=", "v.id_cliente");

        $query->select(
            "v.id_cliente",
            "v.id_venta",
	        "c.nombre AS cliente",
            \DB::raw("DATEDIFF(CURDATE(), v.fecha) AS dias_transcurridos"),
	        "p.descripcion",
	        "p.codigo_producto",
	        "s.nombre AS sucursal",
	        "a.almacen",
	        "almacenes_reservados.reservado",
	        "vd.costo",
	        "v.fecha",
            \DB::raw("(SELECT SUM(ax.existencia) FROM almacenes_existencias AS ax WHERE ax.id_almacen = almacenes_reservados.id_almacen AND ax.id_producto = almacenes_reservados.id_producto) AS existencia"),
            \DB::raw("ifnull(v.fecha_compromiso,'') as fecha_compromiso"),
            \DB::raw("ifnull(vd.cantidad,'') as solicitado"),
            \DB::raw("(SELECT sum(vjd.cantidad) FROM vehiculos_viajes_detalles AS vjd WHERE vjd.id_producto = vd.id_producto AND vjd.id_venta = vd.id_venta) AS salida_viaje")
        );

        $query->where("almacenes_reservados.reservado", ">", 0);

        if (!empty($datos["id_sucursal"])){

            $query->where("a.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_almacen"])){

            $query->where("almacenes_reservados.id_almacen", $datos["id_almacen"]);

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
