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
            {{--            <div class="col-md-2">--}}
            {{--                <!-- Button trigger modal -->--}}
            {{--                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#newPatient">--}}
            {{--                    <i class="icon icon-plus"></i> New Patient--}}
            {{--                </button>--}}
            {{--            </div>--}}
        </div>
        <div class="row">
            <div class="col-md-3 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('filter-patients')}}" id="filterPatientForm">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="patient-type">Type</label>
                                    <select name="type" id="patient-type" class="form-control selectMedicine " style="width: 100%;">
                                        <option value=""></option>
{{--                                        <option {{ old('type') == 'All' ? 'selected' : '' }} value="All">All</option>--}}
                                        <option {{ old('type') == '1' ? 'selected' : '' }} value="1">Detained Patients</option>
                                        <option {{ old('type') == '2' ? 'selected' : '' }} value="2">Discharged Patients</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2 date-between ">
                                    <label for="age_between">Date (FROM)</label>
                                    <div id="datepicker-popup" class="input-group date datepicker">
                                        <input type="text"  value="{{ old('from')}}"  name="from" placeholder="From" class="form-control">
                                        <span class="input-group-addon input-group-append border-left">
                                                                    <span class="icon-calendar input-group-text"></span>
                                                                </span>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2 date-between" >
                                    <label for="age_between">Date (TO)</label>
                                    <div id="datepicker-popup1" class="input-group date datepicker">
                                        <input placeholder="To" value="{{ old('to')}}" name="to"  type="text" class="form-control">
                                        <span class="input-group-addon input-group-append border-left">
                                                                            <span class="icon-calendar input-group-text"></span>
                                                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label for="Type">Gender</label>
                                    <select name="gender" id="Type" class="form-control selectMedicine filterPatient" style="width: 100%;">
                                        <option value=""></option>
                                        <option {{ old('gender') == 'Male' ? 'selected' : '' }}  value="Male">Male</option>
                                        <option {{ old('gender') == 'Female' ? 'selected' : '' }}  value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label for="Type">Marital Status</label>
                                    <select name="marital_status" id="Type" class="form-control selectMedicine filterPatient" style="width: 100%;">
                                        <option value=""></option>
                                        <option {{ old('marital_status') == 'Single' ? 'selected' : '' }}  value="Single">Single</option>
                                        <option {{ old('marital_status') == 'Married' ? 'selected' : '' }} value="Married">Married</option>
                                        <option {{ old('marital_status') == 'Divorced' ? 'selected' : '' }} value="Divorced">Divorced</option>
                                        <option {{ old('marital_status') == 'Widowed' ? 'selected' : '' }} value="Widowed">Widowed</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label for="Type">Religion</label>
                                    <select name="religion" id="Type" class="form-control selectMedicine filterPatient" style="width: 100%;">
                                        <option value="">~Select Religion~</option>
                                        <option {{ old('religion') == 'Christianity' ? 'selected' : '' }} value="Christianity">Christianity</option>
                                        <option {{ old('religion') == 'Islam' ? 'selected' : '' }} value="Islam">Islam</option>
                                        <option {{ old('religion') == 'Hinduism' ? 'selected' : '' }} value="Hinduism">Hinduism</option>
                                        <option {{ old('religion') == 'Buddhism' ? 'selected' : '' }} value="Buddhism">Buddhism</option>
                                        <option {{ old('religion') == 'Non Religious' ? 'selected' : '' }} value="Non Religious">Non Religious</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2 text-right">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <button name="btn_export" value="export" type="submit" class="btn btn-success"> <i class="icon icon-docs"></i> Export</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('bulk_deleteCharge')}}" id="bulkDeleteChargeForm" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <h4 class="card-title">All Patients</h4>
                                </div>
                                <div class="col-md-6 text-right mb-3">
                                    <button type="button" class="btn btn-link text-danger" data-toggle="modal" data-target="#deleteCharge" id="deleteSelectedCharge" disabled>
                                        <i class="icon icon-trash"></i> Delete Selected
                                    </button>
                                    <span class="float-right">{{ $data->appends(Request::all())->links() }}</span>

                                </div>
                            </div>
                            <button name="btn_export" value="export" type="submit" class="btn btn-success"> <i class="icon icon-docs"></i> Export</button>
                            <div class="table-responsive">
                                <table class="table" id="patients_table">
                                    <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <input type="checkbox"  class="form-check-input"  id="checkAllCharges">
                                                </label>
                                            </div>
                                        </th>
                                        <th>Patient Info</th>
                                        <th>Other Info</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $i=1;
                                    @endphp
                                    @foreach($data as $datum)
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-flat">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" value="{{$datum->id}}" class="form-check-input checkItemCharge" name="selected_charges[]" id="remember">
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="text-uppercase">
                                                <i class="icon icon-user"></i> {{$datum->first_name." ".$datum->other_name." ".$datum->last_name}}
                                                <br>
                                                <i class="icon icon-folder"></i> {{$datum->folder_number}}
                                                <br>
                                                <i class="icon icon-phone"></i> {{$datum->phone_number}}
                                            </td>
                                            <td>{{$datum->date_of_birth}}
                                                <br>
                                                {{$datum->gender}}
                                                <br>
                                                {{$datum->marital_status}}
                                            </td>
                                            <td>
                                                <a role="button" href="{{route('patients.edit',$datum->id)}}" class="btn btn-sm btn-secondary edit_charge">
                                                    <i class="icon icon-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @php
                                            $i = $i+1;
                                        @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                <span class="float-right">{{ $data->appends(Request::all())->links() }}</span>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
@endsection
