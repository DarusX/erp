<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CausasMuertes extends Model
{

    protected $table = "agr_causa_muerte";

    protected $fillable = ["causa_muerte"];

    public function buscar($datos)
    {

        $query = $this->from("agr_causa_muerte as c");

        $query->select("c.*");

        return $query->get();

    }

}
