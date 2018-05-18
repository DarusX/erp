<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class InmobiliariaCatalogosCliente extends Model
{
    protected $table = 'catalogos_clientes_inmobiliaria';
    protected $fillable = ['tipo', 'nombre', 'rfc', 'calle', 'no_exterior', 'no_interior', 'colonia', 'localidad', 'municipio', 'entidad_federativa', 'pais', 'cp', 'telefono', 'email', 'copia_identificacion', 'curp', 'acta_nacimiento', 'comprobante_domicilio', 'observaciones', 'credito_id', 'medio_difusion_id', 'nss1', 'nss2', 'fecha_nacimiento1', 'fecha_nacimiento2', 'vendedor_id'];
    protected $carpeta = "inmobiliaria/catalogos/clientes/";
    protected $sesion;

    public function __construct()
    {
        $this->sesion = Session::get('usuario');
    }

    public function buscar($datos)
    {
        $query = $this->select(
            'catalogos_clientes_inmobiliaria.*',
            'cmdi.nombre AS medio_difusion'
        )
            ->leftJoin('catalogos_medios_difusion_inmobiliaria AS cmdi', 'cmdi.id', '=', 'catalogos_clientes_inmobiliaria.medio_difusion_id');
        if (!empty($datos['nombre'])) {
            $query = $query->where('catalogos_clientes_inmobiliaria.nombre', 'LIKE', '%' . $datos['nombre'] . '%');
        }
        if (!empty($datos['rfc'])) {
            $query = $query->where('rfc', 'LIKE', '%' . $datos['rfc'] . '%');
        }
        if (!empty($datos['tipo'])) {
            $query = $query->where('tipo', $datos['tipo']);
        }
        if (isset($datos['id']) && $datos['id'] != "") {
            if ($datos['id'] != 0) {
                $query = $query->where('catalogos_clientes_inmobiliaria.id', $datos['id']);
            } else {
                $usuario = User::find(\Auth::id());
                $query = $query->where('vi.cliente_id', $usuario->cliente->id);
            }
        }

        if ($this->sesion['rol_id'] == 16) {
            $query = $query->where('vendedor_id', $this->sesion['id_usuario']);
        }
        if ($this->sesion['rol_id'] == 15) {
            $empresa = \Auth::user()->empresa->id;
            $vendedores = InmobiliariaCatalogosVendedores::where('empresa_id', $empresa)
                ->lists('usuario_id');
            $vendedores[] = \Auth::id();
            $query = $query->whereIn('vendedor_id', $vendedores);
        }

        if (!empty($datos['fecha_inicio'])) {
            $query = $query->where(\DB::raw('DATE(catalogos_clientes_inmobiliaria.created_at)'), '>=', $datos['fecha_inicio']);
        }

        if (!empty($datos['fecha_termino'])) {
            $query = $query->where(\DB::raw('DATE(catalogos_clientes_inmobiliaria.created_at)'), '<=', $datos['fecha_termino']);
        }

        if (!empty($datos['medio_difusion_id']) && $datos['medio_difusion_id'] != "Todos") {
            $query = $query->where('catalogos_clientes_inmobiliaria.medio_difusion_id', $datos['medio_difusion_id']);
        }

        if (!empty($datos['empresa_id']) && $datos['empresa_id'] != 0) {
            if (!empty($datos['vendedor_id']) && $datos['vendedor_id'] != 0) {
                $query = $query->where('vendedor_id', $datos['vendedor_id']);
            } else {
                $vendedores = InmobiliariaCatalogosVendedores::where('empresa_id', $datos['empresa_id'])
                    ->lists('usuario_id');
                $query = $query->whereIn('vendedor_id', $vendedores);
            }
        }
        return $query->get();
    }

    public function setCopiaIdentificacionAttribute($value)
    {
        if ($value) {
            $ext = $value->getClientOriginalExtension();
            $nombre = 'copia_identificacion' . md5(time()) . "." . $ext;
            $value->move($this->carpeta, $nombre);
            $this->attributes['copia_identificacion'] = "/" . $this->carpeta . $nombre;
        }
    }

    public function setCurpAttribute($value)
    {
        if ($value) {
            $ext = $value->getClientOriginalExtension();
            $nombre = 'curp' . md5(time()) . "." . $ext;
            $value->move($this->carpeta, $nombre);
            $this->attributes['curp'] = "/" . $this->carpeta . $nombre;
        }
    }

    public function setActaNacimientoAttribute($value)
    {
        if ($value) {
            $ext = $value->getClientOriginalExtension();
            $nombre = 'acta_nacimiento' . md5(time()) . "." . $ext;
            $value->move($this->carpeta, $nombre);
            $this->attributes['acta_nacimiento'] = "/" . $this->carpeta . $nombre;
        }
    }

    public function setComprobanteDomicilioAttribute($value)
    {
        if ($value) {
            $ext = $value->getClientOriginalExtension();
            $nombre = 'comprobante_domicilio' . md5(time()) . "." . $ext;
            $value->move($this->carpeta, $nombre);
            $this->attributes['comprobante_domicilio'] = "/" . $this->carpeta . $nombre;
        }
    }

    public function credito()
    {
        return $this->belongsTo(InmobiliariaCatalogosCredito::class, 'credito_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
