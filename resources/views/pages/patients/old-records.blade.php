@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 offset-md-2 grid-margin">
                <form class="needs-validation" novalidate action="{{route('searchPatient')}}" method="get">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-12 ">
                            <div class="input-group">
                                <input type="text" required class="form-control" name="search" placeholder=" Search by Folder Number or Patient Name or Phone Number">
                                <div class="input-group-prepend">
                                    <button type="submit" class="input-group-text btn"><i class="icon-magnifier"></i></button>
                                </div>
                                <div class="invalid-feedback">
                                    Search by Folder Number or Patient Name or Phone Number
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-2 text-right grid-margin ">
            {{--<button type="button" class="btn btn-danger  mr-3" data-toggle="modal" data-target="#deleteStaff">
                <i class="icon icon-trash"></i> Delete Selected
            </button>--}}
            <!-- Button trigger modal -->
                <a role="button" href="{{route('consultation.show',$patient->id)}}" class="btn btn-danger">
                    <i class="icon icon-close"></i> Go Back
                </a>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><span class="text-danger">{{$patient->first_name." ".$patient->last_name."'s"}}</span> Old Records</h4>
                        <div class="wrapper">
                            @if(count($records)>0)
                                @foreach($records as $date => $record_files)
                                    <blockquote class="blockquote">
                                        <div class="row">
                                            <div class="col-md-1 mb-2 text-white bg-dark text-right">
                                                <small>{{$date}}</small>
                                            </div>
                                        </div>
                                        <div id="lightgallery-without-thumb" class="row lightGallery">
                                            @foreach($record_files as $record)
                                                <a href="{{asset('public/old_records/'.$patient->registration_number.'/'.$record->files)}}" class="image-tile"><img src="{{asset('public/old_records/'.$patient->registration_number.'/'.$record->files)}}" alt="{{$record->files}}"></a>
                                            @endforeach
                                        </div>
                                    </blockquote>
                                @endforeach

                                    @foreach($records as $date => $record_files)
                                        <blockquote class="blockquote">
                                            <div class="row">
                                                <div class="col-md-1 mb-2 text-white bg-dark text-right">
                                                    <small>{{$date}}</small>
                                                </div>
                                            </div>
                                            <div id="lightgallery-without-thumb" class="row lightGallery">
                                                @foreach($record_files as $record)
                                                    <a href="{{asset('public/old_records/'.$patient->registration_number.'/'.$record->files)}}" class="image-tile"><img src="{{asset('public/old_records/'.$patient->registration_number.'/'.$record->files)}}" alt="{{$record->files}}"></a>
                                                @endforeach
                                            </div>
                                        </blockquote>
                                    @endforeach

                            @else
                                <h4 class="display-4">No Record Uploaded</h4>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->








    <!-- Confirm bulk Staff modal -->
    <div class="modal fade" id="deleteStaff" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Staff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <p>Are you want to delete selected staff</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="icon icon-close"></i> Close
                    </button>
                    <button type="submit" id="btn_submit_bulk_delete" class="btn btn-success">
                        <i class="icon icon-trash"></i> Yes! Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection