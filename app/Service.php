<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function charge(){
        return $this->belongsTo('App\Charge');
    }
}
