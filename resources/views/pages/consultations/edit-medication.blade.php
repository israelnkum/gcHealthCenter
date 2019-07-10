@extends('layouts.app')
@section('content')
    <!-- partial -->
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-4 offset-md-4 mb-2">
                <a href="{{route('consultation.edit',[$medication->registration_id])}}" class="btn btn-danger">
                    <i class="icon icon-arrow-left-circle"></i>Back
                </a>
            </div>
            <div class ="col-md-4 offset-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Medication</h4>
                        <form novalidate action="{{route('edit_med')}}" method="post" class="mb-1 needs-validation">
                            @csrf
                            <input type="hidden" class="form-control" name="med_id" value="{{$medication->id}}">
                            <div class="form-group row mb-0">
                                <div class="col-md-12 mb-2">
                                    <label for="drug">Drug</label>
                                    <select  name="drug_id" id="drug" class="js-example-basic-single w-100 form-control">
                                        <option value="{{$drug->id}}">{{$drug->name}}- ({{$drug->drug_type->name}})</option>
                                        @foreach($drugs as $drg)
                                            @if($drug->id != $drg->id)
                                                <option value="{{$drg->id}}">{{$drg->name}} - ({{$drg->drug_type->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Select a Drug
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="dosage">Dosage</label>
                                    <select required class="selectMedicine col-12 form-control mr-1"    name="dosage" id="dosage">
                                        <option value="{{$medication->qty / $medication->days.$medication->dosage}}">{{$medication->dosage}}</option>
                                        <option value="3tid">tid</option>
                                        <option value="2bd">bd</option>
                                        <option value="1nocte">nocte</option>
                                        <option value="1stat">stat</option>
                                        <option value="1dly">dly</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Dosage is required
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="days">Days</label>
                                    <input required type="number" min="1" value="{{$medication->days}}" id="days" name="days" class="form-control">
                                    <div class="invalid-feedback">
                                        Days required
                                    </div>
                                </div>

                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-info"> <i class="icon icon-note"></i> Update</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection