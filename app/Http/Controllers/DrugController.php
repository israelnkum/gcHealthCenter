<?php

namespace App\Http\Controllers;

use App\Drug;
use App\DrugType;
use App\Registration;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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
        $registration = Registration::with('patient')
            ->where('vitals',1)
            ->whereDate('created_at', Carbon::today())
            ->limit(1)
            ->orderBy('created_at','asc')
            ->get();

        return view('pages.pharmacy.dispense')
            ->with('registration',$registration);
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
            $drug->cost_price = $request->input('cost_price');
            $drug->retail_price = $request->input('retail_price');
            $drug->quantity_in_stock = $request->input('receiving_stock');
            $drug->user_id = Auth::user()->id;
            $drug->supplier_id = $request->input('supplier_id');

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

                        $testQuery = Drug::where('name', $name)->first();
                        if(empty($testQuery)){
                            $drug = new Drug();
                            $drug->name = $name;
                            $drug->drug_type_id = $request->input('drug_type_id');
                            $drug->cost_price = $cost_price;
                            $drug->supplier_id = $request->input('supplier_id');
                            $drug->retail_price = $retail_price;
                            $drug->user_id = Auth::user()->id;
                            $drug->save();
                        }
                        else{
                            //update student information if student indexNumber already exist
                            $drug = Drug::where('name', $name)->first();
                            $drug->name = $name;
                            $drug->drug_type_id = $request->input('drug_type_id');
                            $drug->cost_price = $cost_price;
                            $drug->supplier_id = $request->input('supplier_id');
                            $drug->retail_price = $retail_price;
                            $drug->user_id = Auth::user()->id;
                            $drug->save();
                        }
                    }
                }
            } else {
                // return redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'></span> ");
                return redirect('/drugs')->with("error", "Only excel file is accepted!");
            }
        } else {
            // return redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'></span> ");
            return redirect('drugs')->with("error", "Please upload an excel file!");
        }
        return redirect('/drugs')->with("success", " $total Student uploaded successfully");
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
        $drug =Drug::find($id);



        $drug->name = $request->input('name');
        $drug->drug_type_id = $request->input('type_id');
        $drug->cost_price = $request->input('cost_price');
        $drug->retail_price = $request->input('retail_price');
        $drug->quantity_in_stock =$drug->quantity_in_stock+ $request->input('receiving_stock');
        $drug->user_id = Auth::user()->id;
        $drug->supplier_id = $request->input('supplier_id');

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
        //
    }
}
