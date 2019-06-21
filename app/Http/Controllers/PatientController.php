<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Charge;
use App\Consultation;
use App\Insurance;
use App\Patient;
use App\Registration;
use App\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
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
        $data="none";
        $insuranceType = Insurance::all();

        $charges = Charge::all();
        return view('pages.patients.index')
            ->with('data',$data)
            ->with('insuranceType',$insuranceType)
            ->with('charges',$charges);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data=Patient::all();
        $insuranceType = Insurance::all();

        $charges = Charge::all();
        return view('pages.patients.patients')
            ->with('data',$data)
            ->with('insuranceType',$insuranceType)
            ->with('charges',$charges);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'folder_number' => ['unique:patients'],
            'registration_number' => ['unique:patients'],
        ]);
        $patient = new Patient();

        $countLogs = Patient::get()->count();
        if ($countLogs == 0){
            $folderNumber=  $patient->registration_number = substr(date('Y'),'2').'01';
        }else{
            $record = Patient::latest()->first();

            $expNum =$record->registration_number;
            //return $expNum;
            if ($expNum == '' ){
                $folderNumber=  $patient->registration_number = substr(date('Y'),'2').'1';
            } else {
                if (substr($expNum,0,2) == substr(date('Y'),2)){
                    $folderNumber=  $patient->registration_number =  $expNum+1;
                }else{
                    $folderNumber=  $patient->registration_number =  substr(date('Y'),'2').'01';
                }


//                return $folderNumber;//
            }
        }

        $patient->registration_number= $folderNumber;
        $patient->folder_number= "GC/".substr($folderNumber,0,2)."/".substr($folderNumber,2);
        $patient->title= $request->input('title');
        $patient->first_name= $request->input('first_name');
        $patient->last_name= $request->input('last_name');
        $patient->other_name= $request->input('other_name');
        $patient->date_of_birth= $request->input('date_of_birth');
        $patient->gender= $request->input('gender');
        $patient->age= Carbon::parse(str_replace('/','-',$request->input('date_of_birth')))->age;
        $patient->marital_status= $request->input('marital_status');
        $patient->other_information= $request->input('other_information');
        $patient->postal_address= $request->input('postal_address');
        $patient->house_number= $request->input('house_number');
        $patient->locality= $request->input('locality');
        $patient->phone_number= $request->input('phone_number');
        $patient->occupation= $request->input('occupation');
        $patient->religion= $request->input('religion');
        $patient->name_of_nearest_relative= $request->input('name_of_relative');
        $patient->number_of_nearest_relative= $request->input('relative_phone_number');
        $patient->user_id=Auth::user()->id;


        //  $data = Patient::where('id',$patient->id)->get();
        //check if the incoming request has register patient
        if (\Request::has('register_patient')){
            //get the charge information selected
            $charges = Charge::find($request->input('charges'));

            //check if incoming request has insured
            if (\Request::has('insured')){
                //check if insured is selected as charge option in the charge select box
                if ($charges->name != "Insured") {
                    return back()->with('error','Please Select Insured as charge option');
                }else{
                    $patient->save();
                    //create new registration
                    $register = new Registration();
                    $register->patient_id = $patient->id;
                    $register->isInsured = 1;
                    $register->insurance_type = substr($request->input('insurance_type'), 0, strpos($request->input('insurance_type'), ','));
                    $register->insurance_number = strtoupper($request->input('insurance_number'));
                    $register->insurance_amount = str_replace(',', '', substr($request->input('insurance_type'), strpos($request->input('insurance_type'), ',')));
                    $register->registration_fee = $charges->amount;
                    if (\Request::has('old_patient')) {
                        $register->old_patient =1;
                        $register->last_visit = $request->input('last_visit');
                    }
                    $register->user_id = Auth::user()->id;
                    if ($register->save()){
                        /*
                        *if registration is saved  then create vitals, consultation, patient_diagnosis
                        *and medication options
                        */

                        $vital = new Vital();
                        $vital->patient_id =$patient->id;
                        $vital->registration_id =$register->id;
                        $vital->save();

                        $consultation = new Consultation();
                        $consultation->patient_id =$patient->id;
                        $consultation->registration_id =$register->id;
                        $consultation->save();

                        //check if patient is old patient
                        if (!\Request::has('old_patient')) {
                            $bill = new Bill();
                            $bill->registration_id = $register->id;
                            $bill->patient_id = $patient->id;
                            $bill->item = "Registration (Insured)";
                            $bill->amount = $charges->amount;
                            $bill->insurance_amount = $charges->amount;
                            $bill->total_amount_to_pay = $charges->amount;
                            $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
                            $bill->save();
                        }
                    }
                }
            }else{
                if ($charges->name == "Insured"){
                    $this->validate($request, [
                        'insurance_type' => 'required',
                        'insurance_number' => 'required',
                    ]);
                }
//                return $charges;
                $patient->save();
                $register = new Registration();
                $register->patient_id = $patient->id;
                $register->isInsured = 0;
                $register->registration_fee = $charges->amount;
                if (\Request::has('old_patient')) {
                    $register->old_patient =1;
                    $register->last_visit = $request->input('last_visit');
                }
                $register->user_id=Auth::user()->id;
                if ($register->save()) {
                    /*
                    *if registration is saved  then create vitals, consultation, patient_diagnosis
                    *and medication options
                    */

                    $vital = new Vital();
                    $vital->patient_id = $patient->id;
                    $vital->registration_id = $register->id;
                    $vital->save();

                    $consultation = new Consultation();
                    $consultation->patient_id = $patient->id;
                    $consultation->registration_id = $register->id;
                    $consultation->save();


                    if (\Request::has('old_patient')) {
                        $bill = new Bill();
                        $bill->registration_id = $register->id;
                        $bill->patient_id = $patient->id;
                        $bill->item = "Registration (Non-Insured)";
                        $bill->amount = 5;
                        $bill->insurance_amount = 0;
                        $bill->total_amount_to_pay = 5;
                        $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;

                        $bill->save();
                    }else{
                        $bill = new Bill();
                        $bill->registration_id = $register->id;
                        $bill->patient_id = $patient->id;
                        $bill->item = "Registration (Non-Insured)";
                        $bill->amount = $charges->amount;
                        $bill->insurance_amount = 0;
                        $bill->total_amount_to_pay = $charges->amount;
                        $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;

                        $bill->save();
                    }
                }

            }
        }


        return redirect()->route('patients.show',[$patient->id])
            ->with('success','New Patient Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Patient::with('registration')
            ->where('id',$id)
            ->get();
//      return $data;

        $insuranceType = Insurance::all();
        $charges = Charge::all();
        return view('pages.patients.index')
            ->with('data',$data)
            ->with('insuranceType',$insuranceType)
            ->with('charges',$charges);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $patient = Patient::find($id);

        return view('pages.patients.edit')
            ->with('patient',$patient);
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
        $patient = Patient::find($id);
        $patient->title= $request->input('title');
        $patient->first_name= $request->input('first_name');
        $patient->last_name= $request->input('last_name');
        $patient->other_name= $request->input('other_name');
        $patient->date_of_birth= $request->input('date_of_birth');
        $patient->gender= $request->input('gender');
        $patient->age= Carbon::parse(str_replace('/','-',$request->input('date_of_birth')))->age;
        $patient->marital_status= $request->input('marital_status');
        $patient->other_information= $request->input('other_information');
        $patient->postal_address= $request->input('postal_address');
        $patient->house_number= $request->input('house_number');
        $patient->locality= $request->input('locality');
        $patient->phone_number= $request->input('phone_number');
        $patient->occupation= $request->input('occupation');
        $patient->religion= $request->input('religion');
        $patient->name_of_nearest_relative= $request->input('name_of_relative');
        $patient->number_of_nearest_relative= $request->input('relative_phone_number');
        $patient->user_id=Auth::user()->id;
        $patient->save();

        return redirect()->route('patients.show',[$id])
            ->with('success','Patient\'s Information Updated');
    }




    public function searchPatient(Request $request){
        $data=Patient::where('folder_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('phone_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('last_name', 'like', '%' . $request->input("search") . '%')
            ->get();

        $insuranceType = Insurance::all();
        $charges = Charge::all();
        return view('pages.patients.index')
            ->with('data',$data)
            ->with('charges',$charges)
            ->with('insuranceType',$insuranceType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $patient = Patient::find($id);

        $patient->status =1;
        $patient->save();

        return redirect()->route('patients.show',[$id]);
    }
}
