<?php

namespace App\Model\cfd;

use Illuminate\Database\Eloquent\Model;

class CfdDescripcion extends Model
{
    protected $table = 'cfd_descripcion';

    protected $fillable = [
        'id_cfd_descripcion',
        'id_cfd',
        'cantidad',
        'descripcion',
        'precio_unitario',
        'tasa_iva',
        'unidad'
    ];

    public $timestamps = false;
}
