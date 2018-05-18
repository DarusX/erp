<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cfd_ventas extends Model
{
    //
    protected $table = "cfd_ventas";
    protected $primaryKey = "id_cfd_venta";
    public $timestamps = false;
    protected $fillable = [
        "id_venta",
        "id_cfd"
    ];
}
