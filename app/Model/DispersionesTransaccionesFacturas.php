<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DispersionesTransaccionesFacturas extends Model
{
    protected $table = "dispersionesTransaccionesFacturas";

    protected $primaryKey = "id_transaccionFactura";
}
