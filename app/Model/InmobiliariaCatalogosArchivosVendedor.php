<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaCatalogosArchivosVendedor extends Model
{
    protected $table = 'catalogos_archivos_vendedores_inmobiliaria';

    protected $fillable = ['nombre', 'tipo', 'archivo', 'usuario_id'];

    public function buscar($datos)
    {
        $query = $this;
        if (!empty($datos['nombre'])) {
            $query->where('nombre', 'LIKE', '%' . $datos['nombre'] . '%');
        }
        return $query->get();
    }

    public function setArchivoAttribute($value)
    {
        if ($value) {
            $carpeta = 'inmobiliaria/catalogos/archivos-vendedores/';
            $ext = $value->getClientOriginalExtension();
            $nombre = md5(time()) . "." . $ext;
            $value->move($carpeta, $nombre);
            $this->attributes['ruta'] = "/" . $carpeta . $nombre;
            $this->tipo = $ext;
        }
    }
}