<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cprocedimientoReferencia extends Model
{
    //
    protected $table = "procedimiento_referencia";
    protected $fillable = [
        'procedimiento_id',
        'referencia_id',


    ];
}
