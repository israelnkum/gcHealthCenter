@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-8 text-right grid-margin">
                <form class="needs-validation" novalidate action="{{route('searchConsultation')}}" method="post">
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
            <div class="col-md-4">

                <form action="{{route('patientRecord')}}" method="post" class="mb-1">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-5">
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
            {{--  <div class="col-md-4 text-right grid-margin ">
                  <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#newPatient">
                      <i class="icon icon-plus"></i> New Patient
                  </button>
              </div>--}}
        </div>
        @if(count($registration)  == 0)
            <div class="text-center">
                <h6 class="display-4 text-info">Relax! No Patient in Cue</h6>
            </div>
        @elseif(count($registration)  == 1)
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            @foreach($registration as $registered)

                                <label class="text-info">Patient Info</label>
                                <blockquote class="blockquote">
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
                                            <p class="mb-0">Date Of Birth: <span class="font-weight-bold">{{$registered->patient->date_of_birth}}</span></p>
                                            <p class="mb-0">Age: <span class="font-weight-bold">{{$registered->patient->age}}</span></p>
                                            <p class="mb-0">Gender: <span class="font-weight-bold">{{$registered->patient->gender}}</span></p>
                                            <p class="mb-0">Marital Status: <span class="font-weight-bold">{{$registered->patient->marital_status}}</span></p>
                                        </div>
                                    </div>
                                </blockquote>

                                <label class="text-info">Registration</label>
                                <blockquote class="blockquote">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-0"><b>Date:</b> {{substr($registered->created_at,0,10)}}</p>
                                            <p class="mb-0"><b>Time:</b> {{substr($registered->created_at,10)}}</p>
                                            <p class="mb-0"><b>Registered by:</b> {{$registered->user->first_name." ".$registered->user->last_name}}</p>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <p class="mb-0"><b>Insurance:</b> @if($registered->insurance_type){{$registered->insurance_type}} @else Non Insured @endif</p>
                                            <p class="mb-0"><b>Insurance Number:</b> @if($registered->insurance){{$registered->insurance}} @else ----- @endif</p>
                                            <p class="mb-0"><b>Amount Charged:</b> {{$registered->registration_fee}}</p>
                                        </div>
                                    </div>
                                </blockquote>
                                <label class="text-info">Vital Signs</label>
                                <blockquote class="blockquote">
                                    <div class="row">
                                        @foreach($vitals as $vital)
                                        <div class="col-md-6">
                                            <p class="mb-0"><b>Blood Pressure(BP):</b> <span class="text-danger">{{$vital->blood_pressure}} mmHg</span></p>
                                            <p class="mb-0"><b>Weight:</b> <span class="text-danger">{{$vital->weight}} kg</span></p>
                                            <p class="mb-0"><b>Temperature:</b> <span class="text-danger">{{$vital->temperature}} °c</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-0"><b>Pulse:</b>  <span class="text-danger">{{$vital->pulse}} bpm</span></p>
                                            <p class="mb-0"><b>Glucose:</b> <span class="text-danger">{{$vital->glucose}} mol</span></p>
                                            <p class="mb-0"><b>RDT:</b> <span class="text-danger">{{$vital->RDT}} bpm</span></p>
                                        </div>
                                        @endforeach

                                        <div class="col-md-12 text-right mt-3">
                                            <p><b>Prepared By:</b> {{$vital->user->first_name." ".$vital->user->last_name}}.  <b>Time:</b> {{substr($vital->created_at,0,10)}}</p>
                                        </div>
                                    </div>
                                </blockquote>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            @if(\Request::is('patientRecord'))
                                @foreach($consultation as $consult)
                                    <h6 class="card-title text-danger">Consultation</h6>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="text-info">Complains</label>
                                            <blockquote class="blockquote">
                                                <p class="mb-0">{{$consult->complains}}</p>
                                            </blockquote>
                                        </div>
                                        <div class="col-md-12 p-1">
                                            <label class="text-info">Findings</label>
                                            <blockquote class="blockquote">
                                                <p class="mb-0">{{$consult->findings}}</p>
                                            </blockquote>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="text-info">Physical Examination</label>
                                            <blockquote class="blockquote">
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
                                        <blockquote class="blockquote">
                                            <ul>
                                                @foreach($patientDiagnosis as $diagnosis)
                                                    <li>{{$diagnosis->diagnoses->name}}</li>
                                                @endforeach
                                                <li>{{$other_diagnosis}}</li>
                                            </ul>
                                        </blockquote>
                                    </div>
                                </div>
                                <hr>
                                <h6 class="card-title">Medications</h6>
                                @foreach($getPatientDrugs as $med)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <p>{{$med->name}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center">
                <h6 class="display-4 text-info">Good Job! No Patient in Cue</h6>
            </div>
        @endif
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection