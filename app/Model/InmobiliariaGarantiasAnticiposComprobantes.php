<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaGarantiasAnticiposComprobantes extends Model
{
    protected $table = 'garantias_anticipos_comprobantes_inmobiliaria';
    protected $fillable = ['garantia_anticipo_id', 'monto', 'comprobante', 'usuario_pendiente_id'];

    public function setComprobanteAttribute($value)
    {
        $carpeta = "inmobiliaria/garantias/anticipos_pagos/";
        $ext = $value->getClientOriginalExtension();
        $nombre = md5(time()) . "." . $ext;
        $value->move($carpeta, $nombre);
        $this->attributes['comprobante'] = "/" . $carpeta . $nombre;
    }

    public function buscar($datos)
    {
        $query = $this->select(
            'garantias_anticipos_comprobantes_inmobiliaria.*'
        )
            ->where('garantia_anticipo_id', $datos['anticipo_id']);

        return $query->get();
    }

    public function anticipo()
    {
        return $this->belongsTo(InmobiliariaGarantiasAnticipos::class, 'garantia_anticipo_id');
    }
}