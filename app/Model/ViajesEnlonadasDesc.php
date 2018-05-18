<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ViajesEnlonadasDesc extends Model
{

    protected $table = "viajes_enlonadas_costo_detalle";

    protected $primaryKey = "id_costo";

    protected $fillable = [
        "id_costo",
        "peso_inicial",
        "peso_final",
        "costo"
    ];

    public function buscar($datos)
    {

        $query = $this->select(
            "viajes_enlonadas_costo_detalle.*"
        );

        if (isset($datos["peso"])){
            
            $query->whereRaw(\DB::raw($datos["peso"]. " between peso_inicial and peso_final"));

        }

        if (!empty($datos["first"])){

            return $query->first();

        }

        //dd($query->toSql());

        return $query->get();

    }

}
