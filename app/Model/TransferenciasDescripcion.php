<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TransferenciasDescripcion extends Model
{
    protected $table = "transferencias_descripcion";

    protected $primarykey = "id_transferencia_descripcion";

    protected $fillable = [
        'id_transferencia_orden',
        'id_transferencia'
    ];
}
