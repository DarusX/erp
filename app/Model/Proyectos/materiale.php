<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class materiale extends Model
{
    //
    protected $table= 'proyectos_materiales';
    protected $fillable= ['nombre', 'cantidad','costo', 'descripcion','proyecto_id', 'updated_at', 'created_at','sumatoria','impuesto','iva', 'sumatoriaIva'];
    public function proyecto(){
        return $this->hasMany('App\Model\Proyectos\proyecto');
    }
    public function buscar($datos){
        $query = $this->leftJoin("proyectos_proyectos as p", "p.id", "=", "proyectos_materiales.proyecto_id");
        $query->select(
            'proyectos_materiales.*',
            'p.nombre as proyecto',
            //'p.costo_total as sumatoria',
            \DB::raw('2 as bandera')
        );
        if (!empty($datos['proyecto_id'])){
            $query->where("proyectos_materiales.proyecto_id", $datos['proyecto_id']);
        }
        return $query->get();
    }
}
