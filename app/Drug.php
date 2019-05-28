<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    public function drug_type(){
        return $this->belongsTo('App\DrugType');
    }

    public function supplier(){
        return $this->belongsTo('App\Supplier');
    }

    public function medication(){
        return $this->hasMany('App\Medication');
    }
}
