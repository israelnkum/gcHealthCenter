<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diagnose extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    public  function patient_diagnoses(){
        return $this->hasMany('App\PatientDiagnosis');
    }
}
