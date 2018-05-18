<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class departamento extends Model
{
    //
    protected $table = "rh_departamento";
    protected $fillable = [
        'departamento',
        'estatus'
    ];

    function buscar($datos){
        if(isset($datos["departamento"]))
            if($datos["departamento"]!="")
                return $this->where("departamento","like","%".$datos["departamento"]."%")->get();
        return $this::all();
    }
}
