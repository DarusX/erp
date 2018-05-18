<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class grupoAcl extends Model
{
    //
    protected $table = "acl_grupo";

    protected $fillable = [
        'grupo',
        'descripcion',
        'modulo_id'
    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin('acl_modulo as m', 'm.id', '=', 'acl_grupo.modulo_id');

        $query->select(
            'acl_grupo.*',
            'm.modulo'
        );

        if (!empty($datos["grupo"])) {
            $query = $query->where("grupo", "like", "%" . $datos["grupo"] . "%");
        }

        return $query->get();

    }
}
