<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    protected $table = "productos_categorias";

    protected $primaryKey = "id_categoria";

    public function buscar($datos)
    {
        $query = $this->select(
            "productos_categorias.*"
        );

        if(!empty($datos['categoria'])){
            $query->where("categoria", "like" ,"%".$datos['categoria']."%");
        }

        return $query->get();
    }
}
