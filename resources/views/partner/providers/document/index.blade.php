@extends('partner.layout.base')
@section('title', 'Driver Documents ')
@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
<link rel="stylesheet" href="{{asset('main/vendor/select2/dist/css/select2.min.css')}}">
<style>
    .viewtable tr{
        margin: 10px 5px;
        display: block;
    }
    .viewtable td:first-child{
        width: 165px;
    }
    .form-check-input {
        margin-left: 0px;
    }
    .margin-left{
        margin-left: 150px;
    }
    form{
        display: inline-block;
    }
</style>
@endsection
@section('content')
<div class="content-area py-1">
   <div class="container-fluid">
      <div class="row bg-title">
         <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h4 class="page-title">{{ $provider->name }} Documents</h4>
            <a href="{{ route('partner.provider.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.list_drivers')</a>
         </div>
         <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <ol class="breadcrumb">
               <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
               <li class="active">@lang('admin.list_drivers')</li>
            </ol>
         </div>
      </div>
      <div class="box box-block bg-white">
         <div class="row">
            <div class="col-xs-12">
               @if($providerService  != null)
               <table class="viewtable">
                  <h6>
                     @lang('admin.member.allocated_services') :   
                     <span style="display: inline-block;">
                        <form action="{{ route('partner.provider.document.remove',$provider->id) }}" method="POST">
                           {{ csrf_field() }}
                           <button class="btn btn-danger btn-sm">@lang('admin.member.delete')</button>
                        </form>
                     </span>
                  </h6>
                  <tr>
                     <td>@lang('admin.member.service_name')</td>
                     <td>: {{ $providerService->service->name }}</td>
                  </tr>
                  <tr>
                     <td>@lang('admin.member.service_type')</td>
                     <td>: @if($providerService->taxi_type ==0)
                        Own Service
                        @elseif($providerService->taxi_type ==1)
                        Attached Service  @if($providerService->fleet) ({{ $providerService->fleet->company }}) @else   @endif
                        @else
                        -
                        @endif
                     </td>
                  </tr>
                  <tr>
                     <td>@lang('admin.member.service_number')</td>
                     <td>: {{ $providerService->vehicle->vehicle_no }}</td>
                  </tr>
                  <tr>
                     <td>@lang('admin.member.service_model')</td>
                     <td>: {{ $providerService->vehicle->vehicle_model }}</td>
                  </tr>
                  <tr>
                     <td>Driver License Number</td>
                     <td>: @if($providerService->license_no !='')
                        {{ $providerService->license_no }}
                        @else
                        -
                        @endif
                     </td>
                  </tr>
                  <tr>
                     <td>License Expire Date</td>
                     <td>: @if($providerService->license_expire !='')
                        {{ $providerService->license_expire }}
                        @else
                        -
                        @endif
                     </td>
                  </tr>
                  <tr>
                     <td>Vehicle Insurance Number</td>
                     <td>: @if($providerService->vehicle->insurance_no  !='')
                        {{ $providerService->vehicle->insurance_no  }}
                        @else
                        -
                        @endif
                     </td>
                  </tr>
                  <tr>
                     <td>Insurance Expire Date</td>
                     <td>: @if($providerService->vehicle->insurance_exp !='')
                        {{ $providerService->vehicle->insurance_exp }}
                        @else
                        -
                        @endif
                     </td>
                  </tr>
               </table>
               <hr>
            </div>
            <div class="col-xs-12">
               <h5 class="mb-1">Update Driver Service:</h5>
               <br>
               <form action="{{ route('partner.provider.document.store', $provider->id) }}" method="POST">
                  {{ csrf_field() }}
                  <div class="col-xs-12">
                     <div class="col-xs-4">
                        <h6>Service Details:  </h6>
                        <br>
                        <div class="form-group">
                           <label for="service_type_id" >Service Types</label>
                           <select name="service_type_id" id="service_type_id" required="required" class="form-control">
                            <option value="">Select Service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" @if($service->id == $provider->service_type_id) selected @endif >{{ $service->name }}</option>
                            @endforeach
                        </select>
                        </div>
                         <div class="form-group">
                           <label for="allowed_service" >Allowed service Types</label>
                                <select id="allowed_service" name="allowed_service[]"  class="form-control" data-plugin="select2" multiple="multiple">
                                 <!-- <option value="0">All service</option> -->
                                 @foreach($services as $service)
                                 <option value="{{ $service->id }}" @if(in_array($service->id, $allowed_service)) selected @endif>{{ $service->name }}</option>
                                 @endforeach
                              </select>
                        </div>
