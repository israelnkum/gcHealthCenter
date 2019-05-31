<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    public function drug(){
        return $this->hasMany('App\Drug');
    }
}
