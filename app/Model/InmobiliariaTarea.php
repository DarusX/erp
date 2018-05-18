<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaTarea extends Model
{
    protected $table = 'tareas_inmobiliaria';
    public $fillable = ['nombre', 'categoria_id', 'inmueble_id', 'responsable_id', 'fecha_compromiso', 'usuario_id'];

    public function buscar($datos)
    {
        $query = $this->select(
            'tareas_inmobiliaria.*',
            'u.nombre AS responsable',
            \DB::raw('IF(u.id_usuario = ' . \Auth::id() . ', "Si", "No") AS propietario'),
            'ii.nombre AS inmueble',
            \DB::raw('(SELECT COUNT(*) FROM tareas_subtareas_inmobiliaria WHERE tarea_id = tareas_inmobiliaria.id AND estado = "Pendiente") AS pendientes'),
            \DB::raw('(SELECT COUNT(*) FROM tareas_subtareas_inmobiliaria WHERE tarea_id = tareas_inmobiliaria.id AND estado = "Validado") AS validados')
        )
            ->leftJoin('inmuebles_inmobiliaria AS ii', 'ii.id', '=', 'tareas_inmobiliaria.inmueble_id')
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'tareas_inmobiliaria.responsable_id');

        if (!empty($datos['id'])) {
            return $query->where('id', $datos['id'])
                ->get();
        }

        if (!empty($datos['fecha_inicio'])) {
            $query = $query->where("tareas_inmobiliaria." . $datos['tipo_fecha'], '>=', $datos['fecha_inicio']);
        }

        if (!empty($datos['fecha_termino'])) {
            $query = $query->where("tareas_inmobiliaria." . $datos['tipo_fecha'], '<=', $datos['fecha_termino']);
        }

        if ($datos['estado'] != "Todos") {
            $query = $query->where('estado', $datos['estado']);
        }

        return $query->get();
    }

    public function subtareas()
    {
        return $this->hasMany(InmobiliariaTareasSubtarea::class, 'tarea_id');
    }
}