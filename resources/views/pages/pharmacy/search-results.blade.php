@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-8 offset-md-2 text-right grid-margin">
                <form class="needs-validation" novalidate action="{{route('searchPatientForDrugDispersion')}}" method="get">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-12 ">
                            <div class="input-group">
                                <input type="text" required class="form-control" name="search" placeholder=" Search by Folder Number or Patient's Last Name or Phone Number">
                                <div class="input-group-prepend">
                                    {{--                                    <div class="form-check form-check-flat">--}}
                                    {{--                                        <label class="form-check-label">--}}
                                    {{--                                            <input type="checkbox"   class="form-check-input">--}}
                                    {{--                                            Detaned--}}
                                    {{--                                        </label>--}}
                                    {{--                                    </div>--}}
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
            {{--  <div class="col-md-4 text-right grid-margin ">
                  <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#newPatient">
                      <i class="icon icon-plus"></i> New Patient
                  </button>
              </div>--}}
        </div>
        <div class="row">
            @foreach($searchResults as $results)
                <div class="col-md-4 grid-margin">
                    <div class="card">
                        <h4 class="card-title mt-0"></h4>
                        <a href="{{route('records.show',$results->id)}}" style="text-decoration: none" class="">
                            <div class="card-body">
                                <h6 class=" text-uppercase mb-0">{{$results->title." ".$results->first_name." ".$results->other_name." ".$results->last_name}}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-inline-block pt-3">
                                        <div class="d-md-flex">
                                            <h5 class="mb-0 text-uppercase"><span class="text-danger">Folder Number:</span> {{$results->folder_number}}</h5>
                                        </div>
                                        <small class="text-gray">{{$results->phone_number}}</small>
                                    </div>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection