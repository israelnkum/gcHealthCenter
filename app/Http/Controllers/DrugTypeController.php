<?php

namespace App\Http\Controllers;

use App\DrugType;
use Illuminate\Http\Request;

class DrugTypeController extends Controller
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
        $checkIfExist = DrugType::where('name',$request->input('name'))->count();

        if ($checkIfExist == 1){
            return back()->with('error','Drug Type Already Exist');
        }else {
            $type = new DrugType();

            $type->name = $request->input('name');

            $type->save();
            return redirect('/drugs')
                ->with('success','Drug Type Added');
        }

    }

    public function  bulk_deleteDrugType(Request $request){

        $selected_id = $request->input('selected_types');


        foreach ($selected_id as $value){
            $level = DrugType::find($value);
            $level->delete();
        }

        return redirect('/drugs')
            ->with('success','Drug Type Deleted.');

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
        $type =DrugType::find($id);

        $type->name = $request->input('name');

        $type->save();
        return redirect('/drugs')
            ->with('success','Drug Type Updated');
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
