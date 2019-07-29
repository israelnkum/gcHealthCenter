<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function change_password(Request $request){
        $this->validate($request,[
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (Auth::User()->updated == 1){
            if (!Hash::check($request['old_password'],Auth::user()->password)){
                return back()->with('error','Old Password is incorrect');
            }elseif(Hash::check($request['password'],Auth::user()->password)){
                return back()->with('error','New Password is the same as current');
            }else{
                $user = User::find(Auth::User()->id);

                $user->password = Hash::make($request->input('password'));
                $user->updated = 1;

                $user->save();
                toastr()->success('Password Changed Successfully');
                return back();
            }
        }else{
            $user = User::find(Auth::User()->id);

            $user->password = Hash::make($request->input('password'));
            $user->updated = 1;

            if ($user->save()){
                toastr()->success('Password Changed Successfully');
                return redirect('/home');
            }
        }

    }


    public function password_change(){
        return view('change-password');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('pages.users.index')
            ->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->role = $request->input('role');
        $user->password = Hash::make('11111111');

        $user->save();

        toastr()->success('A new '.$user->role.' Added');
        return redirect('/users');
    }


    /*
     * Bulk delete level
     */
    public function  bulk_delete(Request $request){

        $selected_id = $request->input('selected_id');


        foreach ($selected_id as $value){
            $level = User::find($value);
            $level->delete();
        }

        toastr()->success('Deleted Successfully');
        return redirect('/users');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        return view('pages.users.profile')
            ->with('user',$user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->last_name = $request->input('last_name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->date_of_birth = $request->input('date_of_birth');
        $user->place_of_birth = $request->input('place_of_birth');
        $user->school_attended = $request->input('school_attended');
        $user->year_completed = $request->input('year_completed');
        $user->pin = $request->input('pin');
        $user->qualification = $request->input('qualification');
        $user->document = $request->input('document');

        $user->save();

        toastr()->success('Profile Updated');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
