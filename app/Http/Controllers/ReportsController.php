<?php

namespace App\Http\Controllers;

use App\Patient;
use App\Registration;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.reports.index');
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


    public function patient_report(Request $request){
        $reports = Patient::query();
        if ($request->has('type') && $request->input('type') != ""){
            if ($request->input('type') == "all"){
                $reports = Patient::all();
            }elseif ($request->input('type') == "detained"){
                $reports = Registration::with('patient')->where('detain',1)->get();
            }elseif ($request->input('type') == "discharged"){
                $reports = Registration::with('patient')->where('detain',2)->get();
            }
        }


        if ($request->has('gender') && $request->input('gender') != ""){
            $reports = Patient::where('gender',$request->input('gender'))->get();
        }

        return $reports;
    }
}
