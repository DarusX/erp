<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaTrabajoProducto extends Model
{
    protected $table = 'trabajos_productos_inmobiliaria';
    protected $fillable = ['trabajo_id', 'producto_id', 'solicitado', 'entregado'];

    public function trabajo()
    {
        return $this->belongsTo(InmobiliariaTrabajo::class, 'trabajo_id');
    }

    public function producto()
    {
        return $this->belongsTo(Productos::class, 'producto_id');
    }

    public function buscar($datos)
    {
        $query = $this->select(
            'trabajos_productos_inmobiliaria.*',
            'p.codigo_producto',
            'p.descripcion'
        )
            ->leftJoin('productos AS p', 'p.id_producto', '=', 'trabajos_productos_inmobiliaria.producto_id')
            ->whereRaw('trabajos_productos_inmobiliaria.solicitado > trabajos_productos_inmobiliaria.entregado')
            ->where('trabajo_id', $datos['trabajo_id']);

        return $query->get();
    }
}