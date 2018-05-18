<?php

namespace App;

use App\Model\InmobiliariaCatalogosCliente;
use App\Model\InmobiliariaCatalogosEmpresas;
use App\Model\InmobiliariaCatalogosVendedores;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_empleado', 'id_rol', 'rol_id', 'nombre', 'usuario', 'password', 'id_sucursal', 'cambio_contrasena'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function buscarUsuario($datos)
    {
        $query = $this->leftJoin("rh_empleados as e", "e.id_empleado", "=", "usuarios.id_empleado");
        $query->leftJoin("rh_empleados_huellas as h", "e.id_empleado", "=", "h.id_empleado");
        $query->leftJoin("rh_foto_empleado as f", "f.id_empleado", "=", "e.id_empleado");
        $query->leftJoin("rh_puestos_sucursales as ps", "ps.id_puesto_sucursal", "=", "e.id_puesto_sucursal");
        $query->leftJoin("rh_puestos as p", "ps.id_puesto", "=", "p.id_puesto");
        $query->leftJoin("configuraciones_generales as cg", function ($join){
            $join->where("cg.id", "=", 1);
        });

        $select = [
            "usuarios.*", "e.id_sucursal as id_sucursal",
            "e.huella", "e.estatus as estatus_empleado", "e.email",
            "h.huella as huella_empleado",
            "f.id_foto_empleado", "p.id_puesto", "p.puesto", "p.departamento",
            \DB::raw('if(concat(YEAR(CURDATE()),DATE_FORMAT(fecha_nacimiento,\'-%m-%d\')) = CURDATE(),"SI","NO") as cumple'),
            \DB::raw('obtenerCajaNombre(usuarios.id_caja) as cajaNombre'),
            "cg.tipo_trabajo"
        ];


        $query->select(
            $select
        );

        if (isset($datos["usuario"]) && isset($datos['password'])) {
            //dd(md5($datos["password"]));
            $query->where("usuarios.usuario", "=", $datos["usuario"]);
            $query->where("usuarios.password", "=", md5($datos["password"]));
            $query->where("usuarios.activo", "=", "Si");

            return $query->first();
        }

    }

    public function buscar($datos)
    {
        $query = $this->leftJoin('acl_rol as rol', 'rol.id', '=', 'usuarios.id_rol');
        $query->leftJoin('acl_rol as rol_n', 'rol_n.id', '=', 'usuarios.rol_id');
        $query->leftJoin('rh_empleados as e', 'e.id_empleado', '=', 'usuarios.id_empleado');

        $query->select(
            'usuarios.*',
            'rol.rol',
            'rol_n.rol as rol_nuevo',
            'e.email',
            'e.*',

            \DB::raw('CONCAT(e.nombre, " ", e.apaterno, " ", e.amaterno) as nombre_empleado'),
            \DB::raw('fechaCheque(curdate()) as fecha')
        )->where('activo', 'Si')->where("rol_id", "<>", "");

        if (!empty($datos['nombre'])) {
            $query->where(\DB::raw('CONCAT(e.nombre, " ", e.apaterno, " ", e.amaterno)'), 'LIKE', '%' . $datos['nombre'] . '%');
        }
        if (!empty($datos['id'])) {
            $query->where('usuarios.id_usuario', $datos['id']);
            return $query->first();
        }
        if (!empty($datos['id_sucursal'])) {
            $query->where("id_sucursal", $datos['id_sucursal']);
        }
        if (!empty($datos['activo'])) {
            $query->where("activo", $datos['activo']);
        }

        //dd($query->toSql());

        return $query->get();
    }

    public function buscar_catalogo($datos)
    {
        $query = $this->leftJoin('acl_roles as rol', 'rol.id_rol', '=', 'usuarios.id_rol');
        $query->leftJoin('rh_empleados as e', 'e.id_empleado', '=', 'usuarios.id_empleado');
        $query->leftJoin('acl_rol as rol_n', 'rol_n.id', '=', 'usuarios.rol_id');

        $query->select(
            'usuarios.*',
            \DB::raw('ifnull(rol_n.rol,"S/R") as rol_nuevo'),
            \DB::raw('ifnull(rol.rol,"S/R") as rol'),
            \DB::raw('CONCAT(e.nombre, " ", e.apaterno, " ", e.amaterno) as nombre_empleado'),
            \DB::raw('fechaCheque(curdate()) as fecha'));

        if (!empty($datos['nombre'])) {
            $query->where(\DB::raw('CONCAT(e.nombre, " ", e.apaterno, " ", e.amaterno)'), 'LIKE', '%' . $datos['nombre'] . '%');
        }
        if (!empty($datos['id'])) {
            $query->where('usuarios.id_usuario', $datos['id']);
            return $query->first();
        }
        if (!empty($datos['id_sucursal'])) {
            $query->where("usuarios.id_sucursal", $datos['id_sucursal']);
        }

        if (!empty($datos['rol_id'])) {
            $query->where("usuarios.rol_id", $datos['rol_id']);
        }

        if (!empty($datos['activo'])) {
            $query->where("activo", $datos['activo']);
        }

        //dd($query->toSql());

        return $query->get();
    }

    public function cliente()
    {
        return $this->hasOne(InmobiliariaCatalogosCliente::class, 'usuario_id', 'id_usuario');
    }

    public function empresa()
    {
        return $this->hasOne(InmobiliariaCatalogosEmpresas::class, 'usuario_id', 'id_usuario');
    }

    public function vendedor()
    {
        return $this->hasOne(InmobiliariaCatalogosVendedores::class, 'usuario_id', 'id_usuario');
    }

    public function buscarPDF($datos)
    {

        $query = $this->leftJoin('acl_roles as rol', 'rol.id_rol', '=', 'usuarios.id_rol');
        $query->leftJoin('acl_rol as rol_n', 'rol_n.id', '=', 'usuarios.rol_id');
        $query->leftJoin('rh_empleados as e', 'e.id_empleado', '=', 'usuarios.id_empleado');
        $query->leftJoin("informacion_formatos as i", function ($join){
            $join->where("i.id_informacion", "=", 1);
        });

        $query->select(
            'usuarios.*',
            \DB::raw('ifnull(rol_n.rol,"S/R") as rol_nuevo'),
            \DB::raw('ifnull(rol.rol,"S/R") as rol'),
            \DB::raw('CONCAT(e.nombre, " ", e.apaterno, " ", e.amaterno) as nombre_empleado'),
            \DB::raw('fechaCheque(curdate()) as fecha'),
            "i.nombre_empresa"
        );

        if (!empty($datos['nombre'])) {
            $query->where(\DB::raw('CONCAT(e.nombre, " ", e.apaterno, " ", e.amaterno)'), 'LIKE', '%' . $datos['nombre'] . '%');
        }
        if (!empty($datos['id_sucursal'])) {
            $query->where("id_sucursal", $datos['id_sucursal']);
        }
        if (!empty($datos['activo'])) {
            $query->where("activo", $datos['activo']);
        }
        if (!empty($datos['id'])) {
            $query->where('usuarios.id_usuario', $datos['id']);
            return $query->first();
        }

        //dd($query->toSql());

        return $query->get();

    }

    public function buscarTodos($datos)
    {

        $query = $this->leftJoin('acl_roles as rol', 'rol.id_rol', '=', 'usuarios.id_rol');
        $query->leftJoin('acl_rol as rol_n', 'rol_n.id', '=', 'usuarios.rol_id');
        $query->leftJoin('rh_empleados as e', 'e.id_empleado', '=', 'usuarios.id_empleado');

        $query->select(
            'usuarios.*',
            'rol.rol',
            'rol_n.rol as rol_nuevo',
            'e.email',
            \DB::raw('CONCAT(e.nombre, " ", e.apaterno, " ", e.amaterno) as nombre_empleado'),
            \DB::raw('fechaCheque(curdate()) as fecha')
        )->where('activo', 'Si');

        if (!empty($datos['nombre'])) {
            $query->where(\DB::raw('CONCAT(e.nombre, " ", e.apaterno, " ", e.amaterno)'), 'LIKE', '%' . $datos['nombre'] . '%');
        }
        if (!empty($datos['id'])) {
            $query->where('usuarios.id_usuario', $datos['id']);
            return $query->first();
        }
        if (!empty($datos['id_sucursal'])) {
            $query->where("id_sucursal", $datos['id_sucursal']);
        }
        if (!empty($datos['activo'])) {
            $query->where("activo", $datos['activo']);
        }

        //dd($query->toSql());

        return $query->get();

    }
    public function proyectos(){
        return $this->belongsToMany('App\proyecto','proyectos_observadores');
    }
    public function proyectosEquipos(){
        return $this->belongsToMany('App\proyecto','proyectos_equipos');
    }

    public function buscarCorreos ($datos)
    {

        \Log::debug($datos);

        $query = $this->from("usuarios as u");

        $query->select(
            "u.*",
            \DB::raw("obtenerCorreoUsuario(u.id_usuario) as correoUsuario")
        );

        $query->where("u.activo", "=", "si");

        if (!empty($datos["rol_id"])) {

            $query->where("u.rol_id", $datos["rol_id"]);

        }

        if (!empty($datos["id_sucursal"])) {

            $query->where("u.id_sucursal", $datos["id_sucursal"]);

        }

        if (!empty($datos["first"])) {

            return $query->first();

        }

        return $query->get();

    }

}