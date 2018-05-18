<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaTareasSubtarea extends Model
{
    protected $table = 'tareas_subtareas_inmobiliaria';
    protected $fillable = ['tarea_id', 'descripcion', 'nivel'];

    public function fotos()
    {
        return $this->hasMany(InmobiliariaTareasSubtareasFoto::class, 'tarea_subtarea_id');
    }
}