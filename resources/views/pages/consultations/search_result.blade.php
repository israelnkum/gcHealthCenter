@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 offset-md-2 text-right @if(count($searchPatient) == 1 && count($registration)==0) offset-md-2 @endif grid-margin">
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
            @if(count($searchPatient) == 1 && count($registration)!=0)
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
            @endif
        </div>

        @if(count($searchPatient)>1)
            <div class="row">
                @foreach($searchPatient as $dat)
                    <div class="col-md-6 grid-margin">
                        <div class="card">
                            <h4 class="card-title mt-0"></h4>
                            <div class="card-body">
                                <h6 class=" text-uppercase mb-0">{{$dat->first_name." ".$dat->other_name." ".$dat->last_name}}</h6>
                                <div class="d-flex justify-content-between align-items-center">
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
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif(count($searchPatient) == 1)
            @if(count($registration) == 1)
                <div class="row">
                    <div class="@if(\Request::is('patientRecord')) col-md-6 @else col-md-8 offset-md-2 @endif grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="accordion accordion-bordered" id="accordion-2" role="tablist">
                                    @php($i =1)

                                    @foreach($registration as $registered)
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
                                                            <h6>RDT - <span class="text-danger">{{$getVitals[0]->RDT}} bpm</span></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div id="GC{{$registered->patient->registration_number}}" class="collapse show" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-2">
                                            <div class="card-body">
                                                <form class="needs-validation" enctype="multipart/form-data"  novalidate method="post" action="{{route('consultation.update',$registered->id)}}">
                                                    @csrf
                                                    {!! method_field('put') !!}
                                                    <input type="hidden" name="registration_id" value="{{$registered->id}}">
                                                    <input type="hidden" name="patient_id" value="{{$registered->patient->id}}">
                                                    <input type="hidden" name="registration_number" value="{{$registered->patient->registration_number}}">
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label for="blood_pressure" class="text-info">Complains</label>
                                                            <textarea required name="complains" class="form-control" id="complains{!! $i !!}" rows="10"></textarea>
                                                            <div class="invalid-feedback">
                                                                Complains is required
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label for="blood_pressure" class="text-info">Physical Examination</label>
                                                            <textarea required name="physical_examination" class="form-control" id="physical_examination{!! $i !!}" rows="10"></textarea>
                                                            <div class="invalid-feedback">
                                                                Physical Examination is required
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label for="blood_pressure" class="text-info">Findings</label>
                                                            <textarea required name="findings" class="form-control" id="findings{!! $i !!}" rows="10"></textarea>
                                                            <div class="invalid-feedback">
                                                                Finding is required
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label for="diagnosis" class="text-info">Diagnosis</label>
                                                            <select required class="form-control js-example-basic-multiple" multiple="multiple" style="width: 100%" name="diagnosis[]" id="select_diagnosis">
                                                                <option value="">Select Diagnosis</option>
                                                                @foreach($diagnosis as $diag)
                                                                    <option value="{{$diag->id}}">{{$diag->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Diagnosis is required
                                                            </div>
                                                        </div>
                                                        {{-- <div class="col-sm-2 ml-5 ">
                                                             <div class="form-check form-check-flat mt-0">
                                                                 <label class="form-check-label mt-4 ">
                                                                     <input type="checkbox" class="form-check-input"  id="other_diagnosis_check">
                                                                     Other
                                                                 </label>
                                                             </div>
                                                         </div>--}}
                                                    </div>
                                                    <div class="form-group row" id="other_diagnosis_div">
                                                        {{--                                                <label for="glucose" class="col-sm- col-form-label text-right">Glucose(Sugar Level)</label>--}}
                                                        <div class="col-sm-12">
                                                            {{--                                                        <input type="text" placeholder="Enter diagnosis"  class="form-control" id="other_diagnosis_text" name="other_diagnosis">--}}
                                                            <textarea placeholder="Other Diagnosis" class="form-control" id="other_diagnosis_text" name="other_diagnosis"></textarea>
                                                            <div class="invalid-feedback">
                                                                Diagnosis is required
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <label for="treatment_medication" class="text-info">Medication</label>
                                                            <select required class="js-example-basic-multiple w-100" style="width: 100%" multiple="multiple" name="treatment_medication[]" id="treatment_medication">
                                                                {{--                                                            <option value="">Select</option>--}}
                                                                @foreach($drugs as $drug)
                                                                    <option value="{{$drug->id}}">{{$drug->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Medication is required
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label>Upload <u><b>LAB</b></u> Result(s)</label>
                                                            <input style="border-radius: 0; border: dashed 1px; padding: 3px;" name="labs[]" type="file"  multiple  class="form-control-file">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>Upload <u><b>SCAN</b></u> Result(s)</label>
                                                            <input style="border-radius: 0; border: dashed 1px; padding: 3px;" name="scan[]" type="file"  multiple  class="form-control-file">
                                                        </div>
                                                    </div>


                                                    <div class="form-group row mt-5">
                                                        <div class="col-sm-12 text-right">
                                                            <button class="btn btn-primary">Finalize</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        @php($i++)
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                    @if(\Request::is('patientRecord'))
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">
                                        Previous Record
                                    </h4>

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
                                                    <h6 style="font-size: 13px;">RDT - <span class="text-danger">{{$vital->RDT}} bpm</span></h6>
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
                                                <blockquote class="blockquote" style="padding: 5px;">
                                                    <p class="mb-0">{{$consult->complains}}</p>
                                                </blockquote>
                                            </div>
                                            <div class="col-md-12 p-1">
                                                <label class="text-info">Findings</label>
                                                <blockquote class="blockquote" style="padding: 5px;">
                                                    <p class="mb-0">{{$consult->findings}}</p>
                                                </blockquote>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="text-info">Physical Examination</label>
                                                <blockquote class="blockquote" style="padding: 5px;">
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
                                            <blockquote class="blockquote" style="padding: 5px;">
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

                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="row">
                    <div class="col-md-2 offset-md-2">
                        {{--                        <h6 class="display-4 text-info">{{$searchPatient[0]->title." ".$searchPatient[0]->first_name." ".$searchPatient[0]->last_name." ".$searchPatient[0]->other_name}} has not <span class="text-danger">registered</span> today!</h6>--}}
                        <h6 class="text-danger">You can access the previous record(s)</h6>
                        @if($previousRegistration)
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
                    </div>
                    <div class="col-md-6">
                        <h4 class="card-title">
                            Recent Registration
                        </h4>
                        <div class="card">
                            <div class="card-body">
                                @if(!empty($recentVitals))

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
                                                <form action="{{route('patientRecord')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="fromSearchPage" value="fromSearchPage">
                                                    <button name="data" value="{{substr($previous->created_at,0,10).",".$previous->patient_id}}" class="btn btn-link" style="text-decoration: none;">
                                                        <i class="icon-info icon-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        {{--                                        {{$recentRegistration->id}}--}}
                                    </div>
                                    <hr>
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
                                                <h6>RDT - <span class="text-danger">{{$recentVitals->RDT}} bpm</span></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h6 class="card-title">Consultation</h6>
                                    <form class="needs-validation" enctype="multipart/form-data"  novalidate method="post" action="{{route('consultation.update',$recentRegistration->id)}}">
                                        @csrf
                                        {!! method_field('put') !!}
                                        <input type="hidden" name="registration_id" value="{{$recentRegistration->id}}">
                                        <input type="hidden" name="patient_id" value="{{$recentRegistration->patient->id}}">
                                        <input type="hidden" name="registration_number" value="{{$recentRegistration->patient->registration_number}}">
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="blood_pressure" class="text-info">Complains</label>
                                                <textarea required name="complains" class="form-control" rows="10">{{$recentConsultation->complains}}</textarea>
                                                <div class="invalid-feedback">
                                                    Complains is required
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="blood_pressure" class="text-info">Physical Examination</label>
                                                <textarea required name="physical_examination" class="form-control" rows="10">{{$recentConsultation->physical_examination}}</textarea>
                                                <div class="invalid-feedback">
                                                    Physical Examination is required
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="blood_pressure" class="text-info">Findings</label>
                                                <textarea required name="findings" class="form-control"  rows="10">{{$recentConsultation->findings}}</textarea>
                                                <div class="invalid-feedback">
                                                    Finding is required
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="diagnosis" class="text-info">Diagnosis</label>
                                                <select required class="form-control js-example-basic-multiple" multiple="multiple" style="width: 100%" name="diagnosis[]" id="patient_diagnosis">
                                                    <option value="">Select Diagnosis</option>

                                                    @foreach($diagnosis as $diag)
                                                        <option value="{{$diag->id}}">{{$diag->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Diagnosis is required
                                                </div>
                                            </div>
                                            {{-- <div class="col-sm-2 ml-5 ">
                                                 <div class="form-check form-check-flat mt-0">
                                                     <label class="form-check-label mt-4 ">
                                                         <input type="checkbox" class="form-check-input"  id="other_diagnosis_check">
                                                         Other
                                                     </label>
                                                 </div>
                                             </div>--}}
                                        </div>
                                        <div class="form-group row" id="other_diagnosis_div">
                                            {{--                                                <label for="glucose" class="col-sm- col-form-label text-right">Glucose(Sugar Level)</label>--}}
                                            <div class="col-sm-12">
                                                {{--                                                        <input type="text" placeholder="Enter diagnosis"  class="form-control" id="other_diagnosis_text" name="other_diagnosis">--}}
                                                <textarea placeholder="Other Diagnosis" class="form-control" id="other_diagnosis_text1" name="other_diagnosis"></textarea>
                                                <div class="invalid-feedback">
                                                    Diagnosis is required
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="med" class="text-info">Medication</label>
                                                <select required class="js-example-basic-multiple w-100" style="width: 100%" multiple="multiple" name="treatment_medication[]" id="patient_medication">
                                                    {{--                                                    <option value="">Select</option>--}}
                                                    @foreach($drugs as $drug)
                                                        <option  value="{{$drug->id}}">{{$drug->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Medication is required
                                                </div>
                                            </div>
                                        </div>

                                        <h4>Scan Result(s)</h4>
                                        <div class="col-sm-12">
                                            <?php
                                            $scans=explode(',',$recentConsultation->ultra_sound_scan)
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
                                            $labs=explode(',',$recentConsultation->labs)
                                            ?>
                                            <div id="lightgallery-without-thumb" class="row lightGallery">
                                                @foreach($labs as $lab)
                                                    {{--                                                    {{$lab}}--}}
                                                    <a href="{{asset('public/labs/'.$lab)}}" class="image-tile"><img src="{{asset('public/labs/'.$lab)}}" alt="{{$lab}}"></a>
                                                @endforeach
                                            </div>

                                        </div>


                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label>Upload <u><b>LAB</b></u> Result(s)</label>
                                                <input style="border-radius: 0; border: dashed 1px; padding: 3px;" name="labs[]" type="file"  multiple  class="form-control-file">
                                            </div>
                                            <div class="col-sm-6">
                                                <label>Upload <u><b>SCAN</b></u> Result(s)</label>
                                                <input style="border-radius: 0; border: dashed 1px; padding: 3px;" name="scan[]" type="file"  multiple  class="form-control-file">
                                            </div>
                                        </div>


                                        <div class="form-group row mt-5">
                                            <div class="col-sm-12 text-right">
                                                <button class="btn btn-primary" type="submit" id="finalize">Finalize</button>
                                            </div>
                                        </div>
                                    </form>
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
                <br>
                <label for="" class="badge badge-danger p-3">Search Again</label>
            </div>
        @endif
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
@endsection
