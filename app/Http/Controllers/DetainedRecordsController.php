<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Charge;
use App\Consultation;
use App\DetentionRecord;
use App\Diagnose;
use App\Drug;
use App\Medication;
use App\OtherMedication;
use App\Patient;
use App\PatientDiagnosis;
use App\Payment;
use App\Registration;
use App\Service;
use App\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DetainedRecordsController extends Controller
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
     */
    public function store(Request $request)
    {
        $registration = Registration::find($request->input('registration_id'));

        //Upload labs
        $labFileNames = [];
        $scanFileNames = [];
        if (\Request::has('labs')) {

            for ($i = 0; $i < count($request->file('labs')); $i++) {
                $file = $request->file('labs')[$i];
                $extension = $file->getClientOriginalExtension();
                $files = substr($file->getClientOriginalName(), 0, strpos($file->getClientOriginalName(), '.'));
                $fileName = $files . '_' . time() . '.' . $extension;

                $file->move('public/labs', $fileName);

                array_push($labFileNames, $fileName);
            }
        }


//Upload Scans
        if (\Request::has('scan')) {
            for ($i = 0; $i < count($request->file('scan')); $i++) {
                $scannedFile = $request->file('scan')[$i];
                $scannedExtension = $scannedFile->getClientOriginalExtension();
                $scannedFiles = substr($scannedFile->getClientOriginalName(), 0, strpos($scannedFile->getClientOriginalName(), '.'));
                $scannedFileName = $scannedFiles . '_' . time() . '.' . $scannedExtension;

                $scannedFile->move('public/scan', $scannedFileName);
                array_push($scanFileNames, $scannedFileName);
            }
        }


        $records = new DetentionRecord();
        $records->patient_id = $request->input('patient_id');
        $records->registration_id = $request->input('registration_id');
        $records->complains = $request->input('complains');
        $records->findings = $request->input('findings');
        $records->physical_examination = $request->input('physical_examination');
        $records->other_diagnosis = $request->input('other_diagnosis');
//        $records ->detain_admit=$request->input('detain_admit');
        $records->labs = implode($labFileNames, ',');
        $records->ultra_sound_scan = implode($scanFileNames, ',');
        $records->user_id = Auth::user()->id;
        $records->save();


        //Add medications
        foreach ($request->input('group-a') as $med) {
            if (!empty($med['drug_id']) && !empty($med['dosage'])) {

                //check if medication already exist
                $check = Medication::where('drugs_id', $med['drug_id'])
                    ->where('patient_id', $request->input('patient_id'))
                    ->where('registration_id', $request->input('registration_id'))
                    ->whereDate('created_at', Carbon::today())
                    ->first();
                if (empty($check)) {
                    $drugs = Drug::find($med['drug_id']);

                    //if patient is NOT Insured then insert the drug selling price
                    if ($registration->isInsured != 1) {
                        $bill = new Bill();
                        $bill->registration_id = $request->input('registration_id');
                        $bill->patient_id = $request->input('patient_id');
                        $bill->item = $drugs->name;
                        $bill->item_id = $drugs->id;
                        $bill->type = "Drug";
                        $bill->amount = $drugs->retail_price;
                        $bill->insurance_amount = $drugs->nhis_amount;
                        $bill->total_amount_to_pay = $drugs->retail_price;
                        $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
                        $bill->save();
                    } else {
                        //if patient is Insured the total amount to pay is sellingPrice - NHIS Price
                        $bill = new Bill();
                        $bill->registration_id = $request->input('registration_id');
                        $bill->patient_id = $request->input('patient_id');
                        $bill->item = $drugs->name;
                        $bill->item_id = $drugs->id;
                        $bill->type = "Drug";
                        $bill->amount = $drugs->retail_price;
                        $bill->insurance_amount = $drugs->nhis_amount;
                        $bill->total_amount_to_pay = $drugs->retail_price - $drugs->nhis_amount;
                        $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
                        $bill->save();
                    }

                    $medication = new Medication();
                    $medication->bill_id = $bill->id;
                    $medication->patient_id = $request->input('patient_id');
                    $medication->registration_id = $request->input('registration_id');
                    $medication->drugs_id = $med['drug_id'];
                    $medication->dosage = $med['dosage'];
                    $medication->user_id = Auth::user()->id;
                    $medication->save();
                } else {

                    //update
                    $medication = Medication::find($check->id);
                    $medication->patient_id = $request->input('patient_id');
                    $medication->registration_id = $request->input('registration_id');
                    $medication->drugs_id = $med['drug_id'];
                    $medication->dosage = $med['dosage'];
                    $medication->user_id = Auth::user()->id;
                    $medication->save();
                }
            }
        }


        //Add other  medications
        if (\Request::has('group-b')) {
            foreach ($request->input('group-b') as $other) {
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
                    ->whereDate('created_at', Carbon::today())
                    ->first();
                if (empty($check)) {
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


        if (\Request::has('service')) {
            $service_charge = Charge::where('name', 'Consultation')->first();

            //charge for consultation if only the person is not insured
            if ($registration->isInsured != 1) {
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

            //insert selected service charge
            foreach ($request->input('service') as $key) {
                $data = explode(',', $key);
                $check = Service::where('charge_id', $data[0])
                    ->where('patient_id', $request->input('patient_id'))
                    ->where('registration_id', $request->input('registration_id'))
                    ->whereDate('created_at', Carbon::today())
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


        return back()->with('success', 'New Record Added');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function searchPatientForDrugDispersion(Request $request)
    {
        $totalCashSales = Bill::sum('total_amount_to_pay');
        $totalSales = Bill::sum('amount');
        $totalNhisSale = Bill::sum('insurance_amount');
        $drugs = Drug::all()->count();

        //get patient
        $searchPatient = Patient::where('folder_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('phone_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('last_name', 'like', '%' . $request->input("search") . '%')->first();

        //get patient recent registration
        $recentRegistration = Registration::with('patient')->where('patient_id', $searchPatient->id)->latest()->first();
        $vitals = Vital::where('registration_id', $recentRegistration->id)
            ->where('patient_id', $recentRegistration->patient_id)
            ->latest()->first();



        /*
         * Start Detention Bill Calculation
         */
        //check if patient is detained Or Admitted
        if ( $recentRegistration->detain == 0){
            $detentionBill = 0;
        }elseif ( $recentRegistration->detain == 1){
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
         * End Detention Bill Calculation
         */

        $getBills = Bill::where('patient_id',$recentRegistration->patient_id)
            ->where('registration_id',$recentRegistration->id)
//                ->where('type','!=','Drug')
            ->get();




        if ($recentRegistration->medication == 0){

            $registration = $recentRegistration;
            $medication= Medication::with('bill','drugs')
                ->where('dispensed',0)
                ->where('registration_id',$registration->id)
                ->where('patient_id',$registration->patient_id)
                ->get();

            return view('pages.pharmacy.drug-dispense')
                ->with('registration',$registration)
                ->with('drugs',$drugs)
                ->with('vitals',$vitals)
                ->with('medication',$medication)
                ->with('totalCashSales',$totalCashSales)
                ->with('totalNhisSale',$totalNhisSale)
                ->with('totalSales',$totalSales)
                ->with('getBills',$getBills)
                ->with('detentionBill',$detentionBill);
        }
        elseif($recentRegistration->medication == 1){

            return view('pages.pharmacy.bill')
                ->with('vitals',$vitals)
                ->with('recentRegistration',$recentRegistration)
                ->with('totalCashSales', $totalCashSales)
                ->with('totalNhisSale', $totalNhisSale)
                ->with('totalSales', $totalSales)
                ->with('getBills',$getBills)
                ->with('detentionBill',$detentionBill);
        }
        elseif ($recentRegistration->medication == 2) {
            $medication = Medication::with('bill')->where('registration_id', $recentRegistration->id)
                ->where('patient_id', $recentRegistration->patient_id)
                ->where('dispensed', 0)->get();


//            return $medication;
            $arrears = Payment::where('registration_id', $recentRegistration->id)
                ->where('patient_id', $recentRegistration->patient_id)
                ->first();

            $registration = "";

            return view('pages.pharmacy.dispense-drugs-and-arrears')
                ->with('registration', $registration)
                ->with('drugs', $drugs)
                ->with('vitals', $vitals)
                ->with('medication', $medication)
                ->with('recentRegistration', $recentRegistration)
                ->with('totalCashSales', $totalCashSales)
                ->with('totalNhisSale', $totalNhisSale)
                ->with('totalSales', $totalSales)
                ->with('arrears', $arrears);
        }



    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { $charges = Charge::all();
        $diagnosis = Diagnose::all();
        $drugs = Drug::all();
        $registration = Registration::with('patient')->find($id);
//        return $registration;
        $getVitals = Vital::where('registration_id',$registration->id)
            ->where('patient_id',$registration->patient_id)->get();

        /*
                $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', '2015-5-5 07:30:34');

                $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', '2015-5-8 07:30:35');

                $diff_in_days = $to->diffInDays($from);*/


        return view('pages.detention_records.new_detained_record')
            ->with('getVitals',$getVitals)
            ->with('charges',$charges)
            ->with('diagnosis',$diagnosis)
            ->with('drugs',$drugs)
            ->with('registration',$registration);
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

    public function view_detention_record($patient_id, $registration_id){
        $patient =Patient::find($patient_id);
        $recentRecord = DetentionRecord::where('patient_id',$patient_id)
            ->where('registration_id',$registration_id)
            ->latest()->first();

        $allRecords=DetentionRecord::where('patient_id',$patient_id)
            ->where('registration_id',$registration_id)
            ->get();

        return view('pages.detention_records.view_detention_record')
            ->with('recentRecord',$recentRecord)
            ->with('allRecords',$allRecords)
            ->with('patient',$patient);
    }

    public function view_detention(Request $request){

        $data = explode(',',$request->input('info'));
        $recentRecord = DetentionRecord::where('patient_id',$data[0])
            ->where('registration_id',$data[1])
            ->latest()->first();

        $allRecords=DetentionRecord::where('patient_id',$data[0])
            ->where('registration_id',$data[1])
            ->get();

        $patient =Patient::find($data[0]);


        $charges = Charge::all();
        $diagnosis = Diagnose::all();
        return view('pages.detention_records.view_detention_record')
            ->with('recentRecord',$recentRecord)
            ->with('allRecords',$allRecords)
            ->with('patient',$patient)
            ->with('charges',$charges)
            ->with('diagnosis',$diagnosis);
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
