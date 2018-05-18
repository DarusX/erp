<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $table = 'clientes';
    protected $fillable = ['codigo_cliente','nombre', 'fecha_nacimiento', 'rfc', 'domicilio', 'calle', 'no_exterior', 'no_interior', 'colonia', 'localidad', 'municipio', 'id_estado', 'textEstado', 'textCiudad',  'cp', 'telefono', 'email', 'descuento', 'limite_credito', 'plazo_credito', 'id_tipo_credito', 'id_cuenta', 'id_ocupacion', 'porcentaje', 'contacto', 'cuenta', 'clave', 'venta_especial', 'venta_a_costo', 'estatus_cliente', 'id_empleado', 'fecha_creacion', 'imagen', 'created_at', 'updated_at', 'precioVentaEspecial', 'id_clasificacion', 'id_tipo_precio', 'tipo_precio_asignado', 'clasificacion_mensual','id_tipo_credito', 'status', 'envio_correo'];
    protected $primaryKey = 'id_cliente';

    public function buscar($datos){

        $query = $this->leftJoin("ventas_clasificacion_clientes as c", "c.id", "=", "clientes.id_clasificacion");
        $query->leftJoin("ventas_tipo_precio as t", "t.id", "=", "clientes.id_tipo_precio");
        $query->leftJoin('cat_estados as e', 'e.id_estado', '=', 'clientes.id_estado');
        $query->leftJoin('ventas_tipo_precio as t1', "t1.id", "=", "clientes.tipo_precio_asignado");

        $query->select(
          'clientes.*',
          'c.id',
          'c.clasificacion',
          \DB::raw("ifnull(clientes.id_tipo_precio,1) as id_tipo_precio"),
          \DB::raw("ifnull(t.tipo,'Publico') as tipo"),
          't.id as id_tipo_precio',
          't.tipo as nombre_tipo_precio',
          't1.tipo as precio_asignado',
          'e.estado'
        );

        if (!empty($datos["id_cliente"])) {

            $query->where("clientes.id_cliente", $datos["id_cliente"]);

        }

        if (!empty($datos["cliente_id"])) {
            $query->where("clientes.id_cliente", $datos["cliente_id"]);
        }

        if(!empty($datos['tipo_precio'])){
            $query->where('t.id', $datos['tipo_precio']);
        }

        if(!empty($datos['clasificacion'])){
            $query->where('c.id', $datos['clasificacion']);
        }

        if(!empty($datos['cliente'])){
            $query->where('clientes.nombre','like', '%'.$datos['cliente'].'%');
        }
        if(!empty($datos['rfc'])){
            $query->where('clientes.rfc', 'like', '%'.$datos['rfc'].'%');
        }

        if (!empty($datos["busqueda"])){
            $query->where("clientes.nombre", "like", $datos["busqueda"] ."%")
                ->orWhere("clientes.rfc", "like", $datos["busqueda"] ."%");
            $query->where("clientes.status", "activo");
        }

        if (!empty($datos['limite'])) {
            $query = $query->limit($datos['limite']);
        }

        if (!empty($datos["first"])) {
            return $query->first();
        }

        if(!empty($datos['status'])){
            $query->where('clientes.status', $datos['status']);
        }

        $query->groupBy("clientes.id_cliente");

        return $query->get();
    }
}
