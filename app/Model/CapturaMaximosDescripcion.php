<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CapturaMaximosDescripcion extends Model
{

    protected $table = "captura_maximos_descripcion";

    protected $fillable = [
        "id_existencia",
        "maximo_actual",
        "maximo",
        "id_captura_maximo"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("almacenes_existencias as ae", "ae.id_existencia", "=", "captura_maximos_descripcion.id_existencia");
        $query->leftJoin("captura_maximos as cm", "cm.id", "=", "captura_maximos_descripcion.id_captura_maximo");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "ae.id_sucursal");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "ae.id_almacen");
        $query->leftJoin("productos as p", "p.id_producto", "=", "ae.id_producto");
        $query->leftJoin("productos_familias as f", "f.id_familia", "=", "ae.id_familia");
        $query->leftJoin("productos_lineas as l", "l.id_linea", "=", "ae.id_linea");
        $query->leftJoin("productos_categorias as c", "c.id_categoria", "=", "ae.id_categoria");

        $query->select(
            "captura_maximos_descripcion.*",
            "s.nombre as sucursal",
            "a.almacen",
            "p.codigo_producto",
            "p.descripcion",
            "f.familia",
            "l.linea",
            "c.categoria",
            \DB::raw("ifnull(ae.stock_maximo,0) as stock_maximo"),
            \DB::raw("ifnull(ae.stock_minimo,0) as stock_minimo"),
            "captura_maximos_descripcion.maximo as nuevo_maximo"
        );

        if (!empty($datos["id"])){
            $query->where("id_captura_maximo", $datos["id"]);
        }

        //dd($query->toSql());

        return $query->get();

    }

}
