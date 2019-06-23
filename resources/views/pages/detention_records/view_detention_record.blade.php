@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        {{--<div class="row">
            <div class="col-md-6 offset-md-3 stretch-card grid-margin">

            </div>
            <div class="col-md-2 grid-margin stretch-card">
                <form class="needs-validation" novalidate action="{{route('searchConsultation')}}" method="post">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-12 text-right">
                            --}}{{--                            <input type="hidden" value="{{$registration->patient->folder_number}}"  class="form-control" name="search" placeholder=" Search by Folder Number or Patient's Last Name or Phone Number">--}}{{--
                            <button class="btn btn-primary" type="submit">Go Back</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>--}}

        @if(!empty($recentRecord))
            <div class="row">
                <div class="col-md-2 grid-margin stretch-card offset-md-1">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('view_detention')}}" method="post" class="needs-validation" novalidate>
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="">Previous Record</label>
                                        <select class="js-example-basic-single form-control" required name="info" id="">
                                            <option value="">~Select~</option>
                                            @foreach($allRecords as $record)
                                                <option value="{{$record->patient_id.",".$record->registration_id}}">{{substr($record->created_at,0,10)}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Date required
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <button class="btn btn-info btn-block" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class ="col-md-8 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">
                                {{$patient->title." ".$patient->first_name." ".$patient->other_name." ".$patient->last_name}}
                            </h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Complains</label>
                                    <blockquote class="blockquote">
                                        {{$recentRecord->complains}}
                                    </blockquote>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Physical Examination</label>
                                    <blockquote class="blockquote">
                                        {{$recentRecord->physical_examination}}
                                    </blockquote>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Findings</label>
                                    <blockquote class="blockquote">
                                        {{$recentRecord->findings}}
                                    </blockquote>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Other Diagnosis</label>
                                    <blockquote class="blockquote">
                                        {{$recentRecord->other_diagnosis}}
                                    </blockquote>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Lab Result(s)</label>
                                    <blockquote class="blockquote">
                                        {{$recentRecord->labs}}
                                    </blockquote>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Scan Result(s)</label>
                                    <blockquote class="blockquote">
                                        {{$recentRecord->ultra_sound_scan}}
                                    </blockquote>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-5 mb-5 text-right">
                                    <a href="{{$recentRecord->id}}" class="btn btn-success">Edit</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class ="col-md-12 text-center">
                    <h6 class="display-4">Sorry! No record Found</h6>
                    <form class="needs-validation" novalidate action="{{route('searchConsultation')}}" method="post">
                        @csrf
                        <div class="form-group row mb-0">
                            <div class="col-md-12 ">
                                <input type="hidden"  class="form-control" name="search" value="{{$patient->folder_number}}">
                                <button type="submit" class="btn btn-success">
                                    <i class="icon icon-arrow-left-circle"></i> Go Back
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
    {{--   <div class="modal fade" id="edit_record_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-lg" role="document">
               <form method="post" action="{{route('records.update',$record->id)}}" class="needs-validation" novalidate>
                   @csrf
                   <div class="modal-content">
                       <div class="modal-header">
                           <h5 class="modal-title" id="exampleModalLabel">New Patient</h5>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                           </button>
                       </div>
                       <div class="modal-body pt-0">
                           <div class="form-group row">
                               <label for="complains" class="text-info">Complains</label>
                               <div class="col-sm-12">
                                   <textarea required name="complains" class="form-control" id="complains" rows="10"></textarea>
                                   <div class="invalid-feedback">
                                       Complains is
                                   </div>
                               </div>
                           </div>
                           <div class="form-group row">
                               <label for="physical_examination" class="text-info">Physical Examination</label>
                               <div class="col-sm-12">
                                   <textarea  required name="physical_examination" class="form-control" id="physical_examination" rows="10"></textarea>
                                   <div class="invalid-feedback">
                                       Physical Examination is
                                   </div>
                               </div>
                           </div>
                           <div class="form-group row">
                               <div class="col-sm-12">
                                   <label for="findings" class="text-info">Findings</label>
                                   <textarea required name="findings" class="form-control" id="findings" rows="10"></textarea>
                                   <div class="invalid-feedback">
                                       Finding is
                                   </div>
                               </div>
                           </div>
                           <div class="form-group row">
                               <div class="col-sm-12">
                                   <label for="diagnosis" class="text-info">Diagnosis</label>
                                   <select  class="form-control js-example-basic-multiple" multiple="multiple" style="width: 100%" name="diagnosis[]" id="select_diagnosis">
                                       <option value="">Select Diagnosis</option>
                                       @foreach($diagnosis as $diag)
                                           <option value="{{$diag->id}}">{{$diag->name}}</option>
                                       @endforeach
                                   </select>
                                   <div class="invalid-feedback">
                                       Diagnosis is required
                                   </div>
                               </div>
                           </div>
                           <div class="form-group row">
                               <div class="col-sm-12">
                                   <textarea placeholder="Other Diagnosis" class="form-control" id="other_diagnosis_text" name="other_diagnosis"></textarea>
                                   <div class="invalid-feedback">
                                       Diagnosis is required
                                   </div>
                               </div>
                           </div>
                           <div class="row">
                               <div class="col-md-6" >
                                   <label for="" class="text-info">Drugs</label>
                               </div>
                               <div class="col-md-6">
                                   <label for="" class="text-info">Dosage</label>
                               </div>
                           </div>
                           <div class="repeater">
                               <div data-repeater-list="group-a">
                                   <div data-repeater-item class="mb-2">
                                       <div class="row">
                                           <div class="col-md-6">
                                               <select  class="selectMedicine col-12 form-control mr-1"   name="drug_id" id="drug_id">
                                                   <option value=""></option>
                                                   @foreach($drugs as $drug)
                                                       <option value="{{$drug->id}}">{{$drug->name}}</option>
                                                   @endforeach
                                               </select>
                                               <div class="invalid-feedback">
                                                   Drug is required
                                               </div>
                                           </div>
                                           <div class="col-md-5">
                                               <input type="text" name="dosage" id="dosage"  class="form-control col-12 ml-1">
                                               <div class="invalid-feedback">
                                                   Dosage is required
                                               </div>
                                           </div>
                                           <div class="col-md-1">
                                               <button data-repeater-delete type="button" class="btn btn-danger p-2 icon-btn ml-2" >
                                                   <i class="icon-close"></i>
                                               </button>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                               <div class="text-right">
                                   <button data-repeater-create type="button" class="btn btn-info icon-btn p-2 mb-2">
                                       <i class="icon-plus"></i>
                                   </button>
                               </div>
                           </div>

                           <div class="row">
                               <div class="col-md-6" >
                                   <label for="" class="text-info">Other Medication(s)</label>
                               </div>
                           </div>
                           <div class="other-repeater">
                               <div data-repeater-list="group-b">
                                   <div data-repeater-item class="mb-2">
                                       <div class="row">
                                           <div class="col-md-6">
                                               <input type="text" title="Drug Name" placeholder="Enter Drug name" name="other_medication" id="other_medication"  class="form-control col-12 ml-1">
                                           </div>
                                           <div class="col-md-5">
                                               <input type="text" name="other_dosage" id="other_dosage" placeholder="Dosage"  class="form-control col-12 ml-1">
                                           </div>
                                           <div class="col-md-1">
                                               <button data-repeater-delete type="button" class="btn btn-danger p-2 icon-btn" >
                                                   <i class="icon-close"></i>
                                               </button>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                               <div class="text-right">
                                   <button data-repeater-create title="New Medication" type="button" class="btn btn-info p-2 icon-btn mb-2">
                                       <i class="icon-plus"></i>
                                   </button>
                               </div>
                           </div>

                           <div class="form-group row">
                               <div class="col-sm-6">
                                   <div class="row">
                                       <div class="col-md-12 mb-3">
                                           <label class="text-info">Upload <u><b>LAB</b></u> Result(s)</label>
                                           <input style="border-radius: 0; border: dashed 1px; padding: 3px;" name="labs[]" type="file"  multiple  class="form-control-file">
                                       </div>
                                       <div class="col-md-12">
                                           <label class="text-info">Upload <u><b>SCAN</b></u> Result(s)</label>
                                           <input style="border-radius: 0; border: dashed 1px; padding: 3px;" name="scan[]" type="file"  multiple  class="form-control-file">

                                       </div>
                                   </div>
                               </div>
                               <div class="col-sm-6">
                                   <div class="row">
                                       <div class="col-md-12">
                                           <label class="text-info">Select <u><b>Service</b></u></label>
                                           <select  class="col-12 form-control mr-1 js-example-basic-multiple" multiple  name="service[]" id="service">
                                               @foreach($charges as $charge)
                                                   @if($charge->name != "Insured" && $charge->name !="Detain/Admit" && $charge->name != "Non-Insured" && $charge->name != "Consultation")
                                                       <option value="{{$charge->id.",".$charge->name}}">{{$charge->name}}</option>
                                                   @endif
                                               @endforeach
                                           </select>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-dismiss="modal">
                               <i class="icon icon-close"></i> Close
                           </button>
                           <button type="submit" id="btn_add_patient" class="btn btn-primary">
                               <i class="icon icon-plus"></i> Add Patient
                           </button>
                       </div>
                   </div>
               </form>
           </div>
       </div>
   --}}
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection