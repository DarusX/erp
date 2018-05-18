<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class InmobiliariaPagosMantenimientosComprobante extends Model
{
    protected $table = 'pagos_mantenimientos_comprobantes_inmobiliaria';

    public function buscar($datos)
    {
        $usuario = Session::get('usuario');
        $query = $this->select(
            'pagos_mantenimientos_comprobantes_inmobiliaria.*',
            'ii.nombre AS inmueble',
            'cii.nombre AS cliente'
        )
            ->leftJoin('pagos_mantenimientos_inmobiliaria AS pmi', 'pmi.id', '=', 'pagos_mantenimientos_comprobantes_inmobiliaria.pago_id')
            ->leftJoin('inmuebles_inmobiliaria AS ii', 'ii.id', '=', 'pmi.inmueble_id')
            ->leftJoin('catalogos_clientes_inmobiliaria AS cii', 'cii.id', '=', 'pmi.cliente_id');
        if (!empty($datos['pago'])) {
            $query = $query->where('id', $datos['pago']);
        }

        if ($usuario['rol_id'] == "23") {
            $query = $query->where('cii.usuario_id', $usuario['id_usuario']);
        }

        return $query->get();
    }
}