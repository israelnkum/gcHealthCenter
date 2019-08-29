<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    public function patient(){
        return $this->belongsTo('App\Patient');
    }

    public function drugs(){
        return $this->belongsTo('App\Drug');
    }

    public function bill(){
        return $this->belongsTo('App\Bill');
    }

    public function service_and_drugs(){
        return $this->belongsTo('App\ServiceAndDrugs');
    }

    public function registration(){
        return $this->belongsTo('App\Registration');
    }
}
