<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductosRotacionesVenta extends Model
{
    public function buscarRotacionTotal()
    {
        $query = $this->select(
            '*'
        );

        return $query->orderBy('id', 'DESC')
            ->get();
    }
}