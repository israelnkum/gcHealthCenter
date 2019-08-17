<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Patient;
use App\Payment;
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
        $vital->type = "Consultation";
        $vital->user_id= Auth::user()->id;
        $vital->save();

        $registration = Registration::find($request->input('registration_id'));
        $registration->vitals = 1;
        $registration->save();

        toastr()->success('Vitals Added Successfully');
        return redirect()->route('vitals.index');
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
            toastr()->error('Please register patient before checking vitals');
            return  redirect()->route('patients.show',$registered->id);
        }else{
//            return $registered;
            return view('pages.vitals.edit')
                ->with('registration',$registration);
        }
    }



    public function searchRegistrationForVitals(Request $request){

        /*$search_patient= Patient::with('registration','vitals')
            ->where('folder_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('last_name', 'like', '%' . $request->input("search") . '%')
            ->orWhere('phone_number', 'like', '%' . $request->input("search") . '%')
            ->get();*/
        $search_patient= Patient::where('folder_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('last_name', 'like', '%' . $request->input("search") . '%')
            ->orWhere('phone_number', 'like', '%' . $request->input("search") . '%')
            ->get();

//        return $search_patient;
        if (count($search_patient) >1){
            return view('pages.vitals.edit')
                ->with('search_patient',$search_patient);
        }elseif(count($search_patient) == 0){
            $search_patient = "Nothing Found";
            return view('pages.vitals.index')
                ->with('search_patient',$search_patient);
        }else{

            $vitals = Vital::where('patient_id',$search_patient[0]->id)
                ->latest()->first();

//            return $vitals;
            $allPatientVitals = Vital::where('patient_id',$search_patient[0]->id)->get();
            return view('pages.vitals.edit')
                ->with('vitals',$vitals)
                ->with('search_patient',$search_patient)
                ->with('allPatientVitals',$allPatientVitals);
            /* foreach ($search_patient as $registered){

             }

             if (count($registered->registration) == 0){
                 toastr()->error('Please register patient before checking vitals');
                 return  redirect()->route('patients.show',$registered->id);
             }else{

                 return view('pages.vitals.edit')
                     ->with('search_patient',$search_patient);
             }*/
        }

    }

    public function vitalByDate(Request $request){


        $search_patient= Patient::where('folder_number', 'like', '%' . $request->input("folder_number") . '%')
            ->get();

        $vitals = Vital::where('patient_id',$search_patient[0]->id)
            ->where('created_at',$request->input('vital_date'))
            ->latest()->first();


//        return $vitals;
        $allPatientVitals = Vital::where('patient_id',$search_patient[0]->id)->get();
        return view('pages.vitals.edit')
            ->with('vitals',$vitals)
            ->with('search_patient',$search_patient)
            ->with('allPatientVitals',$allPatientVitals);
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
        //check if patient doesn't need rdt(malaria) test
        if (empty($request->input('rdt'))){
            $vital = Vital::where('registration_id',$request->input('registration_id'))
                ->where('patient_id',$request->input('patient_id'))->first();
            $vital->blood_pressure = $request->input('systolic')."/".$request->input('diastolic');
            $vital->weight = $request->input('weight');
            $vital->temperature = $request->input('temperature');
            $vital->pulse = $request->input('pulse');
            $vital->RDT = $request->input('rdt');
            $vital->glucose = $request->input('glucose');
            $vital->type = "Consultation";
            $vital->user_id=Auth::user()->id;
            $vital->save();

            $registration = Registration::find($request->input('registration_id'));
            $registration->vitals = 1;
            $registration->save();
            toastr()->success('Vitals Updated Successfully');
            return redirect()->route('vitals.index');
        }else{
            $registration = Registration::find($request->input('registration_id'));



            $vital = Vital::where('registration_id',$request->input('registration_id'))
                ->where('patient_id',$request->input('patient_id'))->first();
            if ($vital->RDT == ""){
                //add a new bill for rdt (malaria Test)
                $bill = new Bill();
                $bill->registration_id = $request->input('registration_id');
                $bill->patient_id =$registration->patient_id;
                $bill->item = "Malaria Test";
                $bill->amount =10;
                $bill->insurance_amount =0;
                $bill->total_amount_to_pay=10;
                $bill->billed_by =Auth::user()->first_name." ".Auth::user()->last_name;

                $bill->save();
            }

            $vital->blood_pressure = $request->input('systolic')."/".$request->input('diastolic');
            $vital->weight = $request->input('weight');
            $vital->temperature = $request->input('temperature');
            $vital->pulse = $request->input('pulse');
            $vital->RDT = $request->input('rdt');
            $vital->glucose = $request->input('glucose');
            $vital->type = "Consultation";
            $vital->user_id=Auth::user()->id;
            $vital->save();


            $registration->vitals = 1;
            $registration->save();

            toastr()->success('Vitals Updated');
            return back();
        }
    }


    //if patient is detained, you can check vitals
    public function addVital(Request $request){
        if ($request->has('rdt')){
            $check_bill = Bill::where('patient_id',$request->input('patient_id'))
                ->where('registration_id',$request->input('registration_id'))
                ->where('item','Malaria Test')->first();
            if (empty($check_bill)) {
                $bill = new Bill();
                $bill->registration_id = $request->input('registration_id');
                $bill->patient_id = $registration->patient_id;
                $bill->item = "Malaria Test";
                $bill->amount = 10;
                $bill->insurance_amount = 0;
                $bill->total_amount_to_pay = 10;
                $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
                $bill->save();
            }else{
                $check_bill->amount=$check_bill->amount+10;
                $check_bill->total_amount_to_pay=$check_bill->total_amount_to_pay+10;
                $check_bill->save();
            }
            $payment = Payment::where('registration_id',$request->input('registration_id'))
                ->where('patient_id',$request->input('patient_id'))
                ->first();

            $payment->grand_total = $payment->grand_total+10;
            $payment->arrears = str_replace('-','',$payment->arrears)+10;
            $payment->save();
        }

        $vital = new Vital();
        $vital->registration_id = $request->input('registration_id');
        $vital->patient_id = $request->input('patient_id');
        $vital->blood_pressure = $request->input('systolic')."/".$request->input('diastolic');
        $vital->weight = $request->input('weight');
        $vital->temperature = $request->input('temperature');
        $vital->pulse = $request->input('pulse');
        $vital->RDT = $request->input('rdt');
        $vital->glucose = $request->input('glucose');
        $vital->type= "Detention";
        $vital->user_id=Auth::user()->id;
        $vital->save();

        toastr()->success('Vitals Updated');
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
