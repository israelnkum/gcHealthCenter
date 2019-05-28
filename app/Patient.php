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

    public function consultation(){
        return $this->hasMany('App\Consultation');
    }
    public function diagnosis(){
        return $this->hasMany('App\PatientDiagnosis');
    }

    public function medication(){
        return $this->hasMany('App\Medication');
    }

}
