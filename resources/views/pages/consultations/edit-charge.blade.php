@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-4 offset-md-4 mb-2">
                <a href="{{route('consultation.edit',[$patient_service->registration_id])}}" class="btn btn-danger">
                    <i class="icon icon-arrow-left-circle"></i>Back
                </a>
            </div>
            <div class ="col-md-4 offset-md-2 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Service</h4>
                        <form action="{{route('edit_service_charge')}}" method="post" class="mb-1">
                            @csrf
                            <input type="hidden" value="{{$patient_service->id}}" name="p_service_id">
                            <div class="form-group row mb-0">
                                <div class="col-md-12 mb-2">
                                    <label for="drug">Service</label>
                                    <select  name="charge_id" id="charge_id" class="js-example-basic-single w-100 form-control">
                                        <option value="{{$service->id}}">{{$service->name}}</option>
                                        @foreach($allServices as $services)
                                            @if($services->name != "Insured" && $services->name != "Non-Insured" && $services->name != "Consultation")
                                                @if($service->id != $services->id)
                                                    <option value="{{$services->id}}">{{$services->name}}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Select a Service
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
            <div class="col-md-4">
                <ul class="list-group list-group-flush">
                    @php($i =1)
                    @foreach($getAllServices as $allService)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{">> ".$allService->charge->name}}

                            <span>{{$allService->created_at}}</span>
                        </li>
                        @php($i++)
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection