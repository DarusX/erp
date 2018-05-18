<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SobrantesFaltantes extends Model
{

    protected $table = "sobrantes_faltantes";

    protected $primaryKey = "id_sobrante_faltante";

    public $timestamps = false;

    protected $fillable = [
        "fecha",
        "rotacion",
        "id_almacen",
        "almacen",
        "id_sucursal",
        "id_producto",
        "id_linea",
        "codigo_producto",
        "descripcion",
        "factor_conversion",
        "faltante",
        "requerido",
        "existencia",
        "stock_minimo",
        "stock_maximo",
        "solicitudes",
        "transferencias",
        "dias_analisis",
        "sf"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "sobrantes_faltantes.id_sucursal");
        $query->leftJoin("almacenes as a", "a.id_almacen", "=", "sobrantes_faltantes.id_almacen");
        $query->leftJoin("productos as p", "p.id_producto", "=", "sobrantes_faltantes.id_producto");
        $query->leftJoin("productos_familias as f", "f.id_familia", "=", "p.id_familia");
        $query->leftJoin("productos_lineas as l", "l.id_linea", "=", "p.id_linea");
        $query->leftJoin("productos_categorias as c", "c.id_categoria", "=", "p.id_categoria");

        $query->select(
            "sobrantes_faltantes.*",
            "s.nombre as sucursal",
            "a.almacen",
            "p.codigo_producto as codigo_producto_a",
            "p.descripcion",
            "f.familia",
            "l.linea",
            "c.categoria"
        );

        if (!empty($datos["id_sucursal"])){

            $query->whereIn("sobrantes_faltantes.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["id_almacen"])){

            $query->whereIn("sobrantes_faltantes.id_almacen", $datos["id_almacen"]);

        }
        if (!empty($datos["producto"])){

            $query->where("p.codigo_producto", "like", "%". $datos["producto"] ."%");

        }
        if (!empty($datos["fecha"])){

            $query->where("sobrantes_faltantes.fecha", ">=", $datos["fecha"]);

        }
        if (!empty($datos["tipo"])){

            $query->where("sobrantes_faltantes.sf", $datos["tipo"]);

        }
        if (!empty($datos["id_familia"])){

            $query->whereIn("p.id_familia", $datos["id_familia"]);

        }
        if (!empty($datos["id_linea"])){

            $query->whereIn("p.id_linea", $datos["id_linea"]);

        }
        if (!empty($datos["id_categoria"])){

            $query->whereIn("p.id_categoria", $datos["id_categoria"]);

        }

        //dd($query->toSql());
        return $query->get();

    }

}
