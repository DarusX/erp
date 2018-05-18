<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cfd_impuestos extends Model
{
    //
    protected $table = "cfd_impuestos";
    protected $primaryKey = "id_cfd_impuestos";
    public $timestamps = false;
    protected $fillable = [
        'id_cfd',
        'etiqueta',
        'valor',

    ];
}
