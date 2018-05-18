<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaTrabajoChecklistFoto extends Model
{
    protected $table = 'trabajos_checklists_fotos_inmobiliaria';

    public function trabajoChecklist(){
        return $this->belongsTo(InmobiliariaTrabajoChecklist::class, 'trabajo_checklist_id');
    }
}