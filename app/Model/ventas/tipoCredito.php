<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class tipoCredito extends Model
{
    protected $table = 'creditos_tipos';
    protected $fillable = ['id_tipo_credito', 'tipo_credito', 'por_venta'];
    protected $primaryKey = 'id_tipo_credito';

    public function buscar($datos)
    {
        $query = $this->select(
            'creditos_tipos.*'
        );

        if (!empty($datos['id_tipo_credito'])) {
            $query->where("creditos_tipos.id_tipo_credito", $datos['id_tipo_credito']);
        }
        if (!empty($datos['tipo_credito'])) {
            $query->where("creditos_tipos.tipo_credito", "like", "%" . $datos['tipo_credito'] . "$");
        }
        if(!empty($datos['por_venta'])){
            $query->where("creditos_tipos.por_venta", $datos['por_venta']);
        }

        if (!empty($datos['estado'])) {
            $query->where("creditos_tipos.estado", $datos['estado']);
        }

        $query->groupBy("creditos_tipos.id_tipo_credito");

        return $query->get();
    }
}
