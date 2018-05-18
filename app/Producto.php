<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'id_producto', ' id_familia', ' id_categoria', ' id_linea', ' codigo_producto', ' codigo_barras', ' descripcion_corta', ' descripcion', ' clave_producto_servicio', ' unidad_compra', ' unidad_venta', ' unidad_medida_sat', ' minimo', ' factor_conversion', ' negativos', ' peso', ' id_iva', ' uc', ' uv', ' linea', ' codigo_truper', ' activo', ' familia', ' id_usuario_edito', ' fecha_edicion', ' garantia', ' estatus_producto', ' fecha_creacion', ' usuario_creo', ' id_usuario_creo', ' clasificacion', ' master', ' pagina', ' imagen', ' tipo_factor_conversion', ' actualizacion_precios', ' costeo_varios', ' estatus_utilidad', ' estatus'
    ];

    public function productos()
    {
        return $this->belongsToMany(Venta::class,'ventas_descripcion','id_venta','id_venta');
        //->withPivot('id_descripcion','fecha','id_sucursal','id_almacen','iva','cantidad','precio','porcentaje_iva','precio_vigente','costo','unidad_medida','forma_venta','codigo_producto','descripcion','porcentaje_envio','ventaAtipica');
    }


}
