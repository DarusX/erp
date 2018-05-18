<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class rolRecurso extends Model
{
    //
    protected $table = "acl_rol_recurso";
    protected $fillable = [
        "rol_id",
        "recurso_id"
    ];
}
