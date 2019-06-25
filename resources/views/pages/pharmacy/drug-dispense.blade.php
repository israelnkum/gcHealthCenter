@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-8 offset-md-2 text-right grid-margin">
                <form class="needs-validation" novalidate action="{{route('searchPatientForDrugDispersion')}}" method="get">
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

        @if(!empty($registration))
            <form action="{{route('payment.store')}}" method="post" class="needs-validation" novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Patient's Information</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        {{--                                                    GC{{$registration->patient->registration_number}}--}}
                                        <h5 class="mb-1 text-primary" >
                                            <i class="icon-folder mr-1"></i> {{$registration->patient->folder_number}}
                                        </h5>
                                        <h6 class="mb-1 text-danger">
                                            <i class="icon-user mr-1"></i>
                                            {{$registration->patient->title." ".$registration->patient->first_name." ".$registration->patient->other_name." ".$registration->patient->last_name}}
                                        </h6>
                                        <small class="text-muted mb-0" ><i class="icon-phone mr-1"></i>{{$registration->patient->phone_number}}</small>

                                    </div>
                                    <div class="col-md-6 text-right">
                                        <small style="font-size: 14px"> Date Of Birth: <span class="font-weight-bold">{{$registration->patient->date_of_birth}}</span></small><br>
                                        <small style="font-size: 14px">Age: <span class="font-weight-bold">{{$registration->patient->age}}</span></small><br>
                                        <small style="font-size: 14px">Gender: <span class="font-weight-bold">{{$registration->patient->gender}}</span></small><br>
                                        <small style="font-size: 14px">Marital Status: <span class="font-weight-bold">{{$registration->patient->marital_status}}</span></small>
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
                                <input type="hidden" value="{{$registration->patient->id}}" name="patient_id">
                                <input type="hidden" value="{{$registration->id}}" name="registration_id">
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="">Bill</h4>
                                        <table class="table table-borderless">
                                            <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php($total = 0)
                                            @foreach($getBills as $bill)
                                                {{--                                                {{$bill}}--}}
                                                @if($bill->type == "" || $bill->type=="Service")
                                                    <tr>
                                                        <td>{{$bill->item}}</td>
                                                        <td>{{$bill->total_amount_to_pay}}</td>
                                                    </tr>
                                                    @php($total +=$bill->total_amount_to_pay)
                                                @endif
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
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Dispense Drugs</h4>
                                <div class="table-responsive">
                                    <table class="table table-borderless" id="dispenseTable">
                                        <thead>
                                        <tr>
                                            <th>Drug</th>
                                            <th>Dose</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                            <th>Dispensed</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php( $i =1)
                                        @foreach($medication as $med)
                                            <tr class="txtMult">
                                                <td>{{$med->drugs->name}}</td>
                                                <td>{{$med->dosage}} x {{$med->days}}days</td>
                                                <td>
                                                    @if($registration->isInsured ==0)
                                                        @if($med->drugs->unit_of_pricing == "Blister (x10tabs)")
                                                            <span class="val1">{{$med->drugs->retail_price/10}}</span>
                                                        @else
                                                            <span class="val1">{{$med->drugs->retail_price}}</span>
                                                        @endif
                                                    @else
                                                        @if($med->drugs->unit_of_pricing == "Blister (x10tabs)")
                                                            <span class="val1">{{($med->drugs->retail_price-$med->drugs->nhis_amount)/10}}</span>
                                                        @else
                                                            <span class="val1">{{$med->drugs->retail_price-$med->drugs->nhis_amount}}</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="val2">{{$med->qty}}</span>
                                                    <input name="number_to_dispensed[{{$med->drugs->id}}]" required value="{{$med->qty}}" min="1" max="{{$med->drugs->quantity_in_stock}}" type="number" hidden style="width: 80px;" class=" form-control">
                                                </td>
                                                <td>
                                                    <span class="multTotal">0.00</span>
                                                    <input type="hidden" id="drug_total_value" name="drug_total[{{$med->drugs->id}}]">
                                                </td>
                                                <td>
                                                    <input name="number_dispensed[{{$med->drugs->id}}]" value="0" min="0" max="{{$med->drugs->quantity_in_stock}}" type="number" style="width: 80px;" class="val3 form-control">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <blockquote class="blockquote" style="border: dashed 1px;">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Service Total:
                                                        <span class="badge  badge-pill" id="serviceText">GH₵ {!! $total+$detentionBill !!}.00</span>
                                                        <input type="hidden" value="{!! $total+$detentionBill !!}" id="service" name="service_total">
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Drugs Total:
                                                        <span  class="badge badge-pill">GH₵ <span id="drugsText"></span></span>
                                                        <input type="hidden" value="0" name="drugs_total" id="drugs">
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Grand Total:
                                                        <span class="badge badge-pill"> GH₵ <span id="grandText">0.00</span> </span>
                                                        <input type="hidden" value="0" id="grand" name="grand_total">
                                                    </li>
                                                </ul>
                                            </blockquote>
                                        </div>
                                        <div class="col-md-6">
                                            <blockquote class="blockquote" style="border: dashed 1px;">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <b>Amount Patient Can Pay</b>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        Grand Total
                                                        <span class="badge badge-pill">GH₵ <span class="dispenseTotal"></span></span>
                                                    </li>
                                                </ul>
                                            </blockquote>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2 mt-5">
                                        <div class="col-md-4 offset-md-8">
                                            <label for="">Amount Paid</label>
                                            <div class="input-group">
                                                <input type="number" min="0" required class="form-control" data-inputmask="'alias': 'currency'" name="amount_paid" placeholder="Amount Paid">
                                                <div class="input-group-prepend bg-info text-white">
                                                    <button type="submit" class="input-group-text btn text-white">
                                                        Dispense
                                                    </button>
                                                </div>
                                                <div class="invalid-feedback">
                                                    Amount required
                                                </div>
                                            </div>
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
        @elseif($registration == 1)
            me
        @else
            <div class="row">
                <div class="col-md-12 text-center">
                    <h6 class="display-4">
                        Relax! No patient in Queue
                    </h6>
                </div>
            </div>
        @endif
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection