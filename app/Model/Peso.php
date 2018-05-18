<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Peso extends Model
{
    protected $table = 'agr_animal_peso';

    protected $fillable = [
        'id',
        'animal_id',
        'peso',
        'fecha_peso',
        'usuario_id'
    ];

    public function buscar($datos){

        if(!empty($datos['animal_id'])){

            $query = $this->where('animal_id', $datos['animal_id']);

            $query->select(
                'agr_animal_peso.*'
            );

            return $query->get();
        }
    }

    public function buscarPesos($datos)
    {

        $query = $this->leftJoin("agr_animal as a", "a.id", "=", "agr_animal_peso.animal_id");
        $query->leftJoin("agr_potrero as p", "p.id", "=", "a.potrero_id");
        $query->leftJoin("agr_rancho as r", "r.id", "=", "p.rancho_id");

        $query->select(
            "agr_animal_peso.id",
            "agr_animal_peso.animal_id",
            \DB::raw("peso_anterior(agr_animal_peso.id) as peso_anterior"),
            "agr_animal_peso.peso as peso_nuevo"
        );

        $query->where("agr_animal_peso.fecha_peso", ">=", $datos['fecha_ini']);
        $query->where("agr_animal_peso.fecha_peso", "<=", $datos["fecha_fin"]);

        $query->groupBy("agr_animal_peso.animal_id");

        $query->orderBy("agr_animal_peso.id", "desc");

        //dd($query->toSql());

        return $query->get();

    }

    public function buscarPesosRancho($datos)
    {

        $query = $this->leftJoin("agr_animal as a", "a.id", "=", "agr_animal_peso.animal_id");
        $query->leftJoin("agr_potrero as p", "p.id", "=", "a.potrero_id");
        $query->leftJoin("agr_rancho as r", "r.id", "=", "p.rancho_id");
        $query->leftJoin("agr_animal_peso as apa", "apa.animal_id", "=", "agr_animal_peso.animal_id");

        $query->select(
            "r.id",
            "r.rancho",
            \DB::raw("avg(apa.peso) as peso_nuevo"),
            \DB::raw("avg(agr_animal_peso.peso) as peso_anterior")
        );

        $query->where("agr_animal_peso.fecha_peso", ">=", $datos['fecha_ini']);
        $query->where("agr_animal_peso.fecha_peso", "<=", $datos["fecha_fin"]);
        $query->whereRaw("agr_animal_peso.id < apa.id");

        $query->groupBy("r.id");

        //dd($query->toSql());

        return $query->get();

    }
}
