<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class xml_recuperacion extends Model
{
    //
    protected $table = "xml_recuperado_detalle";
    public $timestamps = false;
    protected $fillable = [
        'xml_id',
        'nombre',
        'id_venta',
        'id_cfd',

    ];
}
