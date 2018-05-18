<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class materiale extends Model
{
    //
    protected $table= 'proyectos_materiales';
    protected $fillable= ['nombre', 'cantidad','costo', 'descripcion','proyecto_id','importe', 'updated_at', 'created_at','subtotal','impuesto'];
    public function proyecto(){
        return $this->hasMany('App\proyecto');
    }
    public function buscar($datos){
        $query = $this->leftJoin("proyectos_proyectos as p", "p.id", "=", "proyectos_materiales.proyecto_id");
        $query->select(
            'proyectos_materiales.*',
            'p.nombre as proyecto',
            \DB::raw('2 as bandera')
        );
        if (!empty($datos['proyecto_id'])){
            $query->where("proyectos_materiales.proyecto_id", $datos['proyecto_id']);
        }
        return $query->get();
    }
}
