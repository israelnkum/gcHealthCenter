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
                                    <a class="nav-link active" id="drugs-tab" data-toggle="pill" href="#drugs" role="tab" aria-controls="drugs"  aria-selected="false">All Drugs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="drug_type-tab" data-toggle="pill" href="#drug_type" role="tab" aria-controls="drug_type" aria-selected="false">Types</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" id="suppliers-tab" data-toggle="pill" href="#suppliers" role="tab" aria-controls="suppliers" aria-selected="false">Suppliers</a>
                                </li>
                            </ul>
                            <div class="row ">
                                <div class="col-md-12">
                                    <div class="tab-content tab-body" id="profile-log-switch">
                                        {{--Start Drug TAb--}}

                                        <div class="tab-pane active fade show  fade" id="drugs" role="tabpanel" aria-labelledby="drugs-tab">
                                            <div class="card-body">
                                                <form action="{{route('bulk_deleteDrug')}}" id="bulkDeleteDrugForm" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6 text-left">
                                                            <h4 class="card-title">All Drugs</h4>
                                                        </div>
                                                        <div class="col-md-6 text-right mb-3">
                                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteDrug" id="deleteSelectedDrugs" disabled>
                                                                <i class="icon icon-trash"></i> Delete Selected
                                                            </button>
                                                            <!-- Button trigger modal -->
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newDrugModal">
                                                                <i class="icon icon-plus"></i> New Drug
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <table class="table table-responsive" id="drug_table">
                                                        <thead>
                                                        <tr>
                                                            <th>
                                                                <div class="form-check form-check-flat">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox"  class="form-check-input"  id="checkAllDrugs">
                                                                    </label>
                                                                </div>
                                                            </th>
                                                            <th>N<u>o</u></th>
                                                            <th>ID</th>
                                                            <th>Name</th>
                                                            <th>Supplier</th>
                                                            <th>Type</th>
                                                            <th>Qty In Stock</th>
                                                            <th>Cost Price</th>
                                                            <th>Retail Price</th>
                                                            <th>Supplier ID</th>
                                                            <th>Drug type ID</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php
                                                            $i=1;
                                                        @endphp
                                                        @foreach($drugs as $drug)
                                                            <tr>
                                                                <td>
                                                                    <div class="form-check form-check-flat">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox" value="{{$drug->id}}" class="form-check-input checkDrugItem" name="selected_drugs[]" id="remember">
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td>{!! $i !!}</td>
                                                                <td>{{$drug->id}}</td>
                                                                <td>{{$drug->name}}</td>
                                                                <td>{{$drug->supplier->name}}</td>
                                                                <td>{{$drug->drug_type->name}}</td>
                                                                <td>{{$drug->quantity_in_stock}}</td>
                                                                <td>{{$drug->cost_price}}</td>
                                                                <td>{{$drug->retail_price}}</td>
                                                                <td>{{$drug->supplier_id}}</td>
                                                                <td>{{$drug->drug_type_id}}</td>
                                                                {{--<td>{{substr($drug->created_at,0,10)}}</td>--}}
                                                                {{--<td>{{substr($drug->created_at,10)}}</td>--}}
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
                                        {{--End Drug TAb--}}


                                        {{--Start Drug Type TAb--}}
                                        <div class="tab-pane fade" id="drug_type" role="tabpanel" aria-labelledby="drug_type-tab">
                                            <div class="card-body">
                                                <form action="{{route('bulk_deleteDrugType')}}" id="bulkDeleteDrugTypeForm" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6 text-left">
                                                            <h4 class="card-title">All Drug Types</h4>
                                                        </div>
                                                        <div class="col-md-6 text-right mb-3">
                                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteDrugType" id="deleteSelectedType" disabled>
                                                                <i class="icon icon-trash"></i> Delete Selected
                                                            </button>
                                                            <!-- Button trigger modal -->
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newDrugType">
                                                                <i class="icon icon-plus"></i> New Drug Type
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <table class="table" id="drug_type_table">
                                                        <thead>
                                                        <tr>
                                                            <th>
                                                                <div class="form-check form-check-flat">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox"  class="form-check-input"  id="checkAllDrugTypes">
                                                                    </label>
                                                                </div>
                                                            </th>
                                                            <th>N<u>o</u></th>
                                                            <th>ID</th>
                                                            <th>Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php
                                                            $i=1;
                                                        @endphp
                                                        @foreach($drug_types as $type)
                                                            <tr>
                                                                <td>
                                                                    <div class="form-check form-check-flat">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox" value="{{$type->id}}" class="form-check-input checkItemDrugType" name="selected_types[]" >
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td>{!! $i !!}</td>
                                                                <td>{{$type->id}}</td>
                                                                <td>{{$type->name}}</td>
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
                                        {{--End Drug Type TAb--}}

                                        {{--Start suppliers TAb--}}
                                        <div class="tab-pane fade" id="suppliers" role="tabpanel" aria-labelledby="suppliers-tab">
                                            <div class="card-body">
                                                <form action="{{route('bulk_deleteSupplier')}}" id="bulkDeleteSupplierForm" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6 text-left">
                                                            <h4 class="card-title">All Suppliers</h4>
                                                        </div>
                                                        <div class="col-md-6 text-right mb-3">
                                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteSupplier" id="deleteSelectedSupplier" disabled>
                                                                <i class="icon icon-trash"></i> Delete Selected
                                                            </button>
                                                            <!-- Button trigger modal -->
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newSupplierModal">
                                                                <i class="icon icon-plus"></i> New Suppliers
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <table class="table" id="supplier_table">
                                                        <thead>
                                                        <tr>
                                                            <th>
                                                                <div class="form-check form-check-flat">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox"  class="form-check-input"  id="checkAllSuppliers">
                                                                    </label>
                                                                </div>
                                                            </th>
                                                            <th>N<u>o</u></th>
                                                            <th>ID</th>
                                                            <th>Name</th>
                                                            <th>Phone Number</th>
                                                            <th>Email</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php
                                                            $i=1;
                                                        @endphp
                                                        @foreach($suppliers as $supplier)
                                                            <tr>
                                                                <td>
                                                                    <div class="form-check form-check-flat">
                                                                        <label class="form-check-label">
                                                                            <input type="checkbox" value="{{$supplier->id}}" class="form-check-input checkItemSupplier" name="selected_suppliers[]">
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td>{!! $i !!}</td>
                                                                <td>{{$supplier->id}}</td>
                                                                <td>{{$supplier->name}}</td>
                                                                <td>{{$supplier->phone_number}}</td>
                                                                <td>{{$supplier->email}}</td>
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
                                        {{--End suppliers TAb--}}

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- new DrugType modal -->
    <div class="modal fade" id="newDrugType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{route('drugs-types.store')}}" class="needs-validation" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Drug Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="name">Name</label>
                                <input title="Enter Insurance Name" type="text" name="name" class="form-control" id="name" required>
                                <div class="invalid-feedback">
                                    Name is required.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="icon icon-close"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon icon-plus"></i> Add Type
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- edit Drug Type modal -->
    <div class="modal fade" id="editDrugTypeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="/drug-types" id="editDrugTypeForm" class="needs-validation" novalidate>
                @csrf
                {!! method_field('put') !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDrugTypeTitle">Edit Drug Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="name">Name</label>
                                <input title="Enter Insurance Name" type="text" name="name" class="form-control" id="edit_type_name" required>
                                <div class="invalid-feedback">
                                    Name is required.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="icon icon-close"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon icon-pencil"></i> Edit Type
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Delete Drug Type modal -->
    <div class="modal fade" id="deleteDrugType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Drug type</h5>
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
                    <button type="submit" id="btn_bulk_delete_drugType" class="btn btn-primary">
                        <i class="icon icon-trash"></i> Yes! Delete
                    </button>
                </div>
            </div>
        </div>
    </div>





    {{--    Drugs--}}
    <!-- new Drug modal -->
    <div class="modal fade" id="newDrugModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{route('drugs.store')}}" class="needs-validation" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Drug Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="name">Drug Name</label>
                                <input title="Enter Insurance Name" type="text" name="name" class="form-control" id="name" required>
                                <div class="invalid-feedback">
                                    Drug Name is required.
                                </div>
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <div class="col-md-12">
                                <label for="">Drug Type</label>
                                <select title="Select Drug Type" name="type_id" class="js-example-basic-single form-control" style="width: 100%" required>
                                    <option value="">~Select Type~</option>
                                    @foreach($drug_types as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Drug Type is required.
                                </div>
                            </div>
                        </div>

                        <div class="form-row form-group">
                            <div class="col-md-12">
                                <label for="">Supplier</label>
                                <select title="Select Supplier" name="supplier_id" class="js-example-basic-single form-control" style="width: 100%" required>
                                    <option value="">~Select Supplier~</option>
                                    @foreach($suppliers as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Supplier is required.
                                </div>
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <div class="col-md-12">
                                <label for="">Receiving Stock</label>
                                <input required type="text" class="form-control" data-inputmask="'alias': 'currency'" style="text-align: right;" name="receiving_stock">
                                <div class="invalid-feedback">
                                    Receiving Stock is required
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="temperature" class="col-sm-2 col-form-label">Cost Price</label>
                            <div class="col-sm-4">
                                <input required type="text" class="form-control" data-inputmask="'alias': 'currency'" style="text-align: right;" name="cost_price">
                                <div class="invalid-feedback">
                                    Cost Price is required
                                </div>
                            </div>

                            <label for="retail_price" class="col-sm-2 col-form-label text-right">Retail Price</label>
                            <div class="col-sm-4">
                                <input required type="text" name="retail_price" class="form-control" data-inputmask="'alias': 'currency'" style="text-align: right;" id="retail_price">
                                <div class="invalid-feedback">
                                    Retail Price is required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="icon icon-close"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon icon-plus"></i> Add Drug
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- edit Drug Type modal -->
    <div class="modal fade" id="editDrugModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="/drugs" id="editDrugForm" class="needs-validation" novalidate>
                @csrf
                {!! method_field('put') !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDrugTitle">Edit Drug</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="name">Drug Name</label>
                                <input title="Enter Insurance Name" type="text" name="name" class="form-control" id="edit_drug_name" required>
                                <div class="invalid-feedback">
                                    Drug Name is required.
                                </div>
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <div class="col-md-12">
                                <label for="">Drug Type</label>
                                <select title="Select Drug Type" id="edit_drug_type_id" name="type_id" class="js-example-basic-single form-control" style="width: 100%" required>
                                    <option value="">~Select Type~</option>
                                    @foreach($drug_types as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Drug Type is required.
                                </div>
                            </div>
                        </div>

                        <div class="form-row form-group">
                            <div class="col-md-12">
                                <label for="">Supplier</label>
                                <select title="Select Supplier" id="edit_supplier_id" name="supplier_id" class="js-example-basic-single form-control" style="width: 100%" required>
                                    <option value="">~Select Supplier~</option>
                                    @foreach($suppliers as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Supplier is required.
                                </div>
                            </div>
                        </div>
                        <div class="form-row form-group">
                            <div class="col-md-6">
                                <label for="">Quantity In Stock</label>
                                <input required type="text" disabled class="form-control" data-inputmask="'alias': 'currency'" style="text-align: right;" id="quantity_stock" name="quantity_stock">
                                <div class="invalid-feedback">
                                    Quantity is required
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="">Receiving Stock</label>
                                <input required type="text" class="form-control" data-inputmask="'alias': 'currency'" style="text-align: right;" id="edit_receiving_stock" name="receiving_stock">
                                <div class="invalid-feedback">
                                    Receiving Stock is required
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="temperature" class="col-sm-2 col-form-label">Cost Price</label>
                            <div class="col-sm-4">
                                <input required type="text" class="form-control" data-inputmask="'alias': 'currency'" style="text-align: right;" id="edit_cost_price" name="cost_price">
                                <div class="invalid-feedback">
                                    Cost Price is required
                                </div>
                            </div>

                            <label for="retail_price" class="col-sm-2 col-form-label text-right">Retail Price</label>
                            <div class="col-sm-4">
                                <input required type="text" name="retail_price" class="form-control" data-inputmask="'alias': 'currency'" style="text-align: right;" id="edit_retail_price">
                                <div class="invalid-feedback">
                                    Retail Price is required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="icon icon-close"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon icon-pencil"></i> Edit Drug
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Delete Drug modal -->
    <div class="modal fade" id="deleteDrug" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Drug</h5>
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
                    <button type="submit" id="btn_bulk_delete_drug" class="btn btn-primary">
                        <i class="icon icon-trash"></i> Yes! Delete
                    </button>
                </div>
            </div>
        </div>
    </div>








    <!-- new Supplier modal -->
    <div class="modal fade" id="newSupplierModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{route('suppliers.store')}}" class="needs-validation" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Supplier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="name">Supplier Name</label>
                                <input title="Enter Insurance Name" type="text" name="name" class="form-control" id="name" required>
                                <div class="invalid-feedback">
                                    Supplier Name is required
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="amount">Phone Number</label>
                                <input required type="text"  class="form-control"  id="phone_number"   name="phone_number" >
                                <div class="invalid-feedback">
                                    Phone Number is required.
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="amount">Email</label>
                                <input  type="email"  class="form-control"  id="email"   name="email" >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="icon icon-close"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon icon-plus"></i> Add Supplier
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- edit Supplier modal -->
    <div class="modal fade" id="editSupplierModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="/suppliers" id="editSupplierForm" class="needs-validation" novalidate>
                @csrf
                {!! method_field('put') !!}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSupplierTitle">Edit Supplier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="name">Supplier Name</label>
                                <input title="Enter Insurance Name" type="text" name="name" class="form-control" id="s_name" required>
                                <div class="invalid-feedback">
                                    Supplier Name is required
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="amount">Phone Number</label>
                                <input required type="text"  class="form-control"  id="s_phone_number"   name="phone_number" >
                                <div class="invalid-feedback">
                                    Phone Number is required.
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="amount">Email</label>
                                <input  type="email"  class="form-control"  id="s_email"   name="email" >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="icon icon-close"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon icon-pencil"></i> Edit Info
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Delete Supplier modal -->
    <div class="modal fade" id="deleteSupplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Supplier</h5>
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
                    <button type="submit" id="btn_bulk_delete_supplier" class="btn btn-primary">
                        <i class="icon icon-trash"></i> Yes! Delete
                    </button>
                </div>
            </div>
        </div>
    </div>




@endsection