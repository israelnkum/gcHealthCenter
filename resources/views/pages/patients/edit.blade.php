@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 offset-md-2 grid-margin">
                <form class="needs-validation form-sub" novalidate action="{{route('searchPatient')}}" method="get">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-12 ">
                            <div class="input-group">
                                <input type="text" required class="form-control" name="search" placeholder=" Search by Folder Number or Patient Name or Phone Number">
                                <div class="input-group-prepend">
                                    <button type="submit" class="input-group-text btn loading"><i class="icon-magnifier"></i></button>
                                </div>
                                <div class="invalid-feedback">
                                    Search by Folder Number or Patient Name or Phone Number
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-2 text-right grid-margin ">
            {{--<button type="button" class="btn btn-danger  mr-3" data-toggle="modal" data-target="#deleteStaff">
                <i class="icon icon-trash"></i> Delete Selected
            </button>--}}
            <!-- Button trigger modal -->
                <a role="button" href="{{route('patients.show',$patient->id)}}" class="btn btn-danger">
                    <i class="icon icon-close"></i> Cancel
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit <span class="text-danger">{{$patient->first_name." ".$patient->last_name."'s"}}</span> Information</h4>
                        <div class="row">
                            <div class="col-md-12 pl-md-5">
                                <h4 class="mb-3"><span class="text-uppercase text-danger">Folder Number</span> :{{$patient->folder_number}}</h4>
                                <div class="wrapper mb-4">
                                    <form method="post" action="{{route('patients.update',$patient->id)}}" class="needs-validation" novalidate>
                                        @csrf
                                        {!! method_field('put') !!}
                                        <div class="form-row">
                                            <div class="col-md-3">
                                                <label for="title">Title</label>
                                                <select title="Select Title"  name="title" class="js-example-basic-single form-control" style="width: 100%" required>
                                                    <option value="{{$patient->title}}">{{$patient->title}}</option>
                                                    <option value="Mr">Mr</option>
                                                    <option value="Mrs">Mrs</option>
                                                    <option value="Miss">Miss</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Title is required.
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="first_name">First Name</label>
                                                <input title="Enter First name" type="text" name="first_name" class="form-control" value="{{$patient->first_name}}" id="first_name" placeholder="First Name"  required>
                                                <div class="invalid-feedback">
                                                    First Name is required
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="last_name">Last Name</label>
                                                <input title="Enter Last Name" type="text" name="last_name" class="form-control" value="{{$patient->last_name}}" id="last_name" placeholder="Last Name"  required>
                                                <div class="invalid-feedback">
                                                    Last Name is required
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="other_name">Other Name</label>
                                                <input title="Enter Other Name" type="text" name="other_name" class="form-control" value="{{$patient->other_name}}" id="other_name" placeholder="Other Name"  >

                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <label for="date_of_birth">Date of Birth</label>
                                                <input placeholder="Date of Birth" name="date_of_birth" class="form-control" value="{{$patient->date_of_birth}}" data-inputmask="'alias': 'date'" required>
                                                <div class="invalid-feedback">
                                                    Date of Birth is required.
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="gender">Gender</label>
                                                <select title="Select Gender" name="gender"  class="js-example-basic-single form-control" style="width: 100%" required>
                                                    <option value="{{$patient->gender}}">{{$patient->gender}}</option>
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
                                                <label for="postal_address">Postal Address</label>
                                                <input title="Enter Postal Address" value="{{$patient->postal_address}}" type="text" name="postal_address" class="form-control" id="postal_address" placeholder="Postal Address" >
                                                <div class="invalid-feedback">
                                                    Postal Address is required
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="house_number">House Number</label>
                                                <input title="Enter House Number" value="{{$patient->house_number}}" type="text" name="house_number" class="form-control" id="house_number" placeholder="House Number" >
                                                <div class="invalid-feedback">
                                                    House Number is required
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <label for="locality">Locality</label>
                                                <input title="Enter Locality" type="text" value="{{$patient->locality}}" name="locality" class="form-control" id="locality" placeholder="Locality"  required>
                                                <div class="invalid-feedback">
                                                    Locality is required
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="phone_number">Phone Number</label>
                                                <input required type="text"  class="form-control phone_number" value="{{$patient->phone_number}}"  id="phone_number" minlength="10"   name="phone_number" placeholder="Phone Number">
                                                <div class="invalid-feedback">
                                                    Phone Number is required.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row form-group">
                                            <div class="col-md-6">
                                                <label for="occupation">Occupation</label>
                                                <input title="Enter Occupation" type="text" name="occupation" value="{{$patient->occupation}}" class="form-control" id="occupation" placeholder="Occupation"  required>
                                                <div class="invalid-feedback">
                                                    Occupation is required
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="religion">Religion</label>
                                                <select title="Select Religion" name="religion" class="js-example-basic-single form-control" style="width: 100%" required>
                                                    <option value="{{$patient->religion}}">{{$patient->religion}}</option>
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
                                                <label for="name_of_relative">Name of Nearest Relative</label>
                                                <input title="Enter Name Of Nearest Relative" value="{{$patient->name_of_nearest_relative}}" type="text" name="name_of_relative" class="form-control" id="name_of_relative" placeholder="Name Of Nearest Relative">
                                                {{--<div class="invalid-feedback">
                                                    Locality is required
                                                </div>--}}
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="relative_phone_number">Phone Number(Relative)</label>
                                                <input type="text"  class="form-control phone_number"  id="relative_phone_number" value="{{$patient->number_of_nearest_relative}}" minlength="10"   name="relative_phone_number" placeholder="Relative Phone Number">
                                                {{--<div class="invalid-feedback">
                                                    Phone Number is required.
                                                </div>--}}
                                            </div>
                                        </div>
                                        <div class="form-row form-group">
                                            <div class="col-md-6">
                                                <label for="other_information">Other Information</label>
                                                <textarea name="other_information" id="other_information" placeholder="Other Information" class="form-control" rows="4">{{$patient->other_information}}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="marital_status">Marital Status</label>
                                                <select title="Select Marital Status" name="marital_status" class="js-example-basic-single form-control" style="width: 100%" required>
                                                    <option value="{{$patient->marital_status}}">{{$patient->marital_status}}</option>
                                                    <option value="Single">Single</option>
                                                    <option value="Married">Married</option>
                                                    <option value="Divorced">Divorced</option>
                                                    <option value="Widowed">Widowed</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Marital Status is required.
                                                </div>

                                                <div class="mt-1 text-right">
                                                    <button type="submit" class="btn btn-dark loading">Edit Info</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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
                    <button type="submit" id="btn_submit_bulk_delete" class="btn btn-success loading">
                        <i class="icon icon-trash"></i> Yes! Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection