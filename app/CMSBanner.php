<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CMSBanner extends Model
{
    protected $table = 'cms_banners';
    protected $fillable = [
        'imagen', 'estado'
    ];

    public function sucursales()
    {
        return $this->belongsToMany('App\Model\sucursal', 'cms_banner_sucursal', 'cms_banner_id', 'sucursal_id');
    }
}
