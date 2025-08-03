@extends('partner.layout.base')

@section('title', 'Drivers ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">@lang('admin.member.drivers')</h4>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('partner.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.list_drivers')</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <h5>Assign Vehicle</h5>
                <form class="form-horizontal" action="{{ route('partner.assign.vehicle') }}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <div class="row">
                <div class="col-md-12">  
                    <div class="form-group row">
                        <label for="provider_id" class="col-xs-12 col-form-label">Driver Name</label>
                        <div class="col-xs-8">
                            <select name="provider_id" required="required" id="provider_id" class="form-control">
                                <option value="">Select Driver</option>
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->id }}">{{ $provider->name }} - {{ $provider->mobile }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-xs-12 col-form-label">Vehicle Number</label>
                        <div class="col-xs-8">
                            <select name="vehicle_id" required="required" id="vehicle_id" class="form-control">
                                <option value="">Select Vehicle</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i>Assign Driver</button>
                            <a href="{{route('partner.assignlist')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
                        </div>
                    </div>
                </div>
                </div>
                </form><br><br>
            <table class="table table-striped table-bordered dataTable" id="provider-list">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.name')</th>
                        <th>@lang('admin.member.email')</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>Account status</th>
                        <th>Status</th>
                        <th>Vehicle Number</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function update_content(){
        $('#provider-list').DataTable({
            "destroy": true,
            "responsive": true,
            "dom": 'Bfrtip',
            "buttons": [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            "oLanguage": {
                'sProcessing': '<i class="fa fa-circle-o-notch fa-spin" style="font-size:24px;color:#a377b1;"></i>'
            },
            "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "{{ route('partner.assign.row') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":function ( d ) {
                            d._token = "{{csrf_token()}}";
                        },
                   },
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "email" },
                { "data": "mobile" },
                { "data": "account_status" },
                { "data": "status" },
                { "data": "vehicle" },
            ]    

        });
    }

    $(window).load(function(){
        update_content();
    });
</script>

@endsection