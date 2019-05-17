<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
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
/*        $user = User::find(Auth::user()->id);
        $user->updated_at =date('Y-m-d H:i:s');
        $user->save();*/
        $lastVisit=\Carbon\Carbon::createFromTimeStamp(strtotime(@\Auth::user()->updated_at))->diffForHumans();

        return view('home')
            ->with('lastVisit',$lastVisit);
    }
}
