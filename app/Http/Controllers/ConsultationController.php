<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Charge;
use App\Consultation;
use App\Diagnose;
use App\Drug;
use App\Medication;
use App\OtherMedication;
use App\Patient;
use App\PatientDiagnosis;
use App\Registration;
use App\Service;
use App\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
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
            ->where('vitals',1)
            ->where('consult',0)
            ->whereDate('created_at', Carbon::today())
            ->limit(1)
//            ->orderBy('created_at','asc')
            ->get();

        $allRegistrations=0;
        if (count($registration) == 1){
            $allRegistrations = Registration::where('patient_id',$registration[0]->patient->id)->get();


            // return $allRegistrations;
            $getVitals = Vital::where('patient_id',$registration[0]->patient_id)
                ->whereDate('created_at', Carbon::today())->get();
        }else{
            $getVitals =[];
        }

        $diagnosis = Diagnose::all();
        $drugs = Drug::where('qty_in_stock','>',0)
            ->where('retail_price','>',0)->get();
        $charges = Charge::all();

        return view('pages.consultations.index')
            ->with('registration',$registration)
            ->with('getVitals',$getVitals)
            ->with('diagnosis',$diagnosis)
            ->with('drugs',$drugs)
            ->with('allRegistrations',$allRegistrations)
            ->with('charges',$charges);
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
        /*foreach ($request->input('medications') as $med) {
            $qty = substr($med['dosage'],0,1)*$med['days'];
            $remainder = $qty % $med['days'];

            $quotient = ($qty - $remainder) / $med['days'];
        }

        return $quotient;*/
        $getRegistration = Consultation::where('registration_id',$request->input('registration_id'))
            ->latest()
            ->first();

        $registration = Registration::find($request->input('registration_id'));

//        return $registration;
        //Upload labs
        $labFileNames = [];
        $scanFileNames =[];
        if (\Request::has('labs')) {

            for ($i = 0; $i < count($request->file('labs')); $i++) {
                $file = $request->file('labs')[$i];
                $extension = $file->getClientOriginalExtension();
                $files = substr($file->getClientOriginalName(), 0, strpos($file->getClientOriginalName(), '.'));
                $fileName = $files . '_' . time() . '.' . $extension;

                $file->move('public/labs', $fileName);

                array_push($labFileNames,$fileName);
            }
        }


        //Upload Scans
        if (\Request::has('scan')){
            for ($i = 0; $i < count($request->file('scan')); $i++) {
                $scannedFile = $request->file('scan')[$i];
                $scannedExtension = $scannedFile->getClientOriginalExtension();
                $scannedFiles = substr($scannedFile->getClientOriginalName(), 0, strpos($scannedFile->getClientOriginalName(), '.'));
                $scannedFileName = $scannedFiles . '_' . time() . '.' . $scannedExtension;

                $scannedFile->move('public/scan', $scannedFileName);
                array_push($scanFileNames,$scannedFileName);
            }
        }


        $consultation = Consultation::find($getRegistration->id);
        $consultation ->patient_id =$request->input('patient_id');
        $consultation ->complains=$request->input('complains');
        $consultation ->findings=$request->input('findings');
        $consultation ->physical_examination=$request->input('physical_examination');
        $consultation ->other_diagnosis=$request->input('other_diagnosis');
        $consultation ->labs=implode($labFileNames,',');
        $consultation ->ultra_sound_scan=implode($scanFileNames,',');
        $consultation ->user_id=Auth::user()->id;
        $consultation->save();



        //Add medications
        foreach ($request->input('medications') as $med) {
            if (!empty($med['drug_id']) && !empty($med['dosage'])) {

                //check if medication already exist
                $check = Medication::where('drugs_id', $med['drug_id'])
                    ->where('patient_id', $request->input('patient_id'))
                    ->where('registration_id', $request->input('registration_id'))
                    ->whereDate('created_at', date('Y-m-d'))
                    ->first();
                if (empty($check)){

                    /*$drugs = Drug::find($med['drug_id']);

                    //if patient is NOT Insured then insert the drug selling price
                    if ($registration->isInsured != 1) {
                        $bill = new Bill();
                        $bill->registration_id = $request->input('registration_id');
                        $bill->patient_id = $request->input('patient_id');
                        $bill->item = $drugs->name;
                        $bill->item_id = $drugs->id;
                        $bill->type="Drug";
                        $bill->amount = $drugs->retail_price;
                        $bill->insurance_amount = $drugs->nhis_amount;
                        $bill->total_amount_to_pay = $drugs->retail_price;
                        $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
                        $bill->save();
                    }
                    else {
                        //if patient is Insured the total amount to pay is sellingPrice - NHIS Price
                        $bill = new Bill();
                        $bill->registration_id = $request->input('registration_id');
                        $bill->patient_id = $request->input('patient_id');
                        $bill->item = $drugs->name;
                        $bill->item_id = $drugs->id;
                        $bill->type="Drug";
                        $bill->amount = $drugs->retail_price;
                        $bill->insurance_amount = $drugs->nhis_amount;
                        $bill->total_amount_to_pay = $drugs->retail_price - $drugs->nhis_amount;
                        $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
                        $bill->save();
                    }*/

                    $medication = new Medication();
//                    $medication->bill_id = $bill->id;
                    $medication->patient_id = $request->input('patient_id');
                    $medication->registration_id = $request->input('registration_id');
                    $medication->drugs_id = $med['drug_id'];
                    $medication->dosage = substr($med['dosage'],1);
                    $medication->days = $med['days'];
                    $medication->qty = substr($med['dosage'],0,1)*$med['days'];
                    $medication->qty_dispensed =0;
                    $medication->user_id = Auth::user()->id;
                    $medication->save();


                }else{

                    //update
                    $medication = Medication::find($check->id);
                    $medication->patient_id = $request->input('patient_id');
                    $medication->registration_id = $request->input('registration_id');
                    $medication->drugs_id = $med['drug_id'];
                    $medication->dosage = substr($med['dosage'],1);
                    $medication->days = $med['days'];
                    $medication->qty = substr($med['dosage'],0,1)*$med['days'];
                    $medication->qty_dispensed =0;
                    $medication->save();
                }
            }
        }


        //Add other  medications
        if (\Request::has('other-medications')) {
            foreach ($request->input('other-medications') as $other) {
                if ($other['other_medication'] != "" && $other['other_dosage'] != "") {
                    $medication = new OtherMedication();
                    $medication->patient_id = $request->input('patient_id');
                    $medication->registration_id = $request->input('registration_id');
                    $medication->drug = $other['other_medication'];
                    $medication->dosage = substr($other['other_dosage'],1)." x ".$other['other_days']." days";
                    $medication->user_id = Auth::user()->id;
                    $medication->save();
                }
            }
        }


        //add diagnosis
        if (\Request::has('diagnosis')) {

            foreach ($request->input('diagnosis') as $key) {
                $check = PatientDiagnosis::where('diagnoses_id', $key)
                    ->where('patient_id', $request->input('patient_id'))
                    ->where('registration_id', $request->input('registration_id'))
                    ->whereDate('created_at', date('Y-m-d'))
                    ->first();
                if (empty($check)){
                    $diagnosis = new PatientDiagnosis();
                    $diagnosis->patient_id = $request->input('patient_id');
                    $diagnosis->registration_id = $request->input('registration_id');
                    $diagnosis->diagnoses_id = $key;
                    $diagnosis->user_id = Auth::user()->id;
                    $diagnosis->save();
                }
            }
        }

        //add detention bill if patient is detained or admitted
