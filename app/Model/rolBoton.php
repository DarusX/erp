<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class rolBoton extends Model
{
    //
    protected $table = "acl_boton_rol";
    protected $fillable = [
        "rol_id",
        "boton_id"
    ];
}
