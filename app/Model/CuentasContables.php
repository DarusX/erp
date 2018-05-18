<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CuentasContables extends Model
{

    protected $table = "contabilidad_cat_cuentas";

    protected $primaryKey = "id_cuenta";

    public function buscar($datos)
    {

        $query = $this->leftJoin("contabilidad_cat_cuentas as cp", "cp.id_cuenta", "=", "contabilidad_cat_cuentas.id_cuenta_padre");
        $query->leftJoin("contabilidad_cat_cuentas_rubros as cr", "cr.id_rubro", "=", "contabilidad_cat_cuentas.id_rubro");

        $query->select(
            "contabilidad_cat_cuentas.*",
            "cp.descripcion as padre_descripcion",
            "cp.clave as padre_clave",
            "cr.rubro"
        );

        if (!empty($datos["clave"])){
            if (!empty($datos["first"])){
                $query->where("contabilidad_cat_cuentas.clave", $datos["clave"]);
                return $query->first();
            }
            $query->where("contabilidad_cat_cuentas.clave", "like", $datos["clave"] ."%");
        }

        if (!empty($datos["claveDescripcion"])){

            $query->where("contabilidad_cat_cuentas.clave", "like", "%". $datos["claveDescripcion"]. "%")
                ->orWhere("contabilidad_cat_cuentas.descripcion", "like", "%". $datos["claveDescripcion"]. "%");

        }

        if (!empty($datos["id_cuenta"])){
            $query->where("contabilidad_cat_cuentas.id_cuenta", $datos["id_cuenta"]);
            if (!empty($datos["first"])){
                return $query->first();
            }
        }

        return $query->get();

    }
    
    public function comprobar($datos)
    {

        $query = $this;

        $query->select(
            "contabilidad_cat_cuentas.*"
        );

        $query = $query->where("clave", "like", $datos["clave"]."%");

        $query->where("clave", ">", $datos["clave"]);

        $query->where("estatus_activo", "Si");

        return $query->get();

    }

}
