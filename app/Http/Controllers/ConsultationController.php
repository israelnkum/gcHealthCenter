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
        $drugs = Drug::where('quantity_in_stock','>',0)->get();
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


        $getRegistration = Consultation::where('registration_id',$request->input('registration_id'))
            ->latest()
            ->first();


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
        $consultation ->detain_admit=$request->input('detain_admit');
        $consultation ->labs=implode($labFileNames,',');
        $consultation ->ultra_sound_scan=implode($scanFileNames,',');
        $consultation ->user_id=Auth::user()->id;

        $consultation->save();



        //Add medications
        foreach ($request->input('group-a') as $med) {
            $medication = new Medication();
            $medication->patient_id = $request->input('patient_id');
            $medication->registration_id = $request->input('registration_id');
            $medication->drugs_id = $med['drug_id'];
            $medication->dosage = $med['dosage'];
            $medication->user_id =Auth::user()->id;
            $medication->save();
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
                $diagnosis = new PatientDiagnosis();
                $diagnosis->patient_id = $request->input('patient_id');
                $diagnosis->registration_id = $request->input('registration_id');
                $diagnosis->diagnoses_id = $key;
                $diagnosis->user_id = Auth::user()->id;
                $diagnosis->save();
            }
        }

        $registration = Registration::find($request->input('registration_id'));

        if (\Request::has('service')) {

            $service_charge = Charge::where('name','Consultation')->first();
            if ($registration->isInsured != 1){
                $bill = new Bill();
                $bill->registration_id = $request->input('registration_id');
                $bill->patient_id =$request->input('patient_id');
                $bill->item = "Consultation";
                $bill->amount =$service_charge->amount;
                $bill->insurance_amount =0;
                $bill->total_amount_to_pay=$service_charge->amount;
                $bill->billed_by =Auth::user()->first_name." ".Auth::user()->last_name;
                $bill->save();
            }

            foreach ($request->input('service') as $key) {
                $data = explode(',', $key);
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
                    $bill->amount =$service_charge->amount;
                    $bill->insurance_amount =0;
                    $bill->total_amount_to_pay=$service_charge->amount;
                    $bill->billed_by =Auth::user()->first_name." ".Auth::user()->last_name;
                    $bill->save();
                }
            }

        }




        $registration->consult = 1;
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

        $searchPatient[] = Patient::find($id);
        $recentConsultation="";
        $recentVitals ="";
        $recentRegistration="";
        $getImplodedMedicine ="";
        $getImplodedDiagnosis="";


        if (!empty($searchPatient)) {

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

//        return $recentMedication;

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
            ->with('getBills',$getBills);
    }


    public function searchConsultation(Request $request){

        $diagnosis = Diagnose::all();
        $drugs = Drug::all();
        $charges = Charge::all();
        $searchPatient= Patient::where('folder_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('phone_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('last_name', 'like', '%' . $request->input("search") . '%')->get();

        $recentConsultation="";
        $recentVitals ="";
        $recentRegistration="";
        $getImplodedMedicine ="";
        $getImplodedDiagnosis="";
        if (count($searchPatient) !=0) {
            $recentRegistration = Registration::with('patient')
                ->where('patient_id', $searchPatient[0]->id)
                ->latest()->first();
        }

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

        if (count($searchPatient) == 1){
            $registration = Registration::with('patient')
                ->where('vitals',1)
                ->where('consult',0)
                ->where('patient_id',$searchPatient[0]->id)
                ->whereDate('created_at', Carbon::today())
                ->limit(1)
                ->get();

//            return $registration;

            //get previous registrations
            $previousRegistration = Registration::where('patient_id',$searchPatient[0]->id)->get();

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
                ->with('getBills',$getBills);
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
                ->with('getBills',$getBills);
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

        $getImplodedMedicine = "";
        $getImplodedDiagnosis = "";
        $registration = Registration::find($id);
        $patient = Patient::find($registration->patient_id);

        $consultation = Consultation::where('registration_id',$registration->id)
            ->where('patient_id',$patient->id)->first();

        $vitals = Vital::where('registration_id',$registration->id)
            ->where('patient_id',$patient->id)->first();

        $medications = Medication::with('drugs')->where('patient_id', $patient->id)
            ->where('registration_id', $registration->id)->latest()->get();
        $patientDiagnosis = PatientDiagnosis::with('diagnoses')->where('patient_id',$patient->id)
            ->where('registration_id',$registration->id)->latest()->get();


//        return $patientDiagnosis;
        return view('pages.consultations.edit')
            ->with('registration',$registration)
            ->with('patient',$patient)
            ->with('consultation',$consultation)
            ->with('medications',$medications)
            ->with('patientDiagnosis',$patientDiagnosis)
            ->with('getImplodedMedicine',$getImplodedMedicine)
            ->with('getImplodedDiagnosis',$getImplodedDiagnosis)
            ->with('vitals',$vitals);

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

        $old_medicine= explode(',',$request->input('old_medicine'));

        return $request->input('treatment_medication');
//         $old_medicine;
        //get current registration from the consultation table to update
        $data = Consultation::where('registration_id',$id)
            ->where('patient_id',$request->input('patient_id'))->first();


        $labFileNames = [];
        $scanFileNames =[];

        //check if request has lab result before uploading
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

        //check if request has scan result before uploading
        if (\Request::has('scan')){
            for ($i = 0; $i < count($request->file('scan')); $i++) {
                $file = $request->file('scan')[$i];
                $extension = $file->getClientOriginalExtension();
                $files = substr($file->getClientOriginalName(), 0, strpos($file->getClientOriginalName(), '.'));
                $fileName = $files . '_' . time() . '.' . $extension;

                $file->move('public/scan', $fileName);
                array_push($scanFileNames,$fileName);

            }


        }


        //update consultation table where patient_id and registration equals the current
        $consultation = Consultation::find($data->id);
        $consultation ->complains=$request->input('complains');
        $consultation ->findings=$request->input('findings');
        $consultation ->physical_examination=$request->input('physical_examination');
        $consultation ->other_diagnosis=$request->input('other_diagnosis');
        $consultation ->detain_admit=$request->input('detain_admit');
        $consultation ->labs=implode($labFileNames,',');
        $consultation ->ultra_sound_scan=implode($scanFileNames,',');
        $consultation ->user_id=Auth::user()->id;
        $consultation->save();

        //Add medications
        foreach ($request->input('treatment_medication') as $key){
            $med = new Medication();
            $med->patient_id =$request->input('patient_id');
            $med->registration_id = $id;
            $med->drug_id = $key;
            $med->save();
        }

        //add diagnosis
        if (\Request::has('diagnosis')) {

            foreach ($request->input('diagnosis') as $key) {
                $diagnosis = new PatientDiagnosis();
                $diagnosis->patient_id = $request->input('patient_id');
                $diagnosis->registration_id = $id;
                $diagnosis->diagnoses_id = $key;
                $diagnosis->user_id = Auth::user()->id;
                $diagnosis->save();
            }
        }





        //update registration table, set consultation = 1 to avoid multiple consultations
        $registration = Registration::find($request->input('registration_id'));
        $registration->consult = 1;
        $registration->save();

        return redirect()->route('consultation.index')
            ->with('success','Consulting Success');
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
                ->with('getBills',$getBills);
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
                ->with('getBills',$getBills);
        }

    }




    //EDit patient's medication

    public function editMedication($drug_id, $med_id){
        $drug = Drug::find($drug_id);

        $drugs = Drug::all();

        $medication = Medication::find($med_id);


        return view('pages.consultations.edit-medication')
            ->with('drug',$drug)
            ->with('drugs',$drugs)
            ->with('medication',$medication);
    }

    //EDit patient's diagnosis

    public function editDiagnosis($drug_id, $med_id){
        $drug = Drug::find($drug_id);

        $drugs = Drug::all();

        $medication = Medication::find($med_id);


        return view('pages.consultations.edit-medication')
            ->with('drug',$drug)
            ->with('drugs',$drugs)
            ->with('medication',$medication);
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
