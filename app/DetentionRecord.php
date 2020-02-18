<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetentionRecord extends Model
{
    public function service(){
        return $this->hasMany('App\Service');
    }

    public function registration(){
        return $this->belongsTo('App\Registration');
    }
}
