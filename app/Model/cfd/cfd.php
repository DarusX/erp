<?php

namespace App\Model\cfd;

use Illuminate\Database\Eloquent\Model;

class cfd extends Model
{
    protected $table = 'cfd';

    protected $fillable = [
        'fecha',
        'id_venta',
        'folio_serie',
        'folio_numero',
        'no_certificado',
        'cadena_original',
        'sello_digital',
        'xml',
        'estatus',
        'total_texto',
        'codigo',
        'tipo_factura',
        'emisor_rfc',
        'emisor_nombre',
        'emisor_calle',
        'emisor_no_interior',
        'emisor_no_exterior',
        'emisor_colonia',
        'emisor_localidad',
        'emisor_municipio',
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
        'receptor_municipio',
        'receptor_estado',
        'receptor_pais',
        'receptor_cp',
        'subtotal',
        'total',
        'timbrado',
        'estatus_cfd',
        'descripcion_general',
        'tipo_emision'
    ];

    protected $primaryKey = 'id_cfd';
}
