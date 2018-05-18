<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CfdDatosEmisor extends Model
{
    protected $table = 'cfd_datos_emisor_domicilio_fiscal';

    protected $fillable = [
        'id_cfd_datos',
        'id_sucursal',
        'rfc',
        'calle',
        'no_exterior',
        'no_interior',
        'referencia',
        'colonia',
        'localidad',
        'municipio',
        'estado',
        'pais',
        'cp',
        'telefonos',
        'regimen'
    ];

    protected $primaryKey = 'id_cfd_datos';

    public function buscar($datos)
    {
        $query = $this->from('cfd_datos_emisor_domicilio_fiscal AS cdedf')
            ->select(
                'cdedf.*',
                'cs.nombre AS sucursal',
                'cde.no_certificado',
                'cde.nombre'
            )
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'cdedf.id_sucursal')
            ->leftJoin('cfd_datos_emisor AS cde', 'cde.id_sucursal', '=', 'cdedf.id_sucursal');

        if (!empty($datos['sucursal_id'])) {
            $query = $query->where('cdedf.id_sucursal', $datos['sucursal_id']);
        }

        return $query->get();
    }

    public function buscarDatosMapa($datos)
    {
        $query = $this->from('cfd_datos_emisor_domicilio_fiscal AS cdedf')
            ->select(
                'cdedf.*',
                'cs.nombre AS sucursal',
                'cs.mapa AS coordenadas',
                //'pk.*',
                'ae.*'
            )
            ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'cdedf.id_sucursal')
            //->leftJoin('ventas_precios_km as pk', 'pk.sucursal_id', '=', 'cdedf.id_sucursal')
            ->leftJoin('ventas_areas_entregas as ae', 'ae.sucursal_id', '=', 'cdedf.id_sucursal');


        if (!empty($datos['sucursal_id'])) {
            $query->where('cdedf.id_sucursal', $datos['sucursal_id']);

        }

        if (!empty($datos["first"])) {
            return $query->first();
        }


        return $query->get();
    }

    /*  public function buscarRadio($datos){
          $query = $this->from('cfd_datos_emisor_domicilio_fiscal AS cdedf')
          ->select(
              'cdedf.*',
              'cs.nombre AS sucursal',
              'ae.*'

          )
              ->leftJoin('cat_sucursales AS cs', 'cs.id_sucursal', '=', 'cdedf.id_sucursal')


          return $query->get();

      }*/

}