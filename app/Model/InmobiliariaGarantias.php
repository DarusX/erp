<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class InmobiliariaGarantias extends Model
{
    protected $table = 'garantias_inmobiliaria';
    protected $fillable = ['cliente_id', 'inmueble_id', 'descripcion', 'estado_garantia', 'usuario_id', 'estado'];

    public function buscar($datos)
    {
        $query = $this->select(
            'garantias_inmobiliaria.*',
            'ii.nombre AS inmueble',
            'ii.proyecto_id',
            'cci.nombre AS cliente',
            \DB::raw('IFNULL(gai.estado, "Pagado") AS estado_anticipo'),
            'ci.subtitulo AS contrato',
            'gli.estado AS estado_levantamiento'
        )
            ->leftJoin('catalogos_clientes_inmobiliaria AS cci', 'cci.id', '=', 'garantias_inmobiliaria.cliente_id')
            ->leftJoin('inmuebles_inmobiliaria AS ii', 'ii.id', '=', 'garantias_inmobiliaria.inmueble_id')
            ->leftJoin('garantias_anticipos_inmobiliaria AS gai', 'gai.garantia_id', '=', 'garantias_inmobiliaria.id')
            ->leftJoin('garantias_contratos_inmobiliaria AS gci', 'gci.garantia_id', '=', 'garantias_inmobiliaria.id')
            ->leftJoin('contratos_inmobiliaria AS ci', 'ci.id', '=', 'gci.contrato_id')
            ->leftJoin('garantias_levantamientos_inmobiliaria AS gli', function ($join) {
                $join->on('gli.garantia_id', '=', 'garantias_inmobiliaria.id')
                    ->where(function ($query) {
                        $query->where('gli.estado', '=', 'Pendiente')
                            ->orWhere('gli.estado', '=', 'Preautorizado');
                    });
            });

        if (!empty($datos['garantia'])) {
            $query = $query->where('garantias_inmobiliaria.id', $datos['garantia']);
        }

        $usuario = Session::get('usuario');

        if ($usuario['rol_id'] == 23) {
            $query = $query->where('garantias_inmobiliaria.usuario_id', $usuario['id_usuario']);
        }

        return $query->groupBy('garantias_inmobiliaria.id')
            ->get();
    }

    public function cliente()
    {
        return $this->belongsTo(InmobiliariaCatalogosCliente::class, 'cliente_id');
    }

    public function inmueble()
    {
        return $this->belongsTo(InmobiliariaInmueble::class, 'inmueble_id');
    }

    public function levantamientos()
    {
        return $this->hasMany(InmobiliariaGarantiasLevantamientos::class, 'garantia_id');
    }
}