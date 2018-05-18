<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Almacenes extends Model
{
    protected $table = 'almacenes';

    protected $fillable = [
        'id_sucursal',
        'almacen',
        'codigo_almacen',
        'bandera',
        'almacenes'
    ];

    protected $primaryKey = 'id_almacen';

    public function buscar($datos)
    {

        $query = $this->select(
            'almacenes.*'
        );

        if (!empty($datos['almacen'])) {
            if (empty($datos["first"])) {
                $query->where('almacen', 'like', '%' . $datos['almacen'] . '%');
            } else {
                $query->where('almacen', $datos['almacen']);
                return $query->first();
            }
        }
        if (!empty($datos['id_sucursal'])) {
            if (is_array($datos["id_sucursal"])) {
                $query->whereIn('id_sucursal', $datos['id_sucursal']);
            } else {
                $query->where('id_sucursal', $datos['id_sucursal']);
            }
        }
        if (!empty($datos["id_almacen"])) {
            $query->where("id_almacen", $datos["id_almacen"]);
            return $query->first();
        }

        if (!empty($datos['bandera'])) {
            $query = $query->where('bandera', $datos['bandera']);
        }

        if (!empty($datos['first'])) {
            if ($datos['first']) {
                return $query->first();
            }
        }

        //dd($query->toSql());
        return $query->get();
    }
}
