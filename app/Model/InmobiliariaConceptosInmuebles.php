<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaConceptosInmuebles extends Model
{
    protected $table = 'conceptos_inmuebles_inmobiliaria';
    protected $fillable = ['inmueble_id', 'nombre', 'contrato_id', 'porcentaje_inmueble'];

    public function inmueble()
    {
        return $this->belongsTo(InmobiliariaInmueble::class, 'inmueble_id');
    }

    public function checklists()
    {
        return $this->hasMany(InmobiliariaConceptosChecklistInmueble::class, 'concepto_id');
    }

    public function manoObra()
    {
        return $this->hasMany(InmobiliariaConceptosManoObraInmueble::class, 'concepto_id');
    }

    public function productos()
    {
        return $this->hasMany(InmobiliariaConceptosProductosInmueble::class, 'concepto_id');
    }

    public function maquinaria()
    {
        return $this->hasMany(InmobiliariaConceptosMaquinariaInmueble::class, 'concepto_id');
    }

    public function contrato()
    {
        return $this->belongsTo(InmobiliariaContrato::class, 'contrato_id');
    }

    public function trabajo()
    {
        return $this->hasOne(InmobiliariaTrabajo::class, 'concepto_id');
    }
}