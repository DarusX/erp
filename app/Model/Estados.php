<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{

    protected $table = "cat_estados";

    protected $primaryKey = "id_estado";

    public function buscar($datos)
    {

        $query = $this;

        $query->select(
            "cat_estados.*"
        );

        if(!empty($datos["id_estado"])){
            $query->where("id_estado", $datos["id_estado"]);
            if(!empty($query["first"])){
                return $query->first();
            }
        }

        if(!empty($datos["estado"])){
            $query->where("estado", "like", "%".$datos["estado"]."%");
        }

        return $query->get();

    }

}
