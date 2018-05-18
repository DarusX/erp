<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DispersionesProveedores extends Model
{
    protected $table = "dispersion_proveedores_archivos";

    protected $primaryKey = "id_archivo_dispersion";

    public function obtenerArchivo($id){
        $query = $this->leftJoin("cat_proveedores as p", "dispersion_proveedores_archivos.id_empleado", "=", "rh_empleados_monto.empleado_id");
        $query->select('dispersion_proveedores_archivos.*');

        $query->where("dispersionesTransacciones.id_archivo_dispersion ",$id);

        //dd($query->toSql());
        return $query->first();
    }
}
