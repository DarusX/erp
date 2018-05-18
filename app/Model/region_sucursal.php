<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class region_sucursal extends Model
{
    //
    protected $table = "calidad_region_sucursal";
    protected $fillable = [
        "region_id",
        "sucursal_id"

    ];
}
