<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ConversionesSucursal extends Model
{
    protected $table = 'conversiones_sucursales';
    protected $primaryKey = 'id_conversion';
    public $timestamps = false;

    public function detalles()
    {
        return $this->hasOne(ConversionesSucursalesDescripcion::class, 'id_conversion');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function sucursal()
    {
        return $this->belongsTo(sucursal::class, 'id_sucursal');
    }
}