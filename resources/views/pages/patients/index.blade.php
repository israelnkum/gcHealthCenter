@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 grid-margin offset-md-2">
                <form class="needs-validation" novalidate action="{{route('searchPatient')}}" method="get">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-12">
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
            <div class="col-md-2">
            {{--<button type="button" class="btn btn-danger  mr-3" data-toggle="modal" data-target="#deleteStaff">
                <i class="icon icon-trash"></i> Delete Selected
            </button>--}}
            <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#newPatient">
                    <i class="icon icon-plus"></i> New Patient
                </button>
            </div>
        </div>
        @if($data == "none")
        @elseif(count($data) == 0)
            <div class="">
                <div class="card-body text-center">
                    <h4 class="card-title"></h4>
                    <img class="img-fluid mt-0" src="{{asset('public/images/no_result.png')}}" alt="">
                    <br>
                    {{--                    <h4 class="display-4">Try again</h4>--}}
                </div>
            </div>
        @elseif(count($data) == 1)
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Patient's Information</h4>
                            <div class="row ml-md-0 mr-md-0 vertical-tab tab-minimal">
                                <ul class="nav nav-tabs col-md-2 " role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tab-2-1" data-toggle="tab" href="#patient-profile" role="tab" aria-controls="patient-profile" aria-selected="true">
                                            <i class="icon-user"></i>
                                            Profile
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-2-2" data-toggle="tab" href="#patient-registration" role="tab" aria-controls="patient-registration" aria-selected="false">
                                            <i class="icon-pencil"></i>
                                            Registration
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-2-2" data-toggle="tab" href="#patient-records" role="tab" aria-controls="patient-records" aria-selected="false">
                                            <i class="icon-cloud-upload"></i>
                                            Upload Old Records
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content col-md-10">
                                    {{--Profile Table --}}
                                    <div class="tab-pane fade show active" id="patient-profile" role="tabpanel" aria-labelledby="tab-2-1">
                                        <div class="row">
                                            <div class="col-md-12 text-right">
                                                <ul class="list">
                                                    <li class="d-inline-block">
                                                        <a class="text-dark p-1" href="{{route('patients.edit',$data[0]->id)}}">
                                                            <i class="icon icon-note  mt-3"  style="font-size: 20px"></i>
                                                        </a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                      {{--  <form method="post" action="{{route('patients.destroy',$data[0]->id)}}">
                                                            {!! method_field('delete') !!}
                                                            @csrf
                                                            <button class="btn bg-transparent text-dark p-1">
                                                                <i class="icon icon-trash" style="font-size: 20px"></i>
                                                            </button>
                                                        </form>--}}
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-md-12 pl-md-5">
                                                <h4 class="mb-3"><span class="text-uppercase text-danger">Folder Number</span> :{{$data[0]->folder_number}}</h4>
                                                <div class="wrapper mb-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <blockquote class="blockquote">
                                                                <h6 class=" mb-0 text-uppercase text-primary">Personal Information</h6>
                                                                <ul class="list-group" >
                                                                    <li  style="border: none" class="list-group-item d-flex  justify-content-between p-1 ">
                                                                        <b>First Name :</b>
                                                                        <span class="">{{$data[0]->first_name}}</span>
                                                                    </li>
                                                                    <li style="border: none" class="list-group-item d-flex justify-content-between p-1">
                                                                        <b>Middle Name :</b>
                                                                        <span>{{$data[0]->other_name}}</span>
                                                                    </li>
                                                                    <li style="border: none" class="list-group-item d-flex justify-content-between p-1 ">
                                                                        <b> Last Name :</b>
                                                                        <span>{{$data[0]->last_name}}</span>
                                                                    </li>
                                                                    <li style="border: none" class="list-group-item d-flex justify-content-between p-1 ">
                                                                        <b> Date Of Birth :</b>
                                                                        <span>{{$data[0]->date_of_birth}}</span>
                                                                    </li>
                                                                    <li style="border: none" class="list-group-item d-flex justify-content-between p-1 ">
                                                                        <b>Occupation:</b>
                                                                        <span>{{$data[0]->occupation}}</span>
                                                                    </li>
                                                                    <li style="border: none" class="list-group-item d-flex justify-content-between p-1 ">
                                                                        <b>Gender:</b>
                                                                        <span>{{$data[0]->gender}}</span>
                                                                    </li>
                                                                    <li style="border: none" class="list-group-item d-inline-flex justify-content-between p-1 ">
                                                                        <b>Religion:</b>
                                                                        <span>{{$data[0]->religion}}</span>
                                                                    </li>
                                                                </ul>
                                                            </blockquote>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <blockquote class="blockquote">
                                                                <h6 class=" mb-0 text-uppercase text-primary">Contact Information</h6>
                                                                <ul class="list-group" >
                                                                    <li  style="border: none" class="list-group-item d-flex  justify-content-between p-1 ">
                                                                        <b>Address:</b>
                                                                        <span class="">{{$data[0]->postal_address}}</span>
                                                                    </li>
                                                                    <li style="border: none" class="list-group-item d-flex justify-content-between p-1">
                                                                        <b>Home Address:</b>
                                                                        <span>{{$data[0]->house_number}}</span>
                                                                    </li>
                                                                    <li  style="border: none" class="list-group-item d-flex  justify-content-between p-1 ">
                                                                        <b>Locality:</b>
                                                                        <span class="">{{$data[0]->locality}}</span>
                                                                    </li>
                                                                    <li  style="border: none" class="list-group-item d-flex  justify-content-between p-1 ">
                                                                        <b>Phone Number:</b>
                                                                        <span class="">{{$data[0]->phone_number}}</span>
                                                                    </li>
                                                                </ul>
                                                            </blockquote>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-5">
                                                        <div class="col-md-6">
                                                            <blockquote class="blockquote">
                                                                <h6 class=" mb-0 text-uppercase text-primary">Other Information</h6>
                                                                <ul class="list-group" >
                                                                    <li  style="border: none" class="list-group-item d-flex  justify-content-between p-1 ">
                                                                        <b>Marital Status:</b>
                                                                        <span class="">{{$data[0]->marital_status}}</span>
                                                                    </li>
                                                                    <li style="border: none" class="list-group-item d-flex justify-content-between p-1">
                                                                        <b>Other Info :</b>
                                                                        <span>{{$data[0]->other_information}}</span>
                                                                    </li>
                                                                    <li  style="border: none" class="list-group-item d-flex  justify-content-between p-1 ">
                                                                        <b>Relative's Name:</b>
                                                                        <span class="">{{$data[0]->name_of_nearest_relative}}</span>
                                                                    </li>
                                                                    <li style="border: none" class="list-group-item d-flex justify-content-between p-1">
                                                                        <b>Relative's Phone Number:</b>
                                                                        <span>{{$data[0]->number_of_nearest_relative}}</span>
                                                                    </li>
                                                                </ul>
                                                            </blockquote>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--Registration Tab--}}
                                    <div class="tab-pane fade" id="patient-registration" role="tabpanel" aria-labelledby="tab-2-2">
                                        <div class="row">
                                            <div class="col-md-7 pl-md-5">
                                                <div class="wrapper mb-4">
                                                    <h4 class="mb-3">Recent Registration</h4>
                                                    <?php
                                                    $registration = $data[0]->registration;
                                                    ?>
                                                    @foreach($registration as $key => $registered)
                                                        @if($key == count( $registration ) -1 )
                                                            <div class="d-flex align-items-center py-3 border-bottom">
                                                                <div class="ml-3">
                                                                    <h6 class="mb-1">New Registration</h6>
                                                                    <small class="text-muted mb-0"><i class="icon-location-pin-outline mr-1"></i>{{$registered->created_at}}</small>
                                                                </div>
                                                                <div class="ml-3">
                                                                    @if($registered->insurance_type =="")
                                                                        <span class="badge badge-info text-white">Non insured</span>
                                                                    @else
                                                                        <h6 class="mb-1">{{$registered->insurance_type}}</h6>
                                                                    @endif
                                                                    <small class="text-muted mb-0"><i class="icon-location-pin-outline mr-1"></i>{{$registered->insurance_number}}</small>
                                                                </div>
                                                                <?php
                                                                $last_seen =\Carbon\Carbon::createFromTimeStamp(strtotime($registered->updated_at))->diffForHumans();
                                                                ?>
                                                                <i class="icon-check font-weight-bold ml-auto px-1 py-1 text-info"> {!! $last_seen !!}</i>
                                                            </div>

                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            @if(count($registration)  == 0)
                                                <div class="col-md-5 p-3" style="border-radius: 20px; border:solid black 1px;">
                                                    <form novalidate class="needs-validation" method="post" action="{{route('registration.store')}}">
                                                        @csrf
                                                        <div class="form-row form-group">
                                                            <div class="col-md-6">
                                                                <div class="form-check form-check-flat mt-0">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" name="register_patient" id="register_patient">
                                                                        Register
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check form-check-flat mt-0" id="patient_insured_div" style="display: none;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" disabled  name="insured" id="patient_insured">
                                                                        Insured
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-row form-group">
                                                            <div class="col-md-6" >
                                                                <div id="patient_charge_div" style="display: none;">
                                                                    <label for="patient_charges">Charge</label>
                                                                    <select  title="Select Charge"  name="charges" id="patient_charges" class="js-example-basic-single form-control" style="width: 100%" >
                                                                        <option value="">~Charge~</option>
                                                                        @foreach($charges as $charge)
                                                                            @if($charge->name == "Insured" || $charge->name == "Non-Insured")
                                                                                <option value="{{$charge->id}}">{{$charge->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="invalid-feedback">
                                                                        Charge is required.
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="patient_id" value="{{$data[0]->id}}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div  id="patient_insurance_number_div" style="display: none;">
                                                                    <input title="Enter Insurance Number" type="text" name="insurance_number" class="form-control text-uppercase mb-1" id="patient_insurance_number" placeholder="Insurance Number">
                                                                    <div class="invalid-feedback">
                                                                        Insurance Number Required
                                                                    </div>

                                                                    <label class="mt-2">Insurance Type</label>
                                                                    <select title="Select Insurance Type"   name="insurance_type" id="patient_insurance_type" class="js-example-basic-single form-control" style="width: 100%" >
                                                                        <option value="">~Insurance Type~</option>
                                                                        @foreach($insuranceType as $type)
                                                                            <option value="{{$type->name.",".$type->amount}}">{{$type->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="invalid-feedback">
                                                                        Insurance Type is required.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row ">
                                                            <div class="col-md-12 text-right">
                                                                <button class="btn btn-dark" disabled id="btn_register" type="submit">Register</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            @else
                                                @foreach($registration as $key => $registered)
                                                    @if($key == count( $registration ) -1 )
                                                        @if(substr($registered->created_at,0,10) != date('Y-m-d') && $registered->detain != 1)
                                                            <div class="col-md-5 p-3" style="border-radius: 20px; border:solid black 1px;">
                                                                <form novalidate class="needs-validation" method="post" action="{{route('registration.store')}}">
                                                                    @csrf
                                                                    <div class="form-row form-group">
                                                                        <div class="col-md-6">
                                                                            <div class="form-check form-check-flat mt-0">
                                                                                <label class="form-check-label">
                                                                                    <input type="checkbox" class="form-check-input" name="register_patient" id="register_patient">
                                                                                    Register
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-check form-check-flat mt-0" id="patient_insured_div" style="display: none;">
                                                                                <label class="form-check-label">
                                                                                    <input type="checkbox" class="form-check-input" disabled  name="insured" id="patient_insured">
                                                                                    Insured
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-row form-group">
                                                                        <div class="col-md-6" >
                                                                            <div id="patient_charge_div" style="display: none;">
                                                                                <label>Charge</label>
                                                                                <select title="Select Charge"  name="charges" id="patient_charges" class="js-example-basic-single form-control" style="width: 100%" >
                                                                                    <option value="">~Charge~</option>
                                                                                    @foreach($charges as $charge)
                                                                                        @if($charge->name == "Insured" || $charge->name=="Non-Insured")
                                                                                            <option value="{{$charge->id}}">{{$charge->name}}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                                <div class="invalid-feedback">
                                                                                    Charge is required.
                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" name="patient_id" value="{{$data[0]->id}}">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div  id="patient_insurance_number_div" style="display: none;">
                                                                                <input title="Enter Insurance Number" type="text" name="insurance_number" class="form-control text-uppercase mb-1" id="patient_insurance_number" placeholder="Insurance Number">
                                                                                <div class="invalid-feedback">
                                                                                    Insurance Number Required
                                                                                </div>

                                                                                <label class="mt-2">Insurance Type</label>
                                                                                <select title="Select Insurance Type"   name="insurance_type" id="patient_insurance_type" class="js-example-basic-single form-control" style="width: 100%" >
                                                                                    <option value="">~Insurance Type~</option>
                                                                                    @foreach($insuranceType as $type)
                                                                                        <option value="{{$type->name.",".$type->amount}}">{{$type->name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                <div class="invalid-feedback">
                                                                                    Insurance Type is required.
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row ">
                                                                        <div class="col-md-12 text-right">
                                                                            <button class="btn btn-dark" disabled id="btn_register" type="submit">Register</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            @else
                                                            <div class="col-md-5 p-3" style="border-radius: 20px; border:solid black 1px;">
                                                                <h4 class="display-4">Patient is Detained</h4>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="patient-records" role="tabpanel" aria-labelledby="tab-2-2">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <blockquote class="blockquote" style="border: dashed 1px;">
                                                    <h5 class="text-uppercase">Upload Old Record(s)</h5>

                                                    <form action="{{route('upload-records')}}" enctype="multipart/form-data" method="post" novalidate class="needs-validation">
                                                        @csrf
                                                        <div class="form-group row">
                                                            <div class="col-md-6 offset-md-3">
                                                                <input type="hidden"  name="patient_id" value="{{$data[0]->id}}">
                                                                <input required type="file" class="form-control-file bg-dark text-white" name="old_records[]" multiple>
                                                                <div class="invalid-feedback">
                                                                    No file selected
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-6 offset-md-3">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        Record Date
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <input required placeholder="Record Date" type="date" class="form-control" name="record_date">
                                                                        <div class="invalid-feedback">
                                                                            Date is required
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-12 text-center">
                                                                <button class="btn btn-dark" type="submit">Upload Record(s)</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </blockquote>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(count($data) > 1)
            <div class="row">
                @foreach($data as $dat)
                    @if($dat->status != 1)
                        <div class="col-md-6 grid-margin">
                            <div class="card">
                                <h4 class="card-title mt-0"></h4>
                                <div class="card-body">
                                    <h6 class=" text-uppercase mb-0">{{$dat->title." ".$dat->first_name." ".$dat->other_name." ".$dat->last_name}}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{route('patients.show',$dat->id)}}" style="text-decoration: none" class="">

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
                    @endif
                @endforeach
            </div>
        @endif
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->


    <!-- new patient modal -->
    <div class="modal fade" id="newPatient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form method="post" action="{{route('patients.store')}}" class="needs-validation" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Patient</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="form-row">
                            <div class="col-md-3">
                                <select title="Select Title" id="title" name="title" class="js-example-basic-single form-control" style="width: 100%" required>
                                    <option value="">~Title~</option>
                                    <option value="Mr">Mr</option>
                                    <option value="Mrs">Mrs</option>
                                    <option value="Miss">Miss</option>
                                </select>
                                <div class="invalid-feedback">
                                    Title is required.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input title="Enter First name" type="text" name="first_name" class="form-control" id="first_name" placeholder="First Name"  required>
                                <div class="invalid-feedback">
                                    First Name is required
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input title="Enter Last Name" type="text" name="last_name" class="form-control" id="last_name" placeholder="Last Name"  required>
                                <div class="invalid-feedback">
                                    Last Name is required
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input title="Enter Other Name" type="text" name="other_name" class="form-control" id="other_name" placeholder="Other Name"  >

                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <div class="row">
                                    <label for="dateOfBirth" class="col-md-4 col-form-label">Date of Birth</label>

                                    <div class="col-md-8">
                                        <input placeholder="Date of Birth" type="date" name="date_of_birth" class="form-control"  required>
                                        <div class="invalid-feedback">
                                            Date of Birth is required.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select title="Select Gender" name="gender" id="gender" class="js-example-basic-single form-control" style="width: 100%" required>
                                    <option value="">~Select Gender~</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <div class="invalid-feedback">
                                    Gender is required.
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <input title="Enter Postal Address" type="text" name="postal_address" class="form-control" id="postal_address" placeholder="Postal Address" >
                                <div class="invalid-feedback">
                                    Postal Address is required
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input title="Enter House Number" type="text" name="house_number" class="form-control" id="house_number" placeholder="House Number" >
                                <div class="invalid-feedback">
                                    House Number is required
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <input title="Enter Locality" type="text" name="locality" class="form-control" id="locality" placeholder="Locality"  required>
                                <div class="invalid-feedback">
                                    Locality is required
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input required type="text"  class="form-control phone-inputmask"  id="phone_number" minlength="10"   name="phone_number" placeholder="Phone Number">
                                <div class="invalid-feedback">
                                    Phone Number is required.
                                </div>
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <div class="col-md-6">
                                <input title="Enter Occupation" type="text" name="occupation" class="form-control" id="occupation" placeholder="Occupation"  required>
                                <div class="invalid-feedback">
                                    Occupation is required
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select title="Select Religion" id="religion" name="religion" class="js-example-basic-single form-control" style="width: 100%" required>
                                    <option value="">~Select Religion~</option>
                                    <option value="Christianity">Christianity</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Hinduism">Hinduism</option>
                                    <option value="Buddhism">Buddhism</option>
                                    <option value="Non Religious">Non Religious</option>
                                </select>
                                <div class="invalid-feedback">
                                    Religion is required.
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <input title="Enter Name Of Nearest Relative" type="text" name="name_of_relative" class="form-control" id="name_of_relative" placeholder="Name Of Nearest Relative">
                                {{--<div class="invalid-feedback">
                                    Locality is required
                                </div>--}}
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text"  class="form-control  phone-inputmask"  id="relative_phone_number" minlength="10"   name="relative_phone_number" placeholder="Relative Phone Number">
                                {{--<div class="invalid-feedback">
                                    Phone Number is required.
                                </div>--}}
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <div class="col-md-6">
                                <select title="Select Marital Status" id="marital_status" name="marital_status" class="js-example-basic-single form-control" style="width: 100%" required>
                                    <option value="">~Select Marital Status~</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                                <div class="invalid-feedback">
                                    Marital Status is required.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    {{--<div class="col-md-4">
                                        <div class="form-check form-check-flat mt-0">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="register_patient" id="register">
                                                Register
                                            </label>
                                        </div>
                                    </div>--}}
                                    <input type="hidden" name="register_patient" value="register_patient">
                                    <div class="col-md-2">
                                        <label for="charges" class="mt-3">Insurance</label>
                                    </div>
                                    <div class="col-md-6">
                                        <select required title="Select Charge"  name="charges" id="charges" class="js-example-basic-single form-control" style="width: 100%" >
                                            <option value="">~Insurance~</option>
                                            @foreach($charges as $charge)
                                                @if($charge->name == "Insured" || $charge->name=="Non-Insured")
                                                    <option value="{{$charge->id}}">{{$charge->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Field is required.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row form-group">
                            <div class="col-md-6">
                                <textarea name="other_information" id="other_information" placeholder="Other Information" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6" >
                                        <div class="form-check form-check-flat mt-0">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input"  name="insured" id="insurance">
                                                Insured
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div  id="insurance_number_div" style="display: none;">
                                            <input title="Enter Insurance Number" type="text" name="insurance_number" class="form-control text-uppercase mb-1" id="insurance_number" placeholder="Insurance Number">
                                            <div class="invalid-feedback">
                                                Insurance Number Required
                                            </div>

                                            <select title="Select Insurance Type"  name="insurance_type" id="insurance_type" class="js-example-basic-single form-control form-control" style="width: 100%" >
                                                <option value="">~Insurance Type~</option>
                                                @foreach($insuranceType as $type)
                                                    <option value="{{$type->name.",".$type->amount}}">{{$type->name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Insurance Type is required.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="row">
                                    <div class="col-md-6" >
                                        <div class="form-check form-check-flat mt-0">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input"  name="old_patient" id="old_patient">
                                                Old Patient
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div  id="old_patient_div" style="display: none;">
                                            <label for="last_visit">Last Visit</label>
                                            <input title="Last Visit" type="date" name="last_visit" class="form-control text-uppercase mb-1" id="last_visit" placeholder="Last Visit">
                                            <div class="invalid-feedback">
                                                Last Visit Required
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="icon icon-close"></i> Close
                        </button>
                        <button type="submit" id="btn_add_patient" class="btn btn-primary">
                            <i class="icon icon-plus"></i> Add Patient
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- edit Staff modal -->
    <div class="modal fade" id="editStaff" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="/users" id="editStaffForm" class="needs-validation" novalidate>
                @csrf
                {{method_field('put')}}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStaffTitle">New Staff</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <input title="Enter Username" type="text" name="username" class="form-control" id="edit_username" placeholder="Username"  required>
                                <div class="invalid-feedback">
                                    choose a username.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <input title="Enter Email Address" name="email" type="email" class="form-control" id="edit_email" placeholder="Email Address" required>
                                <div class="invalid-feedback">
                                    Email is required.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <input required type="text"  class="form-control phone_number"  id="edit_phone_number" minlength="10"   name="phone_number" placeholder="Phone Number">
                                <div class="invalid-feedback">
                                    Phone Number is required.
                                </div>
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <div class="col-md-12">
                                <select title="Select Role" id="edit_role" name="role" class="js-example-basic-single form-control" style="width: 100%" required>
                                    <option value="">~Select Role~</option>
                                    <option value="Doctor">Doctor</option>
                                    <option value="Nurse">Nurse</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Pharmacist">Pharmacist</option>
                                </select>
                                <div class="invalid-feedback">
                                    Role is required.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="icon icon-close"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon icon-plus"></i> Add Staff
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirm bulk Staff modal -->
    <div class="modal fade" id="deleteStaff" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Staff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <p>Are you want to delete selected staff</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="icon icon-close"></i> Close
                    </button>
                    <button type="submit" id="btn_submit_bulk_delete" class="btn btn-success">
                        <i class="icon icon-trash"></i> Yes! Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection