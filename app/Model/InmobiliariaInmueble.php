<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class InmobiliariaInmueble extends Model
{
    protected $table = 'inmuebles_inmobiliaria';
    protected $fillable = ['nombre', 'direccion', 'precio_venta', 'precio_escritura', 'estado_id', 'valor_catastral', 'predial', 'alineamiento_numero', 'no_adeudo_agua', 'plano_utm', 'aviso_preventivo', 'plano_proyecto_tipo', 'proyecto_id', 'manzana', 'usuario_id', 'tipo_id'];
    protected $carpeta = 'inmobiliaria/inmuebles/';
    protected $usuario;

    public function __construct()
    {
        $this->usuario = \Session::get('usuario');
    }

    public function buscar($datos, $orden = null)
    {
        $query = $this->select(
            'inmuebles_inmobiliaria.*',
            \DB::raw('(SELECT COUNT(*) FROM inmuebles_inmobiliaria AS ii LEFT JOIN trabajos_inmobiliaria AS ti ON ti.inmueble_id = ii.id WHERE ti.id IS NOT NULL AND ii.id = inmuebles_inmobiliaria.id) AS trabajos'),
            \DB::raw('(SELECT SUM(cii.porcentaje_inmueble) FROM inmuebles_inmobiliaria AS ii LEFT JOIN conceptos_inmuebles_inmobiliaria AS cii ON cii.inmueble_id = ii.id WHERE ii.id = inmuebles_inmobiliaria.id) AS porcentaje'),
            \DB::raw('IFNULL((SELECT SUM(vai.monto) FROM inmuebles_inmobiliaria AS ii LEFT JOIN ventas_inmobiliaria AS v ON v.inmueble_id = ii.id LEFT JOIN ventas_abonos_inmobiliaria AS vai ON vai.venta_id = v.id WHERE ii.id = inmuebles_inmobiliaria.id AND v.estado != "Cancelado"), 0) AS abonos'),
            'vi.estado AS estado_venta',
            \DB::raw('IF(DATEDIFF(pmi.vigencia, NOW()) >= 0, "Vigente", "Vencido") AS vigencia'),
            \DB::raw('IFNULL(TIMESTAMPDIFF(MONTH, pmi.vigencia, DATE(NOW())), "N/D") AS meses_adeudo'),
            \DB::raw('(SELECT COUNT(*) FROM contratos_inmuebles_inmobiliaria WHERE inmueble_id = inmuebles_inmobiliaria.id) AS contratos'),
            \DB::raw($this->usuario['id_usuario'] . " AS usuario_actual_id")
        )
            ->leftJoin('ventas_inmobiliaria AS vi', function ($join) {
                $join->on('vi.inmueble_id', '=', 'inmuebles_inmobiliaria.id')
                    ->where('vi.estado', '!=', 'Cancelado');
            })
            ->leftJoin('pagos_mantenimientos_inmobiliaria AS pmi', 'pmi.inmueble_id', '=', 'inmuebles_inmobiliaria.id');
        if (!empty($datos['id'])) {
            $totalProductos = 0.00;
            $totalManoObra = 0.00;
            $totalMaquinaria = 0.00;
            $conceptos = InmobiliariaConceptosInmuebles::where('inmueble_id', $datos['id'])
                ->get();
            foreach ($conceptos as $concepto) {
                $productos = InmobiliariaConceptosProductosInmueble::where('concepto_id', $concepto->id)
                    ->sum('importe');
                $totalProductos += $productos;
                $manoObra = InmobiliariaConceptosManoObraInmueble::where('concepto_id', $concepto->id)
                    ->sum('importe');
                $totalManoObra += $manoObra;
                $maquinaria = InmobiliariaConceptosMaquinariaInmueble::where('concepto_id', $concepto->id)
                    ->sum('importe');
                $totalMaquinaria += $maquinaria;
            }
            $total = $totalProductos + $totalManoObra + $totalMaquinaria;
            $query = $query->addSelect(
                \DB::raw($totalProductos . " AS total_productos"),
                \DB::raw($totalManoObra . " AS total_mano_obra"),
                \DB::raw($totalMaquinaria . " AS total_maquinaria"),
                \DB::raw($total . " AS total_inmueble")
            );
            return $query->where('inmuebles_inmobiliaria.id', $datos['id'])
                ->first();
        }
        if (!empty($datos['nombre'])) {
            $query = $query->where('nombre', 'LIKE', $datos['nombre'] . '%');
        }
        if (!empty($datos['contrato_id']) && ($datos['contrato_id'] != "Todos" || $datos['contrato_id'] != 0)) {
            $contrato = InmobiliariaContrato::find($datos['contrato_id']);
            $query = $query->whereIn('inmuebles_inmobiliaria.id', $contrato->inmuebles()->lists('inmueble_id'));
        }
        if (isset($datos['cliente_id']) && $datos['cliente_id'] != "") {
            if ($datos['cliente_id'] != 0) {
                $query = $query->where('vi.cliente_id', $datos['cliente_id']);
            } else {
                $usuario = User::find(\Auth::id());
                $query = $query->where('vi.cliente_id', $usuario->cliente->id);
            }
        }
        if (!empty($datos["proyecto_id"])) {
            $query->where("inmuebles_inmobiliaria.proyecto_id", $datos["proyecto_id"]);
        }
        if ($orden != null) {
            $query = $query->orderBy($orden);
        }
        return $query->get();
    }

    public function proyecto()
    {
        return $this->belongsTo(InmobiliariaCatalogosProyecto::class, 'proyecto_id');
    }

    public function contratos()
    {
        return $this->belongsToMany(InmobiliariaContrato::class, 'contratos_inmuebles_inmobiliaria', 'inmueble_id', 'contrato_id');
    }

    public function trabajos()
    {
        return $this->hasMany(InmobiliariaTrabajo::class, 'inmueble_id');
    }

    public function setValorCatastralAttribute($value)
    {
        if ($value) {
            $ext = $value->getClientOriginalExtension();
            $nombre = "vc_" . $this->nombre . "." . $ext;
            $value->move($this->carpeta, $nombre);
            $this->attributes['valor_catastral'] = '/' . $this->carpeta . $nombre;
        }
    }

    public function setPredialAttribute($value)
    {
        if ($value) {
            $ext = $value->getClientOriginalExtension();
            $nombre = "predial_" . $this->nombre . "." . $ext;
            $value->move($this->carpeta, $nombre);
            $this->attributes['predial'] = '/' . $this->carpeta . $nombre;
        }
    }

    public function setAlineamientoNumeroAttribute($value)
    {
        if ($value) {
            $ext = $value->getClientOriginalExtension();
            $nombre = "alineamiento_numero_" . $this->nombre . "." . $ext;
            $value->move($this->carpeta, $nombre);
            $this->attributes['alineamiento_numero'] = '/' . $this->carpeta . $nombre;
        }
    }

    public function setNoAdeudoAguaAttribute($value)
    {
        if ($value) {
            $ext = $value->getClientOriginalExtension();
            $nombre = "no_adeudo_agua_" . $this->nombre . "." . $ext;
            $value->move($this->carpeta, $nombre);
            $this->attributes['no_adeudo_agua'] = '/' . $this->carpeta . $nombre;
        }
    }

    public function setPlanoUtmAttribute($value)
    {
        if ($value) {
            $ext = $value->getClientOriginalExtension();
            $nombre = "plano_utm_" . $this->nombre . "." . $ext;
            $value->move($this->carpeta, $nombre);
            $this->attributes['plano_utm'] = '/' . $this->carpeta . $nombre;
        }
    }

    public function setAvisoPreventivoAttribute($value)
    {
        if ($value) {
            $ext = $value->getClientOriginalExtension();
            $nombre = "aviso_preventivo_" . $this->nombre . "." . $ext;
            $value->move($this->carpeta, $nombre);
            $this->attributes['aviso_preventivo'] = '/' . $this->carpeta . $nombre;
        }
    }

    public function conceptos()
    {
        return $this->hasMany(InmobiliariaConceptosInmuebles::class, 'inmueble_id');
    }

    public function estado()
    {
        return $this->belongsTo(InmobiliariaCatalogosEstadoInmueble::class, 'estado_id');
    }

    public function ventas()
    {
        return $this->hasMany(InmobiliariaVentas::class, 'inmueble_id');
    }

    public function tipo()
    {
        return $this->belongsTo(InmobiliariaCatalogosTiposInmueble::class, 'tipo_id');
    }
}