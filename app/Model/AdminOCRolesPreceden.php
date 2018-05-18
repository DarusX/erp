<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminOCRolesPreceden extends Model
{

    protected $table = "administrador_ordenes_compra_roles_preceden";

    protected $fillable = [
        "rol_id",
        "rol_sustituye_id"
    ];

}
