@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-10 offset-md-1 grid-margin grid-margin-md-0 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Profile</h4>
                        <form class="needs-validation" novalidate action="{{route('users.update',$user->id)}}" method="POST">
                            @csrf
                            {!! method_field('put') !!}
                            <blockquote class="blockquote">
                                <h5 class="mb-3 text-dark">Personal Information</h5>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="first_name">First Name</label>
                                        <input required name="first_name" type="text" class="form-control" value="{{$user->first_name}}" id="first_name">
                                        <div class="invalid-feedback">
                                            First required
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="middle_name">Middle Name</label>
                                        <input type="text" name="middle_name" class="form-control" value="{{$user->middle_name}}" id="middle_name">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="last_name">Last Name</label>
                                        <input required type="text" name="last_name" class="form-control" value="{{$user->last_name}}" id="last_name">
                                        <div class="invalid-feedback">
                                            Last Name required
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="date_of_birth">Date of Birth</label>
                                        <input required type="date" name="date_of_birth" class="form-control" value="{{$user->date_of_birth}}" id="date_of_birth">
                                        <div class="invalid-feedback">
                                            Date of Birth required
                                        </div>
                                    </div>

                                </div>
                            </blockquote>

                            <blockquote class="blockquote">
                                <h5 class="mb-3 text-dark">Contact Information</h5>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="username">Username</label>
                                        <input required name="username" type="text" class="form-control" value="{{$user->username}}" id="username">
                                        <div class="invalid-feedback">
                                            Username required
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="email">Email</label>
                                        <input required type="text" name="email" class="form-control" value="{{$user->email}}" id="email">
                                        <div class="invalid-feedback">
                                            Email required
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="phone_number">Phone Number</label>
                                        <input required type="text" name="phone_number" class="form-control" value="{{$user->phone_number}}" id="phone_number">
                                        <div class="invalid-feedback">
                                            Phone Number required
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="place_of_birth">Place of Birth</label>
                                        <input required type="text" name="place_of_birth" class="form-control" value="{{$user->place_of_birth}}" id="place_of_birth">
                                        <div class="invalid-feedback">
                                            Place of Birth required
                                        </div>
                                    </div>
                                </div>
                            </blockquote>

                            <blockquote class="blockquote">
                                <h5 class="mb-3 text-dark">Academic Information</h5>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="school_attended">School Attended</label>
                                        <input required type="text" name="school_attended" class="form-control" value="{{$user->school_attended}}" id="school_attended">
                                        <div class="invalid-feedback">
                                            School Attended required
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="year_completed">Year Completed</label>
                                        <select  class="selectMedicine col-12 form-control mr-1" required   name="year_completed" id="year_completed">
                                            <option selected value="year_completed">{{$user->year_completed}}</option>
                                            @for($i= 1900; $i<= 2018; $i++)
                                                @if($i != $user->year_completed)
                                                    <option value="{!! $i !!}"> {!! $i !!}</option>
                                                @endif
                                            @endfor
                                        </select>
                                        <div class="invalid-feedback">
                                            Year Completed is required
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="pin">Pin</label>
                                        <input required type="text" name="pin" class="form-control" value="{{$user->pin}}" id="pin">
                                        <div class="invalid-feedback">
                                            Pin required
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="qualification">Qualification</label>
                                        <input required name="qualification" type="text" class="form-control" value="{{$user->qualification}}" id="qualification">
                                        <div class="invalid-feedback">
                                            Qualification required
                                        </div>
                                    </div>
                                </div>
                            </blockquote>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-dark" type="submit">Updated Info</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection