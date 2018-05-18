<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursales extends Model
{
    protected $table='cat_sucursales';
    public function proyectos(){
        return $this->belongsToMany('App\proyecto','proyectos_sucursales');
    }
    public function promociones(){
        return $this->belongsToMany('App\Model\ventas\Promociones','ventas_promociones_sucursales');
    }

    public function paquetes(){
        return $this->belongsToMany('App\Model\ventas\kitsSucursales','ventas_paquetes_sucursales');
    }



}
