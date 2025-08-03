@extends('partner.layout.base')

@section('title', 'Request History ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">@lang('admin.member.request_history')</h4>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('partner.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.member.request_history')</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="request-list">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('admin.member.booking_id')</th>
                        <th>@lang('admin.member.user_name')</th>
                        <th>@lang('admin.member.driver_name')</th>
                        <th>@lang('admin.member.date_time')</th>
                        <th>@lang('admin.member.status')</th>
                        <th>@lang('admin.member.amount')</th>
                        <th>@lang('admin.member.payment_mode')</th>
                        <th>@lang('admin.member.payment_status')</th>
                        <th>@lang('admin.member.action')</th>
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
        $('#request-list').DataTable({
            "destroy": true,
            "responsive": true,
            "dom": 'lBfrtip',
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
                     "url": "{{ route('partner.requests.row') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":function ( d ) {
                            d.provider_id = "{{ $provider_id }}";
                            d._token = "{{csrf_token()}}";
                        },
                   },
            "columns": [
                { "data": "id" },
                { "data": "booking_id" },
                { "data": "user_name" },
                { "data": "provider_name" },
                { "data": "date_time" },
                { "data": "status" },
                { "data": "amount" },
                { "data": "payment_mode" },
                { "data": "payment_status" },
                { "data": "action" },
            ]    

        });
    }
    $(window).load(function(){
        update_content();
    });
</script>
@endsection