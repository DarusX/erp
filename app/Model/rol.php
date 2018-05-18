<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class rol extends Model
{
    //
    protected $table = "acl_rol";
    protected $fillable = ["rol"];

    public function buscar($datos)
    {
        if (!empty($datos["rol"])) {
            return $this->where("rol","like","%".$datos["rol"]."%");
        }
        return $this->get();
    }
}
