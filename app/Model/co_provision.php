<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class co_provision extends Model
{
    //
    protected $table = "compras_ordenes_provision";
    protected $fillable = [
        'compra_orden_id',
        'provision_id',
    ];
}
