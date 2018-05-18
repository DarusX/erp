<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InmobiliariaCatalogosVendedores extends Model
{
    protected $table = 'catalogos_vendedores_inmobiliaria';
    protected $fillable = ['nombre', 'email', 'comision', 'usuario_id', 'empresa_id'];

    public function buscar($datos)
    {
        $query = $this->select('*');
        if (\Auth::user()->rol_id == 15) {
            $query->where('empresa_id', \Auth::user()->empresa->id);
        }

        if (!empty($datos['empresa_id']) && $datos['empresa_id'] != 0) {
            $query = $query->where('empresa_id', $datos['empresa_id']);
        }
        return $query->get();
    }
}