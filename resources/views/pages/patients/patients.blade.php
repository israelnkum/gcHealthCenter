@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 grid-margin offset-md-2">
                <form class="needs-validation" novalidate action="{{route('searchPatient')}}" method="get">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" required class="form-control" name="search" placeholder=" Search by Folder Number or Patient's Last Name or Phone Number">
                                <div class="input-group-prepend">
                                    <button type="submit" class="input-group-text btn"><i class="icon-magnifier"></i></button>
                                </div>
                                <div class="invalid-feedback">
                                    Search by Folder Number or Patient's Last Name or Phone Number
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {{--            <div class="col-md-2">--}}
            {{--                <!-- Button trigger modal -->--}}
            {{--                <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#newPatient">--}}
            {{--                    <i class="icon icon-plus"></i> New Patient--}}
            {{--                </button>--}}
            {{--            </div>--}}
        </div>
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('bulk_deleteCharge')}}" id="bulkDeleteChargeForm" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 text-left">
                                <h4 class="card-title">All Patients</h4>
                            </div>
                            <div class="col-md-6 text-right mb-3">
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteCharge" id="deleteSelectedCharge" disabled>
                                    <i class="icon icon-trash"></i> Delete Selected
                                </button>
                                {{-- <!-- Button trigger modal -->
                                 <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newChargeModal">
                                     <i class="icon icon-plus"></i> New Charge
                                 </button>--}}
                            </div>
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
                                    <th>N<u>o</u></th>
                                    <th>ID</th>
                                    <th>Folder Number</th>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Date of Birth</th>
                                    <th>Gender</th>
                                    <th>Marital Status</th>
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
                                        <td>{!! $i !!}</td>
                                        <td>{{$datum->id}}</td>
                                        <td>{{$datum->folder_number}}</td>
                                        <td>{{$datum->first_name." ".$datum->other_name." ".$datum->last_name}}</td>
                                        <td>{{$datum->phone_number}}</td>
                                        <td>{{$datum->date_of_birth}}</td>
                                        <td>{{$datum->gender}}</td>
                                        <td>{{$datum->marital_status}}</td>
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
@endsection