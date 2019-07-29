<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Drug;
use App\DrugType;
use App\Medication;
use App\OtherMedication;
use App\Payment;
use App\Registration;
use App\Supplier;
use App\Vital;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DrugController extends Controller
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
        $suppliers = Supplier::all();
        $drug_types = DrugType::all();
        $drugs = Drug::with('supplier','drug_type')->get();

//        return $drugs;
        return view('pages.pharmacy.index')
            ->with('suppliers',$suppliers)
            ->with('drug_types',$drug_types)
            ->with('drugs',$drugs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allPatientRegistration ="";
        $vitals="";
        $other_medication="";
        $detentionBill ="";
        $getBills ="";
        $medication="";
        $totalCashSales = Bill::sum('total_amount_to_pay');
        $totalSales = Bill::sum('amount');
        $totalNhisSale = Bill::sum('insurance_amount');


        $registration = Registration::with('patient')
            ->where('vitals',1)
            ->where('consult',1)
            ->where('medication',0)
            ->whereDate('created_at', Carbon::today())
            ->limit(1)
            ->orderBy('created_at','asc')
            ->first();

        if (!empty($registration)){
            $allPatientRegistration = Registration::where('patient_id',$registration->patient_id)->get();

            $vitals = Vital::where('registration_id',$registration->id)
                ->where('patient_id',$registration->patient_id)
                ->latest()->first();

            $medication= Medication::with('drugs')
                ->where('dispensed',0)
                ->where('registration_id',$registration->id)
                ->where('patient_id',$registration->patient_id)
                ->get();

//            return $medication;
            $getBills = Bill::where('patient_id',$registration->patient_id)
                ->where('registration_id',$registration->id)
//                ->where('type','!=','Drug')
                ->get();

     /*       $checkForArrears = Payment::where('patient_id',$registration->patient_id)
//                ->where('arrears','<=','grand_total')
                ->where('arrears','!=',0)
                ->get();

            return $checkForArrears;*/

            //check if patient is detained Or Admitted
            if ( $registration->detain == 0){
                $detentionBill = 0;
            }
            elseif ( $registration->detain == 1){
                //get date admitted
                $dateAdmitted = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $registration->created_at);

                $today = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', date('Y-m-d H:s:i'));
                $detentionDays = $today->diffInDays($dateAdmitted);

                if ($detentionDays < 3){
                    $detentionBill = 20;
                }else{
                    $additionalDays = $detentionDays - 2;
                    $calAdditionalCharges = $additionalDays*5;

                    $detentionBill = $calAdditionalCharges+20;
                }
            }
            //if patient is discharged, then use discharged_date instead of today
            elseif ($registration->detain == 2){
                //get date admitted
                $dateAdmitted = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $registration->created_at);

                $today = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $registration->discharged_date);
                $detentionDays = $today->diffInDays($dateAdmitted);

                if ($detentionDays < 3){
                    $detentionBill = 20;
                }else{
                    $additionalDays = $detentionDays - 2;
                    $calAdditionalCharges = $additionalDays*5;

                    $detentionBill = $calAdditionalCharges+20;
                }
            }
            /*
             * End detention bill calculation
             */


            $other_medication= OtherMedication::where('registration_id',$registration->id)
                ->where('patient_id',$registration->patient_id)
                ->whereDate('created_at', Carbon::today())
                ->latest()->get();
        }


        $drugs = Drug::all()->count();

        return view('pages.pharmacy.drug_dispense')
            ->with('registration',$registration)
            ->with('drugs',$drugs)
            ->with('vitals',$vitals)
            ->with('medication',$medication)
            ->with('totalCashSales',$totalCashSales)
            ->with('totalNhisSale',$totalNhisSale)
            ->with('totalSales',$totalSales)
            ->with('getBills',$getBills)
            ->with('detentionBill',$detentionBill)
            ->with('other_medication',$other_medication)
            ->with('allPatientRegistration',$allPatientRegistration);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkIfExist = Drug::where('name',$request->input('name'))->count();

        if ($checkIfExist == 1){
            return back()->with('error','Drug Already Exist');
        }else {
            $drug = new Drug();
            $drug->name = $request->input('name');
            $drug->drug_type_id = $request->input('type_id');
            $drug->qty_in_stock = $request->input('receiving_stock');
            $drug->unit_of_pricing = $request->input('unit_of_pricing');
            if (\Request::has('no_of_tablet')){
//                $drug->no_of_tablet = $request->input('no_of_tablet');
                $drug->no_of_tablet = $request->input('no_of_tablet');
                $drug->qty_in_tablet = $request->input('receiving_stock')*10;
            }

            $drug->retail_price = $request->input('retail_price');

            $drug->supplier_id = $request->input('supplier_id');
            $drug->cost_price = $request->input('cost_price');

            $drug->nhis_amount = $request->input('nhis_amount');
            $drug->expiry_date = str_replace('/','-',$request->input('expiry_date'));
            $drug->user_id = Auth::user()->id;

            $drug->save();

            return redirect('/drugs')
                ->with('success','Drug Added');
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
        //
    }

    public function  bulk_deleteDrug(Request $request){

        $selected_id = $request->input('selected_drugs');


        foreach ($selected_id as $value){
            $level = Drug::find($value);
            $level->delete();
        }

        return redirect('/drugs')
            ->with('success','Drug Deleted.');

    }




    public function upload_drug(Request $request)
    {
        set_time_limit(36000);
        $valid_exts = array('csv','xls','xlsx'); // valid extensions
        $file = $request->file('file');
        //$name = time() . '-' . $file->getClientOriginalName();
        if (!empty($file)) {
            $ext = strtolower($file->getClientOriginalExtension());
            if (in_array($ext, $valid_exts)) {
                $path = $request->file('file')->getRealPath();
                $data = Excel::load($path, function($reader) {})->get();
                $total=count($data);
                if(!empty($data) && $data->count()){
                    // $user = \Auth::user()->id;
                    foreach($data as $value=>$row)
                    {
                        $name = $row->name;
                        $cost_price = $row->cost_price;
                        $retail_price = $row->retail_price;
                        $receiving_stock=$row->receiving_stock;
                        $nhis_amount=$row->nhis_amount;
                        $tablet_per_blister =$row->tablet_per_blister;
                        if ($row->unit_of_pricing == ""){
                            $unit_of_pricing ="-";
                        }else{
                            $unit_of_pricing=$row->unit_of_pricing;
                        }

                        $expiry_date=$row->expiry_date;

                        $testQuery = Drug::where('name', $name)
                            ->where('drug_type_id',$request->input('drug_type_id'))->first();
                        if(empty($testQuery)){
                            $drug = new Drug();
                            $drug->name = $name;
                            $drug->drug_type_id = $request->input('drug_type_id');
                            $drug->qty_in_stock = $receiving_stock;
                            $drug->unit_of_pricing = $unit_of_pricing;
                            if ($unit_of_pricing == "Blister (x10tabs)"){
                                $drug->no_of_tablet = $tablet_per_blister;
                                $drug->qty_in_tablet = $receiving_stock*$tablet_per_blister;
                            }

                            $drug->retail_price = $retail_price;
                            $drug->supplier_id = $request->input('supplier_id');
                            $drug->cost_price = $cost_price;
                            $drug->nhis_amount = $nhis_amount;
                            $drug->expiry_date = str_replace('/','-',$expiry_date);
                            $drug->user_id = Auth::user()->id;

                            $drug->save();
                        }
                        else{
                            //update student information if student indexNumber already exist
                            $drug = Drug::where('name', $name)->first();
                            $drug->name = $name;
                            $drug->drug_type_id = $request->input('drug_type_id');
                            $drug->qty_in_stock =$drug->qty_in_stock+ $receiving_stock;
                            $drug->unit_of_pricing = $unit_of_pricing;
                            if ($unit_of_pricing == "Blister (x10tabs)"){
                                $drug->no_of_tablet = $tablet_per_blister;
                                $drug->qty_in_tablet =$drug->qty_in_tablet+($receiving_stock*$tablet_per_blister);
                            }

                            $drug->retail_price = $retail_price;
                            $drug->supplier_id = $request->input('supplier_id');
                            $drug->cost_price = $cost_price;
                            $drug->nhis_amount = $nhis_amount;
                            $drug->expiry_date = str_replace('/','-',$expiry_date);
                            $drug->user_id = Auth::user()->id;

                            $drug->save();
                        }
                    }
                }
            } else {
                return redirect('/drugs')->with("error", "Only excel file is accepted!");
            }
        } else {
            // return redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'></span> ");
            return redirect('drugs')->with("error", "Please upload an excel file!");
        }
        return redirect('/drugs')->with("success", " $total Drugs uploaded successfully");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $drugs = Drug::find($id);

        return $drugs;
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
        $drug =Drug::find($id);



        $drug->name = $request->input('name');
        $drug->drug_type_id = $request->input('type_id');
        $drug->qty_in_stock = $request->input('receiving_stock');
        $drug->unit_of_pricing = $request->input('unit_of_pricing');
        if (\Request::has('no_of_tablet')){
//                $drug->no_of_tablet = $request->input('no_of_tablet');
            $drug->no_of_tablet = $request->input('no_of_tablet');
            $drug->qty_in_tablet = $request->input('receiving_stock')*10;
        }

        $drug->retail_price = $request->input('retail_price');

        $drug->supplier_id = $request->input('supplier_id');
        $drug->cost_price = $request->input('cost_price');

        $drug->nhis_amount = $request->input('nhis_amount');
        $drug->expiry_date = str_replace('/','-',$request->input('expiry_date'));
        $drug->user_id = Auth::user()->id;

        $drug->save();
        return redirect('/drugs')
            ->with('success','Drug Updated');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $medication = Medication::find($id);
        if ($medication->delete()){
            Bill::where('registration_id',$medication->registration_id)
                ->where('item_id',$medication->drugs_id)
                ->where('patient_id',$medication->patient_id)
                ->where('created_at',$medication->created_at)->delete();
            return back()->with('success','Medication Updated');
        }
    }
}