//        if (\Request::has('detain_admit')){
//            $service_charge = Charge::where('name','Detain/Admit')->first();
//            $bill = new Bill();
//            $bill->registration_id = $request->input('registration_id');
//            $bill->patient_id =$request->input('patient_id');
//            $bill->item = $service_charge->name;
//            $bill->item_id = $service_charge->id;
//            $bill->amount =$service_charge->amount;
//            $bill->insurance_amount =0;
//            $bill->total_amount_to_pay=$service_charge->amount;
//            $bill->billed_by =Auth::user()->first_name." ".Auth::user()->last_name;
//            $bill->save();
//        }




        /*
         * Start Calculating Consultation
         */

        $service_charge = Charge::where('name','Consultation')->first();

        //charge for consultation if only the person is not insured
        if ($registration->isInsured != 1){ //if patient is not insured

            /*
             * check if patient is an old patient that is before the system
             * then use the last visit
             */
            $patient_info = Patient::find($request->input('patient_id'));
            if ($patient_info->old_patient == 1){//if patient had records before the system


                $getAllRegistrations =Registration::where('patient_id',$request->input('patient_id'))->get();

//                    return $getAllRegistrations;
                if (count($getAllRegistrations) == 1){
//                        return "yes";
                    $last_visit = \Carbon\Carbon::createFromFormat('Y-m-d', $patient_info->last_visit);


                    $today = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                    $noOfDays = $today->diffInDays($last_visit);
//                        return $noOfDays;
                }elseif (count($getAllRegistrations)>1){
                    $totalRegistrations = sizeof($getAllRegistrations);
                    $getLastRegistration = $totalRegistrations -2;


                    $last_visit = \Carbon\Carbon::createFromFormat('Y-m-d', substr($getAllRegistrations[$getLastRegistration]->created_at,0,10));


                    $today = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                    $noOfDays = $today->diffInDays($last_visit);
                }

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
            }else{
                /*
                 * if patient is new to the system
                 */

                //get all patient registration

                $getAllRegistrations =Registration::where('patient_id',$request->input('patient_id'))->get();

                if (count($getAllRegistrations) == 1){
                    $last_visit = \Carbon\Carbon::createFromFormat('Y-m-d', substr($getAllRegistrations[0]->created_at,0,10));
                    $today = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                    $noOfDays = $today->diffInDays($last_visit);
                }elseif (count($getAllRegistrations)>1){
                    $totalRegistrations = count($getAllRegistrations);
                    $getLastRegistration = $totalRegistrations -2;
                    $last_visit = \Carbon\Carbon::createFromFormat('Y-m-d', substr($getAllRegistrations[$getLastRegistration]->created_at,0,10));
                    $today = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                    $noOfDays = $today->diffInDays($last_visit);
                }


                if ($noOfDays>=15){
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
            }
        }

        /*
         * End Consultation Calculation
         */


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

        //update registration set consult = 1 and detain_admit if request has detain_admit
        $registration->consult = 1;
        if (\Request::has('detain_admit')){
            $registration->detain= 1;
        }
        $registration->save();

        return redirect()->route('consultation.index')
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
        $diagnosis = Diagnose::all();
        $drugs = Drug::all();
        $charges = Charge::all();
        $searchPatient[] = Patient::find($id);
        $recentConsultation="";
        $recentVitals ="";
        $recentRegistration="";
        $getImplodedMedicine ="";
        $getImplodedDiagnosis="";
        $patientDiagnosis="";
        $medication="";
        $getBills="";
        $otherMedication ="";

        if (count($searchPatient) == 0){
            return back()->with('error','Sorry! No Record Found');
        }

        if (count($searchPatient) != 0) {

            $recentRegistration = Registration::with('consultation')
                ->where('patient_id', $id)->latest()->first();

        }
        if (!empty($recentRegistration)){
            $recentConsultation = Consultation::where('patient_id', $id)
                ->where('registration_id', $recentRegistration->id)->latest()->first();
            $recentVitals = Vital::where('patient_id', $id)
                ->where('registration_id', $recentRegistration->id)->latest()->first();
            $recentMedication = Medication::where('patient_id', $id)
                ->where('registration_id', $recentRegistration->id)->latest()->get();
            $recentPatientDiagnosis = PatientDiagnosis::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->latest()->get();
            $patientDiagnosis = PatientDiagnosis::with('diagnoses')->where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id', $recentRegistration->id)->get();

            $medication = Medication::with('drugs')->where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id', $recentRegistration->id)->get();

            $getBills = Bill::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->get();



            //check if patient is detained Or Admitted
            if ($recentRegistration->detain ==0){
                $detentionBill =0;
            }
            elseif ( $recentRegistration->detain == 1){
                //get date admitted
                $dateAdmitted = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $recentRegistration->created_at);

                $today = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', date('Y-m-d H:s:i'));
                $detentionDays = $today->diffInDays($dateAdmitted);

                if ($detentionDays < 3){
                    $detentionBill = 20;
                }else{
                    $additionalDays = $detentionDays - 2;
                    $calAdditionalCharges = $additionalDays*5;

                    $detentionBill = $calAdditionalCharges+20;
                }
            }
            //if patient is discharged, then use discharged_date instead of today
            elseif ($recentRegistration->detain == 2){
                //get date admitted
                $dateAdmitted = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $recentRegistration->created_at);

                $today = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $recentRegistration->discharged_date);
                $detentionDays = $today->diffInDays($dateAdmitted);

                if ($detentionDays < 3 && $detentionDays >0){
                    $detentionBill = 20;
                }else{
                    $additionalDays = $detentionDays - 2;
                    $calAdditionalCharges = $additionalDays*5;

                    $detentionBill = $calAdditionalCharges+20;
                }
            }

            $getDiagIds =[];
            $getMedIds  =[];


            foreach ($recentMedication as $med){
                array_push($getMedIds,$med->drugs_id);
            }

