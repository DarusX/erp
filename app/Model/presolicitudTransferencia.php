<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class presolicitudTransferencia extends Model
{
    //
    protected $table = "presolicitud_transferencia";
    protected $fillable = [
        'sucursal_origen_id',
        'sucursal_destino_id',
        'empleado_captura_id',
        'empleado_acepta_id',
        'empleado_valida_id',
        'empleado_autoriza_id',
        'empleado_cancela_id',
        'comentario',
        'empleado_seguimiento_id',
        'fecha_captura',
        'fecha_aceptacion',
        'fecha_validacion',
        'fecha_autorizacion',
        'fecha_canccelacion',
        'tipo',
        'estatus'

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("cat_sucursales as sd", "sd.id_sucursal", "=", "presolicitud_transferencia.sucursal_destino_id");
        $query->leftJoin("cat_sucursales as so", "so.id_sucursal", "=", "presolicitud_transferencia.sucursal_origen_id");
        $query->leftJoin("rh_empleados as e", "e.id_empleado", "=", "presolicitud_transferencia.empleado_captura_id");
        $query->leftJoin("rh_empleados as e2", "e2.id_empleado", "=", "presolicitud_transferencia.empleado_acepta_id");
        $query->leftJoin("rh_empleados as e3", "e3.id_empleado", "=", "presolicitud_transferencia.empleado_valida_id");
        $query->leftJoin("rh_empleados as e4", "e4.id_empleado", "=", "presolicitud_transferencia.empleado_autoriza_id");
        $query->leftJoin("rh_empleados as e5", "e5.id_empleado", "=", "presolicitud_transferencia.empleado_cancela_id");
        $query->leftJoin("rh_empleados as e6", "e6.id_empleado", "=", "presolicitud_transferencia.empleado_seguimiento_id");

        if (!empty($datos["presolicitud_id"])) {
            $query->where("presolicitud_transferencia.id", $datos["presolicitud_id"]);
        }

        if (!empty($datos["sucursal_origen"])){
            $query->where("presolicitud_transferencia.sucursal_origen_id", $datos["sucursal_origen"]);
        }

        if (!empty($datos["sucursal_destino"])){
            $query->where("presolicitud_transferencia.sucursal_destino_id", $datos["sucursal_destino"]);
        }

        if (!empty($datos["involucrado"]) && !empty($datos["empleado_captura_id"])) {
            $query->where("presolicitud_transferencia.sucursal_origen_id", $datos["involucrado"])
                ->orWhere("presolicitud_transferencia.sucursal_destino_id", $datos["involucrado"])
                ->orWhere("presolicitud_transferencia.empleado_captura_id", $datos["empleado_captura_id"]);

        }
        if (!empty($datos["fecha_inicio"])) {
            $query->where("presolicitud_transferencia.created_at", ">=", $datos["fecha_inicio"]);
        }
        if (!empty($datos["fecha_termino"])) {
            $query->where("presolicitud_transferencia.created_at", ">=", $datos["fecha_termino"]);
        }
        if (!empty($datos["empleado_seguimiento_id"])) {
            $query->where("presolicitud_transferencia.empleado_seguimiento_id", "=", $datos["empleado_seguimiento_id"]);
        }
        if (!empty($datos["estatus"])) {
            $query->where("presolicitud_transferencia.estatus", $datos["estatus"]);
        }
        if (!empty($datos["orden"])){
            $query->where("presolicitud_transferencia.transferencia_orden_id", $datos["orden"]);
        }


        $select = [
            "presolicitud_transferencia.*",
            "sd.nombre as sucursal_destino",
            "so.nombre as sucursal_origen",
            \DB::raw("concat(e.nombre,' ',e.apaterno,' ',e.amaterno) as empleado_captura"),
            \DB::raw("concat(e2.nombre,' ',e2.apaterno,' ',e2.amaterno) as empleado_acepta"),
            \DB::raw("concat(e3.nombre,' ',e3.apaterno,' ',e3.amaterno) as empleado_valida"),
            \DB::raw("concat(e4.nombre,' ',e4.apaterno,' ',e4.amaterno) as empleado_autoriza"),
            \DB::raw("concat(e5.nombre,' ',e5.apaterno,' ',e5.amaterno) as empleado_cancela"),
            \DB::raw("concat(e6.nombre,' ',e6.apaterno,' ',e6.amaterno) as empleado_seguimiento"),
            \DB::raw("ifnull(presolicitud_transferencia.transferencia_orden_id,'') as transferencia_orden_id"),

        ];
        $query->select($select);
//        dd($query->toSql());

        if (!empty($datos["first"]))
            return $query->first();

        return $query->get();


    }
}
