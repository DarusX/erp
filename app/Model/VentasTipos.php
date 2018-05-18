<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VentasTipos extends Model
{
    protected $table = "ventas_tipos";
    protected $fillable = ['tipo'];

    public function buscar()
    {
        $query = $this;
        return $query->get();
    }
}