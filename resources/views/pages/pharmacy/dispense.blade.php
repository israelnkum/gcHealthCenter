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
                        <h4 class="card-title">Recent Registration's</h4>
                        <div class="accordion accordion-bordered" id="accordion-2" role="tablist">
                            @php($i =1)
                            @foreach($registration as $registered)
                                <div>

                                    <div class="card-header" role="tab" id="heading-4">
                                        <a style="text-decoration: none" data-toggle="collapse" href="#GC{{$registered->patient->registration_number}}" aria-expanded="false" aria-controls="collapse-4">
                                            <div class="row">
                                                <div class="col-md-6">
{{--                                                    GC{{$registered->patient->registration_number}}--}}
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
                                        </a>

                                    </div>
                                    <div id="GC{{$registered->patient->registration_number}}" class="collapse" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <h4></h4>
                                            <form class="needs-validation" novalidate method="post" action="{{route('vitals.store')}}">
                                                @csrf
                                                <input type="hidden" name="registration_id" value="{{$registered->id}}">
                                                <input type="hidden" name="patient_id" value="{{$registered->patient->id}}">
                                                <div class="form-group row">
                                                    <label for="blood_pressure" class="col-sm-2 col-form-label">Blood Pressure(BP)</label>
                                                    <div class="col-sm-3">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <input required type="number" name="systolic" class="form-control" id="systolic{!! $i !!}">
                                                                <div class="invalid-feedback">
                                                                    Systolic is required
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input required type="number" name="diastolic" class="form-control" id="diastolic{!! $i !!}">
                                                                <div class="invalid-feedback">
                                                                    Diastolic is required
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <label for="weight" class="col-sm-2 col-form-label text-right">Weight</label>
                                                    <div class="col-sm-3">
                                                        <input required type="number" class="form-control" id="weight" name="weight">
                                                        <div class="invalid-feedback">
                                                            Weight  is required
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="temperature" class="col-sm-2 col-form-label">Temperature</label>
                                                    <div class="col-sm-3">
                                                        <input required type="number" class="form-control" id="temperature" name="temperature">
                                                        <div class="invalid-feedback">
                                                            Temperature is required
                                                        </div>
                                                    </div>

                                                    <label for="pulse" class="col-sm-2 col-form-label text-right">Pulse</label>
                                                    <div class="col-sm-3">
                                                        <input required type="number" name="pulse" class="form-control" id="pulse">
                                                        <div class="invalid-feedback">
                                                            Pulse is required
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row sugar{!! $i !!}" style="display: none">
                                                    <label for="rdt" class="col-sm-2 col-form-label">RDT (Malaria)</label>
                                                    <div class="col-sm-3">
                                                        <input type="number" class="form-control" id="rdt" name="rdt">
                                                        <div class="invalid-feedback">
                                                            RDT (Malaria) is required
                                                        </div>
                                                    </div>

                                                    <label for="glucose" class="col-sm-2 col-form-label text-right">Glucose(Sugar Level)</label>
                                                    <div class="col-sm-3">
                                                        <input type="number" class="form-control" id="glucose" name="glucose">
                                                        <div class="invalid-feedback">
                                                            Glucose (Sugar Level) is required
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-10 text-right">
                                                        <button class="btn btn-primary">Add Vital</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @php($i++)
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection