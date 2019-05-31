<?php

namespace App\Http\Controllers;

use App\Charge;
use App\Consultation;
use App\Medication;
use App\PatientDiagnosis;
use App\Registration;
use App\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpdRegistrationController extends Controller
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
        //
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

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

                    //create new registration
                    $register = new Registration();
                    $register->patient_id = $request->input('patient_id');
                    $register->isInsured = 1;
                    $register->insurance_type = substr($request->input('insurance_type'), 0, strpos($request->input('insurance_type'), ','));
                    $register->insurance_number = strtoupper($request->input('insurance_number'));
                    $register->insurance_amount = str_replace(',', '', substr($request->input('insurance_type'), strpos($request->input('insurance_type'), ',')));
                    $register->registration_fee = $charges->amount;
                    $register->user_id = Auth::user()->id;
                    if ($register->save()){
                        /*
                        *if registration is saved  then create vitals, consultation, patient_diagnosis
                        *and medication options
                        */

                        $vital = new Vital();
                        $vital->patient_id =$request->input('patient_id');
                        $vital->registration_id =$register->id;
                        $vital->save();

                        $consultation = new Consultation();
                        $consultation->patient_id =$request->input('patient_id');
                        $consultation->registration_id =$register->id;
                        $consultation->save();

//                        $patientDiagnosis = new PatientDiagnosis();
//                        $patientDiagnosis->patient_id =$request->input('patient_id');
//                        $patientDiagnosis->registration_id =$register->id;
//                        $patientDiagnosis->save();

//                        $medication = new Medication();
//                        $medication->patient_id =$request->input('patient_id');
//                        $medication->registration_id =$register->id;
//                        $medication->save();

                    }
                }
            }else{
                if ($charges->name == "Insured"){
                    $this->validate($request, [
                        'insurance_type' => 'required',
                        'insurance_number' => 'required',
                    ]);
                }
                $register = new Registration();
                $register->patient_id = $request->input('patient_id');
                $register->isInsured = 0;
                $register->registration_fee = $charges->amount;
                $register->user_id=Auth::user()->id;
                if ($register->save()) {
                    /*
                    *if registration is saved  then create vitals, consultation, patient_diagnosis
                    *and medication options
                    */

                    $vital = new Vital();
                    $vital->patient_id = $request->input('patient_id');
                    $vital->registration_id = $register->id;
                    $vital->save();

                    $consultation = new Consultation();
                    $consultation->patient_id = $request->input('patient_id');
                    $consultation->registration_id = $register->id;
                    $consultation->save();

                    /*$patientDiagnosis = new PatientDiagnosis();
                    $patientDiagnosis->patient_id =$request->input('patient_id');
                    $patientDiagnosis->registration_id =$register->id;
                    $patientDiagnosis->save();

                    $medication = new Medication();
                    $medication->patient_id =$request->input('patient_id');
                    $medication->registration_id =$register->id;
                    $medication->save();*/

                }

            }
        }

        return redirect()->route('patients.show',[$request->input('patient_id')])
            ->with('success','Registration Successful');

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
