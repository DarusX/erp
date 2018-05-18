<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class InmobiliariaGarantiasProductosEntregas extends Model
{
    protected $table = 'garantias_productos_entregas_inmobiliaria';

    public function detalles()
    {
        return $this->hasMany(InmobiliariaGarantiasProductosEntregasDetalles::class, 'entrega_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id_usuario');
    }

    public function garantia()
    {
        return $this->belongsTo(InmobiliariaGarantias::class, 'garantia_id');
    }
}