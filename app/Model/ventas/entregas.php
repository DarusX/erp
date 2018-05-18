<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class entregas extends Model
{
    protected $table = 'ventas_entregas_fletes';
    protected $fillable = ['sucursal_id','coordenadas_entrega','coordenadas_salida', 'venta_id', 'peso','distancia', 'precio', 'dentro_area', 'ruta', 'direccion_entrega'];

    public function buscar($datos){
        $query = $this->leftJoin('cat_sucursales as s', 's.id_sucursal', '=', 'ventas_entregas_fletes.sucursal_id');
        $query->leftJoin('ventas as v', 'v.id_venta', '=', 'ventas_entregas_fletes.venta_id');
        $query->leftJoin('clientes as c', 'c.id_cliente', '=','v.id_cliente');

        $query->select(
          'ventas_entregas_fletes.*',
          's.nombre',
          'v.id_venta as folio_venta',
          'v.fecha as fecha_venta',
          'c.id_cliente as cliente_id',
          'c.nombre as cliente',
          'c.email',
          'c.telefono'

        );

        if(!empty($datos['venta_id'])){
            $query->where('ventas_entregas_fletes.venta_id', $datos['venta_id']);
        }
        if(!empty($datos['flete_id'])){
            $query->where('ventas_entregas_fletes.id', $datos['flete_id']);
        }
        if(!empty($datos['sucursal_id'])){
            $query->where('ventas_entregas_fletes.sucursal_id', $datos['sucursal_id']);
        }

        if(!empty($datos['fecha_inicio']) && !empty($datos['fecha_final'])){
            $query->where(DB::raw("DATE(v.fecha)"), '>=', $datos['fecha_inicio'])
                ->where(DB::raw("DATE(v.fecha)"),'<=', $datos['fecha_final']);
        }

        if(!empty($datos['first'])){
            return $query->first();
        }
       /* $query->where(\DB::raw("DATE(proyectos_proyectos.fecha_rechazo)"), '>=', $datos['fecha'])
            ->where(\DB::raw("DATE(proyectos_proyectos.fecha_rechazo)"), '<=', $datos['fecha2']);*/

        return $query->get();
}
}
