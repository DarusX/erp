<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CatProveedoresGastosSucursales extends Model
{
    protected $table = "cat_proveedores_cuentas_gastos_sucursales";

    protected $fillable = [
        "proveedor_id",
        "clasificacion_gasto_id",
        "cuenta_id",
        "sucursal_id",
        "estado"
    ];

    public function buscar($datos)
    {

        $query = $this->from("cat_proveedores_cuentas_gastos_sucursales as cpcg");
        $query->leftJoin("cat_proveedores as p", "p.id_proveedor", "=", "cpcg.proveedor_id");
        $query->leftJoin("gastosClasificacion as cg", "cg.id_gasto_clasificacion", "=", "cpcg.clasificacion_gasto_id");
        $query->leftJoin("contabilidad_cat_cuentas as cc", "cc.id_cuenta", "=", "cpcg.cuenta_id");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "cpcg.sucursal_id");

        $query->select(
            "cpcg.*",
            "p.nombre as proveedor",
            "cg.clasificacion",
            "cc.clave",
            "cc.descripcion",
            "s.nombre as sucursal"
        );

        if (!empty($datos["proveedor_id"])){
            $query->where("cpcg.proveedor_id", $datos["proveedor_id"]);
        }

        $query->where("cg.clasificacion_estatus", "activo");

        return $query->get();

    }

}
