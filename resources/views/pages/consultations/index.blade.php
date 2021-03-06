@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 offset-md-2 grid-margin">
                <form class="needs-validation form-sub" novalidate action="{{route('searchConsultation')}}" method="get">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-12 ">
                            <div class="input-group">
                                <input required type="text"  class="form-control font-weight-bold" name="search" placeholder=" Search by Folder Number or Patient's Last Name or Phone Number">
                                <div class="input-group-prepend">
                                    <button type="submit" class="input-group-text btn loading"><i class="icon-magnifier"></i></button>
                                </div>
                                <div class="invalid-feedback">
                                    Search by Folder Number or Patient's Last Name or Phone Number
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {{--<div class="col-md-3">
                <form action="{{route('patientRecord')}}" method="post" class="mb-1">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <label style="font-size: 12px">Search by Date</label>
                                </div>
                                <div class="col-md-8">
                                    <select required name="data" style="width: 100%" class="js-example-basic-single w-100 form-control font-weight-bold">
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
                            </div>
                        </div>
                        <div class="col-md-1 ml-0">
                            <button type="submit" class="btn-dark btn btn-sm  p-1 text-center">Search</button>
                        </div>
                    </div>

                </form>

            </div>--}}
            {{--  <div class="col-md-4 text-right grid-margin ">
                  <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#newPatient">
                      <i class="icon icon-plus"></i> New Patient
                  </button>
              </div>--}}
        </div>
        @if(count($registration)  == 0)
            <div class="text-center">
                <h6 class="display-4 text-info">Relax! No Patient in Queue</h6>
            </div>
        @elseif(count($registration)  == 1)
            <div class="row">
                <div class="col-md-12 text-right mb-1">
                    <a href="{{route('view-old-records',$registration[0]->patient_id)}}" class="badge  badge-dark"><i class="icon icon-folder-alt"></i> View Old Record</a>
                </div>
                <div class ="col-md-6 grid-margin stretch-card">
                    <div class="card-body">
                        <div class="accordion accordion-bordered" id="accordion-2" role="tablist">
                            @foreach($registration as $registered)
                                <div class="card-header" role="tab" id="heading-4">
                                    <a data-toggle="collapse" style="text-decoration: none" href="javascript:void(0)" aria-expanded="false" aria-controls="collapse-4">
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
                                            <div class="col-md-8 mb-0">
                                                <h6>Blood Pressure(BP) - <span class="text-danger">{{$getVitals[0]->blood_pressure}} mmHg</span></h6>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <h6>Weight - <span class="text-danger">{{$getVitals[0]->weight}} kg</span></h6>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Temperature - <span class="text-danger">{{$getVitals[0]->temperature}} °c</span></h6>
                                            </div>

                                            <div class="col-md-6 text-right">
                                                <h6>Pulse - <span class="text-danger">{{$getVitals[0]->pulse}} bpm</span></h6>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Glucose - <span class="text-danger">{{$getVitals[0]->glucose}} mol</span></h6>
                                            </div>

                                            <div class="col-md-6 text-right">
                                                <h6>RDT - <span class="text-danger">{{$getVitals[0]->RDT}}</span></h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div id="GC{{$registered->patient->registration_number}}" class="collapse show" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-2">
                                    <div class="card-body">
                                        {{--  New Consultaion Form --}}
                                        <form class="needs-validation form-sub" enctype="multipart/form-data" id="consultation_form" novalidate method="post" action="{{route('consultation.store')}}">
                                            @csrf
                                            <input required type="hidden" name="registration_id" value="{{$registered->id}}">
                                            <input required type="hidden" name="patient_id" value="{{$registered->patient->id}}">
                                            <input required type="hidden" name="registration_number" value="{{$registered->patient->registration_number}}">
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="complains" class="text-info">Complains</label>
                                                    <textarea style="font-size: 15px"  required  name="complains" class="form-control font-weight-bold" id="complains" rows="10"></textarea>
                                                    <div class="invalid-feedback">
                                                        Complains is required
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="physical_examination" class="text-info">Physical Examination</label>
                                                    <textarea style="font-size: 15px"  required  name="physical_examination" class="form-control font-weight-bold" id="physical_examination" rows="10"></textarea>
                                                    <div class="invalid-feedback">
                                                        Physical Examination is required
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 mt-3">
                                                    <label for="findings" class="text-info">History</label>
                                                    <textarea style="font-size: 15px"   name="findings" class="form-control font-weight-bold" id="findings" rows="8"></textarea>
                                                    <div class="invalid-feedback">
                                                        Finding is required
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 mt-3">
                                                    <label for="select_diagnosis" class="text-info">Diagnosis</label>
                                                    <select  class="form-control font-weight-bold js-example-basic-multiple" multiple="multiple" style="width: 100%" name="diagnosis[]" id="select_diagnosis">
                                                        {{--                                                    <option value="">Select Diagnosis</option>--}}
                                                        @foreach($diagnosis as $diag)
                                                            <option value="{{$diag->id}}">{{$diag->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Diagnosis is required
                                                    </div>
                                                    <label for="other_diagnosis_text" class="text-info mt-3">Other Diagnosis</label>
                                                    <textarea style="font-size: 15px"  rows="3" class="form-control font-weight-bold" id="other_diagnosis_text" name="other_diagnosis"></textarea>
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
                                                                <select class="selectMedicine col-12 form-control font-weight-bold mr-1"    name="drug_id" id="drug_id">
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
                                                                {{-- <input type="text" name="dosage" id="dosage"  class="form-control font-weight-bold col-12 ml-1">--}}
                                                                {{--<select required class="selectMedicine col-12 form-control font-weight-bold mr-1 dosage"    name="dosage" id="dosage">
                                                                    <option value=""></option>
                                                                    <option value="3tid">tid</option>
                                                                    <option value="2bd">bd</option>
                                                                    <option value="1nocte">nocte</option>
                                                                    <option value="1stat">stat</option>
                                                                    <option value="1dly">dly</option>
                                                                </select>--}}
                                                                <input list="dosage" class="form-control font-weight-bold" placeholder="Dosage" name="dosage">
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
                                                            {{--   <div class="col-md-1">
                                                                   <input type="number" min="1"  name="days" value="0" style="width: 70px;" placeholder="Days"  class="form-control font-weight-bold days">
                                                               </div>
                                                               <div class="col-md-1">
                                                                   <input type="number" min="1"  name="qty" value="0" style="width: 70px;"  placeholder="Qty"  class="form-control font-weight-bold qty">
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
                                                <div class="col-md-12" >
                                                    <label for="" class="text-info">Other Medication(s)</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <small>Drug</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <small>Dosage</small>
                                                </div>
                                                {{--<div class="col-md-2">
                                                    <small>Days</small>
                                                </div>--}}
                                            </div>
                                            <div class="other-repeater">
                                                <div data-repeater-list="other-medications">
                                                    <div data-repeater-item class="mb-2">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <input type="text" title="Drug Name" placeholder="Enter Drug name" name="other_medication" id="other_medication"  class="form-control font-weight-bold col-12 ml-1">
                                                            </div>
                                                            <div class="col-md-3">
                                                                {{--                                                                    <input type="text" name="other_dosage" id="other_dosage" placeholder="Dosage"  class="form-control font-weight-bold col-12 ml-1">--}}
                                                                {{-- <select  class="selectMedicine col-12 form-control font-weight-bold mr-1"    name="other_dosage" id="other_dosage">
                                                                     <option value=""></option>
                                                                     <option value="3tid">tid</option>
                                                                     <option value="2bd">bd</option>
                                                                     <option value="1nocte">nocte</option>
                                                                     <option value="1stat">stat</option>
                                                                     <option value="1dly">dly</option>
                                                                 </select>
     --}}
                                                                <input  list="other_dosage" class="form-control font-weight-bold" placeholder="Dosage" name="other_dosage">
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
                                                                <input type="number" name="other_days" style="width: 70px;" min="1" placeholder="Days" class="form-control font-weight-bold">
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
                                                {{--<div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-md-12 mb-3">
                                                            <label class="text-info">Upload <u><b>LAB</b></u> Result(s)</label>
                                                            <input style="border-radius: 0; border: dashed 1px; padding: 3px;" name="labs[]" type="file"  multiple  class="form-control font-weight-bold-file">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="text-info">Upload <u><b>SCAN</b></u> Result(s)</label>
                                                            <input style="border-radius: 0; border: dashed 1px; padding: 3px;" name="scan[]" type="file"  multiple  class="form-control font-weight-bold-file">
                                                        </div>
                                                    </div>
                                                </div>--}}
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-info">Select Service</label>
                                                    <select  class="col-12 form-control font-weight-bold mr-1 selectMedicine" multiple   name="service[]" id="service">
                                                        <option value=""></option>
                                                        @foreach($charges as $charge)
                                                            @if($charge->name != "Insured" && $charge->name !="Detain/Admit" && $charge->name != "Non-Insured" && $charge->name != "Consultation")
                                                                <option value="{{$charge->id.",".$charge->name}}">{{$charge->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 p-3">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" name="detain_admit" class="form-check-input">
                                                            Detain/Admit Patient
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{--<div class="form-group row">
                                                <div class="col-sm-12 mt-3">
                                                    <label for="findings" class="text-info">Note</label>
                                                    <textarea style="font-size: 15px"   name="notes" class="form-control font-weight-bold" id="notes" rows="8"></textarea>
                                                    <div class="invalid-feedback">
                                                        Finding is required
                                                    </div>
                                                </div>
                                            </div>--}}

                                            <div class="form-group row mt-5">
                                                <div class="col-sm-12 text-right">
                                                    <button class="btn btn-primary loading" type="submit" id="btn_finalize">Finalize</button>
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
                                                    <div class="col-md-6">
                                                        <label class="text-info">Complains</label>
                                                        <blockquote class="blockquote" >
                                                            <small>{{$visit->consultation[0]->complains}}</small>
                                                        </blockquote>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="text-info">Physical Examination</label>
                                                        <blockquote class="blockquote">
                                                            <small>{{$visit->consultation[0]->physical_examination}}</small>
                                                        </blockquote>
                                                    </div>
                                                    @if($visit->consultation[0]->findings != "")
                                                        <div class="col-md-6">
                                                            <label class="text-info">History</label>
                                                            <blockquote class="blockquote" >
                                                                <small>{{$visit->consultation[0]->findings}}</small>
                                                            </blockquote>
                                                        </div>
                                                    @endif
                                                    @if($visit->consultation[0]->diagnosis != "")
                                                        <div class="col-md-6">
                                                            <label class="text-info">Diagnosis</label>
                                                            <blockquote class="blockquote">
                                                                <small>{{$visit->consultation[0]->diagnosis}}</small>
                                                            </blockquote>
                                                        </div>
                                                    @endif
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
                                                                    @if($visit->consultation[0]->other_diagnosis != "")
                                                                        <li><small>{{$visit->consultation[0]->other_diagnosis}}</small></li>
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
                                    <?php
                                    $scans=explode(',',$consultation[0]->ultra_sound_scan)
                                    ?>
                                    <div id="lightgallery" class="row lightGallery">
                                        @foreach($scans as $scan)

                                            <a href="{{asset('public/scan/'.$scan)}}" class="image-tile"><img src="{{asset('public/scan/'.$scan)}}" alt="{{$scan}}"></a>
                                        @endforeach
                                    </div>

                                </div>

                                <h4>Lab Result(s)</h4>
                                <div class="col-sm-12">

                                    <?php
                                    $labs=explode(',',$consultation[0]->labs)
                                    ?>
                                    <div id="lightgallery-without-thumb" class="row lightGallery">
                                        @foreach($labs as $lab)
                                            {{--                                                    {{$lab}}--}}
                                            <a href="{{asset('public/labs/'.$lab)}}" class="image-tile"><img src="{{asset('public/labs/'.$lab)}}" alt="{{$lab}}"></a>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center">
                <h6 class="display-4 text-info">Good Job! No Patient in Queue</h6>
            </div>
        @endif
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection