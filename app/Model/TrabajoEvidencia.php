<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TrabajoEvidencia extends Model
{
    protected $table = 'agr_trabajo_evidencia';

    protected $fillable = [
        'id',
        'trabajo_id',
        'imagen'
    ];

    public function buscar($datos){

        $query = $this->select('imagen');

        if(!empty($datos['trabajo_id'])){
            $query->where('trabajo_id', $datos['trabajo_id']);
        }

        return $query->get();
    }
}
