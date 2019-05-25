@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-8 offset-md-2 text-right grid-margin">
                <form class="needs-validation" novalidate action="{{route('searchRegistrationForVitals')}}" method="post">
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
            {{--  <div class="col-md-4 text-right grid-margin ">
                  <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#newPatient">
                      <i class="icon icon-plus"></i> New Patient
                  </button>
              </div>--}}
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if(count($registration)  == 1)
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
                                                <div class="col-md-5 text-right mr-1">
                                                    Date Of Birth: <span class="font-weight-bold">{{$registered->patient->date_of_birth}}</span><br>
                                                    Age: <span class="font-weight-bold">{{$registered->patient->age}}</span><br>
                                                    Gender: <span class="font-weight-bold">{{$registered->patient->gender}}</span><br>
                                                    Marital Status: <span class="font-weight-bold">{{$registered->patient->marital_status}}</span><br>
                                                </div>
                                            </div>
                                            <hr>
                                            <h4 class="card-title">Vital Signs</h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <h6>Blood Pressure(BP) - <span class="text-danger">{{$getVitals[0]->blood_pressure}} mmHg</span></h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <h6>Weight - <span class="text-danger">{{$getVitals[0]->weight}} kg</span></h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <h6>Temperature - <span class="text-danger">{{$getVitals[0]->temperature}} °c</span></h6>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <h6>Pulse - <span class="text-danger">{{$getVitals[0]->pulse}} bpm</span></h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <h6>Glucose - <span class="text-danger">{{$getVitals[0]->glucose}} mol</span></h6>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <h6>RDT - <span class="text-danger">{{$getVitals[0]->RDT}} bpm</span></h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>

                                    </div>
                                    <div id="GC{{$registered->patient->registration_number}}" class="collapse" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <form class="needs-validation" novalidate method="post" action="{{route('consultation.store')}}">
                                                @csrf
                                                <input type="hidden" name="registration_id" value="{{$registered->id}}">
                                                <input type="hidden" name="patient_id" value="{{$registered->patient->id}}">
                                                <div class="form-group row">
                                                    <div class="col-sm-10">
                                                        <label for="blood_pressure" class="text-info">Complains</label>
                                                        <textarea required name="complains" class="form-control" id="complains{!! $i !!}" rows="10"></textarea>
                                                        <div class="invalid-feedback">
                                                            Complains is required
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-10">
                                                        <label for="blood_pressure" class="text-info">Physical Examination</label>
                                                        <textarea required name="physical_examination" class="form-control" id="physical_examination{!! $i !!}" rows="10"></textarea>
                                                        <div class="invalid-feedback">
                                                            Physical Examination is required
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-10">
                                                        <label for="blood_pressure" class="text-info">Findings</label>
                                                        <textarea required name="findings" class="form-control" id="findings{!! $i !!}" rows="10"></textarea>
                                                        <div class="invalid-feedback">
                                                            Finding is required
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-8">
                                                        <label for="diagnosis" class="text-info">Diagnosis</label>
                                                        <select required  class="form-control js-example-basic-single" style="width: 100%" name="diagnosis" id="select_diagnosis">
                                                            <option value="">Select Diagnosis</option>
                                                            @foreach($diagnosis as $diag)
                                                                <option value="{{$diag->id}}">{{$diag->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Diagnosis is required
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 ml-5 ">
                                                        <div class="form-check form-check-flat mt-0">
                                                            <label class="form-check-label mt-4 ">
                                                                <input type="checkbox" class="form-check-input" name="diagnosis" id="other_diagnosis_check">
                                                                Other
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row" style="display: none" id="other_diagnosis_div">
                                                    {{--                                                <label for="glucose" class="col-sm- col-form-label text-right">Glucose(Sugar Level)</label>--}}
                                                    <div class="col-sm-10">
                                                        <input type="text" placeholder="Enter diagnosis" class="form-control" id="other_diagnosis_text" name="other_diagnosis_text">
                                                        <div class="invalid-feedback">
                                                            Diagnosis is required
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-10">
                                                        <label for="blood_pressure" class="text-info">Treatment/Medication</label>
                                                        <textarea required name="treatment_medication" class="form-control" id="physical_examination" rows="10"></textarea>
                                                        <div class="invalid-feedback">
                                                            Physical Examination is required
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-10 text-right">
                                                        <button class="btn btn-primary">Finalize</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @php($i++)
                                @endforeach
                            </div>
                            @else

                            <div class="text-center">
                                <h6 class="display-4">Good Job! No Patient in Cue</h6>
                            </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection