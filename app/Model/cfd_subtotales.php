<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cfd_subtotales extends Model
{
    //
    protected $table = "cfd_subtotales";
    protected $primaryKey = "id_cfd_subtotal";
    public $timestamps = false;
    protected $fillable = [
        'id_cfd',
        'etiqueta',
        'valor',

    ];
}
