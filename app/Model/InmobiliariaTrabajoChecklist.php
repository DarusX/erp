<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaTrabajoChecklist extends Model
{
    protected $table = 'trabajos_checklists_inmobiliaria';
    protected $fillable = ['trabajo_id', 'checklist_id'];

    public function cl()
    {
        return $this->belongsTo(InmobiliariaConceptosChecklistInmueble::class, 'checklist_id');
    }

    public function fotos()
    {
        return $this->hasMany(InmobiliariaTrabajoChecklistFoto::class, 'trabajo_checklist_id');
    }

    public function trabajo(){
        return $this->belongsTo(InmobiliariaTrabajo::class, 'trabajo_id');
    }
}