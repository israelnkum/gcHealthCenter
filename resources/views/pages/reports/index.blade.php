@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Generate Reports</h4>

                    <div class="row">
                        <div class="col-md-4">
                            <h5>Patients</h5>
                            <a href="">All Patient</a><br>
                            <a href="">All Detained Patient(Today)</a><br>
                            <a href="">All Detained Patient(Monthly)</a>
                        </div>
                        <div class="col-md-4">
                            <h5>Pharmacy</h5>
                        </div>

                        <div class="col-md-4">
                            <h5>Staff</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
@endsection