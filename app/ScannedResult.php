<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScannedResult extends Model
{
    public function consultation(){
        return $this->belongsTo('App\Consultation');
    }
}
