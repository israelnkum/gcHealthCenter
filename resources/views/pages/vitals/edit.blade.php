@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-8 offset-md-2 text-right grid-margin">
                <form class="needs-validation" novalidate action="{{route('searchRegistrationForVitals')}}" method="get">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-12 ">
                            <div class="input-group">
                                <input type="text" required class="form-control" name="search" placeholder=" Search by Folder Number o's Last Name or Phone Number">
                                <div class="input-group-prepend">
                                    <button type="submit" class="input-group-text btn"><i class="icon-magnifier"></i></button>
                                </div>
                                <div class="invalid-feedback">
                                    Search by Folder Number o's Last Name or Phone Number
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {{--  <div class="col-md-4 text-right grid-margin ">
                  <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#n">
                      <i class="icon icon-plus"></i> Ne
                  </button>
              </div>--}}
        </div>



        <div class="row">
            @if(count($search_patient) > 1)
                @foreach($search_patient as $registered)
                    <div class="col-md-6 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h4></h4>
                                <h6 class=" text-uppercase mb-0">{{$registered->title." ".$registered->first_name." ".$registered->other_name." ".$registered->last_name}}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{route('vitals.show',$registered->id)}}" style="text-decoration: none" class="">

                                        <div class="d-inline-block pt-3">
                                            <div class="d-md-flex">
                                                <h5 class="mb-0 text-uppercase"><span class="text-danger">Folder Number:</span> {{$registered->folder_number}}</h5>
                                            </div>
                                            <small class="text-gray">{{$registered->phone_number}}</small>
                                        </div>
                                    </a>
                                    <div class="d-inline-block">
                                        <div class=" px-4 py-2 rounded">
                                            <a href="{{route('patients.edit',$registered->id)}}" class="text-dark" style="text-decoration: none;"><i class="icon-note icon-lg"></i></a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-2">
                    <form class="needs-validation" novalidate action="{{route('vitalByDate')}}" method="get">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="filter">Search By Date</label>
                                <select name="vital_date" id="filter" class="form-control selectMedicine" style="width: 100%">
                                    <option value=""></option>
                                    @foreach($allPatientVitals as $all_vitals)
                                        <option value="{{$all_vitals->created_at}}">{{substr($all_vitals->created_at,0,10)}}</option>
                                    @endforeach
                                </select>

                                <input type="hidden" value="{{$search_patient[0]->folder_number}}" name="folder_number">
                                <button class="btn btn-success mt-1 btn-block" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-8 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Recent Registration's</h4>
                            <div class="accordion accordion-bordered" id="accordion-2" role="tablist">
                                @if(count($search_patient) == null)

                                @else
                                    @foreach($search_patient as $registered)
                                        <div class="card">
                                            <div class="card-header" role="tab" id="heading-4">
                                                <a data-toggle="collapse" href="#GC{{$registered->registration_number}}" aria-expanded="false" aria-controls="collapse-4">
                                                    <div class="row">
                                                        <div class="col-md-6">

                                                            <h2 class="mb-1 text-primary" style="font-size: 25px; font-weight: 600">
                                                                <i class="icon-folder mr-1"></i> {{$registered->folder_number}}
                                                            </h2>
                                                            <h5 class="mb-1 text-danger">
                                                                <i class="icon-user mr-1"></i>
                                                                {{$registered->title." ".$registered->first_name." ".$registered->other_name." ".$registered->last_name}}
                                                            </h5>
                                                            <small class="text-muted mb-0" ><i class="icon-phone mr-1"></i>{{$registered->phone_number}}</small>

                                                        </div>
                                                        <div class="col-md-5 text-right mr-1">
                                                            Date Of Birth: <span class="font-weight-bold">{{$registered->date_of_birth}}</span><br>
                                                            Age: <span class="font-weight-bold">{{$registered->age}}</span><br>
                                                            Gender: <span class="font-weight-bold">{{$registered->gender}}</span><br>
                                                            Marital Status: <span class="font-weight-bold">{{$registered->marital_status}}</span><br>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div id="GC{{$registered->registration_number}}" class="collapse" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-2">
                                                <div class="card-body">
                                                    <label class="badge-dark p-1">Date and Time: {{$vitals->created_at}}</label>
                                                    <form class="needs-validation" novalidate method="post" action="{{route('vitals.update',$vitals->id)}}">
                                                        @csrf
                                                        {{method_field('put')}}

                                                        <input type="hidden" name="registration_id" value="{{$vitals->registration_id}}">
                                                        <input type="hidden" name="patient_id" value="{{$vitals->patient_id}}">
                                                        <div class="form-group row">
                                                            <label for="blood_pressure" class="col-sm-2 col-form-label">Blood Pressure(BP)</label>
                                                            <div class="col-sm-3">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <input required type="number" name="systolic" value="{!! substr($vitals->blood_pressure,0,strpos($vitals->blood_pressure,'/')) !!}" class="form-control" >
                                                                        <small class="text-info">Systolic</small>
                                                                        <div class="invalid-feedback">
                                                                            Systolic is required
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input required type="number" value="{!! substr($vitals->blood_pressure,strpos($vitals->blood_pressure,'/')+1) !!}" name="diastolic" class="form-control">
                                                                        <small class="text-info">Diastolic</small>
                                                                        <div class="invalid-feedback">
                                                                            Diastolic is required
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <label for="weight" class="col-sm-2 col-form-label text-right">Weight</label>
                                                            <div class="col-sm-3">
                                                                <input required type="text" value="{!! $vitals->weight !!}" class="form-control" id="weight" name="weight">
                                                                <div class="invalid-feedback">
                                                                    Weight  is required
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="temperature" class="col-sm-2 col-form-label">Temperature</label>
                                                            <div class="col-sm-3">
                                                                <input required value="{!! $vitals->temperature !!}" type="text" class="form-control" id="temperature" name="temperature">
                                                                <div class="invalid-feedback">
                                                                    Temperature is required
                                                                </div>
                                                            </div>

                                                            <label for="pulse" class="col-sm-2 col-form-label text-right">Pulse</label>
                                                            <div class="col-sm-3">
                                                                <input required type="number" value="{!! $vitals->pulse !!}" name="pulse" class="form-control" id="pulse">
                                                                <div class="invalid-feedback">
                                                                    Pulse is required
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row ">
                                                            <label for="rdt" class="col-sm-2 col-form-label">RDT (Malaria)</label>
                                                            <div class="col-sm-3">
                                                                <select name="rdt" id="rdt" class="form-control selectMedicine"  style="width: 100%">
                                                                    <option value=""></option>
                                                                    <option @if($vitals->RDT == "Negative") selected @endif value="Negative">Negative</option>
                                                                    <option @if($vitals->RDT == "Positive") selected @endif value="Positive">Positive</option>
                                                                </select>
                                                                <div class="invalid-feedback">
                                                                    RDT (Malaria) is required
                                                                </div>
                                                            </div>

                                                            <label for="glucose" class="col-sm-2 col-form-label text-right">Glucose(Sugar Level)</label>
                                                            <div class="col-sm-3">
                                                                <input type="text" value="{!! $vitals->glucose !!}" class="form-control" id="glucose" name="glucose">
                                                                <div class="invalid-feedback">
                                                                    Glucose (Sugar Level) is required
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-success">Update Vitals</button>
                                                        {{--<div class="form-group row">
                                                            <div class="col-sm-10 text-right">
                                                                @if($userRegistration->vitals == 1)
                                                                    <button type="submit" class="btn btn-success">Update Vitals</button>
                                                                @else
                                                                    <button type="submit" class="btn btn-primary"> Add Vitals </button>
                                                                @endif
                                                            </div>
                                                        </div>--}}
                                                    </form>

                                                    {{--  @foreach($allRegistration as $key => $userRegistration)
                                                          @if($key == count( $allRegistration ) -1 )
                                                              @if(substr($userRegistration->created_at,0,10) ." 00:00:00"  !=  \Carbon\Carbon::today())
                                                                  <div class="text-center">
                                                                      <p>Oops! No Registration Today</p>
                                                                      <a href="{{route('patients.show',$userRegistration->patient_id)}}" role="button" class="btn btn-info">Register</a>
                                                                  </div>
                                                              @endif
                                                          @endif
                                                      @endforeach--}}
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection