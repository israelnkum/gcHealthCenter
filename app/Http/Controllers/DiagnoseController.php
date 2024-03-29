<?php

namespace App\Http\Controllers;

use App\Diagnose;
use App\PatientDiagnosis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiagnoseController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $diagnoses = new Diagnose();
        $diagnoses->name = $request->input('diagnoses');
        $diagnoses->description = $request->input('description');
        $diagnoses->user_id = Auth::user()->id;

        $diagnoses->save();

        return redirect('/preferences')->with('success','New Diagnoses Added');
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


  /*
    * Bulk delete Insurance
    */
    public function  bulk_deleteDiagnoses(Request $request){

        $selected_id = $request->input('selected_diagnoses');


        foreach ($selected_id as $value){
            $level = Diagnose::find($value);
            $level->delete();
        }

        return redirect('/preferences')
            ->with('success','Diagnoses Deleted.');

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
        $diagnoses = Diagnose::find($id);
        $diagnoses->name = $request->input('diagnoses');
        $diagnoses->description = $request->input('description');
        $diagnoses->user_id = Auth::user()->id;

        $diagnoses->save();

        return redirect('/preferences')->with('success','Diagnoses Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $patient_diagnosis = PatientDiagnosis::find($id);

        if ($patient_diagnosis->delete()){
            return back()->with('success','Diagnosis Deleted Successfully');
        }
    }
}
