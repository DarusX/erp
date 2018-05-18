<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table ='ventas';
    protected $primaryKey ='id_venta';

    protected $fillable = [
       'tipo_venta','fecha','id_usuario','id_sucursal','id_caja','id_cliente','id_tipo_venta','id_tipo_pago','venta_especial','atipica','credito','venta_costo','descuento','subtotal','iva','total','efectivo','estatus','modificacion_precio','autoservicio_mostrador','contado_credito','envio','fecha_compromiso'
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class,'ventas_descripcion','id_venta','id_producto')->withPivot('id_descripcion','fecha','id_sucursal','id_almacen','iva','cantidad','precio','porcentaje_iva','precio_vigente','costo','unidad_medida','forma_venta','codigo_producto','descripcion','porcentaje_envio','ventaAtipica');
    }
}
