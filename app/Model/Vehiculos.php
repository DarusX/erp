<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Vehiculos extends Model
{
    protected $table = "vehiculos";

    protected $primarykey = "id_vehiculo";

    public function buscar($datos)
    {
        $query = $this->leftJoin('productos as p', 'p.id_producto', '=', 'vehiculos.id_producto');
        $query->leftJoin('cat_sucursales as s', 's.id_sucursal', '=', 'vehiculos.id_sucursal');
        $query->leftJoin("vehiculos_empleados_asignacion as vc", function ($join){
            $join->on("vc.id_vehiculo", "=", "vehiculos.id_vehiculo")
                ->where("vc.activo", "=", "SI");
        });
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "vc.id_empleado");

        $query->select(
            'vehiculos.*',
            'p.descripcion',
            's.nombre',
            'p.imagen',
            'vc.id_empleado',
            \DB::raw("ifnull(concat(e.nombre, ' ', e.apaterno, ' ', e.amaterno),'S/R') as empleado_asignado")
        );

        if (!empty($datos["vehiculo"])){
            $query->where("p.descripcion", "like", "%".$datos["vehiculo"]."%");
        }

        if(!empty($datos['id_vehiculo'])){
            $query->where('vehiculos.id_vehiculo', $datos['id_vehiculo']);
            if (!empty($datos["first"])){
                return $query->first();
            }
        }

        if (!empty($datos["id_sucursal"])){
            $query->where("vehiculos.id_sucursal", $datos["id_sucursal"]);
        }

        if (!empty($datos["limit"])){
            $query->limit(15);
        }

        if (!empty($datos["activo"])){
            $query->where("vehiculos.activo", $datos["activo"]);
        }

        //dd($query->toSql());
        return $query->get();
    }
}
