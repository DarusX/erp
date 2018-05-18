<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TransferenciasDirectasMercado extends Model
{

    protected $table = "transferencias_directas_mercado";

    protected $primaryKey = "id_entrada_mercado";

    public function buscar($datos)
    {

        $query = $this->leftJoin("productos as p", "p.id_producto", "=", "transferencias_directas_mercado.id_producto");

        $query->select(
            "transferencias_directas_mercado.*"
        );

        if (!empty($datos["id_almacen"])){

            $query->where("transferencias_directas_mercado.id_almacen", $datos["id_almacen"]);

        }

        if (!empty($datos["codigo_producto"])){

            $query->where("p.codigo_producto", $datos["codigo_producto"]);

        }

        if (!empty($datos["fecha_inicio"])){

            $query->where("transferencias_directas_mercado.fecha",  ">=", $datos["fecha_inicio"]);

        }

        if (!empty($datos["fecha_final"])){

            $query->where("transferencias_directas_mercado.fecha",  "<=", $datos["fecha_final"]);

        }

        return $query->get();

    }

}
