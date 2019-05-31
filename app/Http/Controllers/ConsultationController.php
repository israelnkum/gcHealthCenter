<?php

namespace App\Http\Controllers;

use App\Consultation;
use App\Diagnose;
use App\Drug;
use App\Medication;
use App\Patient;
use App\PatientDiagnosis;
use App\Registration;
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
        $drugs = Drug::all();

        return view('pages.consultations.index')
            ->with('registration',$registration)
            ->with('getVitals',$getVitals)
            ->with('diagnosis',$diagnosis)
            ->with('drugs',$drugs)
            ->with('allRegistrations',$allRegistrations);
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

        $getRegistration = Consultation::where('registration_id',$request->input('registration_id'))->latest()->first();


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

                $file->move('public/scan', $scannedFileName);
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
        foreach ($request->input('treatment_medication') as $key){
            $med = new Medication();
            $med->patient_id =$request->input('patient_id');
            $med->registration_id = $request->input('registration_id');
            $med->drug_id = $key;
            $med->save();
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

//        return $recentMedication;

            $getDiagIds =[];
            $getMedIds  =[];

            foreach ($recentMedication as $med){
                array_push($getMedIds,$med->drug_id);
            }
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
            ->with('getImplodedDiagnosis',$getImplodedDiagnosis);
    }


    public function searchConsultation(Request $request){

        $diagnosis = Diagnose::all();
        $drugs = Drug::all();

        $searchPatient= Patient::where('folder_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('phone_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('last_name', 'like', '%' . $request->input("search") . '%')->get();

//        return count($searchPatient);
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
            $recentConsultation = Consultation::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->latest()->first();
            $recentVitals = Vital::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->latest()->first();
            $recentMedication = Medication::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->latest()->get();

            $recentPatientDiagnosis = PatientDiagnosis::where('patient_id',$recentRegistration->patient_id)
                ->where('registration_id',$recentRegistration->id)->latest()->get();

            $getDiagIds =[];
            $getMedIds  =[];

            foreach ($recentMedication as $med){
                array_push($getMedIds,$med->drug_id);
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
                ->with('getImplodedDiagnosis',$getImplodedDiagnosis);
        }else{
            return view('pages.consultations.search_result')
                ->with('searchPatient',$searchPatient)
                ->with('recentRegistration',$recentRegistration)
                ->with('recentConsultation',$recentConsultation)
                ->with('recentVitals',$recentVitals)
                ->with('diagnosis',$diagnosis)
                ->with('drugs',$drugs)
                ->with('getImplodedMedicine',$getImplodedMedicine)
                ->with('getImplodedDiagnosis',$getImplodedDiagnosis);
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
        $data = Consultation::where('registration_id',$id)
            ->where('patient_id',$request->input('patient_id'))->get();


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



        $consultation = Consultation::find($data[0]->id);


//        return $consultation;
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

//      return $consultation;
//        return $patientDiagnosis;
        $getPatientDrugs=[];
        foreach ($medication as $item) {
            array_push($getPatientDrugs,Drug::find($item->drug_id));
        }

        if (\Request::has('fromSearchPage')) {
            $registration = Registration::with('patient','user')
                ->where('patient_id',$data[1])
                ->whereDate('created_at', $data[0])
                ->get();


//            return $registration;
        }else{
            $registration = Registration::with('patient')
                ->where('vitals', 1)
                ->where('consult', 0)
                ->whereDate('created_at', Carbon::today())
                ->limit(1)
//            ->orderBy('created_at','asc')
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
                ->with('getPatientDrugs',$getPatientDrugs)
                ->with('allRegistrations',$allRegistrations);
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
                ->with('getPatientDrugs',$getPatientDrugs)
                ->with('allRegistrations',$allRegistrations);
        }

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
