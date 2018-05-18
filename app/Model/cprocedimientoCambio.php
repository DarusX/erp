<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cprocedimientoCambio extends Model
{
    //
    protected $table = "procedimiento_cambio";
    protected $fillable = [
        'procedimiento_id',
        'procedimiento_anterior_id'
    ];
}
