<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class categoria extends Model
{
    protected $table = 'proyectos_categorias';
    protected $fillable = ['nombre'];
    //
    public function proyecto(){
        return $this->belongsTo('App\proyecto');
    }

    public function buscar($datos)
    {
        $query = $this;

        $query = $query->select(
            'proyectos_categorias.id',
            'proyectos_categorias.nombre'

        );

        /*if(!empty($datos['menu'])){
            $query->where('menu', 'like', '%'.$datos['menu'].'%');
        }
        if(!empty($datos['tipo'])){
            $query->where('acl_menu.tipo', $datos['tipo']);
        }*/

        return $query->get();

    }
}
