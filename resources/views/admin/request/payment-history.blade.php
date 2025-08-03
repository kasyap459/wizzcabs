@extends('admin.layout.base')

@section('title', 'Payment History ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">@lang('admin.payment_history')</h4>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.payment_history')</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="payment-list">
                <thead>
                    <tr>
                        <th>@lang('admin.member.request_id')</th>
                        <th>@lang('admin.member.transaction_id')</th>
                        <th>@lang('admin.member.from')</th>
                        <th>@lang('admin.member.to')</th>
                        <th>@lang('admin.member.total_amount')</th>
                        <th>@lang('admin.member.payment_mode')</th>
                        <th>@lang('admin.member.payment_status')</th>
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
        $('#payment-list').DataTable({
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
                     "url": "{{ route('admin.paymentrow') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":{ _token: "{{csrf_token()}}"}
                   },
            "columns": [
                { "data": "request_id" },
                { "data": "transaction_id" },
                { "data": "from" },
                { "data": "to" },
                { "data": "total_amount" },
                { "data": "payment_mode" },
                { "data": "payment_status" },
            ]    

        });
    }

    $(window).load(function(){
        update_content();
    });
</script>
@endsection