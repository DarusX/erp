<?php

namespace App\Model\cfd;

use Illuminate\Database\Eloquent\Model;

class cfd_pacs extends Model
{
    protected $table = 'cfd_pacs';

    protected $fillable = [
        'id_cfd_pac',
        'nombre',
        'url_wsdl',
        'tk_wsdl',
        'user_wsdl',
        'pass_wsdl',
        'cuenta_wsdl',
        'url_wsdl_cancelacion',
        'url_wsdl_acuse'
    ];
}