//            return $getMedIds;
            foreach ($recentPatientDiagnosis as $diag){
                array_push($getDiagIds,$diag->diagnoses_id);
            }

            $getImplodedMedicine = implode(',',$getMedIds);
            $getImplodedDiagnosis = implode(',',$getDiagIds);
        }
        $registration = Registration::with('patient')
            ->where('vitals',1)
            ->where('consult',0)
            ->where('patient_id',$searchPatient[0]->id)
            ->whereDate('created_at', Carbon::today())
            ->limit(1)
//            ->orderBy('created_at','asc')
            ->get();

        //get previous registrations
        $previousRegistration = Registration::where('patient_id',$searchPatient[0]->id)->get();

        $allRegistrations=0;
        if (count($registration) == 1){
            $allRegistrations = Registration::where('patient_id',$registration[0]->patient->id)->get();


            // return $allRegistrations;
            $getVitals = Vital::where('patient_id',$registration[0]->patient_id)
                ->whereDate('created_at', Carbon::today())->get();
        }else{
            $getVitals =[];
        }

//        return $getImplodedMedicine;

        return view('pages.consultations.search_result')
            ->with('registration',$registration)
            ->with('getVitals',$getVitals)
            ->with('diagnosis',$diagnosis)
            ->with('drugs',$drugs)
            ->with('allRegistrations',$allRegistrations)
            ->with('searchPatient',$searchPatient)
            ->with('previousRegistration',$previousRegistration)
            ->with('recentRegistration',$recentRegistration)
            ->with('recentConsultation',$recentConsultation)
            ->with('recentVitals',$recentVitals)
            ->with('getImplodedMedicine',$getImplodedMedicine)
            ->with('getImplodedDiagnosis',$getImplodedDiagnosis)
            ->with('patientDiagnosis',$patientDiagnosis)
            ->with('medication',$medication)
            ->with('getBills',$getBills)
            ->with('detentionBill',$detentionBill)
            ->with('charges',$charges)
            ->with('otherMedication',$otherMedication);
    }


    public function searchConsultation(Request $request){

        $diagnosis = Diagnose::all();
        $drugs = Drug::all();
        $charges = Charge::all();
        $searchPatient= Patient::where('folder_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('phone_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('last_name', 'like', '%' . $request->input("search") . '%')->get();


        if (count($searchPatient) == 0){
            return back()->with('error','Sorry! No Record Found');
        }
        $recentConsultation="";
        $recentVitals ="";
        $recentRegistration="";
        $getImplodedMedicine ="";
        $getImplodedDiagnosis="";
        $patientDiagnosis="";
        $medication="";
        $getBills="";
        if (count($searchPatient) !=0) {
            $recentRegistration = Registration::with('patient')
                ->where('patient_id', $searchPatient[0]->id)
                ->latest()->first();
        }

//        return $searchPatient;
        if (!empty($recentRegistration)){

//            return $recentRegistration;
            $recentConsultation = Consultation::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->latest()->first();
            $recentVitals = Vital::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->latest()->first();
            $recentMedication = Medication::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->latest()->get();

            $recentPatientDiagnosis = PatientDiagnosis::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->latest()->get();


            $patientDiagnosis = PatientDiagnosis::with('diagnoses')->where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id', $recentRegistration->id)->get();

            $medication = Medication::with('drugs')->where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id', $recentRegistration->id)->get();

            $otherMedication = OtherMedication::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id', $recentRegistration->id)->get();

            $getBills = Bill::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->get();
            $getDiagIds =[];
            $getMedIds  =[];

            foreach ($recentMedication as $med){
                array_push($getMedIds,$med->drugs_id);
            }
            foreach ($recentPatientDiagnosis as $diag){
                array_push($getDiagIds,$diag->diagnoses_id);
            }

            $getImplodedMedicine = implode(',',$getMedIds);
            $getImplodedDiagnosis = implode(',',$getDiagIds);

        }

        //if searchPatient result is only one
        if (count($searchPatient) == 1){

            $registration = Registration::with('patient')
                ->where('vitals',1)
                ->where('consult',0)
                ->where('patient_id',$searchPatient[0]->id)
                ->whereDate('created_at', Carbon::today())
                ->limit(1)
                ->get();

            $previousRegistration = Registration::where('patient_id',$searchPatient[0]->id)->get();

            /*
             * Calculate detention bill
             */
            //check if patient is detained Or Admitted
            if ( $recentRegistration->detain == 0){
                $detentionBill = 0;
            }
            elseif ( $recentRegistration->detain == 1){
                //get date admitted
                $dateAdmitted = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $recentRegistration->created_at);

                $today = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', date('Y-m-d H:s:i'));
                $detentionDays = $today->diffInDays($dateAdmitted);

                if ($detentionDays < 3){
                    $detentionBill = 20;
                }else{
                    $additionalDays = $detentionDays - 2;
                    $calAdditionalCharges = $additionalDays*5;

                    $detentionBill = $calAdditionalCharges+20;
                }
            }
            //if patient is discharged, then use discharged_date instead of today
            elseif ($recentRegistration->detain == 2){
                //get date admitted
                $dateAdmitted = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $recentRegistration->created_at);

                $today = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $recentRegistration->discharged_date);
                $detentionDays = $today->diffInDays($dateAdmitted);

                if ($detentionDays < 3){
                    $detentionBill = 20;
                }else{
                    $additionalDays = $detentionDays - 2;
                    $calAdditionalCharges = $additionalDays*5;

                    $detentionBill = $calAdditionalCharges+20;
                }
            }
            /*
             * End detention bill calculation
             */

            $allRegistrations=0;
            if (count($registration) == 1){
                $allRegistrations = Registration::where('patient_id',$registration[0]->patient->id)->get();

                // return Vitals;
                $getVitals = Vital::where('patient_id',$registration[0]->patient_id)
                    ->whereDate('created_at', Carbon::today())->get();
            }else{

                $getVitals =[];
            }


            return view('pages.consultations.search_result')
                ->with('registration',$registration)
                ->with('getVitals',$getVitals)
                ->with('diagnosis',$diagnosis)
                ->with('drugs',$drugs)
                ->with('allRegistrations',$allRegistrations)
                ->with('searchPatient',$searchPatient)
                ->with('previousRegistration',$previousRegistration)
                ->with('recentRegistration',$recentRegistration)
                ->with('recentConsultation',$recentConsultation)
                ->with('recentVitals',$recentVitals)
                ->with('getImplodedMedicine',$getImplodedMedicine)
                ->with('getImplodedDiagnosis',$getImplodedDiagnosis)
                ->with('charges',$charges)
                ->with('patientDiagnosis',$patientDiagnosis)
                ->with('medication',$medication)
                ->with('getBills',$getBills)
                ->with('otherMedication',$otherMedication)
                ->with('detentionBill',$detentionBill);
        }else{
            return view('pages.consultations.search_result')
                ->with('searchPatient',$searchPatient)
                ->with('recentRegistration',$recentRegistration)
                ->with('recentConsultation',$recentConsultation)
                ->with('recentVitals',$recentVitals)
                ->with('diagnosis',$diagnosis)
                ->with('drugs',$drugs)
                ->with('getImplodedMedicine',$getImplodedMedicine)
                ->with('getImplodedDiagnosis',$getImplodedDiagnosis)
                ->with('charges',$charges)
                ->with('patientDiagnosis',$patientDiagnosis)
                ->with('medication',$medication)
                ->with('getBills',$getBills)
                ->with('otherMedication',$otherMedication);
        }




    }

    public function discharge($id){
        $registration = Registration::find($id);

        $registration->detain = 2;
        $registration->discharged_date =date('Y-m-d H:i:s');

        $registration->save();

        return back()->with('success','Patient Discharged');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        return $id;
        $diagnosis = Diagnose::all();
        $drugs = Drug::all();
        $allCharges = Charge::all();

        $getImplodedMedicine = "";
        $getImplodedDiagnosis = "";
        $registration = Registration::find($id);
        $patient = Patient::find($registration->patient_id);

        $consultation = Consultation::where('registration_id',$registration->id)
            ->where('patient_id',$patient->id)->first();

        $vitals = Vital::where('registration_id',$registration->id)
            ->where('patient_id',$patient->id)->first();

        $medications = Medication::with('drugs')
            ->where('patient_id', $patient->id)
            ->where('registration_id', $registration->id)
            ->latest()->get();
        $patientDiagnosis = PatientDiagnosis::with('diagnoses')->where('patient_id',$patient->id)
            ->where('registration_id',$registration->id)->latest()->get();

        $services = Service::with('charge')->where('patient_id',$patient->id)
            ->where('registration_id',$registration->id)->latest()->get();

//        return $services;
        return view('pages.consultations.consult-edit')
            ->with('registration',$registration)
            ->with('patient',$patient)
            ->with('consultation',$consultation)
            ->with('medications',$medications)
            ->with('patientDiagnosis',$patientDiagnosis)
            ->with('getImplodedMedicine',$getImplodedMedicine)
            ->with('getImplodedDiagnosis',$getImplodedDiagnosis)
            ->with('vitals',$vitals)
            ->with('services',$services)
            ->with('diagnosis',$diagnosis)
            ->with('drugs',$drugs)
            ->with('allCharges',$allCharges);

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
        $registration = Registration::find($request->input('registration_id'));

        //Upload labs
        $labFileNames = [];
        $scanFileNames =[];
        if (\Request::has('labs')) {

            for ($i = 0; $i < count($request->file('labs')); $i++) {
                $file = $request->file('labs')[$i];
                $extension = $file->getClientOriginalExtension();
                $files = substr($file->getClientOriginalName(), 0, strpos($file->getClientOriginalName(), '.'));
                $fileName = $files . '_' . time() . '.' . $extension;

                $file->move('public/labs', $fileName);

                array_push($labFileNames,$fileName);
            }
        }


//Upload Scans
        if (\Request::has('scan')){
            for ($i = 0; $i < count($request->file('scan')); $i++) {
                $scannedFile = $request->file('scan')[$i];
                $scannedExtension = $scannedFile->getClientOriginalExtension();
                $scannedFiles = substr($scannedFile->getClientOriginalName(), 0, strpos($scannedFile->getClientOriginalName(), '.'));
                $scannedFileName = $scannedFiles . '_' . time() . '.' . $scannedExtension;

                $scannedFile->move('public/scan', $scannedFileName);
                array_push($scanFileNames,$scannedFileName);
            }
        }


        $consultation = Consultation::find($id);
        $consultation->patient_id =$request->input('patient_id');
        $consultation->complains=$request->input('complains');
        $consultation->findings=$request->input('findings');
        $consultation->physical_examination=$request->input('physical_examination');
        $consultation->other_diagnosis=$request->input('other_diagnosis');
//        $consultation->detain_admit=$request->input('detain_admit');
        if (count($labFileNames) == 0){
            $consultation->labs=$consultation->labs;
        }else{
            $consultation->labs=$consultation->labs.",".implode($labFileNames,',');
        }

        if (count($labFileNames) == 0){
            $consultation->ultra_sound_scan=$consultation->ultra_sound_scan;
        }else{
            $consultation->ultra_sound_scan=$consultation->ultra_sound_scan.",".implode($scanFileNames,',');
        }
        $consultation->user_id=Auth::user()->id;

        $consultation->save();



        //Add medications
        foreach ($request->input('medications') as $med) {
            if (!empty($med['drug_id']) && !empty($med['dosage'])) {

                //check if medication already exist
                $check = Medication::where('drugs_id', $med['drug_id'])
                    ->where('patient_id', $request->input('patient_id'))
                    ->where('registration_id', $request->input('registration_id'))
                    ->whereDate('created_at', date('Y-m-d'))
                    ->first();
                if (empty($check)){

                    /*$drugs = Drug::find($med['drug_id']);

                    //if patient is NOT Insured then insert the drug selling price
                    if ($registration->isInsured != 1) {
                        $bill = new Bill();
                        $bill->registration_id = $request->input('registration_id');
                        $bill->patient_id = $request->input('patient_id');
                        $bill->item = $drugs->name;
                        $bill->item_id = $drugs->id;
                        $bill->type="Drug";
                        $bill->amount = $drugs->retail_price;
                        $bill->insurance_amount = $drugs->nhis_amount;
                        $bill->total_amount_to_pay = $drugs->retail_price;
                        $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
                        $bill->save();
                    }
                    else {
                        //if patient is Insured the total amount to pay is sellingPrice - NHIS Price
                        $bill = new Bill();
                        $bill->registration_id = $request->input('registration_id');
                        $bill->patient_id = $request->input('patient_id');
                        $bill->item = $drugs->name;
                        $bill->item_id = $drugs->id;
                        $bill->type="Drug";
                        $bill->amount = $drugs->retail_price;
                        $bill->insurance_amount = $drugs->nhis_amount;
                        $bill->total_amount_to_pay = $drugs->retail_price - $drugs->nhis_amount;
                        $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
                        $bill->save();
                    }*/

                    $medication = new Medication();
//                    $medication->bill_id = $bill->id;
                    $medication->patient_id = $request->input('patient_id');
                    $medication->registration_id = $request->input('registration_id');
                    $medication->drugs_id = $med['drug_id'];
                    $medication->dosage = substr($med['dosage'],1);
                    $medication->days = $med['days'];
                    $medication->qty = substr($med['dosage'],0,1)*$med['days'];
                    $medication->qty_dispensed =0;
                    $medication->user_id = Auth::user()->id;
                    $medication->save();


                }else{

                    //update
                    $medication = Medication::find($check->id);
                    $medication->patient_id = $request->input('patient_id');
                    $medication->registration_id = $request->input('registration_id');
                    $medication->drugs_id = $med['drug_id'];
                    $medication->dosage = substr($med['dosage'],1);
                    $medication->days = $med['days'];
                    $medication->qty = substr($med['dosage'],0,1)*$med['days'];
                    $medication->qty_dispensed =0;
                    $medication->save();
                }
            }
        }


        //Add other  medications
        if (\Request::has('other-medications')) {
            foreach ($request->input('other-medications') as $other) {
                if ($other['other_medication'] != "" && $other['other_dosage'] != "") {
                    $medication = new OtherMedication();
                    $medication->patient_id = $request->input('patient_id');
                    $medication->registration_id = $request->input('registration_id');
                    $medication->drug = $other['other_medication'];
                    $medication->dosage = substr($other['other_dosage'],1)." x ".$other['other_days']." days";
                    $medication->user_id = Auth::user()->id;
                    $medication->save();
                }
            }
        }



        //add diagnosis
        if (\Request::has('diagnosis')) {
            foreach ($request->input('diagnosis') as $key) {
                $check = PatientDiagnosis::where('diagnoses_id', $key)
                    ->where('patient_id', $request->input('patient_id'))
                    ->where('registration_id', $request->input('registration_id'))
                    ->whereDate('created_at', date('Y-m-d'))
                    ->first();
                if (empty($check)){
                    $diagnosis = new PatientDiagnosis();
                    $diagnosis->patient_id = $request->input('patient_id');
                    $diagnosis->registration_id = $request->input('registration_id');
                    $diagnosis->diagnoses_id = $key;
                    $diagnosis->user_id = Auth::user()->id;
                    $diagnosis->save();
                }
            }
        }


        if (\Request::has('service')) {

//            $service_charge = Charge::where('name','Consultation')->first();
//            if ($registration->isInsured != 1){
//                $bill = new Bill();
//                $bill->registration_id = $request->input('registration_id');
//                $bill->patient_id =$request->input('patient_id');
//                $bill->item = $service_charge->name;
//                $bill->item_id = $service_charge->id;
//                $bill->amount =$service_charge->amount;
//                $bill->insurance_amount =0;
//                $bill->total_amount_to_pay=$service_charge->amount;
//                $bill->billed_by =Auth::user()->first_name." ".Auth::user()->last_name;
//                $bill->save();
//            }

            foreach ($request->input('service') as $key) {
                $data = explode(',', $key);
                $check = Service::where('charge_id', $data[0])
                    ->where('patient_id', $request->input('patient_id'))
                    ->where('registration_id', $request->input('registration_id'))
                    ->whereDate('created_at', date('Y-m-d'))
                    ->first();
                if (empty($check)){
                    $service = new Service();
                    $service->patient_id = $request->input('patient_id');
                    $service->registration_id = $request->input('registration_id');
                    $service->charge_id = $data[0];
                    $service->user_id = Auth::user()->id;
                    $service->save();

                    $service_charge = Charge::where('name',$data[1])->first();
                    if ($registration->isInsured != 1){
                        $bill = new Bill();
                        $bill->registration_id = $request->input('registration_id');
                        $bill->patient_id =$request->input('patient_id');
                        $bill->item = $service_charge->name;
                        $bill->item_id = $service_charge->id;
                        $bill->amount =$service_charge->amount;
                        $bill->insurance_amount =0;
                        $bill->total_amount_to_pay=$service_charge->amount;
                        $bill->billed_by =Auth::user()->first_name." ".Auth::user()->last_name;
                        $bill->save();
                    }
                }
            }

        }
        $registration->consult = 1;
        $registration->save();

        return back()->with('success','Record Updated');
    }

    public function patientRecord(Request $request){

        $data= explode(',',$request->input('data'));

        $getRegistration = Registration::where('patient_id',$data[1])
            ->whereDate('created_at', $data[0])->get();
        $vitals = Vital::with('user')->where('patient_id',$data[1])
            ->whereDate('created_at',$data[0])->get();
        $consultation = Consultation::where('patient_id',$data[1])
            ->whereDate('created_at', $data[0])->get();
        $patientDiagnosis = PatientDiagnosis::with('diagnoses')->where('patient_id',$data[1])
            ->whereDate('created_at', $data[0])->get();
        $medication = Medication::with('drugs')->where('patient_id',$data[1])
            ->whereDate('created_at', $data[0])->get();
        $getBills = Bill::where('patient_id',$data[1])
            ->whereDate('created_at', $data[0])->get();


//        return $getBills;

        $count_registration = Registration::with('patient')
            ->where('patient_id',$data[1])
            ->whereDate('created_at', Carbon::today())
            ->get()->count();



        if (\Request::has('fromSearchPage')) {
            $registration = Registration::with('patient','user')
                ->where('patient_id',$data[1])
                ->whereDate('created_at', $data[0])
                ->get();

        }else{
            $registration = Registration::with('patient')
                ->where('vitals', 1)
                ->where('consult', 0)
                ->whereDate('created_at', Carbon::today())
                ->limit(1)
                ->get();

        }

        $allRegistrations=0;
        if (count($registration) == 1){
            $allRegistrations = Registration::where('patient_id',$registration[0]->patient->id)->get();

            // return $allRegistrations;
            $getVitals = Vital::where('patient_id',$registration[0]->patient_id)
                ->whereDate('created_at', Carbon::today())->get();
        }else{
            $getVitals =[];
        }

        $diagnosis = Diagnose::all();
        $drugs = Drug::all();


//        return $registration;
        /*
         * Calculate detention bill
         */
        //check if patient is detained Or Admitted
        if ( $registration[0]->detain == 0){
            $detentionBill = 0;
        }
        elseif ( $registration[0]->detain == 1){
            //get date admitted
            $dateAdmitted = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $registration[0]->created_at);

            $today = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', date('Y-m-d H:s:i'));
            $detentionDays = $today->diffInDays($dateAdmitted);

            if ($detentionDays < 3){
                $detentionBill = 20;
            }else{
                $additionalDays = $detentionDays - 2;
                $calAdditionalCharges = $additionalDays*5;

                $detentionBill = $calAdditionalCharges+20;
            }
        }
        //if patient is discharged, then use discharged_date instead of today
        elseif ($registration[0]->detain == 2){
            //get date admitted
            $dateAdmitted = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $registration[0]->created_at);

            $today = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $registration[0]->discharged_date);
            $detentionDays = $today->diffInDays($dateAdmitted);

            if ($detentionDays < 3){
                $detentionBill = 20;
            }else{
                $additionalDays = $detentionDays - 2;
                $calAdditionalCharges = $additionalDays*5;

                $detentionBill = $calAdditionalCharges+20;
            }
        }
        /*
         * End detention bill calculation
         */

        if (\Request::has('fromSearchPage')){
            return view('pages.consultations.details')
                ->with('registration',$registration)
                ->with('getVitals',$getVitals)
                ->with('diagnosis',$diagnosis)
                ->with('drugs',$drugs)
                ->with('getRegistration',$getRegistration)
                ->with('vitals',$vitals)
                ->with('consultation',$consultation)
                ->with('patientDiagnosis',$patientDiagnosis)
                ->with('medication',$medication)
                ->with('allRegistrations',$allRegistrations)
                ->with('count_registration',$count_registration)
                ->with('getBills',$getBills)
                ->with('detentionBill',$detentionBill);
        }else{
            return view('pages.consultations.index')
                ->with('registration',$registration)
                ->with('getVitals',$getVitals)
                ->with('diagnosis',$diagnosis)
                ->with('drugs',$drugs)
                ->with('getRegistration',$getRegistration)
                ->with('vitals',$vitals)
                ->with('consultation',$consultation)
                ->with('patientDiagnosis',$patientDiagnosis)
                ->with('medication',$medication)
                ->with('allRegistrations',$allRegistrations)
                ->with('count_registration',$count_registration)
                ->with('getBills',$getBills)
                ->with('detentionBill',$detentionBill);
        }

    }

    //return view
    public function editMedication($drug_id, $med_id){
        $drug = Drug::with('drug_type')->find($drug_id);

        $drugs = Drug::all();
        $medication = Medication::find($med_id);
//        return $medication;
        return view('pages.consultations.edit-medication')
            ->with('drug',$drug)
            ->with('drugs',$drugs)
            ->with('medication',$medication);
    }

    //update medication
    public function edit_med(Request $request){
        $medication = Medication::find($request->input('med_id'));
        $medication->drugs_id = $request->input('drug_id');
        $medication->dosage = substr($request->input('dosage'),1);
        $medication->days = $request->input('days');
        $medication->qty = substr($request->input('dosage'),0,1)*$request->input('days');
        $medication->save();

        return redirect()->route('consultation.edit',[$medication->registration_id])
            ->with('success','Medication Updated');
    }

    //EDit patient's diagnosis

    //return view
    public function editDiagnosis($diagnosis_id){
        $patient_diagnosis = PatientDiagnosis::find($diagnosis_id);


        $diagnosis = Diagnose::find($patient_diagnosis->diagnoses_id);

        $allDiagnosis = Diagnose::all();
        return view('pages.consultations.edit-diagnosis')
            ->with('diagnosis',$diagnosis)
            ->with('allDiagnosis',$allDiagnosis)
            ->with('patient_diagnosis',$patient_diagnosis);
    }

    //update diagnosis

    public function edit_diagnosis(Request $request){

//        return $request;
        $patient_diagnosis = PatientDiagnosis::find($request->input('p_diagnosis_id'));
        $patient_diagnosis->diagnoses_id =$request->input('diagnosis_id');
        $patient_diagnosis->save();
        return redirect()->route('consultation.edit',[$patient_diagnosis->registration_id])
            ->with('success','Diagnosis Updated');
    }


    public function edit_service($service_id){
        $patient_service = Service::find($service_id);
        $service = Charge::find($patient_service->charge_id);
        $allServices = Charge::all();

        $getAllServices = Service::with('charge')->where('registration_id',$patient_service->registration_id)
            ->where('patient_id',$patient_service->patient_id)->get();

        return view('pages.consultations.edit-charge')
            ->with('allServices',$allServices)
            ->with('service',$service)
            ->with('patient_service',$patient_service)
            ->with('getAllServices',$getAllServices);
    }

    public function edit_service_charge(Request $request){
        $patient_service = Service::find($request->input('p_service_id'));
        $patient_service->charge_id =$request->input('charge_id');
        $patient_service->save();
        return redirect()->route('consultation.edit',[$patient_service->registration_id])
            ->with('success','Service Updated');
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
