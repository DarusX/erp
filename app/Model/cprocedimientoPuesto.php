<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cprocedimientoPuesto extends Model
{
    //
    protected $table = "procedimiento_puesto";
    protected $fillable = [
        'procedimiento_id',
        'puesto_id',
        'perfil_id',
        'verbo_id',

    ];

    public function buscar($datos)
    {
        //$query = \DB::table("procedimiento_puesto as pp");
        $query = $this->leftJoin("rh_puestos as p", "p.id_puesto", "=", "puesto_id");

        if (!empty($datos["procedimiento_id"])) {
            $query->where("procedimiento_id", "=", $datos["procedimiento_id"]);
        }
        $query->select("*","perfil_id as id_puesto_perfil");

        return $query->get();
    }
}
