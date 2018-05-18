<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaConceptosProductosInmueble extends Model
{
    protected $table = 'conceptos_productos_inmuebles_inmobiliaria';
    protected $fillable = ['concepto_token', 'concepto_id', 'cantidad', 'producto_id', 'codigo_producto', 'descripcion', 'costo', 'importe'];

    public function producto(){
        return $this->belongsTo(Productos::class, 'producto_id');
    }
}