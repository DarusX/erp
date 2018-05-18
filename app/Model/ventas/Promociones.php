<?php

namespace App\Model\ventas;

use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model;
use DB;

class Promociones extends Model
{
    protected $table = 'ventas_promociones_descuentos';
    protected $fillable = ['nombre', 'descuento', 'fecha_final', 'fecha_inicial', 'descuento_volumen', 'familia_id', 'linea_id', 'categoria_id', 'alcance', 'precio', 'utilidad', 'costo'];

    public function sucursales(){
        return $this->belongsToMany('App\Sucursales','ventas_promociones_sucursales','descuento_id','sucursal_id');
    }

    public function productos(){
        return $this->belongsToMany('App\Model\Productos','ventas_promociones_productos', 'descuento_id', 'producto_id');
    }

    public function buscar($datos){
        $query = $this->leftJoin('ventas_promociones_productos as p', 'p.descuento_id', '=', 'ventas_promociones_descuentos.id');
        $query->leftJoin('ventas_promociones_sucursales as s', 's.descuento_id', '=', 'ventas_promociones_descuentos.id');

        $query->select(
            'ventas_promociones_descuentos.*'
        );

        if(!empty($datos['fecha_inicio']) && !empty($datos['fecha_fin'])){
            $query->where(DB::raw("DATE(ventas_promociones_descuentos.fecha_inicial)"), '>=', $datos['fecha_inicio'])
            ->where(DB::raw("DATE(ventas_promociones_descuentos.fecha_final)"), '<=', $datos['fecha_fin']);
        }

        if(!empty($datos['sucursal'])){
            $query->where('s.sucursal_id', $datos['sucursal']);
        }
        if(!empty($datos['nombre'])){
            $query->where('ventas_promociones_descuentos.nombre', 'like', '%' . $datos['nombre'] . '%');
        }
        if (!empty($datos["fecha_actual"])){
            $query->whereRaw("'". $datos["fecha_actual"]. "' BETWEEN ventas_promociones_descuentos.fecha_inicial AND ventas_promociones_descuentos.fecha_final");
        }
        if (!empty($datos["first"])){


        if (!empty($datos['descuento_id'])) {

            $query->where('ventas_promociones_descuentos.id', $datos['descuento_id']);
        }

        if(!empty($datos['first'])){
            return $query->first();
        }

            return $query->first();

        }

        if (!empty($datos['descuento_id'])) {
            $query->where('ventas_promociones_descuentos.id', $datos['descuento_id']);
        }


        $query->groupBy('ventas_promociones_descuentos.id');

        

        return $query->get();
    }
}
