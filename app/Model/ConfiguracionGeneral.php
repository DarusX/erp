<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionGeneral extends Model
{
    protected $table = "configuraciones_generales";

    protected $fillable = [
        "pagina_inicio"
    ];

    public function buscar($datos){

        $query = $this->select(
            "configuraciones_generales.*"
        );

        if(!empty($datos["configuracion_id"])){
            $query->where("id", $datos["configuracion_id"]);
            return $query->first();
        }

        return $query->get();

    }
}
