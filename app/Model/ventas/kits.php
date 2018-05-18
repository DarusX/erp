<?php

namespace App\Model\ventas;

use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;

class kits extends Model
{
    protected $table = 'ventas_paquetes';

    protected $fillable = ['descripcion', 'codigo', 'activo', 'costo'];


    public function sucursales()
    {
        return $this->belongsToMany('App\Sucursales', 'ventas_paquetes_sucursales', 'paquete_id', 'sucursal_id');
    }

    public function precios()
    {
        return $this->belongsToMany('App\Model\ventas\PaqueteSucursalesPrecios', 'ventas_paquetes_productos_sucursales_precios', 'paquete_id');
    }

    public function buscar($datos)
    {
        $query = $this->select(
            'ventas_paquetes.*'
        );

        if (!empty($datos['codigo'])) {
            $query->where('ventas_paquetes', "like", '%' . $datos['codigo'] . '%');
        }

        if (!empty($datos["paquete_id"])) {
            $query->where("ventas_paquetes.id", $datos["paquete_id"]);
        }

        if (!empty($datos["activo"])) {
            $query->where("ventas_paquetes.activo", $datos["activo"]);
        }

        if (!empty($datos["first"])) {
            return $query->first();
        }

        return $query->get();
    }
}
