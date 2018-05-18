<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaConceptosMaquinariaInmueble extends Model
{
    protected $table = 'conceptos_maquinaria_inmuebles_inmobiliaria';
    protected $fillable = ['concepto_id', 'maquinaria_id', 'cantidad', 'costo', 'importe'];

    public function maquinaria(){
        return $this->belongsTo(InmobiliariaCatalogosMaquinaria::class, 'maquinaria_id');
    }
}
