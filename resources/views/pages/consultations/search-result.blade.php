@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-4 offset-md-2 text-right @if(count($searchPatient) == 1 && count($registration)==0) offset-md-2 @endif grid-margin">
                <form class="needs-validation" novalidate action="{{route('searchConsultation')}}" method="get">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-12 ">
                            <div class="input-group">
                                <input type="text" required class="form-control" name="search" placeholder=" Search by Folder Number or Patient's Last Name or Phone Number">
                                <div class="input-group-prepend">
                                    <button type="submit" class="input-group-text btn"><i class="icon-magnifier"></i></button>
                                </div>
                                <div class="invalid-feedback">
                                    Search by Folder Number or Patient's Last Name or Phone Number
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <form action="{{route('searchConsultation')}}" method="get" class="mb-1 needs-validation form-sub" novalidate>
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-4 text-right">
                            <label class="mt-3 text-danger">Not Seen Patients</label>
                        </div>
                        <div class="col-md-6">
                            <select required name="search" style="width: 100%" class="js-example-basic-single w-100 form-control font-weight-bold">
                                <option value="">Select Record Date</option>
                                @if(count($not_seen)>0)
                                    @foreach($not_seen as $seen)
                                        <option value="{{$seen->patient->folder_number}}">{{$seen->patient->first_name." ".$seen->patient->other_name." ".$seen->patient->last_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="invalid-feedback">
                                Select a patient
                            </div>
                        </div>
                        <div class="col-md-1 ml-0">
                            <button type="submit" class="btn-dark btn btn-sm  p-1 text-center">
                                <i class="icon icon-magnifier"></i>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
            {{-- @if(count($searchPatient) == 1 && count($registration)!=0)
                 <div class="col-md-2">
                     <form action="{{route('patientRecord')}}" method="post" class="mb-1">
                         @csrf
                         <div class="form-group row mb-0">
                             <div class="col-md-10">
                                 <select required name="data" class="js-example-basic-single w-100 form-control">
                                     <option value="">Select Record Date</option>
                                     @if(count($registration)  == 1)
                                         @foreach($allRegistrations as $userRegistration)
                                             <option @if(substr($userRegistration->created_at,0,10) == date('Y-m-d'))disabled @endif value="{{substr($userRegistration->created_at,0,10).",".$userRegistration->patient_id}}">{{substr($userRegistration->created_at,0,10)}}</option>
                                         @endforeach
                                     @endif
                                 </select>
                                 <div class="invalid-feedback">
                                     Select a date
                                 </div>
                             </div>
                             <div class="col-md-1 ml-0">
                                 <button type="submit" class="btn-light btn btn-sm btn-rounded p-1 text-center"><i class="icon-magnifier"></i></button>
                             </div>
                         </div>
                     </form>
                 </div>
             @endif--}}
        </div>

        @if(count($searchPatient)>1)
            <div class="row">
                <div class="col-md-12">
                    <h4 class="display-4 text-info">Search Results</h4>
                </div>

                @foreach($searchPatient as $dat)
                    <div class="col-md-4 grid-margin">
                        <div class="card">
                            <h4 class="card-title mt-0"></h4>
                            <div class="card-body">
                                <h6 class=" text-uppercase mb-0">{{$dat->first_name." ".$dat->other_name." ".$dat->last_name}}</h6>
                                {{--<div class="d-flex justify-content-between align-items-center">
                                    <a href="{{route('consultation.show',$dat->id)}}" style="text-decoration: none" class="">

                                        <div class="d-inline-block pt-3">
                                            <div class="d-md-flex">
                                                <h5 class="mb-0 text-uppercase"><span class="text-danger">Folder Number:</span> {{$dat->folder_number}}</h5>
                                            </div>
                                            <small class="text-gray">{{$dat->phone_number}}</small>
                                        </div>
                                    </a>
                                    <div class="d-inline-block">
                                        <div class=" px-4 py-2 rounded">
                                            <a href="{{route('patients.edit',$dat->id)}}" class="text-dark" style="text-decoration: none;"><i class="icon-note icon-lg"></i></a>
                                        </div>
                                    </div>
                                </div>--}}
                                <form class="needs-validation" novalidate action="{{route('consultation.show',$dat->id)}}" method="get">
                                    @csrf
                                    <div class="d-inline-block pt-3">
                                        <div class="d-md-flex">
                                            <h5 class="mb-0 text-uppercase"><span class="text-danger">Folder Number:</span> {{$dat->folder_number}}</h5>
                                        </div>
                                        <small class="text-gray">{{$dat->phone_number}}</small>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-12 text-right">
                                            <input type="hidden"  class="form-control" name="search" value="{{$dat->folder_number}}">
                                            <button type="submit" class="btn btn-info">
                                                <i class="icon icon-book-open"></i> open
                                            </button>
                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif(count($searchPatient) == 1)
            {{--
            Check if patient has registered today
             if yes then add consultation
             --}}

            @if(count($registration) == 1)
                <div class="row">
                    <div class ="@if(\Request::is('patientRecord'))col-md-6 @else col-md-6 @endif grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="accordion accordion-bordered" id="accordion-2" role="tablist">
                                    @foreach($registration as $registered)
                                        <div class="row">
                                            <div class="col-md-12 text-right mb-1">
                                                <a href="{{route('view-old-records',$registered->patient->id)}}" class="badge badge-pill badge-dark"><i class="icon icon-folder-alt"></i> View Old Record</a>
                                            </div>
                                        </div>
                                        <div class="card-header" role="tab" id="heading-4">
                                            <a data-toggle="collapse" style="text-decoration: none" href="#GC{{$registered->patient->registration_number}}" aria-expanded="false" aria-controls="collapse-4">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h2 class="mb-1 text-primary" style="font-size: 25px; font-weight: 600">
                                                            <i class="icon-folder mr-1"></i> {{$registered->patient->folder_number}}
                                                        </h2>
                                                        <h5 class="mb-1 text-danger">
                                                            <i class="icon-user mr-1"></i>
                                                            {{$registered->patient->title." ".$registered->patient->first_name." ".$registered->patient->other_name." ".$registered->patient->last_name}}
                                                        </h5>
                                                        <small class="text-muted mb-0" ><i class="icon-phone mr-1"></i>{{$registered->patient->phone_number}}</small>
                                                    </div>
                                                    <div class="col-md-6 text-right">
                                                        Date Of Birth: <span class="font-weight-bold">{{$registered->patient->date_of_birth}}</span><br>
                                                        Age: <span class="font-weight-bold">{{$registered->patient->age}}</span><br>
                                                        Gender: <span class="font-weight-bold">{{$registered->patient->gender}}</span><br>
                                                        Marital Status: <span class="font-weight-bold">{{$registered->patient->marital_status}}</span><br>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h6 class="card-title">Vital Signs</h6>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <h6>Blood Pressure(BP) - <span class="text-danger">{{$getVitals[0]->blood_pressure}} mmHg</span></h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <div class="form-group">
                                                            <h6>Weight - <span class="text-danger">{{$getVitals[0]->weight}} kg</span></h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <h6>Temperature - <span class="text-danger">{{$getVitals[0]->temperature}} °c</span></h6>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 text-right">
                                                        <div class="form-group">
                                                            <h6>Pulse - <span class="text-danger">{{$getVitals[0]->pulse}} bpm</span></h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <h6>Glucose - <span class="text-danger">{{$getVitals[0]->glucose}} mol</span></h6>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 text-right">
                                                        <div class="form-group">
                                                            <h6>RDT - <span class="text-danger">{{$getVitals[0]->RDT}}</span></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div id="GC{{$registered->patient->registration_number}}" class="collapse show" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-2">
                                            <div class="card-body">
                                                {{--  New Consultaion Form --}}
                                                <form class="needs-validation" enctype="multipart/form-data" id="consultation_form" novalidate method="post" action="{{route('consultation.store')}}">
                                                    @csrf
                                                    <input required type="hidden" name="registration_id" value="{{$registered->id}}">
                                                    <input required type="hidden" name="patient_id" value="{{$registered->patient->id}}">
                                                    <input required type="hidden" name="registration_number" value="{{$registered->patient->registration_number}}">
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label for="complains" class="text-info">Complains</label>

                                                            <textarea required name="complains" class="form-control" id="complains" rows="10"></textarea>
                                                            <div class="invalid-feedback">
                                                                Complains is
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6">
                                                            <label for="physical_examination" class="text-info">Physical Examination</label>
                                                            <textarea  required name="physical_examination" class="form-control" id="physical_examination" rows="10"></textarea>
                                                            <div class="invalid-feedback">
                                                                Physical Examination is
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label for="findings" class="text-info">History</label>
                                                            <textarea  name="findings" class="form-control" id="findings" rows="10"></textarea>
                                                            <div class="invalid-feedback">
                                                                History is required
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
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
                                                            <label for="diagnosis" class="text-info mt-3">Other Diagnosis</label>
                                                            <textarea placeholder="Other Diagnosis" rows="3" class="form-control" id="other_diagnosis_text" name="other_diagnosis"></textarea>
                                                            <div class="invalid-feedback">
                                                                Diagnosis is required
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-7" >
                                                            <label for="" class="text-info">Drugs</label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="" class="text-info">Dosage</label>
                                                        </div>
                                                        {{--<div class="col-md-1">
                                                            <label for="" class="text-info">Days</label>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label for="" class="text-info">Qty</label>
                                                        </div>--}}
                                                    </div>
                                                    <div class="repeater">
                                                        <div data-repeater-list="medications">
                                                            <div data-repeater-item class="mb-2">
                                                                <div class="row">
                                                                    <div class="col-md-7">
                                                                        <select class="selectMedicine col-12 form-control mr-1"    name="drug_id" id="drug_id">
                                                                            <option value=""></option>
                                                                            @foreach($drugs as $drug)
                                                                                <option value="{{$drug->id}}"> {{$drug->name}} - ({{$drug->drug_type->name}}) </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <div class="invalid-feedback">
                                                                            Drug is required
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        {{-- <input type="text" name="dosage" id="dosage"  class="form-control col-12 ml-1">--}}
                                                                        {{--<select required class="selectMedicine col-12 form-control mr-1 dosage"    name="dosage" id="dosage">
                                                                            <option value=""></option>
                                                                            <option value="3tid">tid</option>
                                                                            <option value="2bd">bd</option>
                                                                            <option value="1nocte">nocte</option>
                                                                            <option value="1stat">stat</option>
                                                                            <option value="1dly">dly</option>
                                                                        </select>--}}
                                                                        <input  list="dosage" class="form-control" placeholder="Dosage" name="dosage">
                                                                        <datalist id="dosage">
                                                                            <option value="tid">
                                                                            <option value="bd">
                                                                            <option value="nocte">
                                                                            <option value="stat">
                                                                            <option value="dly">
                                                                            <option value="btl">
                                                                            <option value="course">
                                                                        </datalist>
                                                                        <div class="invalid-feedback">
                                                                            Dosage is required
                                                                        </div>
                                                                    </div>
                                                                    {{--<div class="col-md-1">
                                                                        <input type="number"  name="days" value="0" style="width: 70px;"  placeholder="Days"  class="form-control days">
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <input type="number"  name="qty" value="0" style="width: 70px;" placeholder="Qty"  class="form-control qty">
                                                                    </div>--}}
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
                                                        <div data-repeater-list="other-medications">
                                                            <div data-repeater-item class="mb-2">
                                                                <div class="row">
                                                                    <div class="col-md-7">
                                                                        <input type="text" title="Drug Name" placeholder="Enter Drug name" name="other_medication" id="other_medication"  class="form-control col-12 ml-1">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        {{--                                                                    <input type="text" name="other_dosage" id="other_dosage" placeholder="Dosage"  class="form-control col-12 ml-1">--}}
                                                                        {{-- <select  class="selectMedicine col-12 form-control mr-1"    name="other_dosage" id="other_dosage">
                                                                             <option value=""></option>
                                                                             <option value="3tid">tid</option>
                                                                             <option value="2bd">bd</option>
                                                                             <option value="1nocte">nocte</option>
                                                                             <option value="1stat">stat</option>
                                                                             <option value="1dly">dly</option>
                                                                         </select>
             --}}
                                                                        <input  list="other_dosage" class="form-control" placeholder="Dosage" name="other_dosage">
                                                                        <datalist id="other_dosage">
                                                                            <option value="tid">
                                                                            <option value="bd">
                                                                            <option value="nocte">
                                                                            <option value="stat">
                                                                            <option value="dly">
                                                                            <option value="btl">
                                                                            <option value="course">
                                                                        </datalist>
                                                                        <div class="invalid-feedback">
                                                                            Dosage is
                                                                        </div>
                                                                    </div>
                                                                    {{--<div class="col-md-2">
                                                                        <input type="number" name="other_days" style="width: 70px;" min="1" placeholder="Days" class="form-control">
                                                                    </div>--}}
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
                                                                <div class="col-md-12 mb-3">
                                                                    <label class="text-info">Select <u><b>Service</b></u></label>
                                                                    <select  class="col-12 form-control mr-1 selectMedicine p-3" multiple   name="service[]" id="service">
                                                                        <option value=""></option>
                                                                        @foreach($charges as $charge)
                                                                            @if($charge->name != "Insured" && $charge->name !="Detain/Admit" && $charge->name != "Non-Insured" && $charge->name != "Consultation")
                                                                                <option value="{{$charge->id.",".$charge->name}}">{{$charge->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <blockquote class="blockquote">
                                                                        <div class="form-check">
                                                                            <label class="form-check-label">
                                                                                <input type="checkbox" name="detain_admit" class="form-check-input">
                                                                                Detain/Admit Patient
                                                                            </label>
                                                                        </div>
                                                                    </blockquote>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label for="findings" class="text-info">Note</label>
                                                            <textarea style="font-size: 15px"   name="notes" class="form-control font-weight-bold" id="notes" rows="4"></textarea>
                                                            <div class="invalid-feedback">
                                                                Note is required
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-5">
                                                        <div class="col-sm-12 text-right">
                                                            <button class="btn btn-primary" type="submit" id="btn_finalize">Finalize</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                {{--End New Consultaion Form--}}
                                            </div>
                                        </div>

                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--Last Visit--}}
                    <div class ="col-md-6 grid-margin stretch-card">
                        <div class="card-body">
                            <h4 class="card-title">Last Visit</h4>
                            <div class="accordion accordion-bordered" id="accordion-3" role="tablist">
                                <div>

                                    @foreach($last_visit as $visit)
                                        {{--                                {{substr($visit->created_at,0,10)}}--}}
                                        @if(date('Y-m-d') != substr($visit->created_at,0,10))
                                            <div class="card-header" role="tab" id="heading-4">
                                                <a style="text-decoration: none" data-toggle="collapse" href="#GC{{$visit->id}}" aria-expanded="false" aria-controls="collapse-4">
                                                    <h5 class="mb-1 text-dark">
                                                        <i class="icon-calendar mr-1"></i>
                                                        {{substr($visit->created_at,0,10)}}
                                                    </h5>
                                                </a>

                                            </div>
                                            <div id="GC{{$visit->id}}" class="collapse" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-3">
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach($visit->consultation as $consult)
                                                        <div class="col-md-6">
                                                            <label class="text-info">Complains</label>
                                                            <blockquote class="blockquote" >
                                                                <small>{{$consult->complains}}</small>
                                                            </blockquote>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="text-info">Physical Examination</label>
                                                            <blockquote class="blockquote">
                                                                <small>{{$consult->physical_examination}}</small>
                                                            </blockquote>
                                                        </div>
                                                        @if($consult->findings != "")
                                                            <div class="col-md-6">
                                                                <label class="text-info">History</label>
                                                                <blockquote class="blockquote" >
                                                                    <small>{{$consult->findings}}</small>
                                                                </blockquote>
                                                            </div>
                                                        @endif
                                                        @if($consult->diagnosis != "")
                                                            <div class="col-md-6">
                                                                <label class="text-info">Diagnosis</label>
                                                                <blockquote class="blockquote">
                                                                    <small>{{$consult->diagnosis}}</small>
                                                                </blockquote>
                                                            </div>
                                                        @endif
                                                        @endforeach
                                                        <div class="col-md-6">
                                                            <label class="text-info">Medication</label>
                                                            <blockquote class="blockquote">
                                                                <small>
                                                                    {{--                                                            @php($medication[] = $visit->medication)--}}
                                                                    <ul>
                                                                        @foreach($visit->medications as $med)
                                                                            <li><small>{{$med->drugs->name}}</small></li>
                                                                        @endforeach
                                                                    </ul>
                                                                </small>
                                                            </blockquote>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="text-info">Diagnosis</label>
                                                            <blockquote class="blockquote">
                                                                <small>
                                                                    {{--                                                            @php($medication[] = $visit->medication)--}}
                                                                    <ul>
                                                                        @foreach($visit->diagnosis as $med)
                                                                            <li><small>{{$med->diagnoses->name}}</small></li>
                                                                        @endforeach
                                                                        @if($consult->other_diagnosis != "")
                                                                            <li><small>{{$consult->other_diagnosis}}</small></li>
                                                                        @endif
                                                                    </ul>
                                                                </small>
                                                            </blockquote>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                    @if(\Request::is('patientRecord'))
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title text-info">{{substr($getRegistration[0]->created_at,0,10)}}</h6>
                                    <hr>
                                    {{--Display vitals--}}
                                    @foreach($vitals as $vital)
                                        <h6 class="card-title text-danger">Vital Signs</h6>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <h6 style="font-size: 13px;">Blood Pressure(BP) - <span class="text-danger">{{$vital->blood_pressure}} mmHg</span></h6>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <h6 style="font-size: 13px;">Weight - <span class="text-danger">{{$vital->weight}} kg</span></h6>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h6 style="font-size: 13px;">Temperature - <span class="text-danger">{{$vital->temperature}} °c</span></h6>
                                                </div>
                                            </div>

                                            <div class="col-md-6 text-right">
                                                <div class="form-group">
                                                    <h6 style="font-size: 13px;">Pulse - <span class="text-danger">{{$vital->pulse}} bpm</span></h6>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h6 style="font-size: 13px;">Glucose - <span class="text-danger">{{$vital->glucose}} mol</span></h6>
                                                </div>
                                            </div>

                                            <div class="col-md-6 text-right">
                                                <div class="form-group">
                                                    <h6 style="font-size: 13px;">RDT - <span class="text-danger">{{$vital->RDT}}</span></h6>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    {{-- End Vitals --}}
                                    <hr>
                                    {{--Display consulation--}}
                                    @foreach($consultation as $consult)
                                        <h6 class="card-title text-danger">Consultation</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="text-info">Complains</label>
                                                <blockquote class="blockquote">
                                                    <p class="mb-0">{{$consult->complains}}</p>
                                                </blockquote>
                                            </div>
                                            {{--<div class="col-md-12 p-1">
                                                <label class="text-info">Findings</label>
                                                <blockquote class="blockquote" >
                                                    <p class="mb-0">{{$consult->findings}}</p>
                                                </blockquote>
                                            </div>--}}
                                            <div class="col-md-12">
                                                <label class="text-info">Physical Examination</label>
                                                <blockquote class="blockquote" >
                                                    <p class="mb-0">{{$consult->physical_examination}}</p>
                                                </blockquote>
                                            </div>
                                            <?php
                                            $other_diagnosis =$consult->other_diagnosis;
                                            ?>
                                        </div>
                                    @endforeach
                                    {{--End consulation--}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="text-info">Diagnosis</label>
                                            <blockquote class="blockquote" >
                                                <ul>
                                                    @foreach($patientDiagnosis as $diagnosis)
                                                        <li>{{$diagnosis->diagnoses->name}}</li>
                                                    @endforeach
                                                    @if($other_diagnosis != "")
                                                        <li>{{$other_diagnosis}}</li>
                                                    @endif
                                                </ul>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="text-info">Medications</label>
                                            <blockquote class="blockquote" >
                                                <ul>
                                                    @foreach($getPatientDrugs as $med)
                                                        <li>{{$med->name}}</li>
                                                    @endforeach
                                                </ul>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <h4>Scan Result(s)</h4>
                                    <div class="col-sm-12">


                                    </div>

                                    <h4>Lab Result(s)</h4>
                                    <div class="col-sm-12">

                                        @if($lab_results)
                                            <div id="lightgallery" class="row lightGallery">
                                                @foreach($lab_results as $scan)

                                                    <a href="{{asset('public/labs/'.$scan)}}" class="image-tile"><img src="{{asset('public/labs/'.$scan)}}" alt="{{$scan}}"></a>
                                                @endforeach
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                {{--if patient has not registered today
                    then display recent registration
                    --}}
            @else
                <div class="row">
                    <div class="col-md-2">
                        <h6 class="text-danger">You can access the previous record(s)</h6>
                        @if($previousRegistration)
                            {{--if patient has any previous registration then display form--}}
                            <form action="{{route('patientRecord')}}" method="post" class="mb-1">
                                @csrf
                                <input type="hidden" name="fromSearchPage" value="fromSearchPage">
                                <div class="form-group row mb-0">
                                    <div class="col-md-12">
                                        <select required name="data" class="form-control js-example-basic-single w-100">
                                            <option value="">Select Record Date</option>
                                            @foreach($previousRegistration as $previous)
                                                <option @if(substr($previous->created_at,0,10) == date('Y-m-d'))disabled @endif value="{{substr($previous->created_at,0,10).",".$previous->patient_id}}">{{substr($previous->created_at,0,10)}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Select a date
                                        </div>
                                        <button  type="submit" class="btn-info btn-block text-center btn mt-3 text-left">
                                            <i class="icon-magnifier icon-sm"></i> Search</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                        <a href="{{route('view-old-records',$patient_id)}}" class="badge  badge-dark mt-3"><i class="icon icon-folder-alt"></i> View Old Record</a>
                    </div>
                    <div class="col-md-10">
                        <h4 class="card-title">
                            Recent Registration
                        </h4>
                        @if(!empty($recentVitals))
                            <div class="row">
                                @if($recentRegistration->detain == 1)
                                    <div class="col-md-10 offset-md-2 text-right mb-3">
                                        <div class="row">
                                            <div class="col-md-6 offset-md-4">
                                                <a role="button" href="{{route('records.edit',$recentRegistration->id)}}" class="btn btn-primary pt-1 pb-1 pr-1 pl-1">
                                                    <i class="icon icon-plus"></i> Add Record
                                                </a>
                                                {{--<button data-toggle="modal" data-target="#add_service" role="button" href="{{route('records.edit',$recentRegistration->id)}}" class="btn btn-secondary pt-1 pb-1 pr-1 pl-1">
                                                    <i class="icon icon-plus"></i> Add Service
                                                </button>
                                                <button data-toggle="modal" data-target="#add_drug"  role="button" href="{{route('records.edit',$recentRegistration->id)}}" class="btn btn-dark pt-1 pb-1 pr-1 pl-1">
                                                    <i class="icon icon-plus"></i> Add Med
                                                </button>--}}
                                                <a role="button" href="{{route('view_detention_record',[$recentRegistration->patient_id,$recentRegistration->id])}}?{{Hash::make(time())}}" class="btn btn-info pt-1 pb-1 pr-1 pl-1">
                                                    <i class="icon icon-notebook"></i>View Record
                                                </a>
                                            </div>


                                            {{--Add Service modal--}}
                                            <div class="modal fade" id="add_service" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form action="{{route('add-service-only')}}" method="post" class="needs-validation" novalidate>
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Add Service</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body pt-0 text-left">
                                                                <input type="hidden" value="{{$recentRegistration->patient_id}}" name="patient_id">
                                                                <input type="hidden" value="{{$recentRegistration->id}}" name="registration_id">
                                                                <input type="hidden" name="not_review" value="not_review">
                                                                <div class="row">
                                                                    <div class="col-md-12 mb-3">
                                                                        <label class="text-info">Select Service</label>
                                                                        <select required class="col-12 form-control mr-1 selectMedicine text-left" multiple style="width: 100%;"    name="service[]" id="service_only">
                                                                            <option value="">Nothing Selected</option>
                                                                            @foreach($charges as $charge)
                                                                                @if($charge->name != "Insured" && $charge->name !="Detain/Admit" && $charge->name != "Non-Insured" && $charge->name != "Consultation")
                                                                                    <option value="{{$charge->id.",".$charge->name}}">{{$charge->name}}</option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>

                                                                        <div class="invalid-feedback">
                                                                            Service required
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                    <i class="icon icon-close"></i> Close
                                                                </button>
                                                                <button type="submit" class="btn btn-dark">
                                                                    <i class="icon icon-plus"></i> Add Service
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--End Service Modal--}}


                                            {{--Add Drug modal--}}
                                            <div class="modal fade" id="add_drug" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lgg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Add Medication</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form method="post" novalidate class="needs-validation" action="{{route('add-medication-only')}}">
                                                            @csrf
                                                            <div class="modal-body pt-0">
                                                                <input type="hidden" value="{{$recentRegistration->patient_id}}" name="patient_id">
                                                                <input type="hidden" value="{{$recentRegistration->id}}" name="registration_id">
                                                                <input type="hidden" name="not_review" value="not_review">
                                                                <div class="row">
                                                                    <div class="col-md-7 text-left" >
                                                                        <label for="" class="text-info">Drugs</label>
                                                                    </div>
                                                                    <div class="col-md-2 text-left">
                                                                        <label for="" class="text-info">Dosage</label>
                                                                    </div>
                                                                    <div class="col-md-1 text-left">
                                                                        <label for="" class="text-info">Days</label>
                                                                    </div>
                                                                    <div class="col-md-1 text-left">
                                                                        <label for="" class="text-info">Qty</label>
                                                                    </div>
                                                                </div>
                                                                <div class="repeater">
                                                                    <div data-repeater-list="medications">
                                                                        <div data-repeater-item class="mb-2">
                                                                            <div class="row">
                                                                                <div class="col-md-7">
                                                                                    <select required style="width: 100% " class="selectMedicine col-12 form-control mr-1"    name="drug_id" id="drug_id">
                                                                                        <option value=""></option>
                                                                                        @foreach($drugs as $drug)
                                                                                            <option value="{{$drug->id}}"> {{$drug->name}} - ({{$drug->drug_type->name}}) </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                    <div class="invalid-feedback">
                                                                                        Drug is required
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    {{-- <input type="text" name="dosage" id="dosage"  class="form-control col-12 ml-1">--}}
                                                                                    {{--<select required class="selectMedicine col-12 form-control mr-1 dosage"    name="dosage" id="dosage">
                                                                                        <option value=""></option>
                                                                                        <option value="3tid">tid</option>
                                                                                        <option value="2bd">bd</option>
                                                                                        <option value="1nocte">nocte</option>
                                                                                        <option value="1stat">stat</option>
                                                                                        <option value="1dly">dly</option>
                                                                                    </select>--}}
                                                                                    <input required list="dosage" class="form-control" placeholder="Dosage" name="dosage">
                                                                                    <datalist id="dosage">
                                                                                        <option value="tid">
                                                                                        <option value="bd">
                                                                                        <option value="nocte">
                                                                                        <option value="stat">
                                                                                        <option value="dly">
                                                                                        <option value="btl">
                                                                                        <option value="course">
                                                                                    </datalist>
                                                                                    <div class="invalid-feedback">
                                                                                        Dosage is required
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-1">
                                                                                    <input type="number" required name="days" value="0" style="width: 70px;" min="1" placeholder="Days"  class="form-control days">
                                                                                </div>
                                                                                <div class="col-md-1">
                                                                                    <input type="number" required name="qty" value="0" style="width: 70px;" min="1" placeholder="Qty"  class="form-control qty">
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
                                                                    <div class="col-md-6 text-left" >
                                                                        <label for="" class="text-info">Other Medication(s)</label>
                                                                    </div>
                                                                </div>
                                                                <div class="other-repeater">
                                                                    <div data-repeater-list="other-medications">
                                                                        <div data-repeater-item class="mb-2">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <input type="text" title="Drug Name" placeholder="Enter Drug name" name="other_medication" id="other_medication"  class="form-control col-12 ml-1">
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    {{--                                                                    <input type="text" name="other_dosage" id="other_dosage" placeholder="Dosage"  class="form-control col-12 ml-1">--}}
                                                                                    {{--<select style="width: 100%" class="selectMedicine col-12 form-control mr-1"    name="dosage" id="dosage">
                                                                                        <option value=""></option>
                                                                                        <option value="3tid">tid</option>
                                                                                        <option value="2bd">bd</option>
                                                                                        <option value="1nocte">nocte</option>
                                                                                        <option value="1stat">stat</option>
                                                                                        <option value="1dly">dly</option>
                                                                                    </select>--}}
                                                                                    <input  list="other_dosage" class="form-control" placeholder="Dosage" name="other_dosage">
                                                                                    <datalist id="other_dosage">
                                                                                        <option value="tid">
                                                                                        <option value="bd">
                                                                                        <option value="nocte">
                                                                                        <option value="stat">
                                                                                        <option value="dly">
                                                                                        <option value="btl">
                                                                                        <option value="course">
                                                                                    </datalist>
                                                                                    <div class="invalid-feedback">
                                                                                        Dosage is required
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-2">
                                                                                    <input type="number" name="other_days" style="width: 70px;" min="1" placeholder="Days" class="form-control">
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

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                    <i class="icon icon-close"></i> Close
                                                                </button>
                                                                <button type="submit" class="btn btn-dark">
                                                                    <i class="icon icon-plus"></i> Add Medication
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--End Drug Modal--}}

                                            <div class="col-md-1 text-left">
                                                <form action="{{route('discharge-patient',$recentRegistration->id)}}" method="post">
                                                    @csrf
                                                    <button class="btn btn-success p-1">
                                                        <i class="icon icon-arrow-right-circle"></i>
                                                        Discharge
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($recentRegistration->detain == 2)
                                    <div class="col-md-10 offset-md-2 text-right mb-3">
                                        <a role="button" href="{{route('view_detention_record',[$recentRegistration->patient_id,$recentRegistration->id])}}?{{Hash::make(time())}}" class="btn btn-primary p-2">
                                            <i class="icon icon-notebook"></i> View detention Records
                                        </a>
                                    </div>
                                @endif
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class=" text-uppercase mb-0"><i class="icon icon-user"></i> {{$recentRegistration->patient->first_name." ".$recentRegistration->patient->other_name." ".$recentRegistration->patient->last_name}}</h5>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-inline-block pt-3">
                                                        <div class="d-md-flex">
                                                            <h5 class="mb-0 text-uppercase"><span class="text-danger"><i class="icon icon-folder-alt"></i> Folder Number:</span> {{$recentRegistration->patient->folder_number}}</h5>
                                                        </div>
                                                        <small class="text-gray"><i class="icon icon-phone"></i> {{$recentRegistration->patient->phone_number}}</small>
                                                        <p><i class="icon icon-calendar"></i> {{$recentRegistration->created_at}}</p>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <div class=" px-4 py-2 rounded">
                                                            {{--if patient has no vitals, hide the details form--}}
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <form action="{{route('patientRecord')}}" method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="fromSearchPage" value="fromSearchPage">
                                                                        <button name="data" title="Detailed Info" value="{{substr($previous->created_at,0,10).",".$previous->patient_id}}" class="btn btn-link text-small" style="text-decoration: none;">
                                                                            <i class="icon-info icon-md"></i> Detailed Info
                                                                        </button>
                                                                    </form>
                                                                    <a title="Edit Record" class="ml-4 text-small" href="{{route('consultation.edit',$recentRegistration->id)}}" style="text-decoration: none">
                                                                        <i class="icon icon-note icon-md text-info"></i> Edit Record
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{--                                        {{$recentRegistration->id}}--}}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <h6 class="card-title">Vital Signs</h6>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <h6>Blood Pressure(BP) - <span class="text-danger">{{$recentVitals->blood_pressure}} mmHg</span></h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <div class="form-group">
                                                            <h6>Weight - <span class="text-danger">{{$recentVitals->weight}} kg</span></h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <h6>Temperature - <span class="text-danger">{{$recentVitals->temperature}} °c</span></h6>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 text-right">
                                                        <div class="form-group">
                                                            <h6>Pulse - <span class="text-danger">{{$recentVitals->pulse}} bpm</span></h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <h6>Glucose - <span class="text-danger">{{$recentVitals->glucose}} mol</span></h6>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 text-right">
                                                        <div class="form-group">
                                                            <h6>RDT - <span class="text-danger">{{$recentVitals->RDT}}</span></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h6 class="card-title">Consultation</h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="text-info">Complains</label>
                                                <blockquote class="blockquote">
                                                    <p class="mb-0">{{$recentConsultation->complains}}</p>
                                                </blockquote>


                                                {{-- <label class="text-info">Findings</label>
                                                 <blockquote class="blockquote">
                                                     <p class="mb-0">{{$recentConsultation->findings}}</p>
                                                 </blockquote>--}}

                                                <label class="text-info">Physical Examination</label>
                                                <blockquote class="blockquote">
                                                    <p class="mb-0">{{$recentConsultation->physical_examination}}</p>
                                                </blockquote>

                                                <label class="text-info">History</label>
                                                <blockquote class="blockquote">
                                                    <p class="mb-0">{{$recentConsultation->findings}}</p>
                                                </blockquote>

                                                <label class="text-info">Diagnosis</label>
                                                <blockquote class="blockquote">
                                                    <ul>
                                                        @foreach($patientDiagnosis as $diagnosis)
                                                            <li>{{$diagnosis->diagnoses->name}}</li>
                                                        @endforeach
                                                        @if($recentConsultation->other_diagnosis != "")
                                                            <li>{{$recentConsultation->other_diagnosis}}</li>
                                                        @endif
                                                    </ul>
                                                </blockquote>

                                                @if(!empty($scanned_results))
                                                    <label for="" class="text-info">Scan Result(s)</label>
                                                    <blockquote class="blockquote">

                                                        <div id="lightgallery" class="row lightGallery">
                                                            @foreach($scanned_results as $scan)
                                                                @if($scan->type != "Review")
                                                                    <a href="{{asset('public/scan/'.$scan->file_name)}}" class="image-tile"><img src="{{asset('public/scan/'.$scan->file_name)}}" alt="{{$scan->file_name}}"></a>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </blockquote>
                                                @endif
                                                @if(!empty($lab_results))
                                                    <label for="" class="text-info">Lab Result(s)</label>
                                                    <blockquote class="blockquote">
                                                        <div id="lightgallery-without-thumb" class="row lightGallery">
                                                            @foreach($lab_results as $lab)
                                                                @if($lab->type != "Review")
                                                                    <a href="{{asset('public/labs/'.$lab->file_name)}}" class="image-tile"><img src="{{asset('public/labs/'.$lab->file_name)}}" alt="{{$lab->file_name}}"></a>
                                                                @endif
                                                            @endforeach
                                                        </div>

                                                    </blockquote>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label class="text-info">Notes</label>
                                                <blockquote class="blockquote">
                                                    <p class="mb-0">{{$recentConsultation->notes}}</p>
                                                </blockquote>

                                                <label for="med" class="text-info">Medication</label>
                                                <blockquote class="blockquote">
                                                    <table class="table-borderless table">
                                                        <thead>
                                                        <tr>
                                                            <th>Drug</th>
                                                            <th>Dosage</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($medication as $med)
                                                            <tr>
                                                                <td>{{$med->drugs->name}}</td>
                                                                <td>{{$med->dosage}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </blockquote>
                                                @if(!empty($otherMedication))
                                                    <label class="text-info">Other Medication</label>
                                                    <blockquote class="blockquote">
                                                        <div class="row">
                                                            <table class="table-borderless table">
                                                                <thead>
                                                                <tr>
                                                                    <th>Drug</th>
                                                                    <th>Dosage</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                @foreach($otherMedication as $med)
                                                                    <tr>
                                                                        <td>{{$med->drug}}</td>
                                                                        <td>{{$med->dosage}}</td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                            {{--<table class="table table-borderless">
                                                                <thead>
                                                                <tr>
                                                                    <th>Item</th>
                                                                    <th>Amount</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @php($total = 0)
                                                                @foreach($getBills as $bill)
                                                                    <tr>
                                                                        <td>{{$bill->item}}</td>
                                                                        <td>{{$bill->total_amount_to_pay}}</td>
                                                                    </tr>
                                                                    @php($total +=$bill->total_amount_to_pay)
                                                                @endforeach
                                                                @if($detentionBill)
                                                                    <tr>
                                                                        <td>Detention</td>
                                                                        <td>{{$detentionBill}}.00</td>
                                                                    </tr>
                                                                @endif
                                                                <tr class="bg-primary text-white">
                                                                    <td class="p-2 text-right">
                                                                        Total
                                                                    </td>
                                                                    <td class="p-2">GH₵ {!! $total+$detentionBill !!}</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>--}}

                                                            {{-- <div class="col-md-12 text-right mt-3">
                                                                 <p><b>Prepared By:</b> {{$vital->user->first_name." ".$vital->user->last_name}}.  <b>Time:</b> {{substr($vital->created_at,0,10)}}</p>
                                                             </div>--}}
                                                        </div>
                                                    </blockquote>
                                                @endif
                                            </div>
                                            @if($services && count($services)>0)
                                                <div class="col-md-6">
                                                    <label class="text-info">Services</label>
                                                    <blockquote class="blockquote">
                                                        <ul>
                                                            @foreach($services as $service)
                                                                <li>{{$service->charge->name}}</li>
                                                            @endforeach
                                                        </ul>
                                                    </blockquote>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                    </div>
                </div>
            @endif
        @endif

        @if (count($searchPatient) == 0)
            <div class="card-body text-center">
                <h4 class="card-title"></h4>
                <img class="img-fluid mt-0" src="{{asset('public/images/no_result.png')}}" alt="">
                {{-- <br>
                 <label for="" class="badge badge-danger p-3">Search Again</label>--}}
            </div>
        @endif
    </div>
@endsection