<!--                         <div class="form-group">
                           <label>Attached Service :</label>
                               <select class="form-control input" name="taxi_type" required id="taxi_type">
                                   <option value="">@lang('admin.member.service_type')</option>
                                   <option value="0" @if($providerService->taxi_type ==0) selected @endif>Own Service</option>
                                   <option value="1" @if($providerService->taxi_type ==1) selected @endif>Attached Service</option>
                               </select>
                           </div>
 -->                        <div class="form-group">
                           <label>Service Number:</label>
                           <input type="text" required value="{{ $providerService->vehicle->vehicle_no }}" name="vehicle_no" class="form-control" placeholder="Number (CY 98769)">
                        </div>
                        <div class="form-group">
                           <label>Service Model:</label>
                           <input type="text" required value="{{ $providerService->vehicle->vehicle_model }}" name="vehicle_model" class="form-control" placeholder="Model (Audi R8 - Black)">
                        </div>
                     </div>
                     <div class="col-xs-4">
                        <h6></h6>
                        <br><br>
                        <div class="form-group">
                           <label>License Number :</label>
                           <input type="text" name="license_number" value="{{ $providerService->license_no }}" class="form-control" placeholder="Driver License Number">
                        </div>
                        <div class="form-group">
                           <label>License Expire Date :</label>
                           <input type="text" name="license_expire" value="{{ $providerService->license_expire }}" id="license_expire" class="form-control" placeholder="License Expire Date">
                        </div>
                        <div class="form-group">
                           <label>Insurance Number :</label>
                           <input type="text" name="insurance_number" value="{{ $providerService->vehicle->insurance_no }}" class="form-control" placeholder="Vehicle Insurance Number">
                        </div>
                        <div class="form-group">
                           <label>Insurance Expire Date :</label>
                           <input type="text" name="insurance_expire" value="{{$providerService->vehicle->insurance_exp}}" id="insurance_expire" class="form-control" placeholder="Insurance Expire Date">
                        </div>
                     </div>
                     <div class="col-sm-12">
                        <div class="form-group col-xs-3">
                           <button type="submit" class="btn btn-success btn-rounded label-left b-a-0 waves-effect waves-light"><span class="btn-label"><i class="fa fa-check"></i></span> @lang('admin.member.update')</button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            @else
            <div class="col-xs-12">
               <h5 class="mb-1">Update Driver Service:</h5>
               <br>
               <form action="{{ route('partner.provider.document.store', $provider->id) }}" method="POST">
                  {{ csrf_field() }}
                  <div class="col-xs-12">
                     <div class="col-xs-4">
                        <h6>Service Details:  </h6>
                        <br>
                        <div class="form-group">
                        <select name="service_type_id" id="service_type_id" required="required" class="form-control">
                                 <option value="">Select Service</option>
                                 @foreach($services as $service)
                                 <option value="{{ $service->id }}">{{ $service->name }}</option>
                                 @endforeach
                              </select>
                        </div>
                          <div class="form-group">
                        <select id="allowed_service" name="allowed_service[]" required="required" class="form-control" data-plugin="select2" multiple="multiple">
                                
                                 @foreach($services as $service)
                                 <option value="{{ $service->id }}">{{ $service->name }}</option>
                                 @endforeach
                              </select>
                        </div>
