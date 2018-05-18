<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClasificacionEquipos extends Model
{
    protected $table = "cat_categoria_computo";

    protected $primaryKey = "id_categoria_equipo";

    protected $fillable = [
        "categoria_equipo"
    ];

    public function buscar($datos){

        $query = $this->from("cat_categoria_computo as cc");

        $query->select("cc.*");

        return $query->get();

    }

}
