<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class subtareaChecklist extends Model
{
    protected $table =  "proyectos_subtareas_checklist";
    protected $fillable = ['titulo', 'orden', 'subtarea_id', 'created_at', 'updated_at'];

    public function buscar($datos){
        $query = $this->select(
            'proyectos_subtareas_checklist.*',
            \DB::raw('2 as bandera')

        );

        if(!empty($datos['subtarea_id'])){
            $query->where("proyectos_subtareas_checklist.subtarea_id", $datos['subtarea_id']);
        }
        return $query->get();
    }
}