<!--                         <div class="form-group">
                           <select class="form-control input" name="taxi_type" required id="taxi_type">
                              <option value="">@lang('admin.member.service_type')</option>
                              <option value="0" >Own Service</option>
                              <option value="1" >Attached Service</option>
                           </select>
                        </div>
 -->

                        <div class="form-group">
                           <input type="text" required  name="vehicle_no" class="form-control" placeholder="Number (CY 98769)">
                        </div>
                        <div class="form-group">
                           <input type="text" required name="vehicle_model" class="form-control" placeholder="Model (Audi R8 - Black)">
                        </div>
                     </div>
                     <div class="col-xs-4">
                        <h6></h6>
                        <br><br>
                        <div class="form-group">
                           <input type="text" name="license_number" class="form-control" placeholder="Driver License Number">
                        </div>
                        <div class="form-group">
                           <input type="text" name="license_expire" id="license_expire" class="form-control" placeholder="License Expire Date" autocomplete="off">
                        </div>
                        <div class="form-group">
                           <input type="text" name="insurance_number" class="form-control" placeholder="Vehicle Insurance Number">
                        </div>
                        <div class="form-group">
                           <input type="text" name="insurance_expire" id="insurance_expire" class="form-control" placeholder="Insurance Expire Date" autocomplete="off">
                        </div>
                     </div>
                     <div class="col-sm-12">
                        <div class="form-group col-xs-3">
                           <button type="submit" class="btn btn-success btn-rounded label-left b-a-0 waves-effect waves-light"><span class="btn-label"><i class="fa fa-check"></i></span> @lang('admin.member.update')</button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            @endif
         </div>
      </div>
      <div class="box box-block bg-white">
         <h5 class="mb-1">@lang('admin.member.driver_documents')</h5>
         <table class="table table-striped table-bordered">
            <thead>
               <tr>
                  <th>#</th>
                  <th>@lang('admin.member.document_type')</th>
                  <th>Upload</th>
                  <th>@lang('admin.member.status')</th>
                  <th>@lang('admin.member.action')</th>
               </tr>
            </thead>
            <tbody>
               @foreach($documents as $Index => $document)
               <tr>
                  <td>{{ $Index + 1 }}</td>
                  <td>{{ $document->doc_name }}</td>
                  <td>
                     <form action="{{ route('partner.provider.document.upload', [$provider->id, $document->id]) }}" method="POST" enctype="multipart/form-data" id="form-upload">
                        {{ csrf_field() }}
                        <input type="file" name="document" accept="application/pdf, image/*" id="upload_document">
                        <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                     </form>
                  </td>
                  <td>@foreach($providerdocuments as $Index =>$providerdocument)
                     @if($document->id == $providerdocument->document_id) 
                     {{ $providerdocument->status }} 
                     @endif 
                     @endforeach
                  </td>
                  <td>
                     <div class="input-group-btn">
                        @foreach($providerdocuments as $Index =>$providerdocument)
                        @if($document->id == $providerdocument->document_id) 
                        <a href="{{ route('partner.provider.document.edit', [$provider->id, $providerdocument->id]) }}" class="btn btn-sm btn-info btn-rounded b-a-0 waves-effect waves-light">@lang('admin.member.view')</a>
                        <form action="{{ route('partner.provider.document.destroy', [$provider->id, $providerdocument->document_id]) }}" method="POST" id="form-delete">
                           {{ csrf_field() }}
                           {{ method_field('DELETE') }}
                           <button class="btn btn-danger btn-sm btn-rounded b-a-0 waves-effect waves-light" type="delete">@lang('admin.member.delete')</button>
                        </form>
                        @endif 
                        @endforeach
                     </div>
                  </td>
               </tr>
               @endforeach
            </tbody>
            <tfoot>
               <tr>
                  <th>#</th>
                  <th>@lang('admin.member.document_type')</th>
                  <th>Upload</th>
                  <th>@lang('admin.member.status')</th>
                  <th>@lang('admin.member.action')</th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="{{asset('main/vendor/select2/dist/js/select2.min.js')}}"></script>
<script type="text/javascript">
   var mindate = {!! json_encode( \Carbon\Carbon::today()->format('Y-m-d\TH:i') ) !!}
   $('#license_expire').datetimepicker({
       format:'Y-m-d',
       timepicker: false,
       minDate: mindate
   });
   $('#insurance_expire').datetimepicker({
       format:'Y-m-d',
       timepicker: false,
       minDate: mindate
   });
   $('[data-plugin="select2"]').select2($(this).attr('data-options'));
</script>
@endsection