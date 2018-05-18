<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaConceptosChecklistInmueble extends Model
{
    protected $table = 'conceptos_checklist_inmuebles_inmobiliaria';
    protected $fillable = ['atributo', 'descripcion', 'porcentaje', 'concepto_id'];
}
