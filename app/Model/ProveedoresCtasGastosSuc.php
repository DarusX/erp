<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProveedoresCtasGastosSuc extends Model
{

    protected $table = "cat_proveedoresCuentasGastos_sucursales";

    protected $primaryKey = "id_cuenta_sucursal_proveedor";

    protected $fillable = [
        "id_rubro",
        "id_proveedor",
        "id_sucursal",
        "id_cuenta",
        "cuenta_clave",
        "estatus"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("cat_rubros as r", "r.id", "=", "cat_proveedoresCuentasGastos_sucursales.id_rubro");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "cat_proveedoresCuentasGastos_sucursales.id_sucursal");
        $query->leftJoin("contabilidad_cat_cuentas as c", "c.id_cuenta", "=", "cat_proveedoresCuentasGastos_sucursales.id_cuenta");

        $query->select(
            "cat_proveedoresCuentasGastos_sucursales.*",
            "s.nombre as sucursal",
            "c.descripcion"
        );

        if (!empty($datos["id_rubro"])){
            $query->where("cat_proveedoresCuentasGastos_sucursales.id_rubro", $datos["id_rubro"]);
        }
        
        return $query->get();

    }

}
