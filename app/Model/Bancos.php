<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Bancos extends Model
{

    protected $table = "cat_bancos";

    protected $primaryKey = "id_banco";

    public function buscar($datos){

        $query = $this;

        $query->select(
            "cat_bancos.*"
        );

        if (!empty($datos["id_banco"])){
            $query->where("id_banco", $datos["id_banco"]);
            return $query->first();
        }

        return $query->get();

    }

}
