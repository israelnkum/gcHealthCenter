<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Charge;
use App\Diagnose;
use App\Drug;
use App\DrugArrears;
use App\Medication;
use App\OtherMedication;
use App\Patient;
use App\Payment;
use App\Registration;
use App\Review;
use App\Service;
use App\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
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
        $getVitals ="";
        $registration = Registration::with('patient')
            ->where('vitals',1)
            ->where('review',0)
            ->where('type','Review')
            ->whereDate('created_at', Carbon::today())
            ->limit(1)
//            ->orderBy('created_at','asc')
            ->get();

        $last_visit =[];
        if (count($registration) != 0){
            $getVitals = Vital::where('patient_id',$registration[0]->patient_id)
                ->whereDate('created_at', Carbon::today())->get();
            $last_visit = Registration::with('consultation','medications.drugs','diagnosis.diagnoses')->where('patient_id',$registration[0]->patient_id)->get();

        }

        $diagnosis = Diagnose::all();
        $drugs = Drug::where('qty_in_stock','>',0)
            ->where('retail_price','>',0)->get();
        $charges = Charge::all();

        return view('pages.reviews.review')
            ->with('registration',$registration)
            ->with('getVitals',$getVitals)
            ->with('diagnosis',$diagnosis)
            ->with('drugs',$drugs)
            ->with('charges',$charges)
            ->with('last_visit',$last_visit);
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
        DB::beginTransaction();
        try{
            //        return $request;

            $registration = Registration::find($request->input('registration_id'));

            $count_registration = Registration::where('patient_id',$registration->patient_id)->get()->count();

            /*
                    * Start Calculating Consultation fee
                    */
            $service_charge = Charge::where('name','Consultation')->first();

            //charge for consultation if only the person is not insured
            if ($registration->isInsured != 1){ //if patient is not insured
                /*
                 * check if patient is an old patient that is before the system
                 * then use the last visit
                 */

                if ($count_registration == 1){ //if patient just registered, then registration count will be 1

                    $patient_info = Patient::find($request->input('patient_id')); //get patient info and check if an old patient
                    if ($patient_info->old_patient == 1){//if patient had records before the system
                        //$getAllRegistrations =Registration::where('patient_id',$request->input('patient_id'))->get();

                        /*             if (count($getAllRegistrations) == 1){

                                     }elseif (count($getAllRegistrations)>1){
                                         $totalRegistrations = sizeof($getAllRegistrations);
                                         $getLastRegistration = $totalRegistrations -2;


                                         $last_visit = \Carbon\Carbon::createFromFormat('Y-m-d', substr($getAllRegistrations[$getLastRegistration]->created_at,0,10));


                                         $today = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                                         $noOfDays = $today->diffInDays($last_visit);
                                     }*/
                        $last_visit = \Carbon\Carbon::createFromFormat('Y-m-d', $patient_info->last_visit);

                        $today = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                        $noOfDays = $today->diffInDays($last_visit);

                        if ($noOfDays>=15){ //if difference between today and patient's last visit is > 15

                            $bill = new Bill(); // create a new bill
                            $bill->registration_id = $request->input('registration_id');
                            $bill->patient_id =$request->input('patient_id');
                            $bill->item = $service_charge->name;
                            $bill->item_id = $service_charge->id;
                            $bill->amount =$service_charge->amount;
                            $bill->type="Service";
                            $bill->insurance_amount =0;
                            $bill->total_amount_to_pay=$service_charge->amount;
                            $bill->billed_by =Auth::user()->first_name." ".Auth::user()->last_name;
                            $bill->save();
                        }
                    }
                    else{
                        /*
                         * if patient is new to the system
                         */
                        //get all patient registration


                        $bill = new Bill();
                        $bill->registration_id = $request->input('registration_id');
                        $bill->patient_id =$request->input('patient_id');
                        $bill->item = $service_charge->name;
                        $bill->item_id = $service_charge->id;
                        $bill->amount =$service_charge->amount;
                        $bill->type="Service";
                        $bill->insurance_amount =0;
                        $bill->total_amount_to_pay=$service_charge->amount;
                        $bill->billed_by =Auth::user()->first_name." ".Auth::user()->last_name;
                        $bill->save();
                    }
                }else{
                    $getAllRegistrations =Registration::where('patient_id',$request->input('patient_id'))->get();


//                return $getAllRegistrations;
                    /*   if (count($getAllRegistrations) == 1){
                           $last_visit = \Carbon\Carbon::createFromFormat('Y-m-d', substr($getAllRegistrations[0]->created_at,0,10));
                           $today = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                           $noOfDays = $today->diffInDays($last_visit);
                       }elseif (count($getAllRegistrations)>1){

                       }*/

                    $totalRegistrations = count($getAllRegistrations);
                    $getLastRegistration = $totalRegistrations -2;
                    $last_visit = \Carbon\Carbon::createFromFormat('Y-m-d', substr($getAllRegistrations[$getLastRegistration]->created_at,0,10));
                    $today = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                    $noOfDays = $today->diffInDays($last_visit);

                    if ($noOfDays>=15){ //if difference between today and patient's last visit is > 15
                        $bill = new Bill(); // create a new bill
                        $bill->registration_id = $request->input('registration_id');
                        $bill->patient_id =$request->input('patient_id');
                        $bill->item = $service_charge->name;
                        $bill->item_id = $service_charge->id;
                        $bill->amount =$service_charge->amount;
                        $bill->type="Service";
                        $bill->insurance_amount =0;
                        $bill->total_amount_to_pay=$service_charge->amount;
                        $bill->billed_by =Auth::user()->first_name." ".Auth::user()->last_name;
                        $bill->save();
                    }
                }
            }

            /*
             * End Consultation Calculation
             */

            $review = Review::where('registration_id',$registration->id)
                ->where('patient_id',$registration->patient_id)->first();
            $review->comments = $request->input('comments');
//        $review->type ="Review";
            $review->save();


            //Add medications
            if (\Request::has('medications')){
                foreach ($request->input('medications') as $med) {
                    if (!empty($med['drug_id']) && !empty($med['dosage'])) {

                        //check if medication already exist
                        $check = Medication::where('drugs_id', $med['drug_id'])
                            ->where('patient_id', $request->input('patient_id'))
                            ->where('registration_id', $request->input('registration_id'))
                            ->whereDate('created_at', date('Y-m-d H:m:i'))
                            ->first();
                        if (empty($check)) {
                            $medication = new Medication();
                            $medication->patient_id = $request->input('patient_id');
                            $medication->registration_id = $request->input('registration_id');
                            $medication->drugs_id = $med['drug_id'];
                            $medication->dosage = $med['dosage'];
//                        $medication->days = $med['days'];
//                        $medication->qty =$med['qty'];
                            $medication->qty_dispensed =0;
                            $medication->type ="Review";
                            $medication->user_id = Auth::user()->id;
                            $medication->save();
                        }
                    }
                }
            }

            //Add other  medications
            if (\Request::has('other_medications')) {
                foreach ($request->input('other_medications') as $other) {
                    if ($other['other_medication'] != "" && $other['other_dosage'] != "") {
                        $medication = new OtherMedication();
                        $medication->patient_id = $request->input('patient_id');
                        $medication->registration_id = $request->input('registration_id');
                        $medication->drug = $other['other_medication'];
                        $medication->dosage = $other['other_dosage'];
                        $medication->type="Review";
                        $medication->user_id = Auth::user()->id;
                        $medication->save();
                    }
                }
            }

            if (\Request::has('service')) {
                //insert selected service charge
                foreach ($request->input('service') as $key) {
                    $data = explode(',', $key);
                    $check = Service::where('charge_id', $data[0])
                        ->where('patient_id', $request->input('patient_id'))
                        ->where('registration_id', $request->input('registration_id'))
                        ->whereDate('created_at', date('Y-m-d'))
                        ->first();
                    if (empty($check)) {
                        $service = new Service();
                        $service->patient_id = $request->input('patient_id');
                        $service->registration_id = $request->input('registration_id');
                        $service->charge_id = $data[0];
                        $service->type="Review";
                        $service->user_id = Auth::user()->id;
                        $service->save();


                        $service_charge = Charge::where('name', $data[1])->first();

                        //create a bill for selected service charges
                        $bill = new Bill();
                        $bill->registration_id = $request->input('registration_id');
                        $bill->patient_id = $request->input('patient_id');
                        $bill->item = $service_charge->name;
                        $bill->item_id = $service_charge->id;
                        $bill->amount = $service_charge->amount;
                        $bill->type = "Service";
                        $bill->insurance_amount = 0;
                        $bill->total_amount_to_pay = $service_charge->amount;
                        $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
                        $bill->save();
                    }
                }
            }

            /*
             * if the patient is here for review then add as a new medication
             */
            $registration->review =1;
            $registration->consult =2;
            $registration->save();

            DB::commit();
            toastr()->success('Review Successful');
            return redirect()->route('review.index');
        }catch (\Exception $exception){
            DB::rollBack();
            toastr()->warning('Something went wrong! Try Again');
            return back();
        }

    }


    public function search_review(Request $request){
        $searchPatient= Patient::where('folder_number','like', '%' . $request->input("search") . '%')
            ->orWhere('phone_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('last_name', 'like', '%' . $request->input("search") . '%')->get();


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
