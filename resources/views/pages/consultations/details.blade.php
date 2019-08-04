@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-8 text-right grid-margin">
                <form class="needs-validation" novalidate action="{{route('searchConsultation')}}" method="get">
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
                            <input type="hidden" name="fromSearchPage" value="fromSearchPage">
                            <select required name="data" class="js-example-basic-single w-100 form-control">
                                <option value="">Select Record Date</option>
                                @if(count($registration)  == 1)
                                    @foreach($allRegistrations as $userRegistration)
                                        {{--@if($count_registration == 0 && date('Y-m-d') == substr($userRegistration->created_at,0,10))
                                         @endif--}}
                                        <option  value="{{substr($userRegistration->created_at,0,10).",".$userRegistration->patient_id}}">{{substr($userRegistration->created_at,0,10)}}</option>
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
        </div>
        @if(count($registration)  == 0)
            <div class="text-center">
                <h6 class="display-4 text-info">Sorry! No is associated with this date</h6>
            </div>
        @else
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            @foreach($registration as $registered)
                                {{--Display patient's info --}}
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
                                {{--End patient's info --}}

                                {{--Display patient's registration --}}
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
                                            <p class="mb-0"><b>Insurance Number:</b> @if($registered->insurance_number){{$registered->insurance_number}} @else ----- @endif</p>
                                            <p class="mb-0"><b>Amount Charged:</b> {{$registered->registration_fee}}</p>
                                        </div>
                                    </div>
                                </blockquote>
                                {{--End patient's info --}}

                                {{--Display patient's vital signs --}}
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
                                {{--End patient's info --}}
                            @endforeach

                            {{--<label class="text-info">Total Bill</label>
                            <blockquote class="blockquote">
                                <div class="row">
                                    <table class="table table-borderless"  >
                                        <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php($total = 0)
                                        @foreach($getBills as $bill)
                                            <tr>
                                                <td>{{$bill->item}}</td>
                                                <td>{{$bill->total_amount_to_pay}}</td>
                                            </tr>
                                            @php($total +=$bill->total_amount_to_pay)
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
                                            <td class="p-2">GH₵ {!! $total+$detentionBill !!}.00</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="col-md-12">
                                        <h3>GH₵ {!! $total+$detentionBill !!}</h3>
                                    </div>
                                </div>
                            </blockquote>--}}
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="text-info">Medications</label>
                                    <blockquote class="blockquote">
                                        <ul>
                                            @foreach($medication as $med)
                                                <li>{{$med->drugs->name}}</li>
                                            @endforeach
                                        </ul>
                                    </blockquote>
                                </div>
                            </div>
                            @if($scanned_results)
                                <h4>Scan Result(s)</h4>
                                <div class="col-sm-12">
                                    <div id="lightgallery" class="row lightGallery">
                                        @foreach($scanned_results as $scan)
                                            <a href="{{asset('public/scan/'.$scan->file_name)}}" class="image-tile"><img src="{{asset('public/scan/'.$scan->file_name)}}" alt="{{$scan->file_name}}"></a>
                                        @endforeach
                                    </div>

                                </div>
                            @endif
                            @if($lab_results)
                                <h4>Lab Result(s)</h4>
                                <div class="col-sm-12">
                                    <div id="lightgallery-without-thumb" class="row lightGallery">
                                        @foreach($lab_results as $lab)
                                            <a href="{{asset('public/labs/'.$lab->file_name)}}" class="image-tile"><img src="{{asset('public/labs/'.$lab->file_name)}}" alt="{{$lab->file_name}}"></a>
                                        @endforeach
                                    </div>

                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <label for="">
                                        <form class="needs-validation" novalidate action="{{route('searchConsultation')}}" method="get">
                                            @csrf
                                            <input required type="hidden"  value="{{$registered->patient->folder_number}}" class="form-control" name="search">
                                            <button type="submit" class="btn-danger btn btn-sm p-1 text-small"><i class="icon icon-arrow-left-circle"></i> Back</button>
                                        </form>
                                    </label>
                                    <label class="badge badge-info">
                                        <a href="{{route('consultation.edit',$registered->id)}}" class="text-white" style="text-decoration: none">
                                            <i class="icon icon-note"> </i>Edit
                                        </a>
                                    </label>
                                </div>
                            </div>
                            <h6 class="card-title text-danger">Consultation</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="text-info">Complains</label>
                                    <blockquote class="blockquote">
                                        <p class="mb-0">{{$consultation->complains}}</p>
                                    </blockquote>
                                </div>
                               {{-- <div class="col-md-12 p-1">
                                    <label class="text-info">Findings</label>
                                    <blockquote class="blockquote">
                                        <p class="mb-0">{{$consultation->findings}}</p>
                                    </blockquote>
                                </div>--}}
                                <div class="col-md-12">
                                    <label class="text-info">Physical Examination</label>
                                    <blockquote class="blockquote">
                                        <p class="mb-0">{{$consultation->physical_examination}}</p>
                                    </blockquote>
                                </div>
                                <?php
                                $other_diagnosis =$consultation->other_diagnosis;
                                ?>
                            </div>
                            {{--End consulation--}}

                            {{--Diagnosis--}}
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="text-info">Diagnosis</label>
                                    <blockquote class="blockquote">
                                        <ul>
                                            @foreach($patientDiagnosis as $diagnosis)
                                                <li>{{$diagnosis->diagnoses->name}}</li>
                                            @endforeach
                                            @if($other_diagnosis != "")
                                                <li>{{$other_diagnosis}}</li>
                                            @endif
                                        </ul>
                                    </blockquote>
                                </div>
                            </div>
                            {{--End Diagnosis--}}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection