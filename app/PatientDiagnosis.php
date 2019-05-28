<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientDiagnosis extends Model
{
    public function patient(){
        return $this->belongsTo('App\Patient');
    }

    public  function diagnoses(){
        return $this->belongsTo('App\Diagnose');
    }
}
