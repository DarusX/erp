<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AnimalEvento extends Model
{
    protected $table = 'agr_animal_evento';

    protected $fillable = [
        'id',
        'usuario_id'
    ];

    public function reporte($datos){

        $query = $this->leftJoin('usuarios as u', 'u.id_usuario', '=', 'agr_animal_evento.usuario_id');

        $query->select(
            'agr_animal_evento.*',
            'usuarios'
        );

        return $query->get();

    }
}
