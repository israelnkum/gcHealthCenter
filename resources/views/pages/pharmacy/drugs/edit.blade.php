@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <div class="row profile-page">
            <div class="col-12">
                @component('partials.breadcrumb')
                    <li class="breadcrumb-item" ><a href="{{route('drugs.index')}}">Drugs</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span>{{$drug->name}}</span></li>
                @endcomponent
            </div>
            <div class="col-10 offset-1">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{route('drugs.update',$drug->id)}}" id="editDrugForm" class="needs-validation" novalidate>
                            @csrf
                            {!! method_field('put') !!}
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="name">Drug Name</label>
                                    <input value="{{$drug->name}}" title="Enter Drug Name" type="text" name="name" class="form-control" id="edit_drug_name" required>
                                    <div class="invalid-feedback">
                                        Drug Name is required.
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Drug Type</label>
                                    <select title="Select Drug Type" id="edit_drug_type_id" name="type_id" class="js-example-basic-single form-control" style="width: 100%" required>
                                        <option value="">~Select Type~</option>
                                        @foreach($drug_types as $type)
                                            <option @if($drug->drug_type_id == $type->id) selected @endif  value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Drug Type is required.
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label for="edit_unit_of_pricing">Unit Of Pricing</label>
                                    <select  id="edit_unit_of_pricing" title="Select Supplier" name="unit_of_pricing" class="js-example-basic-single form-control" style="width: 100%">
                                        <option value="">~Select~</option>
                                        <option @if($drug->unit_of_pricing == "Blister (x10tabs)") selected @endif value="Blister (x10tabs)">Blister (x10tabs)</option>
                                        <option @if($drug->unit_of_pricing == "10ml") selected @endif value="10ml">10ml</option>
                                        <option @if($drug->unit_of_pricing == "20ml") selected @endif value="20ml">20ml</option>
                                        <option @if($drug->unit_of_pricing == "30GR") selected @endif value="30GR">30GR</option>
                                        <option @if($drug->unit_of_pricing == "6 PESS") selected @endif value="6 PESS">6 PESS</option>
                                        <option @if($drug->unit_of_pricing == "15G") selected @endif value="15G">15G</option>
                                        <option @if($drug->unit_of_pricing == "AMPOULE") selected @endif value="AMPOULE">AMPOULE</option>
                                        <option @if($drug->unit_of_pricing == "VIAL") selected @endif value="VIAL">VIAL</option>
                                        <option @if($drug->unit_of_pricing == "BOTTLE") selected @endif value="BOTTLE">BOTTLE</option>
                                        <option @if($drug->unit_of_pricing == "SATCHET") selected @endif value="SATCHET">SATCHET</option>
                                        <option @if($drug->unit_of_pricing == "BOTTLE/SATCHET") selected @endif value="BOTTLE/SATCHET">BOTTLE/SATCHET</option>
                                        <option @if($drug->unit_of_pricing == "DOSE") selected @endif value="DOSE">DOSE</option>
                                        <option @if($drug->unit_of_pricing == "SUPP") selected @endif value="SUPP">SUPP</option>
                                        <option @if($drug->unit_of_pricing == "PACK") selected @endif value="PACK">PACK</option>
                                        <option @if($drug->unit_of_pricing == "PACK (6caps)") selected @endif value="PACK (6caps)">PACK (6caps)</option>
                                        <option @if($drug->unit_of_pricing == "PACK (9tabs)") selected @endif value="PACK (9tabs)">PACK (9tabs)</option>
                                        <option @if($drug->unit_of_pricing == "PACK (14tabs)") selected @endif value="PACK (14tabs)">PACK (14tabs)</option>
                                        <option @if($drug->unit_of_pricing == "PACK (28tabs)") selected @endif value="PACK (28tabs)">PACK (28tabs)</option>
                                        <option @if($drug->unit_of_pricing == "PACK (30tabs)") selected @endif value="PACK (30tabs)">PACK (30tabs)</option>
                                        <option @if($drug->unit_of_pricing == "1 course") selected @endif value="1 course">1 course</option>
                                        <option @if($drug->unit_of_pricing == "1 course (6tabs)") selected @endif value="1 course (6tabs)">1 course (6tabs)</option>
                                        <option @if($drug->unit_of_pricing == "1 course (24tabs)") selected @endif value="1 course (24tabs)">1 course (24tabs)</option>
                                        <option @if($drug->unit_of_pricing == "2 course (12tabs)") selected @endif value="2 course (12tabs)">2 course (12tabs)</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Unit of Pricing is required.
                                    </div>
                                </div>
                                <div class="col-md-2" style="display: none;" id="edit_blisters">
                                    <label for="number_of_tablet">Tablets Per Blister</label>
                                    <input name="no_of_tablet" id="number_of_tablet" type="number" value="10" class="form-control">
                                    <div class="invalid-feedback">
                                        N<u>o</u> Blisters is required.
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="">Supplier</label>
                                    <select title="Select Supplier" id="edit_supplier_id" name="supplier_id" class="js-example-basic-single form-control" style="width: 100%" required>
                                        <option value="">~Select Supplier~</option>
                                        @foreach($suppliers as $type)
                                            <option @if($drug->supplier_id == $type->id) selected @endif value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Supplier is required.
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Quantity In Stock</label>
                                    <input required type="text" value="{{$drug->qty_in_stock}}" disabled class="form-control" data-inputmask="'alias': 'currency'" style="text-align: right;" id="quantity_stock" name="quantity_stock">
                                    <div class="invalid-feedback">
                                        Quantity is required
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Receiving Stock</label>
                                    <input required type="text" class="form-control" data-inputmask="'alias': 'currency'" style="text-align: right;" id="edit_receiving_stock" name="receiving_stock">
                                    <div class="invalid-feedback">
                                        Receiving Stock is required
                                    </div>
                                    <small id="check_unit_of_pricing" class="text-danger d-none">Should be in Blisters</small>
                                </div>
                                <div class="col-sm-2">
                                    <label for="temperature">Cost Price</label>
                                    <input required type="text" value="{{$drug->cost_price}}" class="form-control" data-inputmask="'alias': 'currency'" style="text-align: right;" id="edit_cost_price" name="cost_price">
                                    <div class="invalid-feedback">
                                        Cost Price is required
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <label for="retail_price">Retail Price</label>

                                    <input required value="{{$drug->retail_price}}" type="text" name="retail_price" class="form-control" data-inputmask="'alias': 'currency'" style="text-align: right;" id="edit_retail_price">
                                    <div class="invalid-feedback">
                                        Retail Price is required
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="icon icon-pencil"></i> Edit Drug
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
