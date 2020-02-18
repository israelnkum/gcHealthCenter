<?php

namespace App\Http\Controllers;

use App\Consultation;
use App\Diagnose;
use App\Exports\ConsultationExport;
use App\Exports\PatientExport;
use App\Patient;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
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
        return view('pages.reports.patient-report');
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
        //
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

    //consultation report view
    public function consultation_report(){
        $diagnosis = Diagnose::all();
        return view('pages.reports.consultation-report',compact('diagnosis'));
    }

    public function generateConsultationReport(Request $request, Consultation $consultQuery){

        if ($request->has('btn_export')){
            return Excel::download(new ConsultationExport($request),'Consultation'.time().'.xlsx');
        }else {
            $consultQuery = $consultQuery::query();
            if ($request->has('from') && $request->input('from') != '') {
                $from = \Carbon\Carbon::parse($request->from)->format('Y-m-d') . " 00:00:00";

                if (empty($request->to)){
                    $to =\Carbon\Carbon::today()->format('Y-m-d'). " 00:00:00";;
                }else{
                    $to = \Carbon\Carbon::parse($request->to)->format('Y-m-d') . " 23:59:59";

                }

                $consultQuery->whereBetween('created_at', [$from, $to]);
            }


            if ($request->has('gender') && $request->input('gender') != '') {
                $gender = $request->gender;
                $consultQuery->whereHas('patient', function ($q) use ($gender) {
                    $q->where('gender', $gender);
                });
            }

            if ($request->has('marital_status') && $request->input('marital_status') != '') {

                $marital_status = $request->marital_status;
                $consultQuery->whereHas('patient', function ($q) use ($marital_status) {
                    $q->where('marital_status', $marital_status);
                });
            }

            if ($request->has('religion') && $request->input('religion') != '') {

                $religion = $request->religion;
                $consultQuery->whereHas('patient', function ($q) use ($religion) {
                    $q->where('religion', $religion);
                });
            }

           /* if ($request->has('diagnosis') && $request->input('diagnosis') != '') {
                $consultations = $consultQuery->get();
                foreach ($consultations as $consultation){
                    foreach ($consultation->patient->patientDiagnosis as $diagnosis){
                        $registration_id = $diagnosis->patient_id;
                        $consultQuery->whereHas('patient', function ($q) use ($registration_id) {
                            $q->where('id', $registration_id);
                        });
                    }

                }

                return $consultQuery->get();
            }
            return $consultQuery->get()->count();*/
            $data = $consultQuery->simplePaginate(50);

            session()->flashInput($request->input());
            $diagnosis = Diagnose::all();
            return view('pages.reports.consultation-report',compact('diagnosis'))
                ->with('data', $data)
                ->withInput($request->all);
        }
    }

    public function patient_report(Request $request, Patient $patientQuery){

        if ($request->has('btn_export')){
            return Excel::download(new PatientExport($request),'Patients'.time().'.xlsx');
        }else {
            session()->flashInput($request->input());
            $patientQuery = $patientQuery::query();
            if ($request->has('type') && $request->input('type') != '') {
                $type = $request->type;
                $from = \Carbon\Carbon::parse($request->from)->format('Y-m-d') . " 00:00:00";
                $to = \Carbon\Carbon::parse($request->to)->format('Y-m-d') . " 23:59:59";

                $patientQuery->whereHas('registration', function ($q) use ($type, $from, $to) {
                    $q->where('detain', $type)->whereBetween('created_at', [$from, $to]);
                });
            }
            if ($request->has('gender') && $request->input('gender') != '') {
                $patientQuery->where('gender', $request->input('gender'));
            }
            if ($request->has('marital_status') && $request->input('marital_status') != '') {
                $patientQuery->where('marital_status', $request->input('marital_status'));
            }
            if ($request->has('religion') && $request->input('religion') != '') {
                $patientQuery->where('religion', $request->input('religion'));
            }

            $data = $patientQuery->simplePaginate(50);


            return view('pages.reports.patient-report')
                ->with('data', $data)
                ->withInput($request->all);
        }
    }
}
