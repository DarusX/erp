<?php

namespace App\Model\cfd;

use Illuminate\Database\Eloquent\Model;

class CfdVentaAnimal extends Model
{
    protected $table = 'cfd_venta_animal';

    protected $fillable = [
        'id',
        'cfd_id',
        'venta_id'
    ];

    public function buscar($datos){

        $query = $this->leftJoin('cfd as cfd', 'cfd.id_cfd', '=', 'cfd_venta_animal.cfd_id');
        $query->leftJoin('agr_ventas as v', 'v.id', '=', 'cfd_venta_animal.venta_id');

        $query->select(
            'cfd_venta_animal.*',
            'v.formaPago',
            \DB::raw('ifnull(v.metodoPago,"") as metodoPago'),
            \DB::raw('ifnull(v.clave_fiscal_id,"") as clave_fiscal_id'),
            \DB::raw('ifnull(v.NumCtaPago,"") as NumCtaPago')
        );

        if(!empty($datos['cfd_id'])){
            $query->where('cfd_id', $datos['cfd_id']);
        }

        $query->get();

    }
}
