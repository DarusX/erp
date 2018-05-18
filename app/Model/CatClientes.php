<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CatClientes extends Model
{
    
    protected $table = "clientes";
    
    protected $primaryKey = "id_cliente";
    
    public function buscar($datos)
    {
        
        $query = $this->leftJoin("cat_estados as e", "e.id_estado", "=", "clientes.id_estado");

        $query->select(
            "clientes.*",
            "e.estado"
        );

        return $query->get();
        
    }
    
}
