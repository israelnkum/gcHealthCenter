<?php

namespace App\Http\Controllers;

use App\Consultation;
use App\Diagnose;
use App\Patient;
use App\Registration;
use App\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $registration = Registration::with('patient')
            ->where('vitals',1)
            ->whereDate('created_at', Carbon::today())
            ->limit(1)
            ->orderBy('created_at','asc')
            ->get();

//       return count($registration);
        if (count($registration) == 1){
            $getVitals = Vital::where('patient_id',$registration[0]->patient_id)->get();
        }else{
            $getVitals =[];
        }

        $diagnosis = Diagnose::all();


        return view('pages.consultations.index')
            ->with('registration',$registration)
            ->with('getVitals',$getVitals)
            ->with('diagnosis',$diagnosis);
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


        $consultation = new Consultation();
        $consultation ->complains=$request->input('complains');
        $consultation ->finding=$request->input('finding');
        $consultation ->physical_examination=$request->input('physical_examination');
        $consultation ->diagnosis=$request->input('treatment_medication');
        $consultation ->treatment_medication=$request->input('treatment_medication');
        $consultation ->detain_admit=$request->input('detain_admit');
        $consultation ->labs=$request->input('labs');
        $consultation ->ultra_sound_scan=$request->input('ultra_sound_scan');
        $consultation ->user_id=Auth::user()->id;

        $consultation->save();


        $registration = Registration::find($request->input('registration_id'));
        $registration->vitals = 1;
        $registration->save();

        return redirect()->route('consultations.index')
            ->with('success','Consulting Success');
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
        //
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
        //
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
