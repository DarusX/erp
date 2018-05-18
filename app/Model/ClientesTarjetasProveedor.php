<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClientesTarjetasProveedor extends Model
{
    protected $table = 'clientes_tarjeta_proveedor';

    protected $primaryKey = "id_tarjeta_cliente";

    public $timestamps = false;

    protected $fillable = [
        "id_cliente",
        "tarjeta",
        "fecha_alta",
        "id_usuario",
        "id_cliente_proveedor",
        "principal",
        "id_sucursal",
        "estatus",
    ];

    public function buscar ($datos)
    {

        $query = $this->from("clientes_tarjeta_proveedor as ctp")
            ->select("ctp.*")
            ->where("ctp.estatus", "activo");

        if (!empty($datos["cliente_id"])) {

            $query->where("ctp.id_cliente", $datos["cliente_id"]);

        }

        \Log::debug($query->toSql());

        return $query->first();

    }

}