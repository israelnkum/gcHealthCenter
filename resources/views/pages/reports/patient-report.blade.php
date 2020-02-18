@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Generate Reports (Patients)</h4>
                    <form action="{{route('patients-reports')}}" method="get" >
                        <div class="form-row">
                            <div class="col-md-2">
                                <label for="patient-type">Type</label>
                                <select name="type" id="patient-type" class="form-control selectMedicine " style="width: 100%;">
                                    <option value=""></option>
                                    {{--                                        <option {{ old('type') == 'All' ? 'selected' : '' }} value="All">All</option>--}}
                                    <option {{ old('type') == '1' ? 'selected' : '' }} value="1">Detained Patients</option>
                                    <option {{ old('type') == '2' ? 'selected' : '' }} value="2">Discharged Patients</option>
                                </select>
                            </div>
                            <div class="col-md-2 date-between ">
                                <label for="age_between">Date (FROM)</label>
                                <div id="datepicker-popup" class="input-group date datepicker">
                                    <input type="text"  value="{{ old('from')}}"  name="from" placeholder="From" class="form-control">
                                    <span class="input-group-addon input-group-append border-left">
                                                                    <span class="icon-calendar input-group-text"></span>
                                                                </span>
                                </div>
                            </div>
                            <div class="col-md-2 date-between" >
                                <label for="age_between">Date (TO)</label>
                                <div id="datepicker-popup1" class="input-group date datepicker">
                                    <input placeholder="To" value="{{ old('to')}}" name="to"  type="text" class="form-control">
                                    <span class="input-group-addon input-group-append border-left">
                                                                            <span class="icon-calendar input-group-text"></span>
                                                                        </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="Type">Gender</label>
                                <select name="gender" id="Type" class="form-control selectMedicine filterPatient" style="width: 100%;">
                                    <option value=""></option>
                                    <option {{ old('gender') == 'Male' ? 'selected' : '' }}  value="Male">Male</option>
                                    <option {{ old('gender') == 'Female' ? 'selected' : '' }}  value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="Type">Marital Status</label>
                                <select name="marital_status" id="Type" class="form-control selectMedicine filterPatient" style="width: 100%;">
                                    <option value=""></option>
                                    <option {{ old('marital_status') == 'Single' ? 'selected' : '' }}  value="Single">Single</option>
                                    <option {{ old('marital_status') == 'Married' ? 'selected' : '' }} value="Married">Married</option>
                                    <option {{ old('marital_status') == 'Divorced' ? 'selected' : '' }} value="Divorced">Divorced</option>
                                    <option {{ old('marital_status') == 'Widowed' ? 'selected' : '' }} value="Widowed">Widowed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
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
                            {{--<div class="col-md-2">
                                <label for="age_between">Age(Between)</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="number" min="1" class="form-control" id="age_between" placeholder="10">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" min="1" class="form-control" id="age_between" placeholder="20">
                                    </div>
                                </div>
                            </div>--}}
                            <div class="col-md-4 text-left mt-2">
                                <button class="btn  mt-4 btn-secondary" type="submit">Filter</button>

                                <button name="btn_export" value="export" type="submit" class="btn btn-success mt-4"> <i class="icon icon-docs"></i> Export</button>

                            </div>
                        </div>
                    </form>

                    @if(!empty($data))
                        <div class="text-center mb-0 text-capitalize">
                          <h4>Search result</h4>
                        </div>
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
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
@endsection
