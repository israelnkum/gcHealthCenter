<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Charge;
use App\Consultation;
use App\Diagnose;
use App\Drug;
use App\LabResult;
use App\Medication;
use App\OtherMedication;
use App\Patient;
use App\PatientDiagnosis;
use App\Payment;
use App\Registration;
use App\Review;
use App\ScannedResult;
use App\Service;
use App\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
//        $data = Registration::with('vital','consultation', 'medications.drugs', 'diagnosis.diagnoses')
//            ->where('patient_id', 73)->get();
//
//        return $data;
        $registration = Registration::with('patient.vitals')
            ->where('vitals',1)
            ->where('consult',0)
            ->where('type','Consultation')
            ->whereDate('created_at', Carbon::today())
            ->limit(5)
//            ->orderBy('created_at','asc')
            ->get();


//        return $registration;
        $not_seen = Registration::with('patient')
            ->where('vitals',1)
            ->where('consult',0)
            ->where('type','Consultation')
            ->get();

//        return $not_seen;


        $last_visit =[];
        if (count($registration) != 0){
            $last_visit = Registration::with('consultation','medications.drugs','diagnosis.diagnoses')
                ->where('patient_id',$registration[0]->patient_id)->get();
        }

//        return $last_visit;

        $allRegistrations=0;
        if (count($registration) == 1){
            $allRegistrations = Registration::where('patient_id',$registration[0]->patient->id)->get();


            // return $allRegistrations;
            $getVitals = Vital::where('patient_id',$registration[0]->patient_id)
                ->whereDate('created_at', Carbon::today())->get();
        }else{
            $getVitals =[];
        }

//        return $allRegistrations;
//        return $last_visit;

        $diagnosis = Diagnose::all();
        $drugs = Drug::where('qty_in_stock','>',0)
            ->where('retail_price','>',0)->get();
        $charges = Charge::all();

        return view('pages.consultations.index1')
            ->with('registration',$registration)
            ->with('getVitals',$getVitals)
            ->with('diagnosis',$diagnosis)
            ->with('drugs',$drugs)
            ->with('allRegistrations',$allRegistrations)
            ->with('charges',$charges)
            ->with('last_visit',$last_visit)
            ->with('not_seen',$not_seen);
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
        $registration = Registration::find($request->input('registration_id'));

        $count_registration = Registration::where('patient_id',$registration->patient_id)->get()->count();

        DB::beginTransaction();
        try{
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


            $getRegistration = Consultation::where('registration_id',$request->input('registration_id'))
                ->latest()
                ->first();


//        return $registration;

            $consultation = Consultation::find($getRegistration->id);
            $consultation ->patient_id =$request->input('patient_id');
            $consultation ->complains=$request->input('complains');
            $consultation ->findings=$request->input('findings');
            $consultation ->physical_examination=$request->input('physical_examination');
            $consultation ->other_diagnosis=$request->input('other_diagnosis');
            $consultation ->notes=$request->input('notes');
            $consultation ->user_id=Auth::user()->id;
            $consultation->save();


            //Upload labs
            if ($request->has('labs')) {

                for ($i = 0; $i < count($request->file('labs')); $i++) {
                    $file = $request->file('labs')[$i];
                    $extension = $file->getClientOriginalExtension();
                    $files = substr($file->getClientOriginalName(), 0, strpos($file->getClientOriginalName(), '.'));
                    $fileName = $files . '_' . time() . '.' . $extension;

                    $file->move('public/labs', $fileName);
                    $record = new LabResult();
                    $record->patient_id = $request->input('patient_id');
                    $record->registration_id = $request->input('registration_id');
                    $record->consultation_id = $consultation->id;
                    $record->file_name =$fileName;
                    $record->user_id = Auth::user()->id;
                    $record->save();
                }
            }


            //Upload Scans
            if ($request->has('scan')){
                for ($i = 0; $i < count($request->file('scan')); $i++) {
                    $scannedFile = $request->file('scan')[$i];
                    $scannedExtension = $scannedFile->getClientOriginalExtension();
                    $scannedFiles = substr($scannedFile->getClientOriginalName(), 0, strpos($scannedFile->getClientOriginalName(), '.'));
                    $scannedFileName = $scannedFiles . '_' . time() . '.' . $scannedExtension;

                    $scannedFile->move('public/scan', $scannedFileName);


                    $record = new ScannedResult();
                    $record->patient_id = $request->input('patient_id');
                    $record->registration_id = $request->input('registration_id');
                    $record->consultation_id = $consultation->id;
                    $record->file_name =$scannedFileName;
                    $record->user_id = Auth::user()->id;
                    $record->save();
                }
            }


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
                        $medication->dosage =$med['dosage'];
//                    $medication->days = $med['days'];
//                    $medication->qty = $med['qty'];
                        $medication->qty = 0;
                        $medication->qty_dispensed =0;
                        $medication->type ="Consultation";
                        $medication->user_id = Auth::user()->id;
                        $medication->save();
                    }else{
                        //update
                        $medication = Medication::find($check->id);
                        $medication->patient_id = $request->input('patient_id');
                        $medication->registration_id = $request->input('registration_id');
                        $medication->drugs_id = $med['drug_id'];
                        $medication->dosage =$med['dosage'];
//                    $medication->days = $med['days'];
//                    $medication->qty = $med['qty'];
                        $medication->qty = 0;
                        $medication->qty_dispensed =0;
                        $medication->save();
                    }
                }
            }


            //Add other  medications
            if ($request->has('other-medications')) {
                foreach ($request->input('other-medications') as $other) {
                    if ($other['other_medication'] != "" && $other['other_dosage'] != "") {
                        $medication = new OtherMedication();
                        $medication->patient_id = $request->input('patient_id');
                        $medication->registration_id = $request->input('registration_id');
                        $medication->drug = $other['other_medication'];
                        $medication->dosage =$other['other_dosage'];
                        $medication->user_id = Auth::user()->id;
                        $medication->save();
                    }
                }
            }
            //add diagnosis
            /*   if (\Request::has('diagnosis')) {

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
                           $diagnosis->type = "Consultation";
                           $diagnosis->user_id = Auth::user()->id;
                           $diagnosis->save();
                       }
                   }
               }*/

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

            if ($request->has('service')) {
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
                        $service->type = "Consultation";
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
            if ($request->has('detain_admit')){
                $registration->detain= 1;
            }
            $registration->save();

            DB::commit();
            toastr()->success('Consulting Successful');
            return redirect()->route('consultation.index');
        }catch (\Exception $exception){
            DB::rollback();
            toastr()->warning('Something Went Wrong!');
            return back();
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
//        return count($searchPatient);
        $not_seen = Registration::with('patient')
            ->where('vitals',1)
            ->where('consult',0)
            ->where('type','Consultation')
            ->get();
        if (count($searchPatient) == 0){
            toastr()->error('Sorry! No Record Found');
            return back();
        }

        if (count($searchPatient) != 0) {

            $recentRegistration = Registration::with('consultation')
                ->where('patient_id', $id)
//                ->where('consult', 1)
                ->where('type', 'Consultation')
                ->latest()->first();


        }

        if (empty($recentRegistration)){
            toastr()->error('Sorry! Patient has NO Registration');
            return back();
        }
        if (!empty($recentRegistration)){

            $recentConsultation = Consultation::where('patient_id', $id)
                ->where('registration_id', $recentRegistration->id)->latest()->first();

            $services = Service::where('patient_id', $id)
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

        return view('pages.consultations.search-result')
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
            ->with('otherMedication',$otherMedication)
            ->with('patient_id',$id)
            ->with('scanned_results',$recentRegistration->scanned_results)
            ->with('lab_results',$recentRegistration->lab_results)
            ->with('not_seen',$not_seen)
            ->with('services',$services);
    }


    public function searchConsultation(Request $request){
        $diagnosis = Diagnose::all();
        $drugs = Drug::all();
        $charges = Charge::all();
        $not_seen = Registration::with('patient')
            ->where('vitals',1)
            ->where('consult',0)
            ->where('type','Consultation')
            ->get();
        $searchPatient= Patient::where('folder_number','like', '%' . $request->input("search") . '%')
            ->orWhere('phone_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('last_name', 'like', '%' . $request->input("search") . '%')->get();
        if (count($searchPatient) == 0){
            toastr()->error('Sorry! No Record Found');
        }


//        return $searchPatient;
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
                ->where('type','Consultation')
//                ->where('consult', 1)
                ->latest()
                ->first();
        }
//        return $recentRegistration;

        if (empty($recentRegistration)){
            toastr()->error('Sorry! Patient has no registration');
            return back();
        }

        if (!empty($recentRegistration)){

//            return substr($recentRegistration->created_at,0,10);

            $recentConsultation = Consultation::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)
                ->whereDate('created_at',substr($recentRegistration->created_at,0,10))
                ->latest()->first();
            $recentVitals = Vital::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->latest()->first();
            $recentMedication = Medication::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->latest()->get();

            $recentPatientDiagnosis = PatientDiagnosis::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->latest()->get();


            $patientDiagnosis = PatientDiagnosis::with('diagnoses')
                ->where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id', $recentRegistration->id)
                ->get();

            $medication = Medication::with('drugs')
                ->where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id', $recentRegistration->id)
                ->get();

//            return $medication;

            $otherMedication = OtherMedication::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id', $recentRegistration->id)->get();

            $getBills = Bill::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->get();
            $getDiagIds =[];
            $getMedIds  =[];

//            return $medication;
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


            $last_visit =[];
            if (count($registration) != 0){
                $last_visit = Registration::with('consultation','medications.drugs','diagnosis.diagnoses')->where('patient_id',$registration[0]->patient_id)->get();
            }


//            return $last_visit;
            $previousRegistration = Registration::where('patient_id',$searchPatient[0]->id)->get();

            /*
             * Calculate detention bill
             */
            //check if patient is detained Or Admitted

//            return $recentRegistration;
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

//            return $recentConsultation;
            $services  = Service::with('charge')->where('patient_id',$recentConsultation->patient_id)
                ->where('registration_id',$recentConsultation->registration_id)
                ->where('type','Consultation')
                ->get();

            return view('pages.consultations.search-result')
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
                ->with('detentionBill',$detentionBill)
                ->with('patient_id',$recentRegistration->patient_id)
                ->with('lab_results',$recentConsultation->lab_results)
                ->with('scanned_results',$recentConsultation->scanned_results)
                ->with('services',$services)
                ->with('last_visit',$last_visit)
                ->with('not_seen',$not_seen);
        }else{
            return view('pages.consultations.search-result')
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
                ->with('otherMedication',$otherMedication)
                ->with('patient_id',$recentRegistration->patient_id)
                ->with('lab_results',$recentConsultation->lab_results)
                ->with('scanned_results',$recentConsultation->scanned_results)
                ->with('not_seen',$not_seen);
        }




    }

    public function discharge($id){
        $registration = Registration::find($id);

        $registration->detain = 2;
        $registration->medication = 2;
        $registration->discharged_date =date('Y-m-d H:i:s');

        $registration->save();



        /*
         * Start Detention Bill Calculation
         */
        //check if patient is detained Or Admitted
        if ( $registration->detain == 0){
            $detentionBill = 0;
        }
        elseif ( $registration->detain == 1){
            //get date admitted
            $dateAdmitted = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $registration->created_at);

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
        elseif ($registration->detain == 2){
            //get date admitted
            $dateAdmitted = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $registration->created_at);

            $today = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $registration->discharged_date);
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
         * End Detention Bill Calculation
         */

        $payment = Payment::where('registration_id',$id)
            ->where('patient_id',$registration->patient_id)
            ->first();
        $payment->grand_total = $payment->grand_total+$detentionBill;
        if ($payment->change > 0){
            $change = $payment->change - $detentionBill;

            if ($change < 0){
                $payment->arrears = $change;
                $payment->change =0;
            }else{
                $payment->change  =$change;
            }
        }else{
            $payment->arrears = floatval(str_replace('-','',$payment->arrears))+$detentionBill;
        }

        $payment->save();

        toastr()->success('Patient Discharged');
        return back();
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
        $registration->consult =1;
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

        $registration->consult =1;
        $registration->save();
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
            ->with('allCharges',$allCharges)
            ->with('lab_results',$consultation->lab_results)
            ->with('scanned_results',$consultation->scanned_results);

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
        DB::beginTransaction();
        try{
            $consultation = Consultation::find($id);
            $consultation->patient_id =$request->input('patient_id');
            $consultation->complains=$request->input('complains');
            $consultation->findings=$request->input('findings');
            $consultation->physical_examination=$request->input('physical_examination');
            $consultation->other_diagnosis=$request->input('other_diagnosis');
            $consultation ->notes=$request->input('notes');
//        $consultation->type="Consultation";
            $consultation->user_id=Auth::user()->id;

            $consultation->save();


            //Upload labs
            if (\Request::has('labs')) {

                for ($i = 0; $i < count($request->file('labs')); $i++) {
                    $file = $request->file('labs')[$i];
                    $extension = $file->getClientOriginalExtension();
                    $files = substr($file->getClientOriginalName(), 0, strpos($file->getClientOriginalName(), '.'));
                    $fileName = $files . '_' . time() . '.' . $extension;

                    $file->move('public/labs', $fileName);

                    $record = new LabResult();
                    $record->patient_id = $request->input('patient_id');
                    $record->registration_id = $request->input('registration_id');
                    $record->consultation_id = $consultation->id;
                    $record->file_name =$fileName;
                    $record->user_id = Auth::user()->id;
                    $record->save();
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

                    $record = new LabResult();
                    $record->patient_id = $request->input('patient_id');
                    $record->registration_id = $request->input('registration_id');
                    $record->consultation_id = $consultation->id;
                    $record->file_name =$scannedFile;
                    $record->user_id = Auth::user()->id;
                    $record->save();
                }
            }

            //Add medications
            if (\Request::has('medications')) {
                foreach ($request->input('medications') as $med) {
                    if (!empty($med['drug_id']) && !empty($med['dosage'])) {

                        //check if medication already exist
                        $check = Medication::where('drugs_id', $med['drug_id'])
                            ->where('patient_id', $request->input('patient_id'))
                            ->where('registration_id', $request->input('registration_id'))
                            ->whereDate('created_at', date('Y-m-d'))
                            ->first();
                        if (empty($check)) {

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
                            $medication->dosage = $med['dosage'];
//                    $medication->days = $med['days'];
//                    $medication->qty = $med['qty'];
                            $medication->qty_dispensed = 0;
                            $medication->type = "Consultation";
                            $medication->user_id = Auth::user()->id;
                            $medication->save();


                        } else {

                            //update
                            $medication = Medication::find($check->id);
                            $medication->patient_id = $request->input('patient_id');
                            $medication->registration_id = $request->input('registration_id');
                            $medication->drugs_id = $med['drug_id'];
                            $medication->dosage = $med['dosage'];
//                    $medication->days = $med['days'];
//                    $medication->qty = $med['qty'];
                            $medication->qty_dispensed = 0;
                            $medication->type = "Consultation";
                            $medication->save();
                        }
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
                        $medication->dosage = $other['other_dosage'];
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
                        $diagnosis->type ="Consultation";
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
                        $service->type ="Consultation";
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

            DB::commit();
            toastr()->success('Record Updated');
            return back();
        }catch (\Exception $exception){
            DB::rollBack();
            toastr()->warning('Something Went Wrong! Please try again');
            return back();
        }

    }

    public function patientRecord(Request $request){
        $not_seen = Registration::with('patient')
            ->where('vitals',1)
            ->where('consult',0)
            ->where('type','Consultation')
            ->get();
        $charges = Charge::all();
        //data contains patient id and the date of registration
        $data= explode(',',$request->input('data'));

        $consultation ="";
        $review ="";

        $getRegistration = Registration::where('patient_id',$data[1])
            ->whereDate('created_at', $data[0])->get();
        $last_visit =[];
        if (count($getRegistration) != 0){
            $last_visit = Registration::with('consultation','medications.drugs','diagnosis.diagnoses')->where('patient_id',$getRegistration[0]->patient_id)->get();
        }
//        return $last_visit;
        $vitals = Vital::with('user')
            ->where('patient_id',$data[1])
            ->where('registration_id',$getRegistration[0]->id)->get();

        if ($getRegistration[0]->consult == 2){
            $review = Review::where('patient_id',$data[1])
                ->whereDate('created_at', $data[0])->first();
            $patientDiagnosis = PatientDiagnosis::with('diagnoses')
                ->where('patient_id',$data[1])
                ->where('registration_id',$getRegistration[0]->id)
                ->where('type','Review')
                ->get();
            $medication = Medication::with('drugs')
                ->where('patient_id',$data[1])
                ->where('registration_id',$getRegistration[0]->id)
                ->where('type','Review')->get();

        }
        else{
            $consultation = Consultation::where('patient_id',$data[1])
                ->whereDate('created_at', $data[0])->first();
            $patientDiagnosis = PatientDiagnosis::with('diagnoses')
                ->where('patient_id',$data[1])
                ->where('registration_id',$getRegistration[0]->id)
                ->where('type','Consultation')
                ->get();
            $medication = Medication::with('drugs')
                ->where('patient_id',$data[1])
                ->where('registration_id',$getRegistration[0]->id)
                ->where('type','Consultation')->get();

        }

        $getBills = Bill::where('patient_id',$data[1])
            ->where('registration_id',$getRegistration[0]->id)->get();

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

//        return $consultation;

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
                ->with('detentionBill',$detentionBill)
                ->with('scanned_results',$getRegistration[0]->scanned_results)
                ->with('lab_results',$getRegistration[0]->lab_results)
                ->with('review',$review)
                ->with('not_seen',$not_seen)
                ->with('charges',$charges)
                ->with('last_visit',$last_visit);
        }else{

            return view('pages.consultations.index1')
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
                ->with('scanned_results',$getRegistration[0]->scanned_results)
                ->with('lab_results',$getRegistration[0]->lab_results)
                ->with('not_seen',$not_seen)
                ->with('charges',$charges)
                ->with('last_visit',$last_visit);
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

        toastr()->success('Medication Updated');
        return redirect()->route('consultation.edit',[$medication->registration_id]);
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

        toastr()->success('Diagnosis Updated');
        return redirect()->route('consultation.edit',[$patient_diagnosis->registration_id]);
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

        toastr()->success('Service Updated');
        return redirect()->route('consultation.edit',[$patient_service->registration_id]);
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
