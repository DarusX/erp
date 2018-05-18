<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosEmpleado extends Model
{
    protected $table = "productos_empleado";

    protected $fillable = [
        "id_producto",
        "id_empleado"
    ];
}
