<?php

namespace App\Http\Controllers;


use App\Charts\Statistics;
use App\Drug;
use App\Patient;
use App\Registration;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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


//        $live_database = DB::connection('online-db');
//        // Get table data from production
//        foreach(DB::table('users')->get() as $data => $value){
//            // Save data to staging database - default db connection
//         return   $live_database->table('users')
//                ->whereIn('id', (array) $data)
//                ->get();
////            $live_database->table('users')->updateOrInsert((array) $data);
//        }
        $statistics = new Statistics();


        if (Auth::user()->updated == 0) {
            return view('change-password');
        } else {
//        $lastVisit=\Carbon\Carbon::createFromTimeStamp(strtotime(@\Auth::user()->updated_at))->diffForHumans();
            $totalPatient = Patient::all()->where('status', 0)->count();
            $totalDetained = Registration::all()->where('detain', 1)->count();
            $totalDrugs = Drug::all()->where('status', 0)->count();
            $totalStaff = User::where('role', '!=', 'Super Admin')->count();
            $totalMale = Patient::all()
                ->where('status', 0)
                ->where('gender', 'Male')->count();
            $totalFemale = Patient::all()
                ->where('status', 0)
                ->where('gender', 'Female')->count();

//            $statistics->dataset('Total Patient', 'column',[$totalPatient,$totalDetained,$totalMale,$totalFemale]);
            $statistics->dataset('Total Patient', 'column',[$totalPatient]);
            $statistics->dataset('Detained', 'column',[$totalDetained]);
            $statistics->dataset('Male', 'column',[$totalMale]);
            $statistics->dataset('Female', 'column',[$totalFemale]);
            $statistics->labels(['Patient']);

//            $statistics->displayAxes('y');
            //return $totalStaff;
            return view('home',compact('statistics'))
                ->with('totalPatient', $totalPatient)
                ->with('totalStaff', $totalStaff)
                ->with('totalDrugs', $totalDrugs)
                ->with('totalDetained', $totalDetained);
        }
    }
}
