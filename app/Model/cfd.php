<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class cfd extends Model
{
    //
    protected $table = "cfd";
    protected $primaryKey = "id_cfd";
    public $timestamps = false;
    protected $fillable = [
        'fecha',
        'id_venta',
        'id_devolucion',
        'id_cliente',
        'folio_serie',
        'folio_numero',
        'folio_aprobacion_numero',
        'folio_aprobacion_anio',
        'no_certificado',
        'cadena_original',
        'sello_digital',
        'xml',
        'estatus',
        'total_texto',
        'codigo',
        'tipo_factura',
        'version_cfd',
        'tipo_cfd',
        'emisor_rfc',
        'emisor_nombre',
        'emisor_calle',
        'emisor_no_interior',
        'emisor_no_exterior',
        'emisor_colonia',
        'emisor_localidad',
        'emisor_estado',
        'emisor_pais',
        'emisor_cp',
        'emisor_regimen',
        'receptor_rfc',
        'receptor_nombre',
        'receptor_calle',
        'receptor_no_interior',
        'receptor_no_exterior',
        'receptor_colonia',
        'receptor_localidad',
        'receptor_estado',
        'receptor_pais',
        'receptor_cp',
        'subtotal',
        'total',
        'timbrado',
        'estatus_cfd',
        'observacion_general',
        'tipo_emision',


    ];
}
