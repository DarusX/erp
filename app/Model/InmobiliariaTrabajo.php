<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaTrabajo extends Model
{
    protected $table = 'trabajos_inmobiliaria';
    protected $fillable = ['inmueble_id', 'concepto_id', 'fecha_inicial', 'fecha_final', 'costo_productos', 'costo_mano_obra', 'costo_maquinaria', 'estado', 'usuario_id'];

    public function buscar($datos)
    {
        $query = $this->select(
            'trabajos_inmobiliaria.*',
            \DB::raw('DATEDIFF(fecha_final, DATE(NOW())) as dias_restantes'),
            'ii.nombre AS inmueble',
            'cii.nombre AS concepto',
            'u.nombre AS usuario',
            \DB::raw('(SELECT COUNT(*) FROM trabajos_checklists_inmobiliaria WHERE trabajo_id = trabajos_inmobiliaria.id AND estado = "Pendiente") AS pendientes'),
            \DB::raw('(SELECT COUNT(*) FROM trabajos_checklists_inmobiliaria WHERE trabajo_id = trabajos_inmobiliaria.id AND estado = "Validado") AS validados'),
            \DB::raw('(SELECT COUNT(*) FROM trabajos_checklists_inmobiliaria WHERE trabajo_id = trabajos_inmobiliaria.id AND estado = "Preautorizado") AS preautorizados'),
            \DB::raw('(SELECT COUNT(*) FROM trabajos_checklists_inmobiliaria WHERE trabajo_id = trabajos_inmobiliaria.id AND estado = "Autorizado") AS autorizados')
        )
            ->leftJoin('inmuebles_inmobiliaria AS ii', 'ii.id', '=', 'trabajos_inmobiliaria.inmueble_id')
            ->leftJoin('conceptos_inmuebles_inmobiliaria AS cii', 'cii.id', '=', 'trabajos_inmobiliaria.concepto_id')
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'trabajos_inmobiliaria.usuario_id');

        if (!empty($datos['trabajo'])) {
            return $query->where('trabajos_inmobiliaria.id', $datos['trabajo'])
                ->get();
        }

        if (isset($datos['contrato_id']) && $datos['contrato_id'] != "Todos") {

            $contrato = InmobiliariaContrato::find($datos['contrato_id']);
            $query = $query->whereIn('ii.id', $contrato->inmuebles()->lists('inmueble_id'));
        }

        if (!empty($datos['inmueble_id']) && $datos['inmueble_id'] != "Todos") {
            $query = $query->where('ii.id', $datos['inmueble_id']);
        }

        if (!empty($datos['fecha_inicio'])) {
            $query = $query->where(\DB::raw('created_at'), '>=', $datos['fecha_inicio']);
        }

        if (!empty($datos['fecha_termino'])) {
            $query = $query->where(\DB::raw('created_at'), '<=', $datos['fecha_termino']);
        }

        if ($datos['estado'] != "Todos") {
            $query = $query->where('estado', $datos['estado']);
        }
        return $query
            ->where('ii.proyecto_id', $datos['proyecto_id'])
            ->get();
    }

    public function concepto()
    {
        return $this->belongsTo(InmobiliariaConceptosInmuebles::class, 'concepto_id');
    }

    public function inmueble()
    {
        return $this->belongsTo(InmobiliariaInmueble::class, 'inmueble_id');
    }

    public function checklists()
    {
        return $this->hasMany(InmobiliariaTrabajoChecklist::class, 'trabajo_id');
    }

    public function productos()
    {
        return $this->hasMany(InmobiliariaTrabajoProducto::class, 'trabajo_id');
    }

    public function entregas()
    {
        return $this->hasMany(InmobiliariaTrabajoProductoEntrega::class, 'trabajo_id');
    }
}