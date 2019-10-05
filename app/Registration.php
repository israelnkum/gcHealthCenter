<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    public function patient(){
        return $this->belongsTo('App\Patient');
    }

    public function vital(){
        return $this->hasMany('App\Vital');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function consultation(){
        return $this->hasMany('App\Consultation');
    }
    public function medications(){
        return $this->hasMany('App\Medication');
    }

    public function diagnosis(){
        return $this->hasMany('App\PatientDiagnosis');
    }

}
