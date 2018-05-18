<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TransferenciasConversion extends Model
{
    protected $table = 'transferencias_conversiones';
    protected $fillable = ['id_producto_origen', 'id_producto_destino', 'conversion', 'transferencia_automatica'];
    public $timestamps = false;
    public $primaryKey = 'id_conversion';

    public function setIdProductoOrigenAttribute($value)
    {
        $this->attributes['codigo_producto_origen'] = Productos::find($value)->codigo_producto;
    }

    public function setIdProductoDestinoAttribute($value)
    {
        $this->attributes['codigo_producto_destino'] = Productos::find($value)->codigo_producto;
    }

    public function buscar($parametros)
    {
        $query = $this;

        if (isset($parametros['codigo_producto_origen']) && $parametros['codigo_producto_origen']) {
            $query = $query->where('codigo_producto_origen', $parametros['codigo_producto_origen']);
        }

        if (isset($parametros['codigo_producto_destino']) && $parametros['codigo_producto_destino']) {
            $query = $query->where('codigo_producto_destino', $parametros['codigo_producto_destino']);
        }

        if (isset($parametros['transferencia_automatica']) && $parametros['transferencia_automatica'] != "Todos") {
            $query = $query->where('transferencia_automatica', $parametros['transferencia_automatica']);
        }

        if (isset($parametros['estado']) && $parametros['estado'] != "Todos") {
            $query = $query->where('estado', $parametros['estado']);
        }

        return $query->get();
    }
}