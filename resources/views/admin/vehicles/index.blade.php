@extends('admin.layout.base')

@section('title', 'Vehicle ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Vehicles</h4><a href="{{ route('admin.vehicle.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">Add new Vehicle</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">List Vehicle</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="vehicle-list">
                <thead>
                    <tr>
                       <th>ID</th>
                       <th>vehicle Number</th>
                       <th>Vehicle Owner</th>
                       <th>Service</th>
                       <th>Location</th>
                       <th>Vehicle Manufacturer</th>
                       <th>Vehicle Brand</th>
                       <th>Status</th>
                       <th>Action</th>
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
        $('#vehicle-list').DataTable({
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
                     "url": "{{ route('admin.vehicle.row') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":function ( d ) {
                            d._token = "{{csrf_token()}}";
                        },
                   },
            "columns": [
                { "data": "id" },
                { "data": "vehicle_name" },
                { "data": "vehicle_owner" },
                { "data": "service_type" },
                { "data": "location" },
                { "data": "vehicle_manufacturer" },
                { "data": "vehicle_brand" },
                { "data": "status" },
                { "data": "action" },
            ]    

        });
    }

    $(window).load(function(){
        update_content();
    });
</script>

@endsection