<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DrugType extends Model
{
    public function drug(){
        return $this->hasMany('App\Drug');
    }
}
