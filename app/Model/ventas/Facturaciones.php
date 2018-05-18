<?php

namespace App\Model\ventas;

use Illuminate\Database\Eloquent\Model;

class Facturaciones extends Model
{
    protected $table = 'cfd_complementos';

    protected $fillable = ['id_cfd_complemento', 'id_cfd', 'fecha_emision', 'fecha_certificacion', 'folio_fiscal_cfdi', 'no_certificado_cfdi', 'xml_timbrado', 'sello_cfdi', 'sello_sat', 'qr_archivo', 'qr_tipo_archivo', 'qr_nombre_archivo', 'pac_wsdl_url', 'pac_nombre'];

    protected $primaryKey = 'id_cfd_complemento';

    public function cfd_ventas()
    {
        return $this->hasOne('App\Model\cfd_ventas');
    }

    public function cfd()
    {
        return $this->hasOne('App\Model\cfd');
    }

    public function scopeClienteId($query, $cliente_id){
        return $query->where('c.id_cliente', $cliente_id);
    }


}
