<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class OrdenesComprasCandado extends Model
{
    protected $fillable = ['usuario_candado_id', 'comentarios', 'usuario_solicitud_id'];

    public function buscar($parametros)
    {
        $query = $this->from('ordenes_compras_candados AS occ')
            ->select(
                'occ.*',
                'u.nombre AS usuario_solicitud',
                'u2.nombre AS usuario_candado'
            )
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'occ.usuario_solicitud_id')
            ->leftJoin('usuarios AS u2', 'u2.id_usuario', '=', 'occ.usuario_candado_id');

        if (isset($parametros['id']) && $parametros['id']) {
            $query = $query->where('id', $parametros['id']);
        }

        if (isset($parametros['estado']) && $parametros['estado'] != "Todos") {
            $query = $query->where('estado', $parametros['estado']);
        }

        if (isset($parametros['orden_compra_id']) && $parametros['orden_compra_id']) {
            $query = $query->where('orden_compra_id', $parametros['orden_compra_id']);
        }

        return $query->get();
    }

    public function usuarioCandado()
    {
        return $this->belongsTo(User::class, 'usuario_candado_id');
    }
}