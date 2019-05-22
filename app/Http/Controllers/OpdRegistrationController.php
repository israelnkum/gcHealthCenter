<?php

namespace App\Http\Controllers;

use App\Charge;
use App\Registration;
use Illuminate\Http\Request;

class OpdRegistrationController extends Controller
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

        if (\Request::has('register_patient')){
            $charges = Charge::find($request->input('charges'));
            if (\Request::has('insured')){

                $register = new Registration();
                $register->patient_id = $request->input('patient_id');
                $register->isInsured = 1;
                $register->insurance_type = substr($request->input('insurance_type'),0,strpos($request->input('insurance_type'),','));
                $register->insurance_number = $request->input('insurance_number');
                $register->insurance_amount = str_replace(',','',substr($request->input('insurance_type'),strpos($request->input('insurance_type'),',')));
                $register->registration_fee = $charges->amount;

                $register->save();
            }else{
                if ($charges->name == "Insured"){
                    $this->validate($request, [
                        'insurance_type' => 'required',
                        'insurance_number' => 'required',
                    ]);
                }
                $register = new Registration();
                $register->patient_id = $request->input('patient_id');
                $register->isInsured = 0;
                $register->registration_fee = $charges->amount;
                $register->save();
            }
        }

        return redirect()->route('patients.show',[$request->input('patient_id')]);

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
