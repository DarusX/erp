<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaCatalogosPaqueteContenido extends Model
{
    protected $table = 'catalogos_paquetes_contenido_inmobiliaria';
    protected $fillable = ['paquete_id', 'cantidad', 'descripcion'];
}