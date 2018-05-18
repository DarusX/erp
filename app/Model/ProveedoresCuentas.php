<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProveedoresCuentas extends Model
{

    protected $table = "cat_proveedores_cuentas";

    protected $primaryKey = "id_proveedor_cuenta";

    protected $fillable = [
        "id_proveedor",
        "id_cuenta",
        "id_banco",
        "n_sucursal",
        "cuenta_bancaria",
        "clabe",
        "estatus_cuenta_proveedor"
    ];

    public function buscar($datos){

        $query = $this->leftJoin("cat_proveedores as p", "p.id_proveedor", "=", "cat_proveedores_cuentas.id_proveedor");
        $query->leftJoin("contabilidad_cat_cuentas as c", "c.id_cuenta", "=", "cat_proveedores_cuentas.id_cuenta");
        $query->leftJoin("cat_bancos as b", "b.id_banco", "=", "cat_proveedores_cuentas.id_banco");
        $query->leftJoin("contabilidad_cat_cuentas as cp", "cp.id_cuenta", "=", "c.id_cuenta_padre");
        $query->leftJoin("contabilidad_cat_cuentas_rubros as cr", "cr.id_rubro", "=", "c.id_rubro");

        $query->select(
            "cat_proveedores_cuentas.*",
            "p.nombre as nombre_proveedor",
            "c.id_nivel",
            "c.id_rubro",
            "cr.rubro",
            "c.clave",
            "c.descripcion",
            "c.id_cuenta_padre",
            "cp.descripcion as padre_descripcion",
            "cp.clave as padre_clave"
        );

        if (!empty($datos["id_proveedor"])){

            $query->where("cat_proveedores_cuentas.id_proveedor", $datos["id_proveedor"]);

        }
        
        if (!empty($datos["estatus_cuenta_proveedor"])){

            $query->where("estatus_cuenta_proveedor", $datos["estatus_cuenta_proveedor"]);

        }

        //dd($query->toSql());

        return $query->get();

    }

}
