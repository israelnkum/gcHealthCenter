<?php

namespace App\Http\Controllers;

use App\Drug;
use App\Payment;
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

        return $request;
        $drug_amount =[];
        $nhis_amount =[];
        foreach ($request->input('drug_id') as $id){
            $drugs = Drug::where('id',$id)->first();
            array_push($drug_amount,$drugs->retail_price);
            array_push($nhis_amount,$drugs->nhis_amount);
        }


        //Save Payment
        $payment= new Payment();
        $payment->registration_id = $request->input('registration_id');
        $payment->patient_id = $request->input('patient_id');
        $payment->amount_paid = $request->input('amount_paid');
        $payment->drugs_amount = array_sum($drug_amount);
        $payment->nhis_amount = array_sum($nhis_amount);
        $payment->user_id = Auth::user()->id;




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
