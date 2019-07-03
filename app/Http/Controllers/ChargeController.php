<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Charge;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChargeController extends Controller
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
        $ins = new Charge();
        $ins->name = $request->input('name');
        $ins->amount = $request->input('amount');
        $ins->user_id = Auth::user()->id;

        $ins->save();

        return redirect('/preferences')
            ->with('success','New Charge Added');
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
    public function  bulk_deleteCharge(Request $request){

        $selected_id = $request->input('selected_charges');


        foreach ($selected_id as $value){
            $level = Charge::find($value);
            $level->delete();
        }

        //  toastr()->success('success');
        return redirect('/preferences')
            ->with('success','Charge(s) Deleted');

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
        $ins = Charge::find($id);
        $ins->name = $request->input('name');
        $ins->amount = $request->input('amount');

        $ins->save();

        return redirect('/preferences')
            ->with('success','Charge Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::find($id);
//        return $service
        if ($service->delete()){
            Bill::where('registration_id',$service->registration_id)
                ->where('item_id',$service->charge_id)
                ->where('patient_id',$service->patient_id)
                ->where('created_at',$service->created_at)->delete();

            return back()->with('success','Service Deleted');
        }
    }
}
