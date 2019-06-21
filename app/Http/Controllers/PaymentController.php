<?php

namespace App\Http\Controllers;

use App\Drug;
use App\Medication;
use App\Payment;
use App\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
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


        //update medication table set number dispensed
        if (\Request::has('number_dispensed')) {
            foreach ($request->input('number_dispensed') as $drug_id => $no_dispensed) {
                $med = Medication::where('drugs_id',$drug_id)->first();
                $med->number_dispensed = $no_dispensed;
                $med->save();
            }
        }




        if (\Request::has('dispensed')) {
            foreach ($request->input('dispensed') as $drug_id => $no_dispensed) {
                if ($no_dispensed =="on"){
                    $med = Medication::where('drugs_id',$drug_id)->first();
                    $med->dispensed = 1;
                    $med->save();
                }
            }
        }

        $registration = Registration::find($request->input('registration_id'));
        if (empty($request->input('dispensed')) || count($request->input('dispensed')) != count($request->input('number_dispensed'))){
            $registration->medication =2;
        }else{
            $registration->medication =1;
        }

        $registration->save();


        $payment = new Payment();
        $payment->registration_id = $request->input('registration_id');
        $payment->patient_id = $request->input('patient_id');
        $payment->service_total = $request->input('service_total');
        $payment->drugs_total = $request->input('drugs_total');
        $payment->grand_total = $request->input('grand_total');
        $payment->amount_paid = $request->input('amount_paid');
        $payment->arrears = $request->input('amount_paid')-$request->input('grand_total');
        $payment->user_id = Auth::user()->id;

        $payment->save();



        return redirect()->route('drugs.create')
            ->with('success','Drugs Dispensed');
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
}
