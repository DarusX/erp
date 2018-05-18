<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenAjuste extends Model
{
    protected $table = 'almacenes_ajustes';
    protected $primaryKey = 'id_ajuste';
    public $timestamps = false;
}
