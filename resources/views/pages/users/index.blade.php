@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin grid-margin-md-0 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('bulk_delete')}}" id="bulkDeleteForm" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <h4 class="card-title">All Users</h4>
                                </div>
                                <div class="col-md-6 text-right mb-3">
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteStaff" id="deleteSelected" disabled>
                                        <i class="icon icon-trash"></i> Delete Selected
                                    </button>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                        <i class="icon icon-plus"></i> New Staff
                                    </button>
                                </div>
                            </div>

                            <table class="table" id="users_table">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="form-check form-check-flat">
                                            <label class="form-check-label">
                                                <input type="checkbox"  class="form-check-input"  id="checkAll">
                                            </label>
                                        </div>
                                    </th>
                                    <th>N<u>o</u></th>
                                    <th>Name</th>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i=1;
                                @endphp
                                @foreach($users as $user)
                                    @if($user->role != "Super Admin")
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-flat">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" value="{{$user->id}}" class="form-check-input checkItem" name="selected_id[]" id="remember">
                                                    </label>
                                                </div>
                                            </td>
                                            <td>{!! $i !!}</td>
                                            <td>{{$user->first_name." ".$user->middle_name." ".$user->last_name}}</td>
                                            <td>{{$user->id}}</td>
                                            <td>{{$user->username}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->phone_number}}</td>
                                            <td>{{$user->role}}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-secondary edit">
                                                    <i class="icon icon-pencil"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @php
                                            $i = $i+1;
                                        @endphp
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->





    <!-- new Staff modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{route('users.store')}}" class="needs-validation" novalidate>
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
                            <div class="col-md-12 mb-3">
                                <input title="Enter Username" type="text" name="username" class="form-control" id="username" placeholder="Username"  required>
                                <div class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <input title="Enter Email Address" name="email" type="email" class="form-control" id="email" placeholder="Email Address" required>
                                <div class="invalid-feedback">
                                    Please Email is required.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <input required type="text"  class="form-control phone_number"  id="phone_number" minlength="10"   name="phone_number" placeholder="Phone Number">
                                <div class="invalid-feedback">
                                    Please Phone Number is required.
                                </div>
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <div class="col-md-12">
                                <select title="Select Role" name="role" class="js-example-basic-single form-control" style="width: 100%" required>
                                    <option value="">~Select Role~</option>
                                    <option value="Doctor">Doctor</option>
                                    <option value="Nurse">Nurse</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Pharmacist">Pharmacist</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please Role is required.
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
                                    Please choose a username.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <input title="Enter Email Address" name="email" type="email" class="form-control" id="edit_email" placeholder="Email Address" required>
                                <div class="invalid-feedback">
                                    Please Email is required.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <input required type="text"  class="form-control phone_number"  id="edit_phone_number" minlength="10"   name="phone_number" placeholder="Phone Number">
                                <div class="invalid-feedback">
                                    Please Phone Numer is required.
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
                                    Please Role is required.
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