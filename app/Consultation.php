<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    public function patient(){
        return $this->belongsTo('App\Patient');
    }
    public function registration(){
        return $this->belongsTo('App\Registration');
    }

    public function lab_results(){
        return $this->hasMany('App\LabResult');
    }

    public function scanned_results(){
        return $this->hasMany('App\ScannedResult');
    }
}
