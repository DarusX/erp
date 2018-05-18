<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Animal extends Model
{
    protected $table = 'agr_animal';

    protected $fillable = [
        'id',
        'numero',
        'arete',
        'fierro',
        'fecha_nacimiento',
        'genero',
        'raza',
        'raza_id',
        'lugar_nacimiento',
        'status',
        'peso',
        'imagen',
        'imagen_baja',
        'potrero_id',
        'madre_id',
        'padre_id',
        'cambio_status',
        'palpacion'
    ];

    public function buscar($datos)
    {

        //dd($datos);

        $query = $this->leftJoin('agr_potrero as p', 'p.id', '=', 'agr_animal.potrero_id');
        $query->leftJoin('agr_rancho as r', 'r.id', '=', 'p.rancho_id');
        $query->leftJoin('agr_razas as rz', 'rz.id', '=', 'agr_animal.raza_id');
        $query->leftJoin("agr_categorias_razas as craza", "craza.id", "=", "rz.id");
        $query->leftJoin('agr_animal_baja as baja', 'baja.animal_id', '=', 'agr_animal.id');
        $query->leftJoin('agr_motivo_baja as motiv', 'motiv.id', '=', 'baja.motivo_id');
        $query->leftJoin("agr_animales_venta as av", "av.animal_id", "=", "agr_animal.id");
        $query->leftJoin("agr_clasificaciones_animales as ca", function ($join) {
            $join->on("ca.raza_id", "=", "agr_animal.raza_id")
                ->on("ca.genero", "=", "agr_animal.genero")
                ->on("ca.dias_inicio", "<=", \DB::raw("datediff(now(),agr_animal.fecha_nacimiento)"))
                ->on("ca.dias_final", ">=", \DB::raw("datediff(now(),agr_animal.fecha_nacimiento)"));
        });
        $query->leftJoin("agr_cat_clasificaciones as cca", "cca.id", "=", "ca.clasificacion_id");

        if (!empty($datos["madres"])) {

            $query->leftJoin("agr_palpacion as pal", function ($join) {
                $join->on("pal.animal_id", "=", "agr_animal.id")
                    ->where("pal.status", "=", "activo");
            });
            $query->whereRaw("(SELECT COUNT(*) FROM agr_animal as ar where ar.madre_id = agr_animal.id) > 0");
            $query->where("agr_animal.status", "Activo");

            $query->select(
                'agr_animal.*',
                'p.potrero',
                'r.rancho',
                'r.id AS rancho_id',
                'rz.raza',
                'motiv.motivo',
                'baja.estatus',
                \DB::raw("ifnull(av.estatus,'') as estatus_venta"),
                \DB::raw("ifnull(av.comentarios,'') as comentarios"),
                \DB::raw("ifnull(ca.clasificacion,'S/C') AS clasificacion"),
                \DB::raw("ifnull(ca.precio_estandar,'0') AS precio_estandar"),
                \DB::raw("ifnull(craza.nombre,'') AS categoria"),
                \DB::raw("ifnull(pal.estado,'Vacia') AS palpacion"),
                \DB::raw("ifnull(pal.condicion,'S/P') AS condicion")
            );

        } elseif (!empty($datos["partos"])) {

            $query->leftJoin("agr_palpacion as pal", function ($join) {
                $join->on("pal.animal_id", "=", "agr_animal.id")
                    ->where("pal.status", "=", "activo");
            });
            $query->whereRaw("(SELECT COUNT(*) FROM agr_animal as ar where ar.madre_id = agr_animal.id) = 0");
            $query->where("agr_animal.status", "Activo");

            $query->select(
                'agr_animal.*',
                'p.potrero',
                'r.rancho',
                'r.id AS rancho_id',
                'rz.raza',
                'motiv.motivo',
                'baja.estatus',
                \DB::raw("ifnull(av.estatus,'') as estatus_venta"),
                \DB::raw("ifnull(av.comentarios,'') as comentarios"),
                \DB::raw("ifnull(ca.clasificacion,'S/C') AS clasificacion"),
                \DB::raw("ifnull(ca.precio_estandar,'0') AS precio_estandar"),
                \DB::raw("ifnull(craza.nombre,'') AS categoria"),
                \DB::raw("ifnull(pal.estado,'Vacia') AS palpacion"),
                \DB::raw("ifnull(pal.condicion,'S/C') AS condicion")
            );

        } else {

            $query->select(
                'agr_animal.*',
                'p.potrero',
                'r.rancho',
                'r.id AS rancho_id',
                'rz.raza',
                'motiv.motivo',
                'baja.estatus',
                \DB::raw("ifnull(av.estatus,'') as estatus_venta"),
                \DB::raw("ifnull(av.comentarios,'') as comentarios"),
                \DB::raw("ifnull(ca.clasificacion,'S/C') AS clasificacion"),
                \DB::raw("ifnull(ca.precio_estandar,'0') AS precio_estandar"),
                \DB::raw("ifnull(craza.nombre,'') AS categoria")
            );

        }


        if (!empty($datos['numero'])) {
            $query->where('numero', 'LIKE', '%' . $datos['numero'] . '%');
        }
        if (!empty($datos['arete'])) {
            $query->where('arete', 'LIKE', '%' . $datos['arete'] . '%');
        }
        if (!empty($datos['fierro'])) {
            $query->where('fierro', 'LIKE', '%' . $datos['fierro'] . '%');
        }
        if (!empty($datos['fecha_ini'])) {
            $query->where('fecha_nacimiento', '>=', [$datos['fecha_ini']]);
        }
        if (!empty($datos["fecha_fin"])){
            $query->where('fecha_nacimiento', '<=', [$datos['fecha_fin']]);
        }
        if (!empty($datos['genero'])) {
            $query->where('agr_animal.genero', '=', $datos['genero']);
        }
        if (!empty($datos['raza'])) {
            if (count($datos["raza"]) > 1) {
                $query->whereIn("rz.id", $datos["raza"]);
            } else {
                $query->where('rz.id', '=', $datos['raza']);
            }
        }
        if (!empty($datos['rancho_id'])) {
            $query->where('r.id', '=', $datos['rancho_id']);
        }
        if (!empty($datos['potrero_id'])) {
            $query->where('potrero_id', '=', $datos['potrero_id']);
        }
        if (!empty($datos['status'])) {
            $query->where('agr_animal.status', '=', $datos['status']);
        }
        if (!empty($datos['animal_id'])) {
            $query->where('agr_animal.id', '=', $datos['animal_id']);
        }
        if (!empty($datos['motivo_id'])) {
            $query->where('baja.motivo_id', '=', $datos['motivo_id']);
        }
        if (!empty($datos['pendientes']) && $datos['pendientes'] == 1) {
            $query->where('baja.estatus', '=', 'capturado');
        }
        if (!empty($datos["madre_id"])) {
            $query->where("agr_animal.madre_id", "=", $datos['madre_id']);
        }
        if (!empty($datos["clasificacion"])) {
            if (count($datos["clasificacion"]) > 1) {
                $query->whereIn("cca.id", $datos["clasificacion"]);
            } else {
                $query->where("cca.id", $datos["clasificacion"]);
            }
        }
        if (!empty($datos["peso_ini"])) {
            $query->where("peso", ">=", $datos["peso_ini"]);
        }
        if (!empty($datos["peso_fin"])) {
            $query->where("peso", "<=", $datos["peso_fin"]);
        }

        if (!empty($datos['id'])) {
            $query->where('agr_animal.id', $datos['id']);
            if (!empty($datos["first"])) {
                //dd($query->get());
                return $query->first();
            }
        }

        if (!empty($datos["venta"])) {
            $query->where("av.estatus", $datos["venta"]);
        }

        if (!empty($datos["condicion"])) {
            $query->leftJoin("agr_palpacion as pal", "pal.animal_id", "=", "agr_animal.id");
            $query->where("pal.condicion", $datos["condicion"]);
            $query->where("pal.status", "activo");
        }

        if (!empty($datos["categoria"])) {
            if (count($datos["categoria"]) > 1) {
                $query->whereIn("rz.categoria_id", $datos["categoria"]);
            } else {
                $query->where("rz.categoria_id", $datos["categoria"]);
            }
        }

        //dd($query->toSql());

        return $query->get();
    }

    public function raza()
    {
        return $this->hasOne(Raza::class, 'id', 'raza_id');
    }

    public function pesos()
    {

        $query = $this->leftJoin('agr_potrero as p', 'p.id', '=', 'agr_animal.potrero_id');
        $query->leftJoin('agr_rancho as r', 'r.id', '=', 'p.rancho_id');
        $query->leftJoin('agr_razas as rz', 'rz.id', '=', 'agr_animal.raza_id');

        $query->select(
            'r.rancho as name',
            'r.rancho as drilldown',
            \DB::raw('SUM(peso) as y')
        )->groupBy('r.id')->where('agr_animal.status', 'activo')->orderBy('r.id', 'asc');

        return $query->get();

    }

    public function ranchos()
    {

        $query = $this->leftJoin('agr_potrero as p', 'p.id', '=', 'agr_animal.potrero_id');
        $query->leftJoin('agr_rancho as r', 'r.id', '=', 'p.rancho_id');
        $query->leftJoin('agr_razas as rz', 'rz.id', '=', 'agr_animal.raza_id');

        $query->select(
            'r.rancho as name',
            'r.rancho as drilldown',
            \DB::raw('COUNT(*) as y')
        )->groupBy('r.id')->where('agr_animal.status', 'activo')->orderBy('r.id', 'asc');

        return $query->get();

    }

    public function potreros()
    {

        $query = $this->leftJoin('agr_potrero as p', 'p.id', '=', 'agr_animal.potrero_id');
        $query->leftJoin('agr_rancho as r', 'r.id', '=', 'p.rancho_id');
        $query->leftJoin('agr_razas as rz', 'rz.id', '=', 'agr_animal.raza_id');

        $query->select(
            'agr_animal.id',
            'p.potrero',
            \DB::raw('COUNT(*) as cantidad_potreros')
        )->groupBy('p.id')->where('agr_animal.status', 'activo');

        return $query->get();

    }

    public function generos()
    {

        $query = $this->select(
            'agr_animal.genero as name',
            'agr_animal.genero as drilldown',
            \DB::raw('COUNT(*) as y')
        )->groupBy('genero')->where('status', 'activo');

        return $query->get();

    }

    public function razas()
    {

        $query = $this->select(
            'raza as name',
            'raza as drilldown',
            \DB::raw('COUNT(*) as y')
        )->groupBy('raza_id')->where('status', 'activo');

        return $query->get();

    }

    public function buscarVacasPariciones($datos)
    {

        $query = $this->leftJoin('agr_potrero as p', 'p.id', '=', 'agr_animal.potrero_id');
        $query->leftJoin('agr_rancho as r', 'r.id', '=', 'p.rancho_id');
        $query->leftJoin('agr_razas as rz', 'rz.id', '=', 'agr_animal.raza_id');
        $query->leftJoin("agr_categorias_razas as craza", "craza.id", "=", "rz.id");
        $query->leftJoin('agr_animal_baja as baja', 'baja.animal_id', '=', 'agr_animal.id');
        $query->leftJoin('agr_motivo_baja as motiv', 'motiv.id', '=', 'baja.motivo_id');
        $query->leftJoin("agr_animales_venta as av", "av.animal_id", "=", "agr_animal.id");
        $query->leftJoin("agr_clasificaciones_animales as ca", function ($join) {
            $join->on("ca.raza_id", "=", "agr_animal.raza_id")
                ->on("ca.genero", "=", "agr_animal.genero")
                ->on("ca.dias_inicio", "<=", \DB::raw("datediff(now(),agr_animal.fecha_nacimiento)"))
                ->on("ca.dias_final", ">=", \DB::raw("datediff(now(),agr_animal.fecha_nacimiento)"));
        });
        $query->leftJoin("agr_cat_clasificaciones as cca", "cca.id", "=", "ca.clasificacion_id");
        $query->leftJoin("agr_animal as a", function ($join) use ($datos) {
            $join->on("a.madre_id", "=", "agr_animal.id")
                ->where("a.fecha_nacimiento", ">=", $datos["fecha_ini"])
                ->where("a.fecha_nacimiento", "<=", $datos["fecha_fin"]);
        });
        $query->leftJoin("configuraciones_generales as cg", function ($join){
            $join->where("cg.id", "=", 1);
        });

        $query->select(
            "r.id as rancho_id",
            "r.rancho",
            \DB::raw("count(agr_animal.id) as total_rancho"),
            \DB::raw("sum(if(a.id is not null,1,0)) as pariciones")
        );

        $query->where("agr_animal.status", "Activo");
        $query->whereRaw("cca.id = cg.id_vaca");
        $query->groupBy("r.id");

        //dd($query->toSql());
        return $query->get();

    }

    public function totalVacasGestantes($datos)
    {

        $query = $this->leftJoin('agr_potrero as p', 'p.id', '=', 'agr_animal.potrero_id');
        $query->leftJoin('agr_rancho as r', 'r.id', '=', 'p.rancho_id');
        $query->leftJoin('agr_razas as rz', 'rz.id', '=', 'agr_animal.raza_id');
        $query->leftJoin("agr_categorias_razas as craza", "craza.id", "=", "rz.id");
        $query->leftJoin('agr_animal_baja as baja', 'baja.animal_id', '=', 'agr_animal.id');
        $query->leftJoin('agr_motivo_baja as motiv', 'motiv.id', '=', 'baja.motivo_id');
        $query->leftJoin("agr_animales_venta as av", "av.animal_id", "=", "agr_animal.id");
        $query->leftJoin("agr_clasificaciones_animales as ca", function ($join) {
            $join->on("ca.raza_id", "=", "agr_animal.raza_id")
                ->on("ca.genero", "=", "agr_animal.genero")
                ->on("ca.dias_inicio", "<=", \DB::raw("datediff(now(),agr_animal.fecha_nacimiento)"))
                ->on("ca.dias_final", ">=", \DB::raw("datediff(now(),agr_animal.fecha_nacimiento)"));
        });
        $query->leftJoin("agr_cat_clasificaciones as cca", "cca.id", "=", "ca.clasificacion_id");
        $query->leftJoin("agr_palpacion as pal", function ($join) use ($datos) {
            $join->on("pal.animal_id", "=", "agr_animal.id")
                ->where("pal.status", "=", "activo")
                ->where("pal.estado", "=", "Gestante")
                ->where("pal.fecha", ">=", $datos["fecha_ini"])
                ->where("pal.fecha", "<=", $datos["fecha_fin"]);
        });
        $query->leftJoin("configuraciones_generales as cg", function ($join){
            $join->where("cg.id", "=", 1);
        });

        $query->select(
            "r.id as rancho_id",
            "r.rancho",
            \DB::raw("count(agr_animal.id) as total_rancho"),
            \DB::raw("sum(if(pal.id is not null,1,0)) as gestantes")
        );

        $query->where("agr_animal.status", "Activo");
        $query->whereRaw("cca.id = cg.id_vaca");
        $query->groupBy("r.id");

        //dd($query->toSql());
        return $query->get();

    }

    public function verNacimientos($datos)
    {

        $query = $this->leftJoin('agr_potrero as p', 'p.id', '=', 'agr_animal.potrero_id');
        $query->leftJoin('agr_rancho as r', 'r.id', '=', 'p.rancho_id');
        $query->leftJoin('agr_razas as rz', 'rz.id', '=', 'agr_animal.raza_id');
        $query->leftJoin("agr_categorias_razas as craza", "craza.id", "=", "rz.id");
        $query->leftJoin('agr_animal_baja as baja', 'baja.animal_id', '=', 'agr_animal.id');
        $query->leftJoin('agr_motivo_baja as motiv', 'motiv.id', '=', 'baja.motivo_id');
        $query->leftJoin("agr_animales_venta as av", "av.animal_id", "=", "agr_animal.id");
        $query->leftJoin("agr_clasificaciones_animales as ca", function ($join) {
            $join->on("ca.raza_id", "=", "agr_animal.raza_id")
                ->on("ca.genero", "=", "agr_animal.genero")
                ->on("ca.dias_inicio", "<=", \DB::raw("datediff(now(),agr_animal.fecha_nacimiento)"))
                ->on("ca.dias_final", ">=", \DB::raw("datediff(now(),agr_animal.fecha_nacimiento)"));
        });
        $query->leftJoin("agr_cat_clasificaciones as cca", "cca.id", "=", "ca.clasificacion_id");

        $query->select(
            "r.id as rancho_id",
            "r.rancho",
            \DB::raw("count(agr_animal.id) as nacimientos")
        );

        $query->where("agr_animal.status", "Activo");
        $query->where("agr_animal.fecha_nacimiento", ">=", $datos["fecha_ini"]);
        $query->where("agr_animal.fecha_nacimiento", "<=", $datos["fecha_fin"]);
        $query->where("r.id", $datos["rancho_id"]);
        $query->groupBy("r.id");

        //dd($query->toSql());
        return $query->get();

    }

    public function promedioCondicion($datos)
    {

        $query = $this->leftJoin('agr_potrero as p', 'p.id', '=', 'agr_animal.potrero_id');
        $query->leftJoin('agr_rancho as r', 'r.id', '=', 'p.rancho_id');
        $query->leftJoin('agr_razas as rz', 'rz.id', '=', 'agr_animal.raza_id');
        $query->leftJoin("agr_categorias_razas as craza", "craza.id", "=", "rz.id");
        $query->leftJoin('agr_animal_baja as baja', 'baja.animal_id', '=', 'agr_animal.id');
        $query->leftJoin('agr_motivo_baja as motiv', 'motiv.id', '=', 'baja.motivo_id');
        $query->leftJoin("agr_animales_venta as av", "av.animal_id", "=", "agr_animal.id");
        $query->leftJoin("agr_clasificaciones_animales as ca", function ($join) {
            $join->on("ca.raza_id", "=", "agr_animal.raza_id")
                ->on("ca.genero", "=", "agr_animal.genero")
                ->on("ca.dias_inicio", "<=", \DB::raw("datediff(now(),agr_animal.fecha_nacimiento)"))
                ->on("ca.dias_final", ">=", \DB::raw("datediff(now(),agr_animal.fecha_nacimiento)"));
        });
        $query->leftJoin("agr_cat_clasificaciones as cca", "cca.id", "=", "ca.clasificacion_id");
        $query->leftJoin("agr_palpacion as pal", function ($join) use ($datos) {
            $join->on("pal.animal_id", "=", "agr_animal.id")
                ->where("pal.fecha", ">=", $datos["fecha_ini"])
                ->where("pal.fecha", "<=", $datos["fecha_fin"]);
        });

        $query->select(
            "r.id as rancho_id",
            "r.rancho",
            \DB::raw("ifnull(avg(pal.condicion),0) as promedio")
        );

        $query->where("agr_animal.status", "Activo");

        if (!empty($datos["general"])) {
            return $query->first();
        } else {
            $query->groupBy("r.id");
        }

        //dd($query->toSql());
        return $query->get();

    }

    public function muertes($datos)
    {

        $query = $this->leftJoin("agr_potrero as p", "p.id", "=", "agr_animal.potrero_id");
        $query->leftJoin("agr_rancho as r", "r.id", "=", "p.rancho_id");
        $query->leftJoin("agr_animal_baja as ab", function ($join) use ($datos){
            $join->on("ab.animal_id", "=", "agr_animal.id")
                ->where("ab.estatus", "=", "autorizado")
                ->where("ab.motivo_id", "=", 1)
                ->where("ab.fecha_captura", ">=", $datos["fecha_ini"])
                ->where("ab.fecha_captura", "<=", $datos["fecha_fin"]);
        });

        $query->select(
            "r.id",
            "r.rancho",
            \DB::raw("sum(if(agr_animal.`status` = 'Activo',1,0)) AS total_animales"),
            \DB::raw("count(ab.id) as total_muertes")
        );

        $query->groupBy("r.id");

        //dd($query->toSql());
        return $query->get();

    }

}
