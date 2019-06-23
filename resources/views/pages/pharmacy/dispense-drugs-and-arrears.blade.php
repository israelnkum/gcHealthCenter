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

        @if(!empty($recentRegistration))

            <div class="row">
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Patient's Information</h4>
                            <div class="row">
                                <div class="col-md-6">
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
                                    <small style="font-size: 14px">Date Of Birth: <span class="font-weight-bold">{{$recentRegistration->patient->date_of_birth}}</span></small><br>
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
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="">Bill</h4>
                                    <table class="table table-borderless">
                                        <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Total</th>
                                            <th>Amount Paid</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($arrears)
                                            <tr>
                                                <td>Total Bill</td>
                                                <td>{{$arrears->grand_total}}</td>
                                                <td>{{$arrears->amount_paid}}</td>
                                            </tr>
                                        @endif
                                        <tr class="bg-primary text-white">
                                            <td class="p-2 text-right"></td>
                                            <td class="p-2" colspan="2">Arrears:  GH₵ {{$arrears->arrears}}</td>
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
                            {{--check if patient has no drug to take--}}
                            @if(count($medication) !=0)
                                <h4 class="card-title">Dispense Drugs</h4>
                                <form action="{{route('payment.update',2)}}" method="post" class="needs-validation" novalidate>
                                    @csrf
                                    {!! method_field('put') !!}
                                    <input type="hidden" value="{{$recentRegistration->patient->id}}" name="patient_id">
                                    <input type="hidden" value="{{$recentRegistration->id}}" name="registration_id">
                                    <input type="hidden" value="{{$arrears->id}}" name="payment_id">
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th>Drug</th>
                                                <th>Dose</th>
                                                <th>S. Price</th>
                                                <th>N<u>o</u> to Dispense</th>
                                                <th>Last Dispensed</th>
                                                <th>Dispense</th>
{{--                                                <th>Total</th>--}}
                                            </tr>
                                            <tbody>
                                            @php( $i =1)

                                            @foreach($medication as $med)
                                                <tr class="arrearsMulti">
                                                    <td>{{$med->bill->item}}</td>
                                                    <td>{{$med->dosage}}</td>
                                                    <td>
                                                        <span class="val1">{{$med->bill->total_amount_to_pay}}</span>
                                                    </td>
                                                    <td>{{$med->number_to_dispense}}</td>
                                                    <td>
                                                        {{$med->number_dispensed}}
                                                    </td>
                                                    <td>
                                                        <input name="number_dispensed[{{$med->drugs->id}}]" value="0" min="0" max="{{$med->number_to_dispense-$med->number_dispensed}}" type="number" style="width: 80px;" class="val2 form-control">
                                                    </td>
                                                    {{--<td>
                                                        <span class="arrearsTotal">0</span>
                                                    </td>--}}
                                                </tr>
                                            @endforeach

                                            <tr>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @if($arrears->arrears != 0)
                                                <tr class="mt-3">
                                                    <td class="p-2 text-white text-right"   ></td>
                                                    <td class="p-2 text-dark text-center mt-3">Arrears: GH₵ {!! substr($arrears->arrears,1) !!}</td>
                                                    <td class="p-2 text-center" >
                                                        <input type="hidden" value="{!! substr($arrears->arrears,1) !!}" id="arrears" name="service_total">
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                {{--<tr>
                                                    <td class="p-2 text-white text-right"></td>
                                                    <td class="p-2 text-dark text-center">Drugs Total: GH₵ <span id="drugsText">0.00</span></td>
                                                    <td class="p-2 text-dark  text-right">
                                                        <input type="hidden" value="0" name="drugs_total" id="drugs">
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr class="bg-success ">
                                                    <td class="p-2 text-white text-right" ></td>
                                                    <td class="p-2 text-white text-center">
                                                        Grand Total: GH₵ <span id="grandText">0.00</span>
                                                    </td>
                                                    <td class="p-2 text-dark  text-right">
                                                        <input type="hidden" value="0" id="grand" name="grand_total">
                                                    </td>
                                                    <td colspan="3"></td>
                                                </tr>--}}
                                            @endif
                                        </table>
                                        <div class="form-group row mb-2 mt-5">
                                            <div class="col-md-4 offset-md-8">
                                                @if($arrears->arrears != 0)
                                                    <label for="">Amount Paid</label>
                                                @endif
                                                <div class="input-group">
                                                    @if($arrears->arrears != 0)
                                                        <input type="hidden" name="arrears" value="{{substr($arrears->arrears,1)}}">
                                                        <input type="number" min="0" required class="form-control" data-inputmask="'alias': 'currency'" name="amount_paid" placeholder="Amount Paid">
                                                    @endif
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
                                </form>

                            @elseif(count($medication)== 0 && $arrears->arrears !=0)
                                {{--if patient has arrears--}}
                                <h4 class="card-title">Pay Arrears</h4>
                                <form action="{{route('payArrears')}}" method="post" class="needs-validation" novalidate>
                                    @csrf
                                    <input type="hidden" value="{{$recentRegistration->patient->id}}" name="patient_id">
                                    <input type="hidden" value="{{$recentRegistration->id}}" name="registration_id">
                                    <input type="hidden" name="payment_id" value="{{$arrears->id}}">
                                    <div class="form-group row p-3" style="border: dashed  black 1px;">
                                        <div class="col-md-4 offset-md-4">
                                            <label for="amount_paid">Amount Paid</label>
                                            {{--                                            {{substr($arrears->arrears,1)}}--}}
                                            <input type="number" name="amount_paid" min="0" max="{{substr($arrears->arrears,1)}}" id="amount_paid" class="form-control" required>
                                            <div class="invalid-feedback">
                                                Amount required
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-center offset-md-4 mt-3">
                                            <button class="btn btn-info">Pay Arrears</button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="text-center">
                                    <h4 class="display-4">No Drugs, No Arrears</h4>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection