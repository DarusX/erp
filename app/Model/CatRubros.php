<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CatRubros extends Model
{

    protected $table = "cat_rubros";

    protected $fillable = [
        "id_proveedor",
        "cuenta_proveedores",
        "cuenta_compras",
        "cuenta_devoluciones",
        "id_clasificacion_rubro",
        "estatus"
    ];

    public function buscar($datos){

        $query = $this->leftJoin("cat_proveedores as p", "p.id_proveedor", "=", "cat_rubros.id_proveedor");
        $query->leftJoin("contabilidad_cat_cuentas as cp", "cp.id_cuenta", "=", "cat_rubros.cuenta_proveedores");
        $query->leftJoin("contabilidad_cat_cuentas as cc", "cc.id_cuenta", "=", "cat_rubros.cuenta_compras");
        $query->leftJoin("contabilidad_cat_cuentas as cd", "cd.id_cuenta", "=", "cat_rubros.cuenta_devoluciones");
        $query->leftJoin("clasificaciones_rubros as cr", "cr.id", "=", "cat_rubros.id_clasificacion_rubro");

        $query->select(
            "cat_rubros.*",
            "p.nombre as nombre_proveedor",
            "cp.clave as cuentas_proveedores_clave",
            "cp.descripcion as cuentas_proveedores_descripcion",
            "cc.clave as cuentas_compras_clave",
            "cc.descripcion as cuentas_compras_descripcion",
            "cd.clave as cuentas_devoluciones_clave",
            "cd.descripcion as cuentas_devoluciones_descripcion",
            "cr.clasificacion as rubro"
        );

        if (!empty($datos["id_proveedor"])){
            
            $query->where("cat_rubros.id_proveedor", $datos["id_proveedor"]);
            
        }

        if (!empty($datos["estatus"])){

            $query->where("cat_rubros.estatus", $datos["estatus"]);

        }

        //dd($query->toSql());

        return $query->get();

    }

}
