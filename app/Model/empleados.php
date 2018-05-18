<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class empleados extends Model
{
    //
    protected $table = "rh_empleados";
    protected $primaryKey = "id_empleado";
    protected $fillable = [
        'nombre',
        'apaterno',
        'amaterno',
        'email',
        'email_empresa',
        'sexo',
        'fecha_nacimiento',
        'fecha_alta',
        'fecha_baja',
        'fecha_registro',
        'telefono',
        'telefono_empresa',
        'lugar_nacimiento',
        'id_estado',
        'municipio',
        'ciudad',
        'direccion',
        'cp',
        'calle',
        'numero_casa',
        'colonia',
        'rfc',
        'curp',
        'no_seguro',
        'hijos',
        'credito',
        'id_sucursal',
        'id_puesto_sucursal',
        'eneatipo_principal',
        'ala',
        'area',
        'huella',
        'id_sucursal_fiscal',
        'nomina_fiscal',
        'nomina_pago',
        'trabaja_domingo',
        'vsm',
        'sin_huella',
        'infonavit_pagos',
        'alta_fiscal',
        'baja_fiscal',
        'id_jefe',
        'primera_evaluacion',
        'segunda_evaluacion',
        'checar_anterior',
        'retardos',
        'id_usuario_contrata',
        'entrada_libre',
        'sucursal_local',
        'bono',
        'bono_utilidad',
        'estatus',


    ];

    public function buscarEmpleados($datos)
    {

        $query = $this->leftJoin("rh_empleados as e2", "e2.id_empleado", "=", "rh_empleados.id_jefe");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "rh_empleados.id_sucursal");
        $query->leftJoin("cat_sucursales as sf", "sf.id_sucursal", "=", "rh_empleados.id_sucursal_fiscal");
        $query->leftJoin("cat_estados as es", "es.id_estado", "=", "rh_empleados.id_estado");
        $query->leftJoin("cat_estados as es2", "es2.id_estado", "=", "sf.id_estado");
        $query->leftJoin("rh_puestos_sucursales as ps", "ps.id_puesto_sucursal", "=", "rh_empleados.id_puesto_sucursal");
        $query->leftJoin("rh_puestos as p", "p.id_puesto", "=", "ps.id_puesto");
        $query->leftJoin("rh_empleados_tarjeta as te", function ($join) {
            $join->on("te.id_empleado", "=", "rh_empleados.id_empleado")
                ->where("te.id_banco", "=", "1");
        });
        $query->leftJoin("rh_foto_empleado as f", "f.id_empleado", "=", "rh_empleados.id_empleado");
        $query->leftJoin('rh_salario_empleado as se', function ($join) {
            $join->on('se.id_empleado', '=', 'rh_empleados.id_empleado')
                ->where('se.tipo', '=', '"pagos"')
                ->where('se.status', '=', '"a"');

        });

        $query->leftJoin('rh_salario_empleado as saf', function ($join) {
            $join->on('saf.id_empleado', '=', 'rh_empleados.id_empleado')
                ->where('saf.tipo', '=', '"fiscal"')
                ->where('saf.status', '=', '"a"');

        });

        $query->leftJoin("rh_empleados_monto as m", function ($join) {
            $join->on("m.empleado_id", "=", "rh_empleados.id_empleado")
                ->where("m.estatus", "=", "autorizado");
        });
        //$query->leftJoin("rh_empleados_complemento as ec", "ec.id_empleado", "=", "rh_empleados.id_empleado");
        $select = [
            \DB::raw("DATEDIFF(CURDATE(),rh_empleados.alta_fiscal) as dias_laborados"),
            \DB::raw("concat(rh_empleados.nombre,' ',rh_empleados.apaterno,' ',rh_empleados.amaterno) as nombre_completo"),
            \DB::raw("fechaCheque(curdate()) as fecha_letra"),
            \DB::raw("fechaCheque(rh_empleados.alta_fiscal) as fecha_letra"),
            \DB::raw("fechaCheque(ifnull(rh_empleados.baja_fiscal,curdate())) as baja_letra"),
            \DB::raw("ifnull(se.salario,0) as salario"),
            \DB::raw("ifnull(se.id_salario_empleado,0) as id_salario"),
            \DB::raw("ifnull(saf.salario,0) as salario_fiscal"),
            \DB::raw("ifnull(saf.id_salario_empleado,0) as id_salario_empleado_fiscal"),
            \DB::raw("ifnull(m.monto_bono,0) as monto_bono"),
            "rh_empleados.*",
            "e2.id_empleado as id_jefe", "e2.nombre as nombre_jefe", "e2.apaterno as apaterno_jefe", "e2.amaterno as amaterno_jefe",
            's.nombre as nombre_sucursal',
            'sf.nombre as nombre_sucursal_fiscal', "sf.ciudad as ciudad_fiscal",
            "es.estado",
            "es2.estado as estado_sucursal",
            "p.puesto", "p.departamento", "p.id_puesto",
            //"ec.id_empleado_complementos",
            "f.id_foto_empleado",
            "rh_empleados.bono_utilidad"

        ];

        $query->select(
            $select
        );

        if (isset($datos["nombre_empleado"])) {
            $query->where(\DB::raw("concat(rh_empleados.nombre,' ',rh_empleados.apaterno,' ',rh_empleados.amaterno)"), "like", "%" . $datos["nombre_empleado"] . "%");
        }
        if (isset($datos["estatus"]))
            $query->where("rh_empleados.estatus", "=", $datos["estatus"]);

        if (!empty($datos["puesto_in"])) {
            $query->whereIn("p.id_puesto", $datos["puesto_in"]);
            $select[] = "p.datos";
        }


        if (!empty($datos["id_puesto"])) {

            if (count($datos["id_puesto"]) > 1) {

                $query->whereIn("p.id_puesto", $datos["id_puesto"]);

            } else {

                $query->where("p.id_puesto", $datos["id_puesto"]);

            }

        }

        if (!empty($datos["email_activo"])) {
            $query->whereNotNull("rh_empleados.email_empresa");
        }


        if (isset($datos["id_empleado"])) {
            $query->where("rh_empleados.id_empleado", "=", $datos["id_empleado"]);
            return $query->first()->toArray();
        }

        if (isset($datos["empleado_id"])) {
            $query->where("rh_empleados.id_empleado", "=", $datos["empleado_id"]);
        }
        //dd($query->toSql());


        if (!empty($datos["id_sucursal"])) {

            if (count($datos["id_sucursal"]) > 1) {

                $query->whereIn("rh_empleados.id_sucursal", $datos["id_sucursal"]);

            } else {

                $query->where("rh_empleados.id_sucursal", $datos["id_sucursal"]);

            }

        }

        if (!empty($datos["agrupar"])) {

            $query->orderBy("rh_empleados.id_sucursal", "ASC");

        }
        if (isset($datos["id_empleado"])) {
            $query->where("rh_empleados.id_empleado", "=", $datos["id_empleado"]);
            return $query->first()->toArray();
        }

        //dd($query->toSql());

        return $query->get()->toArray();

    }
}

