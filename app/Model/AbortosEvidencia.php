<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AbortosEvidencia extends Model
{

    protected $table = "agr_abortos_evidencia";

    protected $fillable = [
        "aborto_id",
        "imagen"
    ];

    public function buscar($datos)
    {

        $query = $this->leftJoin("agr_abortos as a", "a.id", "=", "agr_abortos_evidencia.aborto_id");

        $query->select(
            "agr_abortos_evidencia.*"
        );

        if(!empty($datos["aborto_id"])){
            $query->where("agr_abortos_evidencia.aborto_id", $datos["aborto_id"]);
        }

        return $query->get();

    }

}
