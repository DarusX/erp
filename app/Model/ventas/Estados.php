<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{
    protected $table =  'cat_estados';
    protected $fillable =  ['estado', 'clave', 'claveEntidad'];

    public function buscar($datos){
        $query = $this->select(
        'cat_estados.*'
        );

        if(!empty($datos['id_estado'])){
            $query->where('cat_estados.id_estado', $datos['id_estado']);
        }

        return $query->get();
    }
}
