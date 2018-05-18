<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cfd_datos_descripcion extends Model
{
    //
    protected $table = "cfd_descripcion";
    protected $primaryKey = "id_cfd_descripcion";
    public $timestamps = false;
    protected $fillable = [
        'id_cfd',
        'cantidad',
        'descripcion',
        'precio_unitario',
        'tasa_iva',
        'unidad',
    ];
}
