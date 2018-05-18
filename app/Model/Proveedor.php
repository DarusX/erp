<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'agr_proveedor';

    protected $fillable = [
        'id',
        'proveedor',
        'rfc',
        'domicilio',
        'colonia',
        'ciudad',
        'estado_id',
        'cp',
        'telefono',
        'correo',
        'estatus',
        'imagen'
    ];

    public  function buscar($datos){
        $query = $this;

        if(!empty($datos['proveedor'])){
            return $query->where('proveedor', 'LIKE', '%'.$datos['proveedor'].'%')->get();
        }

        return $query->all();
    }

}
