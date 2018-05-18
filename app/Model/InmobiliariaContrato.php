<?php

namespace App\Model;

use App\Libraries\EnLetras;
use Illuminate\Database\Eloquent\Model;

class InmobiliariaContrato extends Model
{
    protected $table = 'contratos_inmobiliaria';
    protected $fillable = ['proyecto_id', 'contrato_tipo_id', 'categoria_id', 'titulo', 'subtitulo', 'localidad', 'fraccionamiento', 'ubicacion', 'numero', 'empresa', 'representante_empresa', 'contratista', 'representante_contratista', 'direccion', 'rfc', 'imss', 'testigo_1', 'testigo_2', 'fecha_inicio', 'fecha_termino', 'monto'];

    public function buscar($datos)
    {
        $query = $this->select(
            '*',
            \DB::raw('(SELECT COUNT(*) FROM contratos_inmobiliaria AS ci LEFT JOIN conceptos_inmuebles_inmobiliaria AS cii ON cii.contrato_id = ci.id LEFT JOIN trabajos_inmobiliaria AS ti ON ti.concepto_id = cii.id WHERE ti.concepto_id IS NOT NULL AND ci.id = contratos_inmobiliaria.id) AS trabajos'),
            \DB::raw('(SELECT COUNT(*) FROM contratos_inmobiliaria AS ci LEFT JOIN contratos_anticipos_inmobiliaria AS cai ON cai.contrato_id = ci.id WHERE cai.estado = "Pendiente" AND ci.id = contratos_inmobiliaria.id) AS solicitudes_anticipos_pendientes'),
            \DB::raw('(SELECT COUNT(*) FROM contratos_inmobiliaria AS ci LEFT JOIN contratos_anticipos_inmobiliaria AS cai ON cai.contrato_id = ci.id WHERE cai.estado = "Validado" AND ci.id = contratos_inmobiliaria.id) AS solicitudes_anticipos_validadas'),
            \DB::raw('(SELECT COUNT(*) FROM contratos_inmobiliaria AS ci LEFT JOIN contratos_solicitudes_liberaciones_inmobiliaria AS csli ON csli.contrato_id = ci.id WHERE csli.estado = "Pendiente" AND ci.id = contratos_inmobiliaria.id) AS solicitudes_liberaciones_pendientes'),
            \DB::raw('(SELECT COUNT(*) FROM contratos_inmobiliaria AS ci LEFT JOIN contratos_solicitudes_liberaciones_inmobiliaria AS csli ON csli.contrato_id = ci.id WHERE csli.estado = "Validado" AND ci.id = contratos_inmobiliaria.id) AS solicitudes_liberaciones_validadas'),
            \DB::raw('estimacionDiariaContrato(contratos_inmobiliaria.id) as estimacion_actual'),
            \DB::raw('IFNULL((SELECT SUM(monto_restante) FROM contratos_anticipos_inmobiliaria AS cai WHERE cai.contrato_id = contratos_inmobiliaria.id AND estado = "Autorizado" AND pagado = "No"), 0) AS montoAnticipos')
        );

        if (!empty($datos['proyecto_id'])) {
            $query = $query->where('proyecto_id', $datos['proyecto_id']);
        }

        if (!empty($datos['contrato_id']) && $datos['contrato_id'] != "Todos") {
            $query = $query->where('id', $datos['contrato_id']);
        }

        if (!empty($datos['estado']) && $datos['estado'] != "Todos") {
            $query = $query->where('estado', $datos['estado']);
        }

        if (!empty($datos['restante'])) {
            if ($datos['restante'] == "Si") {
                $query = $query->where('restante', '>', 0);
            } else {
                $query = $query->where('restante', '<', 0);
            }
        }

        return $query->get();
    }

    public function proyecto()
    {
        return $this->belongsTo(InmobiliariaCatalogosProyecto::class, 'proyecto_id');
    }

    public function tipo()
    {
        return $this->belongsTo(InmobiliariaCatalogosContrato::class, 'contrato_tipo_id');
    }

    public function montoLetras()
    {
        $letras = new EnLetras();
        return $letras->ValorEnLetras($this->monto, 'pesos');
    }

    public function inmuebles()
    {
        return $this->hasMany(InmobiliariaContratosInmueble::class, 'contrato_id');
    }

    public function anticipos()
    {
        return $this->hasMany(InmobiliariaContratosAnticipo::class, 'contrato_id');
    }
}