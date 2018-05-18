<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaCatalogosCreditoDocumento extends Model
{
    protected $table = 'catalogos_creditos_documentos_inmobiliaria';
    protected $fillable = ['credito_id', 'documento', 'slug'];

    public function setDocumentoAttribute($value)
    {
        $this->attributes['documento'] = $value;
        $this->slug();
    }

    public function slug()
    {
        $this->attributes['slug'] = str_slug($this->documento, "_");
    }
}