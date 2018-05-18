<?php

namespace App\Model;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class InmobiliariaCatalogosPaquete extends Model
{
    protected $table = 'catalogos_paquetes_inmobiliaria';
    protected $fillable = ['nombre', 'precio', 'vigencia', 'empresa_id'];
    protected $usuario;

    public function __construct()
    {
        $this->usuario = Session::get('usuario');
    }

    public function buscar($datos)
    {
        $query = $this;
        if (!empty($datos['nombre'])) {
            $query->where('nombre', 'LIKE', '%' . $datos['nombre'] . '%');
        }

        if ($this->usuario['rol_id'] == 16 || $this->usuario['rol_id'] == 15) {
            $usuario = User::find($this->usuario['id_usuario']);
            if ($this->usuario['rol_id'] == 15) {
                $empresa = $usuario->empresa->id;
            }
            if ($this->usuario['rol_id'] == 16) {
                $empresa = $usuario->vendedor->empresa_id;
            }
            $query = $query->where('empresa_id', $empresa)
                ->whereNotNull('vigencia')
                ->where('vigencia', '>=', Carbon::now());
        }

        return $query->get();
    }

    public function contenido()
    {
        return $this->hasMany(InmobiliariaCatalogosPaqueteContenido::class, 'paquete_id');
    }
}