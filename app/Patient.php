<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    public function registration(){
        return $this->hasMany('App\Registration');
    }

    public function vitals(){
        return $this->hasMany('App\Vital');
    }
}
