<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdministradorValidaciones extends Model
{
    protected $table = "administrador_validaciones";

    protected $fillable = [
        "rol_id",
        "tipo_valor",
        "valor_inicial",
        "valor_final",
        "orden",
        "estado"
    ];

    public function buscar($datos) {

        $query = $this->from("administrador_validaciones as av");
        $query->leftJoin("acl_rol as r", "r.id", "=", "av.rol_id");

        $select = [
            "av.*",
            "r.rol"
        ];

        $query->select($select);

        if (!empty($datos["rol_id"])) {

            $query->where("av.rol_id", $datos["rol_id"]);

        }

        if (!empty($datos["estado"])) {

            $query->where("av.estado", $datos["estado"]);

        }

        $query->orderBy("av.orden", "asc");

        return $query->get();

    }

}
