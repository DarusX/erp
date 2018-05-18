<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CatProveedores extends Model
{
    protected $table = 'cat_proveedores';

    protected $fillable = [
        'nombre',
        'rfc',
        'domicilio',
        'colonia',
        'ciudad',
        'id_estado',
        'cp',
        'telefono',
        'email_proveedor',
        'plazo_credito',
        'id_cuenta',
        'id_cuenta_proveedores',
        'id_cuenta_devoluciones',
        'id_cuenta_gastos_no_ponderados',
        'cuenta',
        'estatus',
        'tiempo_entrega',
        'dias_inventario',
        'dias_inventario_respaldo',
        'analisis_dias',
        'minimo_peso',
        'analisis_minimo',
        'minimo_opciones',
        'calculo_impuesto_automatico',
        'porcentaje_variacion_precio_permitido',
        'minimo_compra',
        'imagen',
        'pagina',
        'pagina_proveedor',
        'autoajustar_poliza_provision',
        'forzar_pago',
        'edicion',
        'referencia1',
        'referencia2'
    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("cat_estados as e", "e.id_estado", "=", "cat_proveedores.id_estado");
        $query->leftJoin("contabilidad_cat_cuentas as c", "c.id_cuenta", "=", "cat_proveedores.id_cuenta");
        $query->leftJoin("contabilidad_cat_cuentas as cp", "cp.id_cuenta", "=", "cat_proveedores.id_cuenta_proveedores");
        $query->leftJoin("contabilidad_cat_cuentas as cd", "cd.id_cuenta", "=", "cat_proveedores.id_cuenta_devoluciones");
        $query->leftJoin("contabilidad_cat_cuentas as cg", "cg.id_cuenta", "=", "cat_proveedores.id_cuenta_gastos_no_ponderados");

        $query->select(
            'cat_proveedores.*',
            "e.estado",
            \DB::raw("ifnull(cat_proveedores.tiempo_entrega,'') as tiempo_entrega"),
            \DB::raw("ifnull(cat_proveedores.dias_inventario,'') as dias_inventario"),
            \DB::raw("ifnull(cat_proveedores.analisis_dias,'') as analisis_dias"),
            \DB::raw("ifnull(cat_proveedores.minimo_peso,'') as minimo_peso"),
            \DB::raw("ifnull(cat_proveedores.minimo_compra,'') as minimo_compra"),
            \DB::raw("ifnull(cat_proveedores.email_proveedor,'') as email_proveedor"),
            \DB::raw("ifnull(cat_proveedores.referencia1,'') as referencia1"),
            \DB::raw("ifnull(cat_proveedores.referencia2,'') as referencia2"),
            \DB::raw("ifnull(c.clave,'') as clave_cuenta"),
            \DB::raw("ifnull(cp.clave,'') as clave_cuenta_proveedores"),
            \DB::raw("ifnull(cd.clave,'') as clave_cuenta_devoluciones"),
            \DB::raw("ifnull(cg.clave,'') as clave_cuenta_gastos_no_ponderados"),
            \DB::raw("ifnull(c.descripcion,'') as descripcion_cuenta"),
            \DB::raw("ifnull(cp.descripcion,'') as descripcion_cuenta_proveedores"),
            \DB::raw("ifnull(cd.descripcion,'') as descripcion_cuenta_devoluciones"),
            \DB::raw("ifnull(cg.descripcion,'') as descripcion_cuenta_gastos_no_ponderados")
        );

        if(!empty($datos['nombre'])){
            $query->where('nombre', 'like', '%'.$datos['nombre'].'%');
        }

        if (!empty($datos["rfc"])){

            $query->where("rfc", "like", "%". $datos["rfc"] ."%");

        }

        if (!empty($datos["estatus"])){

            $query->where("estatus", $datos["estatus"]);

        }

        if (!empty($datos["id_proveedor"])){

            $query->where("id_proveedor", $datos["id_proveedor"]);

            if (!empty($datos["first"])){

                return $query->first();

            }

        }

        return $query->get();
    }
}
