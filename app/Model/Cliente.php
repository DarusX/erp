<?php

namespace App\Model;

use App\Model\ventas\TipoPrecio;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'agr_cliente';

    protected $fillable = [
        'id',
        'nombre',
        'rfc',
        'calle',
        'numero_ext',
        'numero_int',
        'colonia',
        'localidad',
        'ciudad',
        'municipio',
        'estado_id',
        'estado',
        'cp',
        'telefono',
        'email',
        'contacto',
        'estatus'
    ];

    public function buscar($datos)
    {
        $query = $this;

        if (!empty($datos['nombre'])) {
            return $query->where('nombre', 'LIKE', '%' . $datos['nombre'] . '%')->get();
        }

        return $query->all();
    }

    public function tipoPrecio()
    {
        return $this->belongsTo(TipoPrecio::class, 'tipo_precio_id');
    }
}
