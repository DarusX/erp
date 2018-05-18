<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class profecionPuesto extends Model
{
    //
    protected $table = "rh_profecion_puesto";
    protected $primaryKey = "id_profecion_puesto";
    protected $fillable = [
        'id_puesto',
        'id_profecion',
        'id_perfil',
        'estatus'
    ];
    public function profesion(){
        return $this->belongsTo(profecion::class,"id_profecion","id_profecion");
    }
    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_profecion as p", "p.id_profecion", "=", "rh_profecion_puesto.id_profecion");


        if (isset($datos["id_puesto"])) {
            $query->where("p.id_puesto", "=", $datos["id_puesto"]);
        }
        if (isset($datos["id_perfil"])) {
            $query->where("rh_profecion_puesto.id_perfil", "=", $datos["id_perfil"]);
        }
        $query->whereNotNull("p.profecion");

        //dd($query->toSql());
        $query->select(
            "p.profecion",
            "rh_profecion_puesto.*"

        );


        return $query->get();

    }
}
