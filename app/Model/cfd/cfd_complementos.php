<?php

namespace App\Model\cfd;

use Illuminate\Database\Eloquent\Model;

class cfd_complementos extends Model
{
    protected $table = 'cfd_complementos';

    protected $fillable = [
        'id_cfd_complemento',
        'id_cfd',
        'fecha_emision',
        'fecha_certificacion',
        'folio_fiscal_cfdi',
        'no_certificado_cfdi',
        'xml_timbrado',
        'sello_cfdi',
        'sello_sat',
        'qr_archivo',
        'qr_tipo_archivo',
        'qr_nombre_archivo',
        'pac_wsdl_url',
        'pac_nombre'
    ];

    public $timestamps = false;
}
