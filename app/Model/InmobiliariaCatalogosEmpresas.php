<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaCatalogosEmpresas extends Model
{
    protected $table = 'catalogos_empresas_inmobiliaria';
    protected $fillable = ['nombre', 'email', 'rfc', 'calle', 'no_exterior', 'no_interior', 'colonia', 'ciudad', 'entidad_federativa', 'telefono', 'identificacion_apoderado', 'acta_constitutiva', 'comprobante_domicilio', 'licencia_ventas', 'comision', 'usuario_id'];
    protected $carpeta = "inmobiliaria/catalogos/empresa/";

    public function setIdentificacionApoderadoAttribute($value)
    {
        if ($value) {
            $ext = strtolower($value->getClientOriginalExtension());
            $nombre = md5(time()) . "0." . $ext;
            $value->move($this->carpeta, $nombre);
            $this->attributes['identificacion_apoderado'] = "/" . $this->carpeta . $nombre;
        }
    }

    public function setActaConstitutivaAttribute($value)
    {
        if ($value) {
            $ext = strtolower($value->getClientOriginalExtension());
            $nombre = md5(time()) . "1." . $ext;
            $value->move($this->carpeta, $nombre);
            $this->attributes['acta_constitutiva'] = "/" . $this->carpeta . $nombre;
        }
    }

    public function setComprobanteDomicilioAttribute($value)
    {
        if ($value) {
            $ext = strtolower($value->getClientOriginalExtension());
            $nombre = md5(time()) . "2." . $ext;
            $value->move($this->carpeta, $nombre);
            $this->attributes['comprobante_domicilio'] = "/" . $this->carpeta . $nombre;
        }
    }

    public function setLicenciaVentasAttribute($value)
    {
        if ($value) {
            $ext = strtolower($value->getClientOriginalExtension());
            $nombre = md5(time()) . "3." . $ext;
            $value->move($this->carpeta, $nombre);
            $this->attributes['licencia_ventas'] = "/" . $this->carpeta . $nombre;
        }
    }

    public function buscar($datos)
    {
        $query = $this->select(
            'catalogos_empresas_inmobiliaria.*'
        )
            ->leftJoin('usuarios AS u', 'u.id_usuario', '=', 'catalogos_empresas_inmobiliaria.usuario_id');

        return $query->get();
    }
}