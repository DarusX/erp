<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class procedimietoSucursal extends Model
{
    //
    protected $table = "calidad_procedimiento_sucursal";
    protected $fillable = [
        'procedimiento_id',
        'sucursal_id',
    ];
    public function buscar($datos){

        $query = $this->leftJoin("cat_sucursales as s","s.id_sucursal","=","calidad_procedimiento_sucursal.sucursal_id");

        if(!empty($datos["procedimiento_id"])){
            $query->where("procedimiento_id","=",$datos["procedimiento_id"]);
        }

        $query->select(
            "calidad_procedimiento_sucursal.*",
            "s.nombre as sucursal"
        );
        //dd($datos);

        return $query->get();
    }

}
