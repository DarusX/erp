<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class calidad_categoria_procedimiento extends Model
{
    //
    protected $table = "calidad_categoria_procedimiento";
    protected $fillable = [
        'procedimiento_id',
        'categoria_id',
    ];
    public function buscar($datos){
        $query = $this->leftJoin("calidad_procedimiento as cp","cp.id","=","calidad_categoria_procedimiento.procedimiento_id");

        $query->select(
            "calidad_categoria_procedimiento.*",
            "cp.codigo","cp.procedimiento"
        );
        if(!empty($datos{"categoria_id"})){
            $query->where("categoria_id","=",$datos["categoria_id"]);
        }

        return $query->get();

    }
}
