<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrdenesComprasCandadosValidacion extends Model
{
    protected $table = 'ordenes_compras_candados_validaciones';
    protected $fillable = ['candado_id', 'rol_id', 'orden'];

    public function buscar($parametros)
    {
        $query = $this->from('ordenes_compras_candados_validaciones AS occv')
            ->select(
                'occv.*',
                'ar.rol'
            )
            ->leftJoin('acl_rol AS ar', 'ar.id', '=', 'occv.rol_id');

        if (isset($parametros['candado_id']) && $parametros['candado_id']) {
            $query = $query->where('candado_id', $parametros['candado_id']);
        }

        if (isset($parametros['id']) && $parametros['id']) {
            $query = $query->where('id', $parametros['id']);
        }

        if (isset($parametros['estado']) && $parametros['estado'] != "Todos") {
            $query = $query->where('estado', $parametros['estado']);
        }

        return $query->get();
    }
}