<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InmobiliariaCatalogosEstadoInmueble extends Model
{
    protected $table = 'catalogos_estados_inmuebles_inmobiliaria';
    protected $fillable = ['imagen', 'vigencia', 'orden'];

    public function buscar($datos)
    {
        $query = $this;

        if (!empty($datos['nombre'])) {
            $query->where('nombre', $datos['nombre']);
        }
        return $query->get();
    }

    public function setImagenAttribute($value)
    {
        if ($value) {
            $carpeta = "inmobiliaria/catalogos/estados_inmuebles/";
            $ext = $value->getClientOriginalExtension();
            $nombre = Str::slug($this->nombre) . "." . $ext;
            $value->move($carpeta, $nombre);
            $this->attributes['imagen'] = '/' . $carpeta . $nombre;
        }
    }
}