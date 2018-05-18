<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlmacenesExistenciasInmobiliaria extends Model
{
    protected $table = "almacenes_existencias";

    protected $primarykey = 'id_existencia';

    protected $connection = "inmobiliaria";

    protected $fillable = [
        'id_sucursal',
        'id_almacen',
        'id_familia',
        'id_categoria',
        'id_linea',
        'id_producto',
        'existencia',
        'stock_minimo',
        'stock_maximo',
        'codigo_almacen',
        'codigo_producto',
        'conteo',
        'precio_costo'
    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin('almacenes as a', 'a.id_almacen', '=', 'almacenes_existencias.id_almacen');

        $query->select(
            'almacenes_existencias.id_sucursal',
            'almacenes_existencias.id_almacen',
            'almacenes_existencias.id_producto',
            'a.bandera'
        )->where('almacenes_existencias.id_sucursal', $datos['id_sucursal'])
            ->where('a.bandera', $datos['bandera']);

        if(!empty($datos['id_producto'])){
            $query->where('id_producto', $datos['id_producto']);
        }

        //dd($query->toSql());

        return $query->get();
    }

    public function buscarAlmacenes($datos)
    {
        $query = $this->leftJoin('almacenes as a', 'a.id_almacen', '=', 'almacenes_existencias.id_almacen');

        $query->select(
            'almacenes_existencias.id_sucursal',
            'almacenes_existencias.id_almacen',
            'a.almacen',
            'almacenes_existencias.id_producto',
            'a.bandera'
        );

        if(!empty($datos['id_producto'])){
            $query->where('id_producto', $datos['id_producto']);
        }
        if(!empty($datos['id_sucursal'])){
            $query->where('almacenes_existencias.id_sucursal', $datos['id_sucursal']);
        }

        //dd($query->toSql());

        return $query->get();
    }
}
