<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InmobiliariaTrabajoProductoEntrega extends Model
{
    protected $table = 'trabajos_productos_entregas_inmobiliaria';
    protected $fillable = ['trabajo_id', 'nombre_responsable', 'comentarios', 'usuario_id'];

    public function buscar($datos)
    {
        $query = $this;
        if (!empty($datos['fecha_inicio'])) {
            $query = $query->where(DB::raw('DATE(created_at)'), '<=', $datos['fecha_inicio']);
        }

        if (!empty($datos['fecha_termino'])) {
            $query = $query->where(DB::raw('DATE(created_at)'), '>=', $datos['fecha_termino']);
        }

        if (!empty($datos['id'])) {
            $query = $query->where('trabajo_id', $datos['trabajo_id']);
        }

        return $query->get();
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_usuario');
    }

    public function trabajo()
    {
        return $this->belongsTo(InmobiliariaTrabajo::class, 'trabajo_id');
    }

    public function detalles()
    {
        return $this->hasMany(InmobiliariaTrabajoProductoEntregaDetalle::class, 'entrega_id');
    }
}