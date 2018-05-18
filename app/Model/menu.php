<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class menu extends Model
{
    //
    protected $table = "acl_menu";

    protected $fillable = [
        'menu',
        'icono',
        'tipo',
        'menu_id'
    ];

    public function buscar($datos)
    {
        $query = $this->leftJoin('acl_menu as sub', 'sub.id', '=', 'acl_menu.menu_id');

        $query->select(
            'acl_menu.id',
            'acl_menu.menu',
            'acl_menu.icono',
            'acl_menu.tipo',
            'acl_menu.menu_id',
            'sub.menu as submenu'
        );

        if(!empty($datos['menu'])){
            $query->where('menu', 'like', '%'.$datos['menu'].'%');
        }
        if(!empty($datos['tipo'])){
            $query->where('acl_menu.tipo', $datos['tipo']);
        }

        return $query->get();

    }

}