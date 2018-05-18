<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'acl_rol';

    protected $fillable = [
        'id',
        'rol',
        'estatus'
    ];
}
