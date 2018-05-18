<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TransferenciasOrdenesObservaciones extends Model
{
    protected $table = "transferencias_ordenes_observaciones";

    protected $primarykey = "id_observacion";

    protected $fillable = [
        'id_transferencia_orden',
        'observacion',
        'fecha'
    ];

    public function buscar($datos){
        $query = $this->leftJoin('transferencias_ordenes as to', 'to.id_transferencia_orden', '=', 'transferencias_ordenes_observaciones.id_transferencia_orden');

        $query->select(
            'transferencias_ordenes_observaciones.*'
        );

        if(!empty($datos['id_transferencia_orden'])){
            $query->where('transferencias_ordenes_observaciones.id_transferencia_orden', $datos['id_transferencia_orden']);
        }

        return $query->get();
    }
}
