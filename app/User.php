<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'username',
        'email',
        'phone_number',
        'date_of_birth',
        'place_of_birth',
        'school_attended',
        'year_completed',
        'pin',
        'qualification',
        'document',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function diagnoses(){
        return $this->hasMany('App\Diagnoses');
    }
    public function registration(){
        return $this->hasMany('App\Registration');
    }
    public function vitals(){
        return $this->hasMany('App\Vital');
    }

}
