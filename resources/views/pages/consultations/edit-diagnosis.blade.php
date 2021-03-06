@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class ="col-md-4 offset-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Diagnosis</h4>
                        <form action="{{route('edit_diagnosis')}}" method="post" class="mb-1">
                            @csrf
                            <input type="hidden" value="{{$patient_diagnosis->id}}" name="p_diagnosis_id">
                            <div class="form-group row mb-0">
                                <div class="col-md-12 mb-2">
                                    <label for="drug">Drug</label>
                                    <select  name="diagnosis_id" id="diagnosis_id" class="js-example-basic-single w-100 form-control">
                                        <option value="{{$diagnosis->id}}">{{$diagnosis->name}}</option>
                                        @foreach($allDiagnosis as $diag)
                                            <option value="{{$diag->id}}">{{$diag->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Select a date
                                    </div>
                                </div>

                                <div class="col-md-6 offset-md-4 mt-3">
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