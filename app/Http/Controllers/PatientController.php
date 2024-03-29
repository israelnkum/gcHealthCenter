<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Charge;
use App\Consultation;
use App\DetentionRecord;
use App\Exports\PatientExport;
use App\Insurance;
use App\LabResult;
use App\OldRecord;
use App\Patient;
use App\Payment;
use App\Registration;
use App\ScannedResult;
use App\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
        $detention_records = 0;

        $patient_registrations =0;
        $charges = Charge::all();
        return view('pages.patients.index')
            ->with('data',$data)
            ->with('insuranceType',$insuranceType)
            ->with('charges',$charges)
            ->with('patient_registrations',$patient_registrations)
            ->with('detention_records',$detention_records);
    }

    //upload old data
    public function upload_old_files(Request $request){

        $patient = Patient::find($request->input('patient_id'));
        $old_file_names =[];
        for ($i = 0; $i < count($request->file('old_records')); $i++) {
            $file = $request->file('old_records')[$i];
            $extension = $file->getClientOriginalExtension();
            $files = substr($file->getClientOriginalName(), 0, strpos($file->getClientOriginalName(), '.'));
            $fileName = $files . '_' . time() . '.' . $extension;

            if (!is_dir('public/old_records/'.$patient->registration_number.'')){
                mkdir('public/old_records/'.$patient->registration_number.'',0777);
            }
            $file->move('public/old_records/'.$patient->registration_number.'', $fileName);

            $record = new OldRecord();
            $record->patient_id = $request->input('patient_id');
            $record->files =$fileName;
            $record->date=$request->input('record_date');
            $record->user_id = Auth::user()->id;
            $record->save();
//            array_push($old_file_names,$fileName);
        }



        toastr()->success('Record Uploaded');
        return back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data=Patient::simplePaginate(50);
        $insuranceType = Insurance::all();


        $charges = Charge::all();
        return view('pages.patients.patients')
            ->with('data',$data)
            ->with('insuranceType',$insuranceType)
            ->with('charges',$charges);
    }

    public function filterPatients(Request $request, Patient $patientQuery){
        if ($request->has('btn_export')){
            return Excel::download(new PatientExport($request),'Patients'.time().'.xlsx');
        }else{
            session()->flashInput($request->input());
            $patientQuery = $patientQuery::query();
            if($request->has('type')&& $request->input('type') != '' )
            {
                $type = $request->type;
                $from =  \Carbon\Carbon::parse($request->from)->format('Y-m-d')." 00:00:00";
                $to = \Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59";

                $patientQuery->whereHas('registration', function ($q) use($type,$from,$to){
                    $q->where('detain', $type)->whereBetween('created_at',[$from,$to]);
                });
            }
            if($request->has('gender')&& $request->input('gender') != '' )
            {
                $patientQuery->where('gender', $request->input('gender'));
            }
            if($request->has('marital_status')&& $request->input('marital_status') != '' )
            {
                $patientQuery->where('marital_status', $request->input('marital_status'));
            }
            if($request->has('religion')&& $request->input('religion') != '' )
            {
                $patientQuery->where('religion', $request->input('religion'));
            }

            $data = $patientQuery->simplePaginate(50);


            return view('pages.patients.patients')
                ->with('data',$data)
                ->withInput($request->all);
        }

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
        if ($request->input('last_visit') >= date('Y-m-d')){
            return back()
                ->withInput()
                ->with('error','Last Visit cannot be greater than or Equal to today\'s date');
        }

        DB::beginTransaction();

        try{
            $patient = new Patient();

            $countLogs = Patient::get()->count();

            if ($countLogs == 0){
                $folderNumber=  $patient->registration_number = substr(date('Y'),'2').'01';
            }else{
                $record = Patient::latest('id')->first();

//            return $record;

                $expNum =$record->folder_number;

                if ($expNum == '' ){
                    $folderNumber=  $patient->registration_number = substr(date('Y'),'2').'1';
                } else {

                    $folder_year = substr($expNum,3,strpos($expNum,'/'));
                    $current_year = substr(date('Y'),2);
                    $current_folder_number = substr($expNum,6);

                    if ($folder_year ==$current_year){
//                    $folderNumber=   substr(date('Y'),'2').'01';
                        $folderNumber=  $current_year."".($current_folder_number+1);
                    }else{
                        $folderNumber=   substr(date('Y'),'2').'01';
                    }
//                return $record;
                }
            }

//        return $folderNumber;

            $patient->registration_number= $folderNumber;
            $patient->folder_number= "GC/".substr($folderNumber,0,2)."/".substr($folderNumber,2);
            $patient->title= $request->input('title');
            $patient->first_name= ucwords($request->input('first_name'));
            $patient->last_name= ucwords($request->input('last_name'));
            $patient->other_name= ucwords($request->input('other_name'));
            $patient->date_of_birth= $request->input('date_of_birth');
            $patient->gender= $request->input('gender');
            $patient->age= Carbon::parse(str_replace('/','-',$request->input('date_of_birth')))->age;
            $patient->marital_status= $request->input('marital_status');
            $patient->other_information= $request->input('other_information');
            $patient->postal_address= strtoupper($request->input('postal_address'));
            $patient->house_number= strtoupper($request->input('house_number'));
            $patient->locality= ucwords($request->input('locality'));
            $patient->phone_number= $request->input('phone_number');
            $patient->occupation= ucwords($request->input('occupation'));
            $patient->religion= $request->input('religion');
            if ($request->has('old_patient')) {
                $patient->old_patient =1;
                $patient->last_visit = $request->input('last_visit');
            }
            $patient->name_of_nearest_relative= ucwords($request->input('name_of_relative'));
            $patient->number_of_nearest_relative= $request->input('relative_phone_number');
            $patient->user_id=Auth::user()->id;


            //  $data = Patient::where('id',$patient->id)->get();
            //check if the incoming request has register patient
            if ($request->has('register_patient')){
                //get the charge information selected
                $charges = Charge::find($request->input('charges'));

                //check if incoming request has insured
                if ($request->has('insured')){
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
                        if ($request->has('old_patient')) {
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

                            $payment = new Payment();

                            $payment->patient_id =$patient->id;
                            $payment->registration_id =$register->id;
                            $payment->drugs_total = 0;
                            $payment->service_total = 0;
                            $payment->grand_total = 0;
                            $payment->amount_paid = 0;
                            $payment->arrears = 0;
                            $payment->change = 0;
                            $payment->user_id = Auth::user()->id;

                            $payment->save();

                            //check if patient is old patient
                            if (!$request->has('old_patient')) {
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
                    if ($request->has('old_patient')) {
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

                        $payment = new Payment();
                        $payment->patient_id =$patient->id;
                        $payment->registration_id =$register->id;
                        $payment->drugs_total = 0;
                        $payment->service_total = 0;
                        $payment->grand_total = 0;
                        $payment->amount_paid = 0;
                        $payment->arrears = 0;
                        $payment->change = 0;
                        $payment->user_id = Auth::user()->id;

                        $payment->save();

                        if ($request->has('old_patient')) {
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
            DB::commit();
            toastr()->success('New Patient Added');
            return redirect()->route('patients.show',[$patient->id]);
        }catch(\Exception $e){
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
        $data = Patient::with('registration')
            ->where('id',$id)
            ->get();
//      return $data;

        $patient_registrations =0;
        $patient_vitals =0;
        if (count($data) == 1){
            $patient_registrations = Registration::where('patient_id',$data[0]->id)->get();
            $patient_vitals = Vital::where('patient_id',$data[0]->id)->get();
            $detention_records = DetentionRecord::where('patient_id',$data[0]->id)->get();

        }

        $insuranceType = Insurance::all();
        $charges = Charge::all();

        return view('pages.patients.index')
            ->with('data',$data)
            ->with('insuranceType',$insuranceType)
            ->with('charges',$charges)
            ->with('patient_vitals',$patient_vitals)
            ->with('patient_registrations',$patient_registrations)
            ->with('detention_records',$detention_records);
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
        DB::beginTransaction();
        try{
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

            toastr()->success('Patient\'s Information Updated');
            return redirect()->route('patients.show',[$id]);
        }catch (\Exception $exception){
            DB::rollback();
            toastr()->warning('Something Went Wrong!');
            return back();
        }
    }


    public function searchPatient(Request $request){
        $data=Patient::where('folder_number','like', '%' . $request->input("search"). '%')
            ->orWhere('phone_number', 'like', '%' . $request->input("search") . '%')
            ->orWhere('last_name', 'like', '%' . $request->input("search") . '%')
            ->get();


//        return $data;
        $patient_registrations =0;
        $patient_vitals =0;
        $detention_records=0;
        if (count($data) == 1){
            $patient_registrations = Registration::where('patient_id',$data[0]->id)->get();
            $patient_vitals = Vital::where('patient_id',$data[0]->id)->get();

            $detention_records = DetentionRecord::where('patient_id',$data[0]->id)->get();
        }

//        return $detention_records;
        $insuranceType = Insurance::all();
        $charges = Charge::all();
        return view('pages.patients.index')
            ->with('data',$data)
            ->with('charges',$charges)
            ->with('insuranceType',$insuranceType)
            ->with('patient_vitals',$patient_vitals)
            ->with('patient_registrations',$patient_registrations)
            ->with('detention_records',$detention_records);
    }


    public function viewOldRecord($id){

        $patient = Patient::find($id);

        $records = OldRecord::where('patient_id',$id)
            ->get()
            ->groupBy('date');

//        return $records;

        if (count($records) == 0){
            toastr()->error('No Records Uploaded for '.$patient->first_name.' '.$patient->other_name.' '.$patient->last_name);
            return back();
        }else{
            return view('pages.patients.view-old-records')
                ->with('patient',$patient)
                ->with('records',$records);
        }

    }


    //upload labs or scan REsult for consultation or detention
    public function uploadLabScanResult(Request $request,$patient_id){


//        return $consultation;
        if ($request->has('labs')) {
            $this->validate($request,[
                'lab_registration_id' => ['required'],
            ]);


            $consultation = Consultation::where('registration_id',$request->input('lab_registration_id'))->first();

            for ($i = 0; $i < count($request->file('labs')); $i++) {
                $file = $request->file('labs')[$i];
                $extension = $file->getClientOriginalExtension();
                $files = substr($file->getClientOriginalName(), 0, strpos($file->getClientOriginalName(), '.'));
                $fileName = $files . '_' . time() . '.' . $extension;

                $file->move('public/labs', $fileName);


                $record = new LabResult();
                $record->patient_id = $patient_id;
                $record->registration_id = $request->input('lab_registration_id');
                $record->consultation_id = $consultation->id;
                $record->file_name =$fileName;
                if ($request->has('lab_review')){
                    $record->type="Review";
                }else{
                    $record->type="Consultation";
                }
                $record->user_id = Auth::user()->id;
                $record->save();
            }
        }


        //Upload Scans
        if ($request->has('scan')){
            $this->validate($request,[
                'scan_registration_id' => ['required'],
            ]);


            $consultation = Consultation::where('lab_registration_id',$request->input('scan_registration_id'))->first();

            for ($i = 0; $i < count($request->file('scan')); $i++) {
                $scannedFile = $request->file('scan')[$i];
                $scannedExtension = $scannedFile->getClientOriginalExtension();
                $scannedFiles = substr($scannedFile->getClientOriginalName(), 0, strpos($scannedFile->getClientOriginalName(), '.'));
                $scannedFileName = $scannedFiles . '_' . time() . '.' . $scannedExtension;

                $scannedFile->move('public/scan', $scannedFileName);

                $record = new ScannedResult();
                $record->patient_id = $patient_id;
                $record->registration_id = $request->input('scan_registration_id');
                $record->consultation_id = $consultation->id;
                $record->file_name =$scannedFileName;
                if ($request->has('scan_review')){
                    $record->type="Review";
                }else{
                    $record->type="Consultation";
                }
                $record->user_id = Auth::user()->id;
                $record->save();
            }
        }

        toastr()->success('Upload Successful');
        return back();
    }


    public function uploadDetentionLabScanResult(Request $request,$patient_id){
//        return $consultation;
        if ($request->has('labs')) {
            $this->validate($request,[
                'lab_registration_id' => ['required'],
            ]);
            for ($i = 0; $i < count($request->file('labs')); $i++) {
                $file = $request->file('labs')[$i];
                $extension = $file->getClientOriginalExtension();
                $files = substr($file->getClientOriginalName(), 0, strpos($file->getClientOriginalName(), '.'));
                $fileName = $files . '_' . time() . '.' . $extension;

                $file->move('public/labs', $fileName);


                $record = new LabResult();
                $record->patient_id = $patient_id;
                $record->registration_id = $request->input('lab_registration_id');
                $record->file_name =$fileName;
                $record->type="Detention";
                $record->user_id = Auth::user()->id;
                $record->save();
            }
        }


        //Upload Scans
        if ($request->has('scan')){
            $this->validate($request,[
                'scan_registration_id' => ['required'],
            ]);
            for ($i = 0; $i < count($request->file('scan')); $i++) {
                $scannedFile = $request->file('scan')[$i];
                $scannedExtension = $scannedFile->getClientOriginalExtension();
                $scannedFiles = substr($scannedFile->getClientOriginalName(), 0, strpos($scannedFile->getClientOriginalName(), '.'));
                $scannedFileName = $scannedFiles . '_' . time() . '.' . $scannedExtension;

                $scannedFile->move('public/scan', $scannedFileName);

                $record = new ScannedResult();
                $record->patient_id = $patient_id;
                $record->registration_id = $request->input('scan_registration_id');
                $record->file_name =$scannedFileName;
                $record->type="Detention";
                $record->user_id = Auth::user()->id;
                $record->save();
            }
        }

        toastr()->success('Upload Successful');
        return back();
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
