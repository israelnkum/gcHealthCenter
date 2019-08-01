<?php

namespace App\Http\Controllers;


use App\Drug;
use App\Patient;
use App\User;
use Illuminate\Support\Facades\Auth;

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

        if (Auth::user()->updated == 0) {

            return view('change-password');
        } else {
//        $lastVisit=\Carbon\Carbon::createFromTimeStamp(strtotime(@\Auth::user()->updated_at))->diffForHumans();
            $totalPatient = Patient::all()->where('status', 0)->count();
            $totalDetained = Patient::all()->where('detain', 1)->count();
            $totalDrugs = Drug::all()->where('status', 0)->count();
            $totalStaff = User::where('role', '!=', 'Super Admin')->count();


            //return $totalStaff;
            return view('home')
                ->with('totalPatient', $totalPatient)
                ->with('totalStaff', $totalStaff)
                ->with('totalDrugs', $totalDrugs)
                ->with('totalDetained', $totalDetained);
        }
    }
}
