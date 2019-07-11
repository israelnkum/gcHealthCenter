@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        {{--Search form--}}
        <div class="row">
            <div class="col-md-8 offset-md-2 text-right grid-margin">
                <form class="needs-validation" novalidate action="{{route('searchPatientForDrugDispersion')}}" method="get">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-12 ">
                            <div class="input-group">
                                <input type="text" required class="form-control" name="search" placeholder=" Search by Folder Number or Patient's Last Name or Phone Number">
                                <div class="input-group-prepend">
                                    {{--<div class="form-check form-check-flat">
                                        <label class="form-check-label">
                                            <input type="checkbox"   class="form-check-input">
                                            Detaned
                                        </label>
                                    </div>--}}
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
        {{--End search form --}}

        {{--Statistics--}}
        {{-- <div class="row">
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
                                 --}}{{--                                <small class="text-gray">Raised from 89 orders.</small>--}}{{--
                             </div>
                             <div class="d-inline-block">
                                 <h4 class="card-title mb-0">Total Sales (Nationwide)</h4>
                                 <small class="text-gray">Today</small>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>--}}
        {{--End Statistics--}}

        @if(!empty($recentRegistration))
            <div class="row">
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Patient's Information</h4>
                            {{--display patient information--}}
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
                            {{--End patient information--}}
                            <hr>

                            <h6 class="card-title">Vital Signs</h6>
                            {{--Vital Signs--}}
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
                                    <small style="font-size: 13px">RDT - <span class="text-danger">{{$vitals->RDT}}</span></small>
                                </div>
                            </div>
                            {{--End Vital Signs--}}
                            <hr>

                            {{--Bill--}}
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
                                        {{--If patient is discharged, then add detention bill to other bills--}}
                                        @if($recentRegistration->detain == 1 && $detentionBill)
                                            <tr>
                                                <td>Detention</td>
                                                <td>{{$detentionBill}}.00</td>
                                            </tr>
                                        @endif
                                        {{--<tr class="bg-primary text-white">
                                            <td class="p-2 text-right">
                                                Total
                                            </td>
                                            @if($recentRegistration->detain == 2 && $detentionBill)
                                                <td class="p-2">GH₵ {!! $serviceTotal= $detentionBill+ floatval(substr($arrears->arrears,1)) !!}</td>
                                            @else
                                                <td class="p-2">GH₵ {!! $serviceTotal= floatval(substr($arrears->arrears,1)) !!}</td>
                                            @endif
                                        </tr>
                                        @if($recentRegistration->detain == 2 && $detentionBill)
                                            <td class="p-2">
                                                @php($serviceTotal= $detentionBill+ floatval(substr($arrears->arrears,1)))
                                            </td>
                                        @else
                                            @php( $serviceTotal= floatval(substr($arrears->arrears,1)))
                                        @endif
                                        if patient is still detained, then don't add detention bill
                                        @if($recentRegistration->detain == 1 && $detentionBill)
                                            <tr>
                                                <td>Detention</td>
                                                <td>{{$detentionBill}}.00</td>
                                            </tr>
                                        @endif--}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--End Bill--}}
                        </div>
                    </div>
                </div>
                <div class="col-md-8 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body" >
                            {{--check if patient has no drug to take--}}
                            @if(count($medication) !=0)
                                <h4 class="card-title">Dispense Drugs</h4>
                                <a href="javascript:void(0)" id="btn_print">Print</a>
                             <div id="print_div">
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
                                                 <th>Price</th>
                                                 <th>Qty</th>
                                                 <th>Taken</th>
                                                 <th>Dispensed</th>
                                             </tr>
                                             <tbody>
                                             @php( $i =1)
                                             @foreach($medication as $med)
                                                 <tr>
                                                     <td style="display: none;"><input type="hidden" value="{{$med->medication_id}}" name="medication_id[]"></td>
                                                     <td>
                                                         {{$med->item}}
                                                         <input type="hidden" name="item[]" value="{{$med->item}}">
                                                     </td>
                                                     <td>{{$med->dosage}} x {{$med->days}}days</td>
                                                     <td>
                                                         {{$med->amount}}
                                                         <input type="hidden" name="insurance" value="{{$med->insurance_amount}}">
                                                         <input type="hidden" name="price[]" value="{{$med->amount}}">
                                                     </td>
                                                     <td>{{$med->qty}}</td>
                                                     <td>{{$med->qty_dispensed}}</td>
                                                     <td>
                                                         <input name="qty_dispensed[]" value="0" min="0" max="{{$med->qty-$med->qty_dispensed}}" type="number" style="width: 80px;" class="val3 form-control">
                                                     </td>
                                                 </tr>
                                             @endforeach
                                             {{--@foreach($medication as $med)
                                                 <tr class="arrearsMulti">
                                                     <td>{{$med->bill->item}}</td>
                                                     <td>{{$med->dosage}} x {{$med->days}}days</td>
                                                     <td>
                                                         @if($recentRegistration->isInsured ==0)
                                                             @if($med->drugs->unit_of_pricing == "Blister (x10tabs)")
                                                                 <span class="val1">{{$med->drugs->retail_price/10}}</span>
                                                                 <input name="price[{{$med->drugs->id}}]" type="number" hidden value="{{$med->drugs->retail_price/10}}">
                                                             @else
                                                                 <span class="val1">{{$med->drugs->retail_price}}</span>
                                                                 <input name="price[{{$med->drugs->id}}]" type="number" hidden value="{{($med->drugs->retail_price)}}">
                                                             @endif
                                                         @else
                                                             @if($med->drugs->unit_of_pricing == "Blister (x10tabs)")
                                                                 <span class="val1">{{($med->drugs->retail_price-$med->drugs->nhis_amount)/10}}</span>
                                                                 <input name="price[{{$med->drugs->id}}]" type="number" hidden value="{{($med->drugs->retail_price-$med->drugs->nhis_amount)/10}}">
                                                             @else
                                                                 <span class="val1">{{$med->drugs->retail_price-$med->drugs->nhis_amount}}</span>
                                                                 <input name="price[{{$med->drugs->id}}]" type="number" hidden value="{{($med->drugs->retail_price-$med->drugs->nhis_amount)}}">
                                                             @endif
                                                         @endif
                                                         --}}{{--<span class="val1">{{$med->bill->total_amount_to_pay}}</span>--}}{{--
                                                     </td>
                                                     <td>
                                                         {{$med->qty}}
                                                         <input name="number_to_dispensed[{{$med->drugs->id}}]" required value="{{$med->qty}}" min="1" max="{{$med->drugs->quantity_in_stock}}" type="number" hidden style="width: 80px;" class=" form-control">
                                                     </td>

                                                     <td>
                                                         {{$med->qty_dispensed}}
                                                     </td>
                                                     <td>
                                                         <input name="number_dispensed[{{$med->drugs->id}}]" value="0" min="0" max="{{$med->qty-$med->qty_dispensed}}" type="number" style="width: 80px;" class="val2 form-control">
                                                     </td>
                                                     --}}{{--<td>
                                                         <span class="arrearsTotal">0</span>
                                                     </td>--}}{{--
                                                 </tr>
                                             @endforeach--}}

                                             <tr>
                                                 <td></td>
                                                 <td></td>
                                             </tr>
                                             @if($arrears->arrears != 0)
                                                 <tr class="mt-3">
                                                     <td class="p-2 text-white text-right"   ></td>
                                                     <td class="p-2 text-dark text-center mt-3">Arrears: GH₵ {!! str_replace('-','',$arrears->arrears) !!}</td>
                                                     <td class="p-2 text-center" >
                                                         <input type="hidden" value="{!! str_replace('-','',$arrears->arrears) !!}" id="arrears" name="service_total">
                                                     </td>
                                                     <td></td>
                                                 </tr>
                                             @endif
                                         </table>
                                         <div class="form-group row mb-2 mt-5">
                                             <div class="col-md-4 offset-md-8">
                                                 @if($arrears->arrears != 0)
                                                     <label for="">Amount Paid</label>
                                                 @endif
                                                 <div class="input-group">
                                                     @if($arrears->arrears != 0)
                                                         <input type="hidden" name="arrears" value="{{str_replace('-','',$arrears->arrears)}}">
                                                         <input type="text" min="0" required class="form-control" data-inputmask="'alias': 'currency'" name="amount_paid" placeholder="Amount Paid">
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

                             </div>
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
                                            <input type="text" name="amount_paid" min="0" data-inputmask="'alias': 'currency'" max="{{substr($arrears->arrears,1)}}" id="amount_paid" class="form-control" required>
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