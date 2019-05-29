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



        $consultation = new Consultation();
        $consultation ->patient_id =$request->input('patient_id');
        $consultation ->complains=$request->input('complains');
        $consultation ->findings=$request->input('findings');
        $consultation ->physical_examination=$request->input('physical_examination');
        $consultation ->other_diagnosis=$request->input('other_diagnosis');
        $consultation ->detain_admit=$request->input('detain_admit');
        $consultation ->labs=implode($scanFileNames,',');
        $consultation ->ultra_sound_scan=implode($labFileNames,',');
        $consultation ->user_id=Auth::user()->id;

        $consultation->save();



        //Add medications
        foreach ($request->input('treatment_medication') as $key){
            $med = new Medication();
            $med->patient_id =$request->input('patient_id');
            $med->drug_id = $key;
            $med->save();
        }


        //add diagnosis
        if (\Request::has('diagnosis')) {

            foreach ($request->input('diagnosis') as $key) {
                $diagnosis = new PatientDiagnosis();
                $diagnosis->patient_id = $request->input('patient_id');
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
            ->with('previousRegistration',$previousRegistration);
    }


    public function searchConsultation(Request $request){

        $diagnosis = Diagnose::all();
        $drugs = Drug::all();

        $searchPatient= Patient::where('folder_number', 'like', '%' . $request->input("search") . '%')
            ->get();


       // return count($searchPatient);
        if (count($searchPatient) == 1){
            //return 'me';
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
                ->with('previousRegistration',$previousRegistration);
        }else{
            return view('pages.consultations.search_result')
                ->with('searchPatient',$searchPatient);
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
        $consultation ->labs=implode($scanFileNames,',');
        $consultation ->ultra_sound_scan=implode($labFileNames,',');
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
                ->whereDate('created_at', $data[0])
                ->get();
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
