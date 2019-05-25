<?php

namespace App\Http\Controllers;

use App\Patient;
use App\Registration;
use App\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class VitalsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $registration = Registration::with('patient')
            ->whereDate('created_at', Carbon::today())
            ->where('vitals',0)->limit(5)
            ->orderBy('created_at','asc')->get();

//        return $registration;
        return view('pages.vitals.index')
            ->with('registration',$registration);
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

        $vital = new Vital();
        $vital->patient_id = $request->input('patient_id');
        $vital->blood_pressure = $request->input('systolic')."/".$request->input('diastolic');
        $vital->weight = $request->input('weight');
        $vital->temperature = $request->input('temperature');
        $vital->pulse = $request->input('pulse');
        $vital->RDT = $request->input('rdt');
        $vital->glucose = $request->input('glucose');
        $vital->user_id= Auth::user()->id;
        $vital->save();

        $registration = Registration::find($request->input('registration_id'));
        $registration->vitals = 1;
        $registration->save();

        return redirect()->route('vitals.index')->with('success','Vitals Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registration= Patient::with('registration')
            ->where('id',$id)
            ->get();

//        return $registration;
        foreach ($registration as $registered){

        }

        if (count($registered->registration) == 0){
            return  redirect()->route('patients.show',$registered->id)
                ->with('error','Please register patient before checking vitals');
        }else{
//            return $registered;
            return view('pages.vitals.edit')
                ->with('registration',$registration);
        }
    }



    public function searchRegistrationForVitals(Request $request){

        $registration= Patient::with('registration','vitals')
            ->where('folder_number', 'like', '%' . $request->input("search") . '%')
            ->get();

//        return $registration;
        if (count($registration) >1){
            return view('pages.vitals.edit')
                ->with('registration',$registration);
        }else{
            foreach ($registration as $registered){

            }

//            return $registered;
//
//            ->whereDate('created_at', Carbon::today())
            if (count($registered->registration) == 0){
                return  redirect()->route('patients.show',$registered->id)
                    ->with('error','Please register patient before checking vitals');
            }else{

//                return $registered->created_at;
                return view('pages.vitals.edit')
                    ->with('registration',$registration);
            }
        }

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
        $vital = Vital::find($id);
        $vital->patient_id = $request->input('patient_id');
        $vital->blood_pressure = $request->input('systolic')."/".$request->input('diastolic');
        $vital->weight = $request->input('weight');
        $vital->temperature = $request->input('temperature');
        $vital->pulse = $request->input('pulse');
        $vital->RDT = $request->input('rdt');
        $vital->glucose = $request->input('glucose');
        $vital->save();

        $registration = Registration::find($request->input('registration_id'));
        $registration->vitals = 1;
        $registration->save();

        return redirect()->route('vitals.index')->with('success','Vitals Updated Successfully');
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
