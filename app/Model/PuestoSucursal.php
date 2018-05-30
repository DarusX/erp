<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PuestoSucursal extends Model
{
    protected $table = 'rh_puestos_sucursales';
    protected $primaryKey = 'id_puesto_sucursal';

    public function puesto()
    {
        return $this->belongsTo(Puesto::class, 'id_puesto', 'id_puesto');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal', 'id_sucursal');
    }
    
    public function empleados()
    {
        return $this->hasMany(Empleados::class, 'id_puesto_sucursal', 'id_puesto_sucursal');
    }
}
