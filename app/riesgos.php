<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class riesgos extends Model
{
    protected $table = 'proyectos_riesgos';
    protected $fillable = ['nombre', 'nivel','etapa_id','descripcion','proyecto_id',];

}
