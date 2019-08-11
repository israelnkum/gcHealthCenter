@extends('layouts.app')
@section('content')
    <!-- partial -->

    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 text-right mb-3">
                <form class="needs-validation" novalidate action="{{route('searchConsultation')}}" method="get">
                    @csrf
                    <input required type="hidden"  value="{{$patient->folder_number}}" class="form-control" name="search">
                    <button type="submit" class="btn-danger btn text-small"><i class="icon icon-arrow-left-circle"></i> Go Back</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class ="col-md-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="accordion accordion-bordered" id="accordion-2" role="tablist">
                            <div class="card-header" role="tab" id="heading-4">
                                <a data-toggle="collapse" style="text-decoration: none" href="#GC{{$patient->registration_number}}" aria-expanded="false" aria-controls="collapse-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h2 class="mb-1 text-primary" style="font-size: 25px; font-weight: 600">
                                                <i class="icon-folder mr-1"></i> {{$patient->folder_number}}
                                            </h2>
                                            <h5 class="mb-1 text-danger">
                                                <i class="icon-user mr-1"></i>
                                                {{$patient->title." ".$patient->first_name." ".$patient->other_name." ".$patient->last_name}}
                                            </h5>
                                            <small class="text-muted mb-0" ><i class="icon-phone mr-1"></i>{{$patient->phone_number}}</small>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            Date Of Birth: <span class="font-weight-bold">{{$patient->date_of_birth}}</span><br>
                                            Age: <span class="font-weight-bold">{{$patient->age}}</span><br>
                                            Gender: <span class="font-weight-bold">{{$patient->gender}}</span><br>
                                            Marital Status: <span class="font-weight-bold">{{$patient->marital_status}}</span><br>
                                        </div>
                                    </div>
                                    <hr>
                                    <h6 class="card-title">Vital Signs</h6>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <h6>Blood Pressure(BP) - <span class="text-danger">{{$vitals->blood_pressure}} mmHg</span></h6>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <div class="form-group">
                                                <h6>Weight - <span class="text-danger">{{$vitals->weight}} kg</span></h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h6>Temperature - <span class="text-danger">{{$vitals->temperature}} °c</span></h6>
                                            </div>
                                        </div>

                                        <div class="col-md-6 text-right">
                                            <div class="form-group">
                                                <h6>Pulse - <span class="text-danger">{{$vitals->pulse}} bpm</span></h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h6>Glucose - <span class="text-danger">{{$vitals->glucose}} mol</span></h6>
                                            </div>
                                        </div>

                                        <div class="col-md-6 text-right">
                                            <div class="form-group">
                                                <h6>RDT - <span class="text-danger">{{$vitals->RDT}}</span></h6>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div id="GC{{$patient->registration_number}}" class="collapse show" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-2">
                                <div class="card-body">
                                    {{--  New Consultaion Form --}}
                                    <form class="needs-validation" enctype="multipart/form-data" id="consultation_form" novalidate method="post" action="{{route('consultation.update',$consultation->id)}}">
                                        @csrf
                                        {!! method_field('put') !!}
                                        <input  type="hidden" name="registration_id" value="{{$registration->id}}">
                                        <input  type="hidden" name="patient_id" value="{{$patient->id}}">
                                        <input  type="hidden" name="registration_number" value="{{$patient->registration_number}}">
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="complains" class="text-info">Complains</label>
                                                <textarea  name="complains" class="form-control" id="complains" rows="10">{{$consultation->complains}}</textarea>
                                                <div class="invalid-feedback">
                                                    Complains is
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="physical_examination" class="text-info">Physical Examination</label>
                                                <textarea   name="physical_examination" class="form-control" id="physical_examination" rows="10">{{$consultation->physical_examination}}</textarea>
                                                <div class="invalid-feedback">
                                                    Physical Examination is
                                                </div>
                                            </div>
                                        </div>
                                         <div class="form-group row">
                                             <div class="col-sm-6">
                                                 <label for="findings" class="text-info">History</label>
                                                 <textarea  name="findings" class="form-control" id="findings" rows="10">{{$consultation->findings}}</textarea>
                                                 <div class="invalid-feedback">
                                                     Finding is
                                                 </div>
                                             </div>

                                            <div class="col-sm-6">
                                                <label for="diagnosis" class="text-info">Diagnosis</label>
                                                <select  class="form-control js-example-basic-multiple" multiple="multiple" style="width: 100%" name="diagnosis[]" id="select_diagnosis">
                                                    <option value="">Select Diagnosis</option>
                                                    @foreach($diagnosis as $diag)
                                                        <option value="{{$diag->id}}">{{$diag->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    Diagnosis is
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-7" >
                                                <label for="" class="text-info">Drugs</label>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="" class="text-info">Dosage</label>
                                            </div>
{{--                                            <div class="col-md-1">--}}
{{--                                                <label for="" class="text-info">Days</label>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-1">--}}
{{--                                                <label for="" class="text-info">Days</label>--}}
{{--                                            </div>--}}
                                        </div>
                                        <div class="repeater">
                                            <div data-repeater-list="medications">
                                                <div data-repeater-item class="mb-2">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <select  class="selectMedicine col-12 form-control mr-1"    name="drug_id" id="drug_id">
                                                                <option value=""></option>
                                                                @foreach($drugs as $drug)
                                                                    <option value="{{$drug->id}}"> {{$drug->name}} - ({{$drug->drug_type->name}}) </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Drug is required
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            {{-- <input type="text" name="dosage" id="dosage"  class="form-control col-12 ml-1">--}}
                                                            {{--<select required class="selectMedicine col-12 form-control mr-1 dosage"    name="dosage" id="dosage">
                                                                <option value=""></option>
                                                                <option value="3tid">tid</option>
                                                                <option value="2bd">bd</option>
                                                                <option value="1nocte">nocte</option>
                                                                <option value="1stat">stat</option>
                                                                <option value="1dly">dly</option>
                                                            </select>--}}
                                                            <input  list="dosage" class="form-control" placeholder="Dosage" name="dosage">
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
                                                        {{--<div class="col-md-1">
                                                            <input type="number"  name="days" value="0" style="width: 70px;" min="1" placeholder="Days"  class="form-control days">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <input type="number"  name="qty" value="0" style="width: 70px;" min="1" placeholder="Qty"  class="form-control qty">
                                                        </div>--}}
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
                                            <div class="col-md-6" >
                                                <label for="" class="text-info">Other Medication(s)</label>
                                            </div>
                                        </div>
                                        <div class="other-repeater">
                                            <div data-repeater-list="other-medications">
                                                <div data-repeater-item class="mb-2">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <input type="text" title="Drug Name" placeholder="Enter Drug name" name="other_medication" id="other_medication"  class="form-control col-12 ml-1">
                                                        </div>
                                                        <div class="col-md-4">
                                                            {{--                                                                    <input type="text" name="other_dosage" id="other_dosage" placeholder="Dosage"  class="form-control col-12 ml-1">--}}
                                                            {{--<select  class="selectMedicine col-12 form-control mr-1"    name="other_dosage" id="other_dosage">
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
                                                                Dosage is
                                                            </div>
                                                        </div>
                                                        {{--<div class="col-md-2">
                                                            <input type="number" name="other_days" style="width: 70px;" min="1" placeholder="Days" class="form-control">
                                                        </div>--}}
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

                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="text-info">Upload <u><b>LAB</b></u> Result(s)</label>
                                                        <input style="border-radius: 0; border: dashed 1px; padding: 3px;" name="labs[]" type="file"  multiple  class="form-control-file">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="text-info">Upload <u><b>SCAN</b></u> Result(s)</label>
                                                        <input style="border-radius: 0; border: dashed 1px; padding: 3px;" name="scan[]" type="file"  multiple  class="form-control-file">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="text-info">Select <u><b>Service</b></u></label>
                                                <select  class="col-12 form-control mr-1 js-example-basic-multiple" multiple  name="service[]" id="service">
                                                    @foreach($allCharges as $charge)
                                                        @if($charge->name != "Insured" && $charge->name != "Non-Insured" && $charge->name != "Consultation")
                                                            <option value="{{$charge->id.",".$charge->name}}">{{$charge->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row mt-5">
                                            <div class="col-sm-12 text-right">
                                                <button class="btn btn-primary" type="submit">Update</button>
                                            </div>
                                        </div>
                                    </form>


                                    {{--End New Consultaion Form--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class ="col-md-5 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <label class="text-info">Medication</label>
                        <blockquote class="blockquote">
                            <table class="table ">
                                <thead>
                                <tr>
                                    <th>Drug</th>
                                    <th>Dosage</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($medications as $medicine)
                                    <tr>
                                        <td><small>{{$medicine->drugs->name}}</small></td>
                                        <td><small>{{$medicine->dosage}} x {{$medicine->days}}</small></td>
                                        <td>
                                            <div class="row">
                                                {{--<div class="col-md-6 text-right">
                                                    @if($medicine->dispensed ==1)
                                                        <i class="icon icon-note"></i>
                                                    @else
                                                        <a  href="{{route('editMedication',[$medicine->drugs->id,$medicine->id])}}?{{Hash::make(time())}}">
                                                            <i class="icon icon-note"></i>
                                                        </a>
                                                    @endif

                                                </div>--}}
                                                <div class="col-md-6 text-left">
                                                    <form action="{{route('drugs.destroy',$medicine->id)}}" method="post" onsubmit="return confirm('Do you really want to delete this medication')">
                                                        @csrf
                                                        {!! method_field('delete') !!}
                                                        <button @if($medicine->dispensed ==1) disabled @endif class="btn btn-primary bg-transparent text-danger border-0 ml-0 p-0">
                                                            <i class="icon icon-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </blockquote>

                        <label class="text-info">Diagnosis</label>
                        <blockquote class="blockquote">
                            <table class="table ">
                                <thead>
                                <tr>
                                    <th>Diagnosis</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($patientDiagnosis as $diagnosis)
                                    <tr>
                                        <td><small>{{$diagnosis->diagnoses->name}}</small></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <a href="{{route('editDiagnosis',[$diagnosis->id])}}">
                                                        <i class="icon icon-note"></i>
                                                    </a>
                                                </div>
                                                <div class="col-md-6 text-left">
                                                    <form action="{{route('diagnoses.destroy',$diagnosis->id)}}" method="post" onsubmit="return confirm('Do you really want to delete this Diagnosis')">
                                                        {!! method_field('delete') !!}
                                                        @csrf
                                                        <button class="btn btn-primary bg-transparent text-danger border-0 ml-0 p-0">
                                                            <i class="icon icon-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </blockquote>

                        <label class="text-info">Services</label>
                        <blockquote class="blockquote">
                            <table class="table ">
                                <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Charge</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($services as $service)
                                    <tr>
                                        <td><small>{{$service->charge->name}}</small></td>
                                        <td>{{$service->charge->amount}}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <a href="{{route('edit_service',[$service->id])}}">
                                                        <i class="icon icon-note"></i>
                                                    </a>
                                                </div>
                                                <div class="col-md-6 text-left">
                                                    <form action="{{route('charges.destroy',$service->id)}}" method="post" onsubmit="return confirm('Do you really want to delete this Diagnosis')">
                                                        {!! method_field('delete') !!}
                                                        @csrf
                                                        <button class="btn btn-primary bg-transparent text-danger border-0 ml-0 p-0">
                                                            <i class="icon icon-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </blockquote>

                        @if(count($scanned_results)>0)
                            <label class="text-info">Scan Result</label>
                            <blockquote class="blockquote">
                                <table class="table ">
                                    <thead>
                                    <tr>
                                        <th>Result Img</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($i =1)
                                    @foreach($scanned_results as $scan)
                                        <tr>
                                            <td>
                                                <div id="scan{{$i}}" class="lightGallery">
                                                    <a href="{{asset('public/scan/'.$scan->file_name)}}" class="image-tile">
                                                        <img style="height: auto; width: 70px; border-radius: 0 !important;"  class="img-fluid" src="{{asset('public/scan/'.$scan->file_name)}}"  alt="{{$scan->file_name}}">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-capitalize">{{substr($scan->file_name,0,strpos($scan->file_name,'_'))}}</small>
                                            </td>
                                            <td>
                                                <form action="">
                                                    <button class="btn btn-primary bg-transparent text-danger border-0 ml-0 p-0">
                                                        <i class="icon icon-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @php($i++)
                                    @endforeach
                                    </tbody>
                                </table>
                            </blockquote>
                        @endif

                        @if(count($lab_results)>0)
                            <label class="text-info">Lab Result</label>
                            <blockquote class="blockquote">
                                <table class="table ">
                                    <thead>
                                    <tr>
                                        <th>Result Img</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @php($i= 1)
                                    @foreach($lab_results as $scan)
                                        <tr>
                                            <td>
                                                <div id="lab{{$i}}" class="lightGallery" style="width: 100%">
                                                    <a href="{{asset('public/labs/'.$scan->file_name)}}" class="image-tile">
                                                        <img style="height: auto; width: 70px; border-radius: 0 !important;" class="img-fluid" src="{{asset('public/labs/'.$scan->file_name)}}"  alt="{{$scan->file_name}}">
                                                    </a>
                                                </div>
                                                {{--                                                <img class="img-fluid" style="border-radius: 0 !important;" src="{{asset('public/labs/'.$scan)}}" alt="{{$scan}}">--}}
                                            </td>
                                            <td>
                                                <small class="text-capitalize">{{substr($scan->file_name,0,strpos($scan->file_name,'_'))}}</small>
                                            </td>
                                            <td>
                                                <form action="">
                                                    <button class="btn btn-primary bg-transparent text-danger border-0 ml-0 p-0">
                                                        <i class="icon icon-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @php($i++)
                                    @endforeach

                                    </tbody>
                                </table>
                            </blockquote>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

@endsection