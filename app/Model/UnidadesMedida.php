<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UnidadesMedida extends Model
{
    protected $table = "productos_unidades_medida";

    protected $fillable = [
        "unidad_medida",
        "clave_sat"
    ];

    protected $primaryKey = "id_unidad_medida";

    public function buscar($datos)
    {
        $query = $this->select(
            "productos_unidades_medida.*",
            \DB::raw("ifnull(productos_unidades_medida.clave_sat,'') as clave_sat")
        );

        if(!empty($datos['unidad_medida'])){
            $query->where("unidad_medida", "like", "%".$datos['unidad_medida']."%");
        }

        return $query->get();
    }
}
