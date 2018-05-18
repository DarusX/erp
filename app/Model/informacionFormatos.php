<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class informacionFormatos extends Model
{
    //
    protected $table = "informacion_formatos";
    protected $primaryKey = "id_informacion";

    public function buscar()
    {
        
        $query = $this->from("informacion_formatos as if");

        $query->select("if.*");

        return $query->first();

    }

}
