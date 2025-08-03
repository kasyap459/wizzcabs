@extends('admin.layout.base')

@section('title', 'Drivers ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Production Manager</h4>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.list_drivers')</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="provider-list">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.full_name')</th>
                        <th>@lang('admin.member.email')</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>@lang('admin.member.total_requests')</th>
                        <th>@lang('admin.member.accepted_requests')</th>
                        <th>@lang('admin.member.cancelled_requests')</th>
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
                     "url": "{{ route('admin.shift.row') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":function ( d ) {
                            d.fleet = "{{ $fleet }}";
                            d._token = "{{csrf_token()}}";
                        },
                   },
            "columns": [
                { "data": "id" },
                { "data": "full_name" },
                { "data": "email" },
                { "data": "mobile" },
                { "data": "total_requests" },
                { "data": "accepted_requests" },
                { "data": "cancelled_requests" },
            ]    

        });
    }

    $(window).load(function(){
        update_content();
    });
</script>

@endsection