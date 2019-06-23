<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Drug;
use App\Medication;
use App\Payment;
use App\PaymentLogs;
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

        // return array_sum($request->input('number_dispensed'));

        //update medication table set total number to dispense
        if (\Request::has('number_to_dispensed')) {
            foreach ($request->input('number_to_dispensed') as $drug_id => $no_to_dispensed) {
                $med = Medication::where('drugs_id',$drug_id)
                    ->where('registration_id',$request->input('registration_id'))
                    ->where('patient_id',$request->input('patient_id'))->first();
                $med->number_to_dispense = $no_to_dispensed;
                $med->save();
            }
        }

        //update medication table set number dispensed
        if (\Request::has('number_dispensed')) {
            foreach ($request->input('number_dispensed') as $drug_id => $no_dispensed) {
                $med = Medication::where('drugs_id',$drug_id)
                    ->where('registration_id',$request->input('registration_id'))
                    ->where('patient_id',$request->input('patient_id'))->first();
                $med->number_dispensed = $no_dispensed;
                $med->save();
            }
        }

        foreach ($request->input('number_dispensed') as $drug_id => $no_dispensed) {
            $med = Medication::where('drugs_id',$drug_id)
                ->where('registration_id',$request->input('registration_id'))
                ->where('patient_id',$request->input('patient_id'))->first();
            if ($med->number_to_dispense ==  $med->number_dispensed){
                $med->dispensed = 1;
            }else{
                $med->dispensed = 0;
            }

            $med->save();
        }



        $registration = Registration::find($request->input('registration_id'));
        if (array_sum($request->input('number_to_dispensed')) != array_sum($request->input('number_dispensed'))){
            $registration->medication =2;
        }else{
            $registration->medication =1;
        }

        if ($request->input('grand_total') != $request->input('amount_paid')){
            $registration->hasArrears =1;
        }

        $registration->save();


        foreach ($request->input('drug_total') as $drug_id => $total) {
            $drug = Drug::find($drug_id);
            $bill = new Bill();
            $bill->registration_id = $request->input('registration_id');
            $bill->patient_id = $request->input('patient_id');
            $bill->item = $drug->name;
            $bill->item_id = $drug->id;
            $bill->type="Drug";
            $bill->amount = $drug->retail_price;
            $bill->insurance_amount = $drug->nhis_amount;
            $bill->total_amount_to_pay = $total;
            $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
            $bill->save();

            $med = Medication::where('drugs_id',$bill->item_id)
                ->where('registration_id',$bill->registration_id)
                ->where('patient_id',$bill->patient_id)->first();

            $med->bill_id = $bill->id;
            $med->save();
        }



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


        $paymentLogs = new PaymentLogs();
        $paymentLogs->registration_id = $request->input('registration_id');
        $paymentLogs->patient_id = $request->input('patient_id');
        $paymentLogs->service_total = $request->input('service_total');
        $paymentLogs->drugs_total = $request->input('drugs_total');
        $paymentLogs->grand_total = $request->input('grand_total');
        $paymentLogs->amount_paid = $request->input('amount_paid');
        $paymentLogs->arrears = $request->input('amount_paid')-$request->input('grand_total');
        $paymentLogs->user_id = Auth::user()->id;

        $paymentLogs->save();
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

//        return $request;

        //update medication table set total number to dispense
        /* if (\Request::has('number_to_dispensed')) {
             foreach ($request->input('number_to_dispensed') as $drug_id => $no_to_dispensed) {
                 $med = Medication::where('drugs_id',$drug_id)
                     ->where('registration_id',$request->input('registration_id'))
                     ->where('patient_id',$request->input('patient_id'))->first();
                 $med->number_to_dispense = $no_to_dispensed;
                 $med->save();
             }
         }*/

        //update medication table set number dispensed
        if (\Request::has('number_dispensed')) {
            foreach ($request->input('number_dispensed') as $drug_id => $no_dispensed) {
                $med = Medication::where('drugs_id',$drug_id)
                    ->where('registration_id',$request->input('registration_id'))
                    ->where('patient_id',$request->input('patient_id'))->first();
                $med->number_dispensed =$med->number_dispensed+ $no_dispensed;
                $med->save();
            }
        }

        //set dispensed == 1
        foreach ($request->input('number_dispensed') as $drug_id => $no_dispensed) {
            $med = Medication::where('drugs_id',$drug_id)
                ->where('registration_id',$request->input('registration_id'))
                ->where('patient_id',$request->input('patient_id'))->first();
            if ($med->number_to_dispense ==  $med->number_dispensed){
                $med->dispensed = 1;
            }else{
                $med->dispensed = 0;
            }

            $med->save();
        }



        $registration = Registration::find($request->input('registration_id'));
//        if (array_sum($request->input('number_to_dispensed')) != array_sum($request->input('number_dispensed'))){
//            $registration->medication =2;
//        }else{
//            $registration->medication =1;
//        }

        if ($request->input('grand_total') != $request->input('amount_paid')){
            $registration->hasArrears =1;
        }

        $registration->save();


//        foreach ($request->input('drug_total') as $drug_id => $total) {
//            $drug = Drug::find($drug_id);
//            $bill = new Bill();
//            $bill->registration_id = $request->input('registration_id');
//            $bill->patient_id = $request->input('patient_id');
//            $bill->item = $drug->name;
//            $bill->item_id = $drug->id;
//            $bill->type="Drug";
//            $bill->amount = $drug->retail_price;
//            $bill->insurance_amount = $drug->nhis_amount;
//            $bill->total_amount_to_pay = $total;
//            $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
//            $bill->save();
//        }



        if (\Request::has('arrears')){
            $payment = Payment::where('registration_id',$request->input('registration_id'))
                ->where('patient_id',$request->input('patient_id'))->first();

            $payment->amount_paid =$payment->amount_paid+ $request->input('amount_paid');
            $payment->arrears = $payment->arrears+$request->input('amount_paid');
            $payment->user_id = Auth::user()->id;

            $payment->save();

            $paymentLogs = new PaymentLogs();
            $paymentLogs->registration_id = $request->input('registration_id');
            $paymentLogs->patient_id = $request->input('patient_id');
            $paymentLogs->service_total = 0;
            $paymentLogs->drugs_total = 0;
            $paymentLogs->grand_total = $request->input('arrears');
            $paymentLogs->amount_paid = $request->input('amount_paid');
            $paymentLogs->arrears = $request->input('amount_paid')-$request->input('grand_total');
            $paymentLogs->user_id = Auth::user()->id;

            $paymentLogs->save();
        }


        return redirect()->route('drugs.create')
            ->with('success','Drugs Dispensed');
    }


    public function payArrears(Request $request){

        $payment = Payment::find($request->input('payment_id'));
        $payment->amount_paid = $payment->amount_paid+$request->input('amount_paid');
        $arrears =$payment->arrears = $payment->arrears+$request->input('amount_paid');
        $payment->save();


        $paymentLogs = new PaymentLogs();
        $paymentLogs->registration_id = $payment->registration_id;
        $paymentLogs->patient_id = $payment->patient_id;
        $paymentLogs->service_total = 0;
        $paymentLogs->drugs_total = 0;
        $paymentLogs->grand_total = $payment->grand_total;
        $paymentLogs->amount_paid = $request->input('amount_paid');
        $paymentLogs->arrears = $arrears;
        $paymentLogs->user_id = Auth::user()->id;

        $paymentLogs->save();


        $patient = Payment::find($payment->patient_id);
        return redirect()->route('searchPatientForDrugDispersion',$patient->folder_number)
            ->with('success','Arrears Paid');

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
