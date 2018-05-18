<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DispersionesTransacciones extends Model
{
    protected $table = "dispersionesTransacciones";

    protected $primaryKey = "id_transaccion";

    public function obtenerFolio($cadenaFolio,$idArchivo){
        $query = $this->leftJoin("cat_proveedores as p", "dispersionesTransacciones.id_proveedor", "=", "p.id_proveedor");
        $query->select('dispersionesTransacciones.*');
        $query->where("dispersionesTransacciones.folioRegistro",$cadenaFolio);
        $query->where("dispersionesTransacciones.id_archivo_dispersion",$idArchivo);


        //dd($query->toSql());
        return $query->first();
    }

    public function obtenerSubfolio($cadenaFolio,$idArchivo,$estatusMovimiento){
        $query = $this->leftJoin("cat_proveedores as p", "dispersionesTransacciones.id_proveedor", "=", "p.id_proveedor");
        $query->select('dispersionesTransacciones.*');
        $query->where("dispersionesTransacciones.subfolioConciliacion",$cadenaFolio);
        $query->where("dispersionesTransacciones.id_archivo_dispersion",$idArchivo);
        if(!empty($estatusMovimiento)){
            $query->where('dispersionesTransacciones.estatus_movimiento', '=', $estatusMovimiento);
        }


        //dd($query->toSql());
        return $query->first();
    }

    public function obtenerTransacciones($idArchivo){
        $query =  $this->from('dispersionesTransacciones as dt');
        $query->leftJoin("cat_proveedores as p", "dt.id_proveedor", "=", "p.id_proveedor");
        $query->leftJoin("dispersion_proveedores_archivos as dp", "dt.id_archivo_dispersion", "=", "dp.id_archivo_dispersion");
            $query->select('dt.*','dp.fecha_creacion',
                \DB::raw('obtenerProveedorNombre(dt.id_proveedor) as proveedorNombre'),
                \DB::raw('obtenerBancoNombre(dt.id_banco) as bancoNombre'),
                \DB::raw('contabilidadObtenerClaveCuenta(dt.id_cuenta_contable) as claveCuenta'),
                \DB::raw('nombreMes(DATE(dp.fecha_creacion)) as nombreMes'),
                \DB::raw('contabilidadObtenerDescripcionCuenta(dt.id_cuenta_contable) as descripcionCuenta'));
        $query->where('dt.estatus_movimiento',"aplicado");
//            $query->where('dt.exentar','no');
            $query->where('dt.id_archivo_dispersion',$idArchivo);

        $transacciones = $query->get();

        //OBTENIENDO LOS DATOS DE LAS FACTURAS Y PAGOS EXTRAORDINARIOS

//        $dbTransaccionesFacturas = new DispersionesTransaccionesFacturas();
        foreach($transacciones as $k=> $transaccion){


            $facturas = DispersionesTransaccionesFacturas::where('id_transaccion',$transaccion->id_transaccion)->get();
            $transacciones[$k]->facturas = array();
            if(count($facturas)){
                $transacciones[$k]->facturas = $facturas;
            }

            //PAGOS EXTRAORDINARIOS
            $pagosExtraordinarios = DispersionesTransaccionesPagosExtraordinarios::where('id_transaccion',$transaccion->id_transaccion)->get();
            $transacciones[$k]->pagosExtraordinarios = array();
            if(count($pagosExtraordinarios)){
                $transacciones[$k]->pagosExtraordinarios = $pagosExtraordinarios;
            }

        }
//        dd($query->toSql());
        return $transacciones;


    }
}
