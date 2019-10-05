<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Charge;
use App\Consultation;
use App\Medication;
use App\PatientDiagnosis;
use App\Payment;
use App\Registration;
use App\Review;
use App\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OpdRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Registration::with('vital','consultation', 'medications.drugs', 'diagnosis.diagnoses')
                ->where('patient_id', $request->input('patient_id'))
                ->whereDate('created_at','!=',date('Y-m-d'))->get();
            echo json_encode($data);
        }

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
                    toastr()->error('Please Select Insured as charge option');
                    return back();
                }else{
                    DB::beginTransaction();
                    try{
                        //create new registration
                        $register = new Registration();
                        $register->patient_id = $request->input('patient_id');
                        $register->isInsured = 1;
                        $register->insurance_type = substr($request->input('insurance_type'), 0, strpos($request->input('insurance_type'), ','));
                        $register->insurance_number = strtoupper($request->input('insurance_number'));
                        $register->insurance_amount = str_replace(',', '', substr($request->input('insurance_type'), strpos($request->input('insurance_type'), ',')));
                        $register->registration_fee = $charges->amount;
                        $register->user_id = Auth::user()->id;

                        if (\Request::has('review')){
                            $register->consult=2;
                            $register->type="Review";
                        }
                        $register->save();

                        /*
                         *if registration is saved  then create vitals, consultation
                         */


                        /*
                         * Create a new vitals for insured
                         */

                        $vital = new Vital();
                        $vital->patient_id =$request->input('patient_id');
                        $vital->registration_id =$register->id;
                        $vital->save();

                        /*
                         * Create a new consultation for insured
                         */

                        if (\Request::has('review')){
                            $review = new Review();
                            $review->patient_id =$request->input('patient_id');
                            $review->registration_id =$register->id;
                            $review->user_id =Auth::user()->id;
                            $review->save();
                        }else{
                            $consultation = new Consultation();
                            $consultation->patient_id =$request->input('patient_id');
                            $consultation->registration_id =$register->id;
                            $consultation->save();
                        }


                        $payment = new Payment();

                        $payment->patient_id =$request->input('patient_id');
                        $payment->registration_id =$register->id;
                        $payment->drugs_total = 0;
                        $payment->service_total = 0;
                        $payment->grand_total = 0;
                        $payment->amount_paid = 0;
                        $payment->arrears = 0;
                        $payment->change = 0;
                        $payment->user_id = Auth::user()->id;

                        $payment->save();

                        /*
                         * Create a new bill for insured
                         */

                        /*$bill = new Bill();
                        $bill->registration_id = $register->id;
                        $bill->patient_id =$request->input('patient_id');
                        $bill->item = "Registration (Insured)";
                        $bill->amount =0;
                        $bill->insurance_amount =$charges->amount;
                        $bill->total_amount_to_pay=0;
                        $bill->billed_by =Auth::user()->first_name." ".Auth::user()->last_name;

                        $bill->save();*/
                        DB::commit();
                        toastr()->success('Registration Successful');
                        return redirect()->route('patients.show',[$request->input('patient_id')]);

                    }catch (\Exception $exception){
                        DB::rollback();
                        toastr()->warning('Something Went Wrong!, Try Again');
                        return back();
                    }
                }
            }else
            {
                if ($charges->name == "Insured"){
                    $this->validate($request, [
                        'insurance_type' => 'required',
                        'insurance_number' => 'required',
                    ]);
                }


                /*
                  *if registration is saved  then create vitals, consultation
                  */
                DB::beginTransaction();
                try{
                    /*
             * Create a new registration for non-insured
             */
                    $register = new Registration();
                    $register->patient_id = $request->input('patient_id');
                    $register->isInsured = 0;
                    $register->registration_fee = $charges->amount;
                    $register->user_id=Auth::user()->id;
                    if (\Request::has('review')){
                        $register->consult=2;
                        $register->type="Review";
                    }
                    $register->save();

                    /*
                     * Create a new vitals for non-insured
                     */
                    $vital = new Vital();
                    $vital->patient_id = $request->input('patient_id');
                    $vital->registration_id = $register->id;
                    $vital->save();


                    $payment = new Payment();

                    $payment->patient_id =$request->input('patient_id');
                    $payment->registration_id =$register->id;
                    $payment->drugs_total = 0;
                    $payment->service_total = 0;
                    $payment->grand_total = 0;
                    $payment->amount_paid = 0;
                    $payment->arrears = 0;
                    $payment->change = 0;
                    $payment->user_id = Auth::user()->id;

                    $payment->save();

                    /*
                     * Create a new consultation for non-insured
                     */
                    if (\Request::has('review')){
                        $review = new Review();
                        $review->patient_id =$request->input('patient_id');
                        $review->registration_id =$register->id;
                        $review->user_id =Auth::user()->id;
                        $review->save();
                    }else{
                        $consultation = new Consultation();
                        $consultation->patient_id =$request->input('patient_id');
                        $consultation->registration_id =$register->id;
                        $consultation->save();
                    }

                    /*
                     * Create a new bill for non-insured
                     */
                    $bill = new Bill();
                    $bill->registration_id = $register->id;
                    $bill->patient_id =$request->input('patient_id');
                    $bill->item = "Registration (Non-Insured)";
                    $bill->amount =5;
                    $bill->insurance_amount =0;
                    $bill->total_amount_to_pay=5;
                    $bill->billed_by =Auth::user()->first_name." ".Auth::user()->last_name;
                    $bill->save();

                    DB::commit();
                    toastr()->success('Registration Successful');
                    return redirect()->route('patients.show',[$request->input('patient_id')]);

                }catch (\Exception $exception){
                    DB::rollback();
                    toastr()->warning('Something Went Wrong!');
                    return back();
                }


            }
        }
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
