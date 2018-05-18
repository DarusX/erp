<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class puesto extends Model
{
    //
    protected $table = "rh_puestos";
    protected $primaryKey = "id_puesto";

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_departamento as d", "d.id", "=", "rh_puestos.departamento_id");
        $query->leftJoin("rh_puesto_perfil as pf",function($join){
            $join->on("pf.id_puesto","=","rh_puestos.id_puesto")
                ->where('pf.estatus', '=', 'activo')
            ;
        });


        if (isset($datos["departamento_id"])) {
            if ($datos["departamento_id"] && $datos["departamento_id"] != "") {
                $query->where("d.id", "=", $datos["departamento_id"]);
            }
        }
        if (isset($datos["puesto"])) {
            if ($datos["puesto"] != "") {
                $query->where("rh_puestos.puesto", "like", "%" . $datos["puesto"] . "%");
            }
        }
        $query->select(
            "rh_puestos.*",
            "pf.*","rh_puestos.id_puesto as id_puesto",
            "d.departamento",
            \DB::raw("ifnull(id_puesto_perfil,0) as id_puesto_perfil")
        );
        if (isset($datos["id_puesto"])) {

            if(count($datos["id_puesto"]) > 1) {

                $query->whereIn("rh_puestos.id_puesto", $datos["id_puesto"]);

            } else {

                $query->where("rh_puestos.id_puesto","=",$datos["id_puesto"]);

                return $query->first();


            }

        }

        return $query->get();



    }

    public function validar($data)
    {
        $rules = [
            "puesto" => "required|unique:rh_puestos,puesto," . $this->id_puesto.",id_puesto"
        ];
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) {
            $errores = $validator->messages()->toArray();
            $array = [];
            foreach ($errores as $key => $value) {
                foreach ($value as $v) {
                    array_push($array, $v);
                }
            }
            $array["errores"] = $array;
            $array["bandera"] = false;
        } else {
            $array["bandera"] = true;
        }
        return $array;


    }



}
