@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body mt-5">
                    <h4 class="card-title">Generate Reports</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="accordion accordion-bordered" id="accordion-2" role="tablist">
                                <div>
                                    <div class="" role="tab" id="heading-4">
                                        <a  class="badge badge-dark" data-toggle="collapse" href="#patient" aria-expanded="false" aria-controls="collapse-4">
                                            <i class="icon icon-people"></i> Patients
                                        </a>
                                        <a  class="badge badge-success" data-toggle="collapse" href="#consultation" aria-expanded="false" aria-controls="collapse-4">
                                            <i class="icon icon-energy"></i> Consultation
                                        </a>
                                        <a  data-toggle="collapse" class="badge  badge-info" href="#staff" aria-expanded="false" aria-controls="collapse-4">
                                            <i class="icon icon-people"></i>  Staff
                                        </a>
                                    </div>
                                    <div id="patient" class="collapse" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <h4>Patients</h4>

                                            <div>
                                                <form action="">
                                                    <div class="form-row">
                                                        <div class="col-md-2">
                                                            <label for="Type">Type</label>
                                                            <select name="" id="Type" class="form-control selectMedicine" style="width: 100%;">
                                                                <option value=""></option>
                                                                <option value="1">All Patients</option>
                                                                <option value="2">Detained Patients</option>
                                                                <option value="3">Discharged Patients</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="Type">Gender</label>
                                                            <select name="" id="Type" class="form-control selectMedicine" style="width: 100%;">
                                                                <option value=""></option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="Type">Marital Status</label>
                                                            <select name="" id="Type" class="form-control selectMedicine" style="width: 100%;">
                                                                <option value="">~Select Marital Status~</option>
                                                                <option value="Single">Single</option>
                                                                <option value="Married">Married</option>
                                                                <option value="Divorced">Divorced</option>
                                                                <option value="Widowed">Widowed</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="Type">Religion</label>
                                                            <select name="" id="Type" class="form-control selectMedicine" style="width: 100%;">
                                                                <option value="">~Select Religion~</option>
                                                                <option value="Christianity">Christianity</option>
                                                                <option value="Islam">Islam</option>
                                                                <option value="Hinduism">Hinduism</option>
                                                                <option value="Buddhism">Buddhism</option>
                                                                <option value="Non Religious">Non Religious</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="age_between">Age(Between)</label>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <input type="number" min="1" class="form-control" id="age_between" placeholder="10">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="number" min="1" class="form-control" id="age_between" placeholder="20">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{--<div class="col-md-2">
                                                            <label for="Type">Age</label>
                                                            <div id="datepicker-popup" class="input-group date datepicker">
                                                                <input type="text" class="form-control">
                                                                <span class="input-group-addon input-group-append border-left">
                                                                    <span class="icon-calendar input-group-text"></span>
                                                                </span>
                                                            </div>
                                                        </div>--}}
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <label for="age_between">Date (Between)</label>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div id="datepicker-popup" class="input-group date datepicker">
                                                                        <input type="text" placeholder="From" class="form-control">
                                                                        <span class="input-group-addon input-group-append border-left">
                                                                    <span class="icon-calendar input-group-text"></span>
                                                                </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div id="datepicker-popup1" class="input-group date datepicker">
                                                                        <input placeholder="To" type="text" class="form-control">
                                                                        <span class="input-group-addon input-group-append border-left">
                                                                            <span class="icon-calendar input-group-text"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col-md-2">
                                                            <button class="btn btn-dark mt-4" type="submit">Generate</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="consultation" class="collapse" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <h4>consultation</h4>
                                        </div>
                                    </div>
                                    <div id="staff" class="collapse" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <h4>staff</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
@endsection