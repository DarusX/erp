<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaConceptosManoObraInmueble extends Model
{
    protected $table = 'conceptos_mano_obra_inmuebles_inmobiliaria';
    protected $fillable = ['concepto_id', 'mano_obra_id', 'cantidad', 'costo', 'importe'];

    public function mo(){
        return $this->belongsTo(InmobiliariaCatalogosManoObra::class, 'mano_obra_id');
    }
}
