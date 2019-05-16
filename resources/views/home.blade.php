@extends('layouts.app')
@section('content')
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card card-statistics">
                            <div class="card-body p-0">
                                <div class="row">
                                    <div class="col-md-6 col-lg-3">
                                        <div class="d-flex justify-content-between border-right card-statistics-item">
                                            <div>
                                                <h1>28893</h1>
                                                <p class="text-muted mb-0">Total invoices</p>
                                            </div>
                                            <i class="icon-layers text-primary icon-lg"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="d-flex justify-content-between border-right card-statistics-item">
                                            <div>
                                                <h1>217</h1>
                                                <p class="text-muted mb-0">New users</p>
                                            </div>
                                            <i class="icon-people text-primary icon-lg"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="d-flex justify-content-between border-right card-statistics-item">
                                            <div>
                                                <h1>6875</h1>
                                                <p class="text-muted mb-0">Unique visits</p>
                                            </div>
                                            <i class="icon-pin text-primary icon-lg"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="d-flex justify-content-between card-statistics-item">
                                            <div>
                                                <h1>45596</h1>
                                                <p class="text-muted mb-0">Sales</p>
                                            </div>
                                            <i class="icon-refresh text-primary icon-lg"></i>
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
                                <div class="col-md-5 border-right">
                                    <div class="card-body">
                                        <h4 class="card-title">Performance</h4>
                                        <table class="table table-borderless">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <h6>Tasks</h6>
                                                    <p class="text-muted mb-0">5.6% change today</p>
                                                </td>
                                                <td>
                                                    <h3 class="text-primary">
                                                        +20009
                                                    </h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>Member Profit</h6>
                                                    <p class="text-muted mb-0">3 days ago</p>
                                                </td>
                                                <td>
                                                    <h3 class="text-danger">
                                                        +91964
                                                    </h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>Orders</h6>
                                                    <p class="text-muted mb-0">Weekly Orders</p>
                                                </td>
                                                <td>
                                                    <h3 class="text-success">
                                                        -200
                                                    </h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>Pending</h6>
                                                    <p class="text-muted mb-0">Pending Tasks</p>
                                                </td>
                                                <td>
                                                    <h3 class="text-warning">
                                                        +5152
                                                    </h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>Revenue</h6>
                                                    <p class="text-muted mb-0">5% increase</p>
                                                </td>
                                                <td>
                                                    <h3 class="text-primary">
                                                        +89997
                                                    </h3>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body d-flex flex-column h-100">
                                        <div class="d-flex flex-row">
                                            <h4 class="card-title">Year-wise performance</h4>
                                        </div>
                                        <p>Performance of the team is 75% higher this year!</p>
                                        <canvas id="chart-activity" class="mt-auto"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Open invoices</h4>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Invoice</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <div class="disc bg-secondary"></div>
                                        </td>
                                        <td>
                                            <h4 class="text-primary font-weight-normal">490-525-4779</h4>
                                            <p class="text-muted mb-0">Online sale</p>
                                        </td>
                                        <td>
                                            $41991
                                        </td>
                                        <td>
                                            <p>27 Sep 2018</p>
                                            <p class="text-muted mb-0">3 days ago</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="disc bg-secondary"></div>
                                        </td>
                                        <td>
                                            <h4 class="text-primary font-weight-normal">490-525-4780</h4>
                                            <p class="text-muted mb-0">Online sale</p>
                                        </td>
                                        <td>
                                            $65789
                                        </td>
                                        <td>
                                            <p>27 Sep 2018</p>
                                            <p class="text-muted mb-0">2 days ago</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="disc bg-secondary"></div>
                                        </td>
                                        <td>
                                            <h4 class="text-primary font-weight-normal">490-525-4781</h4>
                                            <p class="text-muted mb-0">Offline sale</p>
                                        </td>
                                        <td>
                                            $98076
                                        </td>
                                        <td>
                                            <p>27 Sep 2018</p>
                                            <p class="text-muted mb-0">4 days ago</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="disc bg-secondary"></div>
                                        </td>
                                        <td>
                                            <h4 class="text-primary font-weight-normal">490-525-4782</h4>
                                            <p class="text-muted mb-0">Online sale</p>
                                        </td>
                                        <td>
                                            $67589
                                        </td>
                                        <td>
                                            <p>27 Sep 2018</p>
                                            <p class="text-muted mb-0">1 day ago</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h4 class="card-title">Revenue</h4>
                                    <h1>20009</h1>
                                    <p class="text-muted">5.6% change today</p>
                                </div>
                                <canvas id="sales-chart" class="mt-auto"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
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
                </div>
                <div class="row">
                    <div class="col-md-7 grid-margin grid-margin-md-0 stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Todo list</h4>
                                <div class="add-items d-flex">
                                    <input type="text" class="form-control todo-list-input"  placeholder="What do you need to do today?">
                                    <button class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
                                </div>
                                <div class="list-wrapper">
                                    <ul class="d-flex flex-column-reverse todo-list">
                                        <li class="completed">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="checkbox" type="checkbox" checked>
                                                    Call John
                                                </label>
                                            </div>
                                            <i class="remove icon-close"></i>
                                        </li>
                                        <li>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="checkbox" type="checkbox">
                                                    Create invoice
                                                </label>
                                            </div>
                                            <i class="remove icon-close"></i>
                                        </li>
                                        <li>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="checkbox" type="checkbox">
                                                    Print Statements
                                                </label>
                                            </div>
                                            <i class="remove icon-close"></i>
                                        </li>
                                        <li class="completed">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="checkbox" type="checkbox" checked>
                                                    Prepare for presentation
                                                </label>
                                            </div>
                                            <i class="remove icon-close"></i>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 grid-margin grid-margin-md-0 stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Projects</h4>
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td class="py-1">
                                            <img src="public/images/faces/face1.jpg" alt="profile" class="img-sm rounded-circle"/>
                                        </td>
                                        <td>
                                            South Shyanne
                                        </td>
                                        <td>
                                            <label class="badge badge-warning">Medium</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">
                                            <img src="public/images/faces/face2.jpg" alt="profile" class="img-sm rounded-circle"/>
                                        </td>
                                        <td>
                                            New Trystan
                                        </td>
                                        <td>
                                            <label class="badge badge-danger">High</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">
                                            <img src="public/images/faces/face3.jpg" alt="profile" class="img-sm rounded-circle"/>
                                        </td>
                                        <td>
                                            East Helga
                                        </td>
                                        <td>
                                            <label class="badge badge-success">Low</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">
                                            <img src="public/images/faces/face4.jpg" alt="profile" class="img-sm rounded-circle"/>
                                        </td>
                                        <td>
                                            Omerbury
                                        </td>
                                        <td>
                                            <label class="badge badge-warning">Medium</label>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="w-100 clearfix">
                    <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2018 <a href="http://www.urbanui.com/" target="_blank">Urbanui</a>. All rights reserved.</span>
                    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="icon-heart text-danger"></i></span>
                </div>
            </footer>
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    @endsection