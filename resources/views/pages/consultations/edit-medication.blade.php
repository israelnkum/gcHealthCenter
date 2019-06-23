@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class ="col-md-4 offset-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Medication</h4>
                        <form action="{{route('edit_med')}}" method="post" class="mb-1">
                            @csrf
                            <input type="hidden" class="form-control" name="med_id" value="{{$medication->id}}">
                            <div class="form-group row mb-0">
                                <div class="col-md-12 mb-2">
                                    <label for="drug">Drug</label>
                                    <select  name="drug_id" id="drug" class="js-example-basic-single w-100 form-control">
                                        <option value="{{$drug->id}}">{{$drug->name}}</option>
                                        @foreach($drugs as $drg)
                                            <option value="{{$drg->id}}">{{$drg->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Select a Drug
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="dosage">Dosage</label>
                                    <input type="text" name="dosage"  id="dosage" class="form-control" required value="{{$medication->dosage}}">
                                    <div class="invalid-feedback">
                                        Dosage required
                                    </div>
                                </div>

                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-info">Update</button>
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