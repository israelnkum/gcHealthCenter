@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Generate Reports (Consultation)</h4>
                    <form action="{{route('gen-consultation-rpt')}}" method="get" >
                        @csrf
                        <div class="form-row">
                            <div class="col-md-2">
                                <label for="patient-type">Diagnosis</label>
                                <select name="diagnosis" id="patient-type" class="form-control selectMedicine " style="width: 100%;">
                                    <option value=""></option>
                                    @foreach($diagnosis as $diagnose)
                                        <option {{ old('diagnosis') == $diagnose->id ? 'selected' : '' }} value="1">{{$diagnose->name}}</option>
                                    @endforeach
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
                                    <th>Patient Info</th>
                                    <th>Diagnosis</th>
                                    <th style="width: 75%">Findings....................</th>
                                    <th>Complains</th>
                                    <th>Physical Examination</th>
                                    <th>Notes</th>
                                    <th>Doctor</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $datum)
                                    @isset($datum->patient)
                                        <tr>
                                            <td class="text-uppercase">
                                                <i class="icon icon-user"></i> {{$datum->patient->first_name." ".$datum->patient->other_name." ".$datum->patient->last_name}}
                                                <br>
                                                <i class="icon icon-folder"></i> {{$datum->patient->folder_number}}
                                                <br>
                                                <i class="icon icon-phone"></i> {{$datum->patient->phone_number}}
                                            </td>
                                            <td>
                                                @foreach ($datum->patient->patientDiagnosis as $diagnosis)
                                                    @isset($diagnosis->diagnoses)
                                                        {{$diagnosis->diagnoses['name']}}<br>
                                                    @endisset
                                                @endforeach
                                                {{--
                                                      {{$datum->other_diagnosis}}--}}

                                                {{--                                                @foreach ($datum->patient->medication as $med)--}}
                                                {{--                                                    {{$med->drugs->name}}<br>--}}
                                                {{--                                                @endforeach--}}
                                            </td>
                                            <td class="">
                                            <textarea rows="10" cols="20" class="form-control" name="" id="" >
                                                {{$datum->findings}}
                                            </textarea>
                                            </td>
                                            <td>
                                            <textarea rows="10" cols="10" class="form-control" name="" id="" >
                                                {{$datum->complains}}
                                            </textarea>
                                            </td>
                                            <td>
                                            <textarea rows="10" cols="10" class="form-control" name="" id="" >
                                                {{$datum->physical_examination}}
                                            </textarea>
                                            </td>
                                            <td>{{$datum->notes}}</td>
                                            <td>
                                                @isset($datum->user)
                                                    {{$datum->user['first_name']." ".$datum->user['last_name']}}
                                                @endisset
                                            </td>
                                        </tr>
                                    @endisset
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
