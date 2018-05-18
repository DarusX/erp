<?php

namespace App\Model\Proyectos;

use Illuminate\Database\Eloquent\Model;

class TareasProductos extends Model
{
    protected $table = 'proyectos_tareas_productos';
    protected $fillable = ['cantidad','costo','descripcion','tarea_id','producto_id', 'importe','subtotal'];

    public function tareas(){
    return $this->belongsToMany('App\Model\Proyectos\tareas', 'proyectos_tareas','tarea_id','id');
    }
    public function productos(){
        return $this->belongsToMany('App\Model\Productos', 'productos', 'producto_id','id_producto');
    }
    public function buscar($datos){
        $query =$this->leftJoin('proyectos_tareas as t', 't.id','=','proyectos_tareas_productos.tarea_id');
        $query->leftJoin('productos as p', 'p.id_producto','=','proyectos_tareas_productos.producto_id');

        $query->select(
            'proyectos_tareas_productos.*',
            \DB::raw('2 as bandera')
        );
        return $query->get();
    }

}
