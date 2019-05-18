@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <div class="row profile-page">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="profile-body">
                            <ul class="nav tab-switch mt-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="insurance-tab" data-toggle="pill" href="#insurance" role="tab" aria-controls="insurance" aria-selected="false">Insurance</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="charges-tab" data-toggle="pill" href="#charges" role="tab" aria-controls="charges" aria-selected="false">Charges</a>
                                </li>
                            </ul>
                            <div class="row ">
                                <div class="col-md-9">
                                    <div class="tab-content tab-body" id="profile-log-switch">
                                        {{--Start General Tab--}}
                                        <div class="tab-pane fade show active pr-3" id="general" role="tabpanel" aria-labelledby="general-tab">
                                            <div class="row">
                                                <div class="col-12 mt-5">
                                                    <h5 class="mb-5">Stages</h5>
                                                    <div class="stage-wrapper pl-4">
                                                        <div class="stages border-left pl-5 pb-4">
                                                            <div class="btn btn-icons btn-rounded stage-badge btn-inverse-success">
                                                                <i class="icon-event"></i>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-2 justify-content-between">
                                                                <h5 class="mb-0">Task Added</h5>
                                                                <small class="text-muted">28 mins ago</small>
                                                            </div>
                                                            <p>Admin is a full featured, multipurpose, premium bootstrap admin template built with Bootstrap 4 Framework</p>
                                                        </div>
                                                        <div class="stages border-left pl-5 pb-4">
                                                            <div class="btn btn-icons btn-rounded stage-badge btn-inverse-danger">
                                                                <i class="icon-cloud-download"></i>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-2 justify-content-between">
                                                                <h5 class="mb-0">Download Completed</h5>
                                                                <small class="text-muted">45 mins ago</small>
                                                            </div>
                                                            <p>one of the best admin panel templates. With this bootstrap admin template, you can quick start your project.</p>
                                                            <div class="file-icon-wrapper">
                                                                <div class="btn btn-outline-danger file-icon">
                                                                    <i class="icon-envelope"></i>
                                                                </div>
                                                                <div class="btn btn-outline-primary file-icon">
                                                                    <i class="icon-chart"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="stages pl-5 pb-4">
                                                            <div class="btn btn-icons btn-rounded stage-badge btn-inverse-primary">
                                                                <i class="icon-checkbox-marked-circle-outline"></i>
                                                            </div>
                                                            <div class="d-flex align-items-center mb-2 justify-content-between">
                                                                <h5 class="mb-0">New Task Completed</h5>
                                                                <small class="text-muted">55 mins ago</small>
                                                            </div>
                                                            <p>Admin dashboard works seamlessly on all major web browsers and devices.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--End General TAb--}}

                                        {{--Start Insurance TAb--}}

                                        <div class="tab-pane fade" id="insurance" role="tabpanel" aria-labelledby="insurance-tab">
                                            <div class="card-body">
                                                <form action="{{route('bulk_deleteInsurance')}}" id="bulkDeleteInsuranceForm" method="POST">
                                                    @csrf
                                                    <input type="hidden" id="form_id" value="bulkDeleteInsuranceForm">
                                                    <div class="row">
                                                        <div class="col-md-6 text-left">
                                                            <h4 class="card-title">All Insurance</h4>
                                                        </div>
                                                        <div class="col-md-6 text-right mb-3">
                                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteInsurance" id="deleteSelected" disabled>
                                                                <i class="icon icon-trash"></i> Delete Selected
                                                            </button>
                                                            <!-- Button trigger modal -->
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newInsurance">
                                                                <i class="icon icon-plus"></i> New Insurance
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <table class="table" id="insurance_table">
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
                                                            <th>ID</th>
                                                            <th>Name</th>
                                                            <th>Amount</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php
                                                            $i=1;
                                                        @endphp
                                                        @foreach($insurance as $insure)
                                                            <tr>
                                                                <td>
                                                                    <div class="form-check form-check-flat">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox" value="{{$insure->id}}" class="form-check-input checkItem" name="selected_id[]" id="remember">
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td>{!! $i !!}</td>
                                                                <td>{{$insure->id}}</td>
                                                                <td>{{$insure->name}}</td>
                                                                <td>{{$insure->amount}}</td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-secondary edit">
                                                                        <i class="icon icon-pencil"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $i = $i+1;
                                                            @endphp
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                        {{--End Insurance TAb--}}


                                        {{--Start charges TAb--}}
                                        <div class="tab-pane fade" id="charges" role="tabpanel" aria-labelledby="charges-tab">
                                            <div class="card-body">
                                                <form action="{{route('bulk_deleteCharge')}}" id="bulkDeleteChargeForm" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6 text-left">
                                                            <h4 class="card-title">All Charges</h4>
                                                        </div>
                                                        <div class="col-md-6 text-right mb-3">
                                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteCharge" id="deleteSelectedCharge" disabled>
                                                                <i class="icon icon-trash"></i> Delete Selected
                                                            </button>
                                                            <!-- Button trigger modal -->
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newChargeModal">
                                                                <i class="icon icon-plus"></i> New Charge
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <table class="table" id="charge_table">
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
                                                            <th>Name</th>
                                                            <th>Amount</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php
                                                            $i=1;
                                                        @endphp
                                                        @foreach($charges as $charge)
                                                            <tr>
                                                                <td>
                                                                    <div class="form-check form-check-flat">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox" value="{{$charge->id}}" class="form-check-input checkItemCharge" name="selected_charges[]" id="remember">
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td>{!! $i !!}</td>
                                                                <td>{{$charge->id}}</td>
                                                                <td>{{$charge->name}}</td>
                                                                <td>{{$charge->amount}}</td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-secondary edit_charge">
                                                                        <i class="icon icon-pencil"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $i = $i+1;
                                                            @endphp
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                        {{--End charges TAb--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- new Insurance modal -->
    <div class="modal fade" id="newInsurance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{route('insurance.store')}}" class="needs-validation" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Insurance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="name">Insurance Name</label>
                                <input title="Enter Insurance Name" type="text" name="name" class="form-control" id="name" required>
                                <div class="invalid-feedback">
                                    Please choose a Insurance Name.
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="amount">Amount</label>
                                <input required type="text"  class="form-control" data-inputmask="'alias': 'currency'" min="1" style="text-align: right;"  id="amount"   name="amount" >
                                <div class="invalid-feedback">
                                    Please Amount is required.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="icon icon-close"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon icon-plus"></i> Add Insurance
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- edit Insurance modal -->
    <div class="modal fade" id="editInsuranceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="/insurance" id="editInsuranceForm" class="needs-validation" novalidate>
                @csrf
                {!! method_field('put') !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editInsuranceTitle">Edit Insurance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="name">Insurance Name</label>
                                <input title="Enter Insurance Name" type="text" name="name" class="form-control" id="edit_name" required>
                                <div class="invalid-feedback">
                                    Please choose a Insurance Name.
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="amount">Amount</label>
                                <input required type="text"  class="form-control" data-inputmask="'alias': 'currency'" min="1" style="text-align: right;"  id="edit_amount"   name="amount" >
                                <div class="invalid-feedback">
                                    Please Amount is required.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="icon icon-close"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon icon-pencil"></i> Edit Insurance
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Delete Insurance modal -->
    <div class="modal fade" id="deleteInsurance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Insurance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <p>Are you want to delete selected Items</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="icon icon-close"></i> Close
                    </button>
                    <button type="submit" id="btn_bulk_delete_insurance" class="btn btn-primary">
                        <i class="icon icon-trash"></i> Yes! Delete
                    </button>
                </div>
            </div>
        </div>
    </div>








    <!-- new Charge modal -->
    <div class="modal fade" id="newChargeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{route('charges.store')}}" class="needs-validation" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Charge</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="name">Charge Name</label>
                                <input title="Enter Insurance Name" type="text" name="name" class="form-control" id="name" required>
                                <div class="invalid-feedback">
                                    Please choose a Charge Name.
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="amount">Amount</label>
                                <input required type="text"  class="form-control" data-inputmask="'alias': 'currency'" min="1" style="text-align: right;"  id="amount"   name="amount" >
                                <div class="invalid-feedback">
                                    Please Amount is required.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="icon icon-close"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon icon-plus"></i> Add Charge
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- edit Charge modal -->
    <div class="modal fade" id="editChargeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="/insurance" id="editChargeForm" class="needs-validation" novalidate>
                @csrf
                {!! method_field('put') !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editChargeTitle">Edit Charge</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="name">Charge Name</label>
                                <input title="Enter Charge Name" type="text" name="name" class="form-control" id="edit_charge_name" required>
                                <div class="invalid-feedback">
                                    Please choose a Charge Name.
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="amount">Amount</label>
                                <input required type="text"  class="form-control" data-inputmask="'alias': 'currency'"  style="text-align: right;"  id="edit_charge_amount"   name="amount" >
                                <div class="invalid-feedback">
                                    Please Amount is required.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="icon icon-close"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon icon-pencil"></i> Edit Charge
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Delete Charge modal -->
    <div class="modal fade" id="deleteCharge" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Charge</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <p>Are you want to delete selected Items</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="icon icon-close"></i> Close
                    </button>
                    <button type="submit" id="btn_bulk_delete_charge" class="btn btn-primary">
                        <i class="icon icon-trash"></i> Yes! Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection