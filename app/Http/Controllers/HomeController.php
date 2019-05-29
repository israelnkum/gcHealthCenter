<?php

namespace App\Http\Controllers;


use App\Patient;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $lastVisit=\Carbon\Carbon::createFromTimeStamp(strtotime(@\Auth::user()->updated_at))->diffForHumans();
        $totalPatient = Patient::all()->where('status',0)->count();
        $totalStaff = User::where('role','Doctor')
            ->orWhere('role','Nurse')
            ->orWhere('role','Admin')
            ->orWhere('role','Pharmacist')->count();

        //return $totalStaff;
        return view('home')
            ->with('totalPatient',$totalPatient)
            ->with('lastVisit',$lastVisit)
            ->with('totalStaff',$totalStaff);
    }
}
