<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class perfil extends Model
{
    //
    protected $table = "rh_puesto_perfil";
    protected $primaryKey = "id_puesto_perfil";
    protected $fillable = [
        'id_puesto',
        'codigo',
        'objetivo',
        'fecha',
        'fecha_autorizacion',
        'fecha_paso_anterior',
        'empleado_captura_id',
        'empleado_autoriza_id',
        'version',
        'departamento',
        'departamento_id',
        'id_puesto_reporta',
        'edad_inicial',
        'edad_final',
        'sexo',
        'experiencia_laboral',
        'estatus'
    ];

    public function puesto()
    {
        return $this->belongsTo(puesto::class, "id_puesto", "id_puesto");
    }

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_puestos as p", "p.id_puesto", "=", "rh_puesto_perfil.id_puesto");
        $query->leftJoin("rh_departamento as d", "d.id", "=", "rh_puesto_perfil.departamento_id");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "rh_puesto_perfil.empleado_captura_id");
        $query->leftJoin("rh_empleados as e2", "e2.id_empleado", "=", "rh_puesto_perfil.empleado_autoriza_id");
        $query->leftJoin("rh_puestos as p2", "p2.id_puesto", "=", "rh_puesto_perfil.id_puesto_reporta");


        $query->select(
            "rh_puesto_perfil.*",
            "p.puesto", "p2.puesto as puesto_reporta",
            "d.departamento",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as empleado_captura"),"e.email_empresa as email_empresa_captura",
            \DB::raw("concat(e2.nombre,' ',e2.apaterno,' ',e2.amaterno) as empleado_autoriza"),"e.email_empresa as email_empresa_autoriza"
        );
        if (!empty($datos["puesto"])) {
            $query->where("p.puesto", "like", "%" . $datos["puesto"] . "%");
        }
        if (!empty($datos["codigo"])) {
            $query->where("rh_puesto_perfil.codigo", "like", "%" . $datos["codigo"] . "%");
        }
        if (!empty($datos["departamento_id"])) {
            $query->where("rh_puesto_perfil.departamento_id", "=", $datos["departamento_id"]);
        }
        if (!empty($datos["id_puesto_reporta"])) {
            $query->where("rh_puesto_perfil.id_puesto_reporta", "=", $datos["id_puesto_reporta"]);
        }
        if (!empty($datos["sexo"])) {
            $query->where("rh_puesto_perfil.sexo", "=", $datos["sexo"]);
        }
        if (!empty($datos["edad_inicial"])) {
            $query->where("rh_puesto_perfil.edad_inicial", ">=", $datos["edad_inicial"]);
        }
        if (!empty($datos["edad_final"])) {
            $query->where("rh_puesto_perfil.edad_final", "<=", $datos["edad_final"]);
        }

        if (!empty($datos["estatus"])) {
            $query->where("rh_puesto_perfil.estatus", "=", $datos["estatus"]);
        }



        if (isset($datos["id_puesto_perfil"])) {
            $query->where("id_puesto_perfil", "=", $datos["id_puesto_perfil"]);
            return $query->first();

        } else {
            return $query->get();

        }


    }


}
