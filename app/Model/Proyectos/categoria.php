<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class categoria extends Model
{
    protected $table = 'proyectos_categorias';
    protected $fillable = ['nombre'];
    //
    public function proyecto(){
        return $this->belongsTo('App\Model\Proyectos\proyecto');
    }

    public function buscar($datos)
    {
        $query = $this;

        $query = $query->select(
            'proyectos_categorias.id',
            'proyectos_categorias.nombre'
        );

        if(!empty($datos['categoria'])){
            $query->where('proyectos_categorias.id', $datos['categoria']);
        }

        return $query->get();

    }
}
