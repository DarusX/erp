<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MontoRemision extends Model
{

    protected $table = "monto_remision";

    protected $fillable = [
        "anio",
        "mes",
        "monto_general"
    ];

}
