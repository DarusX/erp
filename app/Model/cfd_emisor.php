<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cfd_emisor extends Model
{
    protected $table = 'cfd_datos_emisor';

    protected $fillable = [
        'id_emisor',
        'id_sucursal',
        'nombre',
        'razon_social',
        'rfc',
        'no_certificado',
        'user_wsdl',
        'pass_wsdl',
        'password_key'
    ];

    public function buscar($datos)
    {

        $query = $this->from("cfd_datos_emisor as c");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "c.id_sucursal");

        $query->select(
            "c.*",
            "s.nombre as sucursal"
        );

        return $query->get();

    }

    public function buscarExistencias($datos)
    {

        $query = $this->from("cfd_datos_emisor as cde");
        $query->leftJoin("cat_sucursales as s", "s.id_sucursal", "=", "cde.id_sucursal");

        $query->select(
            "s.id_sucursal",
            "s.nombre as sucursal",
            \DB::raw("obtenerExistenciaSucursal(". $datos["producto_id"] .",cde.id_sucursal) as existencia")
        );

        return $query->get();

    }

}
