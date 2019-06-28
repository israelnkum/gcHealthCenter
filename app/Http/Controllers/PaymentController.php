<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Drug;
use App\DrugArrears;
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
        //update medication table set total number to dispense
        if (\Request::has('drug_id')) {
            for ($i=0; $i<count($request->input('drug_id')); $i++) {
                $med = Medication::where('drugs_id',$request->input('drug_id')[$i])
                    ->where('registration_id',$request->input('registration_id'))
                    ->where('patient_id',$request->input('patient_id'))->first();

                $med->qty_dispensed = $request->input('qty_dispensed')[$i];
                $med->save();

                $findMed = Medication::find($med->id);


                //update dispensed to 1 if qty equal to qty_dispensed
                if ($findMed->qty ==  $findMed->qty_dispensed){
                    $findMed->dispensed = 1;
                }else{
                    $findMed->dispensed = 0;
                }
                $findMed->save();

                //get medication
                $check = Medication::find($findMed->id);

                //check if qty = qty_dispensed and set drug_arrears
                if ($check->qty != $check->qty_dispensed){

                    $findDrug = Drug::find($check->drugs_id);

                    $drugArrears = new DrugArrears();
                    $drugArrears->registration_id = $request->input('registration_id');
                    $drugArrears->patient_id = $request->input('patient_id');
                    $drugArrears->item = $findDrug->name;
                    $drugArrears->dosage=$check->dosage;
                    $drugArrears->unit_of_pricing = $findDrug->unit_of_pricing;
                    $drugArrears->qty = $check->qty;
                    $drugArrears->days = $check->days;
                    $drugArrears->qty_dispensed = $check->qty_dispensed;
                    $register = Registration::find($request->input('registration_id'));

                    if ($register->insured != 1){
                        //check if unit of pricing is blister
                        //then divide the retail price and by 10
                        if ($check->unit_of_pricing == "Blister (x10tabs)"){
                            $drugArrears->amount = $findDrug->retail_price/10;
                            $drugArrears->total_amount_to_pay = ($findDrug->retail_price/10) * (floatval($check->qty)*floatval($check->days));
                        }else{
                            $drugArrears->amount = $findDrug->retail_price;
                            $drugArrears->total_amount_to_pay = ($findDrug->retail_price) * (floatval($check->qty)*floatval($check->days));
                        }
                    }else{
                        //check if unit of pricing is blister
                        //then divide the retail price and by 10
                        if ($check->unit_of_pricing == "Blister (x10tabs)"){
                            $drugArrears->amount = ($findDrug->retail_price-$findDrug->nhis_amount)/10;
                            $drugArrears->insurance_amount = $findDrug->nhis_amount/10;
                            $drugArrears->total_amount_to_pay = (($findDrug->retail_price-$drugs->nhis_amount)/10)*(floatval($check->qty)*floatval($check->days));
                        }else{
                            $drugArrears->amount = $findDrug->retail_price-$findDrug->nhis_amount;
                            $drugArrears->insurance_amount = $findDrug->nhis_amount;
                            $drugArrears->total_amount_to_pay = (($findDrug->retail_price) - ($findDrug->nhis_amount))*(floatval($check->qty)*floatval($check->days));
                        }
                    }

                    $drugArrears->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
                    $drugArrears->save();
                }


                $getDrug = Drug::find($findMed->drugs_id);

                $bill = new Bill();
                $bill->registration_id = $request->input('registration_id');
                $bill->patient_id = $request->input('patient_id');
                $bill->item = $getDrug->name;
                $bill->item_id = $getDrug->id;
                $bill->type="Drug";
                $bill->qty = $check->qty;
                $bill->amount = $request->input('price')[$i];
                $bill->insurance_amount = $request->input('insurance')[$i];
                $bill->total_amount_to_pay = $request->input('drug_total')[$i];
                $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
                $bill->save();

                $update_med = Medication::find($check->id);

                $update_med->bill_id = $bill->id;
                $update_med->save();
            }
        }

        $registration = Registration::find($request->input('registration_id'));
        if ($request->input('grand_total') > $request->input('amount_paid')){
            $registration->hasArrears =1;
            $registration->medication =2;
        }else{
            $registration->medication =1;
            $registration->hasArrears =0;
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

        //update medication table set total number to dispense
        if (\Request::has('drug_id')) {
            for ($i=0; $i<count($request->input('drug_id')); $i++) {
                $med = Medication::where('drugs_id',$request->input('drug_id')[$i])
                    ->where('registration_id',$request->input('registration_id'))
                    ->where('patient_id',$request->input('patient_id'))->first();

                $med->qty_dispensed = $request->input('qty_dispensed')[$i];
                $med->save();

                $findMed = Medication::find($med->id);


                //update dispensed to 1 if qty equal to qty_dispensed
                if ($findMed->qty ==  $findMed->qty_dispensed){
                    $findMed->dispensed = 1;
                }else{
                    $findMed->dispensed = 0;
                }
                $findMed->save();

                //get medication
                $check = Medication::find($findMed->id);

                //check if qty = qty_dispensed and set drug_arrears
                if ($check->qty != $check->qty_dispensed){

                    $findDrug = Drug::find($check->drugs_id);

                    $drugArrears = new DrugArrears();
                    $drugArrears->registration_id = $request->input('registration_id');
                    $drugArrears->patient_id = $request->input('patient_id');
                    $drugArrears->item = $findDrug->name;
                    $drugArrears->dosage=$check->dosage;
                    $drugArrears->unit_of_pricing = $findDrug->unit_of_pricing;
                    $drugArrears->qty = $check->qty;
                    $drugArrears->days = $check->days;
                    $drugArrears->qty_dispensed = $check->qty_dispensed;
                    $register = Registration::find($request->input('registration_id'));

                    if ($register->insured != 1){
                        //check if unit of pricing is blister
                        //then divide the retail price and by 10
                        if ($check->unit_of_pricing == "Blister (x10tabs)"){
                            $drugArrears->amount = $findDrug->retail_price/10;
                            $drugArrears->total_amount_to_pay = ($findDrug->retail_price/10) * (floatval($check->qty)*floatval($check->days));
                        }else{
                            $drugArrears->amount = $findDrug->retail_price;
                            $drugArrears->total_amount_to_pay = ($findDrug->retail_price) * (floatval($check->qty)*floatval($check->days));
                        }
                    }else{
                        //check if unit of pricing is blister
                        //then divide the retail price and by 10
                        if ($check->unit_of_pricing == "Blister (x10tabs)"){
                            $drugArrears->amount = ($findDrug->retail_price-$findDrug->nhis_amount)/10;
                            $drugArrears->insurance_amount = $findDrug->nhis_amount/10;
                            $drugArrears->total_amount_to_pay = (($findDrug->retail_price-$drugs->nhis_amount)/10)*(floatval($check->qty)*floatval($check->days));
                        }else{
                            $drugArrears->amount = $findDrug->retail_price-$findDrug->nhis_amount;
                            $drugArrears->insurance_amount = $findDrug->nhis_amount;
                            $drugArrears->total_amount_to_pay = (($findDrug->retail_price) - ($findDrug->nhis_amount))*(floatval($check->qty)*floatval($check->days));
                        }
                    }

                    $drugArrears->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
                    $drugArrears->save();
                }


                $getDrug = Drug::find($findMed->drugs_id);

                $bill = new Bill();
                $bill->registration_id = $request->input('registration_id');
                $bill->patient_id = $request->input('patient_id');
                $bill->item = $getDrug->name;
                $bill->item_id = $getDrug->id;
                $bill->type="Drug";
                $bill->qty = $check->qty;
                $bill->amount = $request->input('price')[$i];
                $bill->insurance_amount = $request->input('insurance')[$i];
                $bill->total_amount_to_pay = $request->input('drug_total')[$i];
                $bill->billed_by = Auth::user()->first_name . " " . Auth::user()->last_name;
                $bill->save();

                $update_med = Medication::find($check->id);

                $update_med->bill_id = $bill->id;
                $update_med->save();
            }
        }

        $registration = Registration::find($request->input('registration_id'));
        if ($request->input('grand_total') > $request->input('amount_paid')){
            $registration->hasArrears =1;
            $registration->medication =2;
        }else{
            $registration->medication =1;
            $registration->hasArrears =0;
        }

        $registration->save();


        $payment = Payment::where('registration_id',$request->input('registration_id'))
            ->where('patient_id',$request->input('patient_id'))->first();

        $payment->amount_paid =$payment->amount_paid+ $request->input('amount_paid');
        $payment->arrears = str_replace('-','',$payment->arrears)-$request->input('amount_paid');
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

        /*if (\Request::has('arrears')){

            $registration = Registration::find($request->input('registration_id'));
            if ($request->input('grand_total') < $request->input('amount_paid')){
                $registration->hasArrears =1;
                $registration->medication =2;
            }else{
                $registration->hasArrears =0;
                $registration->medication =1;
            }
            $registration->save();
        }*/

        return redirect()->route('drugs.create')
            ->with('success','Drugs Dispensed');
    }


    public function payArrears(Request $request){

        $payment = Payment::find($request->input('payment_id'));
        $payment->amount_paid = $payment->amount_paid+$request->input('amount_paid');
//        $patient->grand_total =$patient->grand_total+$request->input('amount_paid');
        $arrears =$payment->arrears = str_replace('-','',$payment->arrears)-$request->input('amount_paid');
        $payment->save();


        if ($request->input('amount_paid') >0)
        {
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
        }


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
