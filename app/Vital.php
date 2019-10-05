<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
    public function patient(){
        return $this->belongsTo('App\Patient');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function registration(){
        return $this->belongsTo('App\Registration');
    }
}
