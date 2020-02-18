@extends('layouts.app')
@section('content')
    <!-- partial -->
    <div class="content-wrapper">
        <div class="row">
{{--            <div class="col-12">--}}
{{--                @component('partials.breadcrumb')--}}
{{--                    <li class="breadcrumb-item active" aria-current="page"><span>Dashboard</span></li>--}}
{{--                @endcomponent--}}
{{--            </div>--}}
            <div class="col-12 grid-margin">
                <div class="card card-statistics">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="d-flex justify-content-between border-right card-statistics-item">
                                    <div>
                                        <h1>{{$totalPatient}}</h1>
                                        <p class="text-muted mb-0">Total Patients</p>
                                    </div>
                                    <i class="icon-people text-primary icon-lg"></i>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="d-flex justify-content-between border-right card-statistics-item">
                                    <div>
                                        <h1>{{$totalDetained}}</h1>
                                        <p class="text-muted mb-0">Detained Patients</p>
                                    </div>
                                    <i class="icon-people text-primary icon-lg"></i>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="d-flex justify-content-between border-right card-statistics-item">
                                    <div>
                                        <h1>{{$totalStaff}}</h1>
                                        <p class="text-muted mb-0">Total Staff</p>
                                    </div>
                                    <i class="icon-people text-primary icon-lg"></i>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="d-flex justify-content-between card-statistics-item">
                                    <div>
                                        <h1>{{$totalDrugs}}</h1>
                                        <p class="text-muted mb-0">Drugs</p>
                                    </div>
                                    <i class="icon-drop text-primary icon-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Earning Report</h4>
                        <div class="w-75 mx-auto">
                            <canvas id="earning-report" width="100" height="100"></canvas>
                        </div>
                        <div class="py-4 d-flex justify-content-center align-items-end">
                            <h1 class="text-center text-md-left mb-0">1.2M</h1>
                            <p class="text-muted mb-0 ml-2">Total</p>
                        </div>
                        <div id="earning-report-legend" class="earning-report-legend"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 grid-margin stretch-card">
                <div class="card">
                    <div class="row h-100">

                        <div class="col-md-7">
                            <div class="card-body d-flex flex-column h-100">
                                <div class="d-flex flex-row">
                                    <h4 class="card-title">PATIENTS | Graphical Representation</h4>
                                </div>
                                {!! $statistics->container() !!}
                            </div>
                        </div>
                        <div class="col-md-5 border-right">
                            <div class="card-body">
                                <h4 class="card-title">Calendar</h4>
                                <div id="inline-datepicker-example" class="datepicker"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--<div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Activities</h4>
                        <div class="d-flex border-bottom pb-3">
                            <img src="public/images/faces/face10.jpg" class="img-sm mr-4 rounded-circle" alt="profile"/>
                            <div>
                                <h6>Emily Kennedy</h6>
                                <p class="text-muted mb-0">Uploaded new invoices for RedBus and Paytm</p>
                            </div>
                        </div>
                        <div class="d-flex border-bottom py-3">
                            <img src="public/images/faces/face12.jpg" class="img-sm mr-4 rounded-circle" alt="profile"/>
                            <div>
                                <h6>Nicholas Armstrong</h6>
                                <p class="text-muted mb-0">Created new work flow for the current project</p>
                            </div>
                        </div>
                        <div class="d-flex border-bottom py-3">
                            <img src="public/images/faces/face5.jpg" class="img-sm mr-4 rounded-circle" alt="profile"/>
                            <div>
                                <h6>Stella Saunders</h6>
                                <p class="text-muted mb-0">Submitted the project schedule to the manager</p>
                            </div>
                        </div>
                        <div class="d-flex pt-3">
                            <img src="public/images/faces/face8.jpg" class="img-sm mr-4 rounded-circle" alt="profile"/>
                            <div>
                                <h6>Noah Bailey</h6>
                                <p class="text-muted mb-0">Scheduled a meeting with the new client for next thursday</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Calendar</h4>
                        <div id="inline-datepicker-example" class="datepicker"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card bg-primary text-white card-update">
                    <div class="card-body">
                        <h4 class="card-title text-white">Updates</h4>
                        <div class="d-flex border-light-white pb-4 update-item">
                            <img src="public/images/faces/face12.jpg" alt="profile" class="img-sm rounded-circle img-bordered mr-4"/>
                            <div>
                                <h6 class="text-white font-weight-medium d-flex">Aaron Tucker
                                    <span class="small ml-auto">8:30 AM</span>
                                </h6>
                                <p>New product is launched with high quality and awesome support. The product will be available for public within 4 days</p>
                                <div class="image-layers">
                                    <div class="profile-image-text bg-danger rounded-circle image-layer-item">S</div>
                                    <img class="rounded-circle image-layer-item" src="public/images/faces/face3.jpg" alt="profile"/>
                                    <img class="rounded-circle image-layer-item" src="public/images/faces/face5.jpg" alt="profile"/>
                                    <img class="rounded-circle image-layer-item" src="public/images/faces/face8.jpg" alt="profile"/>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex pt-4 update-item">
                            <img src="public/images/faces/face14.jpg" alt="profile" class="img-sm rounded-circle img-bordered mr-4"/>
                            <div>
                                <h6 class="text-white font-weight-medium d-flex">Joseph Delgado
                                    <span class="small ml-auto">8:45 AM</span>
                                </h6>
                                <p>The test report is handed over to the production manager. The final decision will be based on the report. It will be announced in the meeting</p>
                                <div class="image-layers">
                                    <div class="profile-image-text bg-warning rounded-circle image-layer-item">M</div>
                                    <img class="rounded-circle image-layer-item" src="public/images/faces/face9.jpg" alt="profile"/>
                                    <img class="rounded-circle image-layer-item" src="public/images/faces/face11.jpg" alt="profile"/>
                                    <img class="rounded-circle image-layer-item" src="public/images/faces/face13.jpg" alt="profile"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection
