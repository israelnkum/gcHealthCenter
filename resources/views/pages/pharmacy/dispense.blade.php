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
            <div class="col-md-4 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-inline-block pt-3">
                                <div class="d-md-flex">
                                    <h2 class="mb-0">₵10,200</h2>
                                </div>
                            </div>
                            <div class="d-inline-block">
                                <h4 class="card-title mb-0">Total Sales (Cash)</h4>
                                <small class="text-gray">Today</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-inline-block pt-3">
                                <div class="d-md-flex">
                                    <h2 class="mb-0">₵10,200</h2>
                                </div>
                            </div>
                            <div class="d-inline-block">
                                <h4 class="card-title mb-0">Total Sales (NHIS)</h4>
                                <small class="text-gray">Today</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin">
                <div class="card">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-inline-block pt-3">
                                <div class="d-md-flex">
                                    <h2 class="mb-0">₵10,200</h2>
                                </div>
                                {{--                                <small class="text-gray">Raised from 89 orders.</small>--}}
                            </div>
                            <div class="d-inline-block">
                                <h4 class="card-title mb-0">Total Sales (Nationwide)</h4>
                                <small class="text-gray">Today</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(count($registration)>0)

            <form action="{{route('payment.store')}}" method="post" class="needs-validation" novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-5 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Patient's Information</h4>
                                @foreach($registration as $registered)
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
                                                <h6>Blood Pressure(BP) - <span class="text-danger">{{$vitals->blood_pressure}} mmHg</span></h6>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <div class="form-group">
                                                <h6>Weight - <span class="text-danger">{{$vitals->weight}} kg</span></h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h6>Temperature - <span class="text-danger">{{$vitals->temperature}} °c</span></h6>
                                            </div>
                                        </div>

                                        <div class="col-md-6 text-right">
                                            <div class="form-group">
                                                <h6>Pulse - <span class="text-danger">{{$vitals->pulse}} bpm</span></h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h6>Glucose - <span class="text-danger">{{$vitals->glucose}} mol</span></h6>
                                            </div>
                                        </div>

                                        <div class="col-md-6 text-right">
                                            <div class="form-group">
                                                <h6>RDT - <span class="text-danger">{{$vitals->RDT}} bpm</span></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{$registered->patient->id}}" name="patient_id">
                                    <input type="hidden" value="{{$registered->id}}" name="registration_id">
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Dispense Drugs</h4>

                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead>
                                        <tr>
                                            <th>Drug</th>
                                            <th>Dose</th>
{{--                                            <th>Quantity In Stock</th>--}}
                                            <th>Selling Price</th>
                                            <th>NHIS Amount</th>
                                            <th>Dispense</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($medication as $med)
                                            <tr>
                                                <td>{{$med->drugs->name}}</td>
                                                <td>{{$med->dosage}}</td>
{{--                                                <td>{{$med->drugs->quantity_in_stock}}</td>--}}
                                                <td>{{$med->drugs->retail_price}}</td>
                                                <td>{{$med->drugs->nhis_amount}}</td>
                                                <td>

                                                    <input name="drug_id[]" required type="number" max="{{$med->drugs->quantity_in_stock}}" min="1"  class="form-control" style="width: 70px;">
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="form-group row mt-5">
                                        <div class="col-md-4 offset-md-4">
                                            <input required placeholder="Amount Paying" type="text" class="form-control" id="amount_paid">
                                            <div class="invalid-feedback">
                                                Amount Paying required
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-info">Dispense</button>
                                        </div>
                                    </div>

                                </div>
                                {{--
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                       --}}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @else

            <div class="row">
                <div class="col-md-12 text-center">
                    <h6 class="display-4">
                        Relax! No patient in Cue
                    </h6>
                </div>
            </div>
        @endif
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection