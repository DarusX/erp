<?phpnamespace App\Model;use Illuminate\Database\Eloquent\Model;class Familia_porcentaje_utilidad extends Model{    protected $table = "productos_familias_porcentaje_utilidad";    protected $fillable = [        'familia_id',        'sucursal_id',        'porcentaje',        'nuevo_porcentaje',        'tipo_venta_id',        'tipo_precio_id',        'empleado_captura_utilidad_id',        'empleado_valida_utilidad_id',        'empleado_autoriza_utilidad_id'    ];    public function buscar($datos)    {        $query = $this->from('productos_familias_porcentaje_utilidad AS pfpu')            ->select(                "pfpu.*"            )            ->where('pfpu.sucursal_id', $datos['sucursal_id'])            ->where('pfpu.familia_id', $datos['familia_id'])            ->where('pfpu.tipo_venta_id', $datos['tipo_venta_id']);        if (isset($datos['first'])) {            return $query->first();        }        return $query->get();    }    public function buscarDatos($datos)    {        $query = $this->from('productos_familias_porcentaje_utilidad AS pfpu')            ->select(                "pfpu.*",                \DB::raw("ifnull(pfpu.porcentaje,0) as porcentaje")            )            ->where('pfpu.sucursal_id', $datos['sucursal_id'])            ->where('pfpu.familia_id', $datos['familia_id'])            ->where('pfpu.tipo_precio_id', $datos['tipo_precio_id']);        if (isset($datos['first'])) {            return $query->first();        }        return $query->get();    }    public function familia()    {        return $this->belongsTo(Familias::class, 'familia_id');    }}