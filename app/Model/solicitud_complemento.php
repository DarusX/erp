<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class solicitud_complemento extends Model
{
    //
    protected $table = "rh_calidad_solicitud_complementos";
    protected $primaryKey = "id_solicitud_complemetno";
    protected $fillable = [
        'id_solicitud',
        'id_orden_descripcion',
        'id_solicitud_comentario',
        'id_existencia',
        'id_producto',
        'costo',
        'cantidad',
        'entregada',
        'auditado',
        'id_auditor',
        'fecha_auditoria',
        'id_ajuste',
        'id_ajuste_descripcion',
        'estatus',

    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin("rh_calidad_solicitud_comentario as sc", "sc.id_solicitud_comentario", "=", "rh_calidad_solicitud_complementos.id_solicitud_comentario");
        $query->leftJoin("rh_calidad_solicitud as sl", "sl.id_solicitud", "=", "rh_calidad_solicitud_complementos.id_solicitud");
        $query->leftJoin("productos as p", "p.id_producto", "=", "rh_calidad_solicitud_complementos.id_producto");

        if (!empty($datos["not_estatus"])) {
            $query->whereNotIn("sl.estatus", $datos["not_estatus"]);
        }
        if (!empty($datos["estatus_comentario"])) {
            $query->where("sc.estatus", "=", $datos["estatus_comentario"]);
        }
        if (!empty($datos["estatus"])) {
            $query->where("rh_calidad_solicitud_complementos.estatus", "=", $datos["estatus"]);
        }
        if (!empty($datos["fecha"])) {
            $query->whereRaw("(DATE_FORMAT(sc.fecha_validacion, '%Y-%m-01')) = '" . $datos["fecha"] . "'");
        }
        if (!empty($datos["id_sucursal"])) {
            $query->where("sl.id_sucursal", "=", $datos["id_sucursal"]);
        }
        if (!empty($datos["tipo"])) {
            $query->where("sl.tipo", "=", $datos["tipo"]);
        }

        $query->select("rh_calidad_solicitud_complementos.*",
            "p.codigo_producto", "p.descripcion", "sl.id_sucursal"
        );

        return $query->get();


        /*SELECT
                        ifnull(sum(cantidad*costo),0)
                        from rh_calidad_solicitud_complementos as c
                        LEFT JOIN rh_calidad_solicitud_comentario as sc on sc.id_solicitud_comentario = c.id_solicitud_comentario
                        LEFT JOIN rh_calidad_solicitud as sl on sl.id_solicitud=c.id_solicitud
                        where	sl.estatus not in('cancelada')
                        and sc.estatus='validado'
                        and c.estatus = 'activo'
                       AND DATE_FORMAT(sc.fecha_validacion, '%Y-%m-01')= fecha
												And sl.id_sucursal = sucursal_id
												AND sl.tipo = tipo
                     */

    }
}
