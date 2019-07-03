@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-8 offset-md-2 text-right grid-margin">
                <form class="needs-validation" novalidate action="{{route('searchPatientForDrugDispersion')}}" method="post">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-12 ">
                            <div class="input-group">
                                <input type="text" required class="form-control" name="search" placeholder=" Search by Folder Number or Patient's Last Name or Phone Number">
                                <div class="input-group-prepend">
                                    <div class="form-check form-check-flat">
                                        <label class="form-check-label">
                                            <input type="checkbox"   class="form-check-input">
                                            Detaned
                                        </label>
                                    </div>
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
                                    <h2 class="mb-0">₵{{$totalCashSales}}</h2>
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
                                    <h2 class="mb-0">₵{{$totalNhisSale}}</h2>
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
                                    <h2 class="mb-0">₵{{$totalSales}}</h2>
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


        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Patient's Information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                {{--                                                    GC{{$registration->patient->registration_number}}--}}
                                <h5 class="mb-1 text-primary" >
                                    <i class="icon-folder mr-1"></i> {{$recentRegistration->patient->folder_number}}
                                </h5>
                                <h6 class="mb-1 text-danger">
                                    <i class="icon-user mr-1"></i>
                                    {{$recentRegistration->patient->title." ".$recentRegistration->patient->first_name." ".$recentRegistration->patient->other_name." ".$recentRegistration->patient->last_name}}
                                </h6>
                                <small class="text-muted mb-0" ><i class="icon-phone mr-1"></i>{{$recentRegistration->patient->phone_number}}</small>

                            </div>
                            <div class="col-md-6 text-right">
                                <small style="font-size: 14px"> Date Of Birth: <span class="font-weight-bold">{{$recentRegistration->patient->date_of_birth}}</span></small><br>
                                <small style="font-size: 14px">Age: <span class="font-weight-bold">{{$recentRegistration->patient->age}}</span></small><br>
                                <small style="font-size: 14px">Gender: <span class="font-weight-bold">{{$recentRegistration->patient->gender}}</span></small><br>
                                <small style="font-size: 14px">Marital Status: <span class="font-weight-bold">{{$recentRegistration->patient->marital_status}}</span></small>
                            </div>
                        </div>
                        <hr>
                        <h6 class="card-title">Vital Signs</h6>
                        <div class="row">
                            <div class="col-md-8">
                                <small style="font-size: 13px">Blood Pressure(BP) - <span class="text-danger">{{$vitals->blood_pressure}} mmHg</span></small>
                            </div>
                            <div class="col-md-4 text-right">
                                <small style="font-size: 13px">Weight - <span class="text-danger">{{$vitals->weight}} kg</span></small>
                            </div>
                            <div class="col-md-6">
                                <small style="font-size: 13px">Temperature - <span class="text-danger">{{$vitals->temperature}} °c</span></small>
                            </div>

                            <div class="col-md-6 text-right">
                                <small style="font-size: 13px">Pulse - <span class="text-danger">{{$vitals->pulse}} bpm</span></small>
                            </div>
                            <div class="col-md-6">
                                <small style="font-size: 13px">Glucose - <span class="text-danger">{{$vitals->glucose}} mol</span></small>
                            </div>

                            <div class="col-md-6 text-right">
                                <small style="font-size: 13px">RDT - <span class="text-danger">{{$vitals->RDT}} bpm</span></small>
                            </div>
                        </div>
                        <input type="hidden" value="{{$recentRegistration->patient->id}}" name="patient_id">
                        <input type="hidden" value="{{$recentRegistration->id}}" name="registration_id">
                    </div>
                </div>
            </div>
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Dispense Drugs</h4>
                        {{--<div class="accordion accordion-bordered" id="accordion-2" role="tablist">
                            @foreach($getBills as $getBill => $date)
                                <div>
                                    <div class="card-header" role="tab" id="heading-4">
                                        <a style="text-decoration: none" data-toggle="collapse" href="#{{$getBill}}" aria-expanded="false" aria-controls="collapse-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h2 class="mb-1 text-primary" style="font-size: 25px; font-weight: 600">
                                                        <i class="icon-folder mr-1"></i> {{$getBill}}
                                                    </h2>
                                                </div>
                                            </div>
                                        </a>

                                    </div>
                                    <div id="{{$getBill}}" class="collapse" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <h4></h4>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
--}}
                        <div class="accordion accordion-bordered" id="accordion-2" role="tablist">

                            @php($i =1)
                            @foreach($getBills as $getBill => $bills)
                                <div>
                                    <div class="card-header" role="tab" id="heading-4">
                                        <a style="text-decoration: none; font-size: 20px;" data-toggle="collapse" href="#date{{str_replace(':','',str_replace(' ','',$getBill))}}" aria-expanded="false" aria-controls="collapse-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{--                                                    GC{{$registered->patient->registration_number}}--}}
                                                    <h6 style="font-weight: 500">
                                                        <i class="icon-calendar mr-1"></i> {{$getBill}}
                                                    </h6>

                                                </div>
                                            </div>
                                        </a>

                                    </div>

                                    <div id="date{{str_replace(':','',str_replace(' ','',$getBill))}}" class="collapse" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <h4></h4>
                                            <ul class="list-group-flush list-group">
                                                @foreach($bills as $bill)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{$bill->item}}
                                                        <span>{{$bill->total_amount_to_pay}}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @php($i++)
                            @endforeach

                        </div>
                        {{--<div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($total = 0)

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
                            </table>

                            <div class="form-group row mb-2 mt-5">
                                <div class="col-md-4 offset-md-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-info text-white">
                                            <button type="submit" class="input-group-text btn text-white">
                                                <i class="icon icon-printer"></i> Print
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>--}}
                        {{--
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
               --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection