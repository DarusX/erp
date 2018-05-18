<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ConversionesSucursalesDescripcion extends Model
{

    protected $table = "conversiones_sucursales_descripcion";
    protected $primaryKey = "id_conversion_descripcion";
    public $timestamps = false;

    public function buscarOrigen($datos)
    {

        $query = $this->leftJoin("conversiones_sucursales as cs", "cs.id_conversion", "=", "conversiones_sucursales_descripcion.id_conversion");
        $query->leftJoin("productos as p", "p.id_producto", "=", "conversiones_sucursales_descripcion.id_producto_origen");

        $query->select(
            "conversiones_sucursales_descripcion.*",
            "cs.fecha"
        );

//        if (!empty($datos["id_sucursal"])){
//
//            $query->where("conversiones_sucursales_descripcion.id_sucursal", $datos["id_sucursal"]);
//
//        }

        if (!empty($datos["id_almacen"])){

            $query->where("conversiones_sucursales_descripcion.id_almacen_origen", $datos["id_almacen"]);

        }

        if (!empty($datos["codigo_producto"])){

            $query->where("p.codigo_producto", $datos["codigo_producto"]);

        }

        if (!empty($datos["fecha_inicio"])){

            $query->where("cs.fecha",  ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])){

            $query->where("cs.fecha",  "<=", $datos["fecha_final"]);

        }

        return $query->get();

    }

    public function buscarDestino($datos)
    {

        $query = $this->leftJoin("conversiones_sucursales as cs", "cs.id_conversion", "=", "conversiones_sucursales_descripcion.id_conversion");
        $query->leftJoin("productos as p", "p.id_producto", "=", "conversiones_sucursales_descripcion.id_producto_destino");

        $query->select(
            "conversiones_sucursales_descripcion.*",
            "cs.fecha"
        );

//        if (!empty($datos["id_sucursal"])){
//
//            $query->where("conversiones_sucursales_descripcion.id_sucursal", $datos["id_sucursal"]);
//
//        }

        if (!empty($datos["id_almacen"])){

            $query->where("conversiones_sucursales_descripcion.id_almacen_destino", $datos["id_almacen"]);

        }

        if (!empty($datos["codigo_producto"])){

            $query->where("p.codigo_producto", $datos["codigo_producto"]);

        }

        if (!empty($datos["fecha_inicio"])){

            $query->where("cs.fecha",  ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])){

            $query->where("cs.fecha",  "<=", $datos["fecha_final"]);

        }

        return $query->get();

    }

    public function almacenOrigen(){
        return $this->belongsTo(Almacenes::class, 'id_almacen_origen');
    }

    public function almacenDestino(){
        return $this->belongsTo(Almacenes::class, 'id_almacen_destino');
    }

    public function productoOrigen(){
        return $this->belongsTo(Productos::class, 'id_producto_origen');
    }

    public function productoDestino(){
        return $this->belongsTo(Productos::class, 'id_producto_destino');
    }
}