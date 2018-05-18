<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class aclModulo extends Model
{
    //
    protected $table = "acl_modulo";
    protected $fillable = [
        'modulo'
    ];

    public function buscar($datos)
    {
        $query = $this;

        if (!empty($datos["modulo"])) {
            $query = $query->where("modulo", "like", "%" . $datos["modulo"] . "%");
        }

        return $query->get();

    }
}
