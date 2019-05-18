@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin grid-margin-md-0 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 text-left">
                                <h4 class="card-title">Patient Information</h4>
                            </div>
                            <div class="col-md-6 text-right mb-3">
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteStaff">
                                    <i class="icon icon-trash"></i> Delete Selected
                                </button>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newPatient">
                                    <i class="icon icon-plus"></i> New Patient
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->





    <!-- new Staff modal -->
    <div class="modal fade" id="newPatient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{route('patients.store')}}" class="needs-validation" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Staff</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <input title="Enter Surname" type="text" name="last_name" class="form-control" id="last_name" placeholder="Surname"  required>
                                <div class="invalid-feedback">
                                    Surname is required
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input title="Enter First name" type="text" name="first_name" class="form-control" id="first_name" placeholder="First Name"  required>
                                <div class="invalid-feedback">
                                    First Name is required
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <input placeholder="Date of Birth" class="form-control" data-inputmask="'alias': 'date'" required>
                                <div class="invalid-feedback">
                                    Date of Birth is required.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select title="Select Role" name="role" class="js-example-basic-single form-control" style="width: 100%" required>
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
                                <input required type="text"  class="form-control phone_number"  id="phone_number" minlength="10"   name="phone_number" placeholder="Phone Number">
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
                                <select title="Select Role" name="religion" class="js-example-basic-single form-control" style="width: 100%" required>
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
                                <input type="text"  class="form-control phone_number"  id="relative_phone_number" minlength="10"   name="relative_phone_number" placeholder="Relative Phone Number">
                                {{--<div class="invalid-feedback">
                                    Phone Number is required.
                                </div>--}}
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <div class="col-md-12">
                                <select title="Select Role" name="marital_status" class="js-example-basic-single form-control" style="width: 100%" required>
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
                        </div>

                        <div class="form-row form-group">
                            <div class="col-md-12">
                                <textarea name="other_information" id="other_information" placeholder="Other Information" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="icon icon-close"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
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