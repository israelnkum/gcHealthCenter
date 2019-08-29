@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 offset-md-2 grid-margin">
                <form class="needs-validation" novalidate action="{{route('searchConsultation')}}" method="get">
                    @csrf
                    <div class="form-group row mb-0">
                        <div class="col-md-12 ">
                            <div class="input-group">
                                <input required type="text"  class="form-control" name="search" placeholder=" Search by Folder Number or Patient's Last Name or Phone Number">
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
        </div>
        @if(count($registration)  == 0)
            <div class="text-center">
                <small class="display-4 text-info">Relax! No Patient in Queue</small>
            </div>
        @elseif(count($registration)  == 1)
            <div class="row">
                <div class ="col-md-4 grid-margin stretch-card">
                    <div class="accordion accordion-bordered" id="accordion-2" role="tablist">
                        @foreach($registration as $registered)
                            <div class="card-header" role="tab" id="heading-4">
                                <a data-toggle="collapse" style="text-decoration: none" href="javascript:void(0)" aria-expanded="false" aria-controls="collapse-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="mb-1 text-primary" >
                                                <i class="icon-folder mr-1"></i> {{$registered->patient->folder_number}}
                                            </small>
                                            <br>
                                            <small class="mb-1 text-danger">
                                                <i class="icon-user mr-1"></i>
                                                {{$registered->patient->title." ".$registered->patient->first_name." ".$registered->patient->other_name." ".$registered->patient->last_name}}
                                            </small>
                                            <br>
                                            <small class="text-muted mb-0" ><i class="icon-phone mr-1"></i>{{$registered->patient->phone_number}}</small>
                                        </div>
                                        <div class="col-md-6 text-right text-small">
                                            Date Of Birth: <span class="font-weight-bold">{{$registered->patient->date_of_birth}}</span><br>
                                            Age: <span class="font-weight-bold">{{$registered->patient->age}}</span><br>
                                            Gender: <span class="font-weight-bold">{{$registered->patient->gender}}</span><br>
                                            Marital Status: <span class="font-weight-bold">{{$registered->patient->marital_status}}</span><br>
                                        </div>
                                    </div>
                                    <hr>
                                    <h6 class="text-uppercase">Vital Signs</h6>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <small>Blood Pressure(BP) - <span class="text-danger">{{$getVitals[0]->blood_pressure}} mmHg</span></small>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <small>Weight - <span class="text-danger">{{$getVitals[0]->weight}} kg</span></small>
                                        </div>
                                        <div class="col-md-6">
                                            <small>Temperature - <span class="text-danger">{{$getVitals[0]->temperature}} °c</span></small>
                                        </div>

                                        <div class="col-md-6 text-right">
                                            <small>Pulse - <span class="text-danger">{{$getVitals[0]->pulse}} bpm</span></small>
                                        </div>
                                        <div class="col-md-6">
                                            <small>Glucose - <span class="text-danger">{{$getVitals[0]->glucose}} mol</span></small>
                                        </div>

                                        <div class="col-md-6 text-right">
                                            <small>RDT - <span class="text-danger">{{$getVitals[0]->RDT}}</span></small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div id="GC{{$registered->patient->registration_number}}" class="collapse show" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-2">
                                <div class="card-body">
                                    {{--  New Consultaion Form --}}
                                    <form class="needs-validation" enctype="multipart/form-data" id="consultation_form" novalidate method="post" action="{{route('consultation.store')}}">
                                        @csrf
                                        <input required type="hidden" name="registration_id" value="{{$registered->id}}">
                                        <input required type="hidden" name="patient_id" value="{{$registered->patient->id}}">
                                        <input required type="hidden" name="registration_number" value="{{$registered->patient->registration_number}}">
                                    </form>
                                </div>
                            </div>
                        @endforeach

                            <button data-toggle="modal" data-target="#add_service" type="button" class="btn btn-secondary pt-1 pb-1 pr-1 pl-1">
                                <i class="icon icon-plus"></i> Add Service
                            </button>
                            <button data-toggle="modal" data-target="#add_drug"  type="button" class="btn btn-dark pt-1 pb-1 pr-1 pl-1">
                                <i class="icon icon-plus"></i> Add Med
                            </button>
                    </div>


                    {{--Add Service modal--}}
                    <div class="modal fade" id="add_service" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{route('add-service-only')}}" method="post" class="needs-validation" novalidate>
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Service</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body pt-0 text-left">
                                        <input type="hidden" value="{{$recentRegistration->patient_id}}" name="patient_id">
                                        <input type="hidden" value="{{$recentRegistration->id}}" name="registration_id">

                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="text-info">Select Service</label>
                                                <select required class="col-12 form-control mr-1 selectMedicine text-left" multiple style="width: 100%;"    name="service[]" id="service_only">
                                                    <option value="">Nothing Selected</option>
                                                    @foreach($charges as $charge)
                                                        @if($charge->name != "Insured" && $charge->name !="Detain/Admit" && $charge->name != "Non-Insured" && $charge->name != "Consultation")
                                                            <option value="{{$charge->id.",".$charge->name}}">{{$charge->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                                <div class="invalid-feedback">
                                                    Service required
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            <i class="icon icon-close"></i> Close
                                        </button>
                                        <button type="submit" class="btn btn-dark">
                                            <i class="icon icon-plus"></i> Add Service
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{--End Service Modal--}}


                    {{--Add Drug modal--}}
                    <div class="modal fade" id="add_drug" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lgg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Medication</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="post" novalidate class="needs-validation" action="{{route('add-medication-only')}}">
                                    @csrf
                                    <div class="modal-body pt-0">
                                        <input type="hidden" value="{{$recentRegistration->patient_id}}" name="patient_id">
                                        <input type="hidden" value="{{$recentRegistration->id}}" name="registration_id">
                                        <div class="row">
                                            <div class="col-md-7 text-left" >
                                                <label for="" class="text-info">Drugs</label>
                                            </div>
                                            <div class="col-md-2 text-left">
                                                <label for="" class="text-info">Dosage</label>
                                            </div>
                                            <div class="col-md-1 text-left">
                                                <label for="" class="text-info">Days</label>
                                            </div>
                                            <div class="col-md-1 text-left">
                                                <label for="" class="text-info">Qty</label>
                                            </div>
                                        </div>
                                        <div class="repeater">
                                            <div data-repeater-list="medications">
                                                <div data-repeater-item class="mb-2">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <select required style="width: 100% " class="selectMedicine col-12 form-control mr-1"    name="drug_id" id="drug_id">
                                                                <option value=""></option>
                                                                @foreach($drugs as $drug)
                                                                    <option value="{{$drug->id}}"> {{$drug->name}} - ({{$drug->drug_type->name}}) </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Drug is required
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            {{-- <input type="text" name="dosage" id="dosage"  class="form-control col-12 ml-1">--}}
                                                            {{--<select required class="selectMedicine col-12 form-control mr-1 dosage"    name="dosage" id="dosage">
                                                                <option value=""></option>
                                                                <option value="3tid">tid</option>
                                                                <option value="2bd">bd</option>
                                                                <option value="1nocte">nocte</option>
                                                                <option value="1stat">stat</option>
                                                                <option value="1dly">dly</option>
                                                            </select>--}}
                                                            <input required list="dosage" class="form-control" placeholder="Dosage" name="dosage">
                                                            <datalist id="dosage">
                                                                <option value="tid">
                                                                <option value="bd">
                                                                <option value="nocte">
                                                                <option value="stat">
                                                                <option value="dly">
                                                                <option value="btl">
                                                                <option value="course">
                                                            </datalist>
                                                            <div class="invalid-feedback">
                                                                Dosage is required
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <input type="number" required name="days" value="0" style="width: 70px;" min="1" placeholder="Days"  class="form-control days">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <input type="number" required name="qty" value="0" style="width: 70px;" min="1" placeholder="Qty"  class="form-control qty">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button data-repeater-delete type="button" class="btn btn-danger p-2 icon-btn ml-2" >
                                                                <i class="icon-close"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <button data-repeater-create type="button" class="btn btn-info icon-btn p-2 mb-2">
                                                    <i class="icon-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 text-left" >
                                                <label for="" class="text-info">Other Medication(s)</label>
                                            </div>
                                        </div>
                                        <div class="other-repeater">
                                            <div data-repeater-list="other-medications">
                                                <div data-repeater-item class="mb-2">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="text" title="Drug Name" placeholder="Enter Drug name" name="other_medication" id="other_medication"  class="form-control col-12 ml-1">
                                                        </div>
                                                        <div class="col-md-3">
                                                            {{--                                                                    <input type="text" name="other_dosage" id="other_dosage" placeholder="Dosage"  class="form-control col-12 ml-1">--}}
                                                            {{--<select style="width: 100%" class="selectMedicine col-12 form-control mr-1"    name="dosage" id="dosage">
                                                                <option value=""></option>
                                                                <option value="3tid">tid</option>
                                                                <option value="2bd">bd</option>
                                                                <option value="1nocte">nocte</option>
                                                                <option value="1stat">stat</option>
                                                                <option value="1dly">dly</option>
                                                            </select>--}}
                                                            <input  list="other_dosage" class="form-control" placeholder="Dosage" name="other_dosage">
                                                            <datalist id="other_dosage">
                                                                <option value="tid">
                                                                <option value="bd">
                                                                <option value="nocte">
                                                                <option value="stat">
                                                                <option value="dly">
                                                                <option value="btl">
                                                                <option value="course">
                                                            </datalist>
                                                            <div class="invalid-feedback">
                                                                Dosage is required
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="number" name="other_days" style="width: 70px;" min="1" placeholder="Days" class="form-control">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button data-repeater-delete type="button" class="btn btn-danger p-2 icon-btn" >
                                                                <i class="icon-close"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <button data-repeater-create title="New Medication" type="button" class="btn btn-info p-2 icon-btn mb-2">
                                                    <i class="icon-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            <i class="icon icon-close"></i> Close
                                        </button>
                                        <button type="submit" class="btn btn-dark">
                                            <i class="icon icon-plus"></i> Add Medication
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{--End Drug Modal--}}
                </div>

            </div>
        @else
            <div class="text-center">
                <small class="display-4 text-info">Good Job! No Patient in Queue</small>
            </div>
        @endif
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection