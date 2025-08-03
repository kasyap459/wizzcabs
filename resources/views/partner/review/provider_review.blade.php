@extends('partner.layout.base')

@section('title', 'Driver Reviews ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h4 class="page-title">@lang('admin.member.driver_reviews')</h4>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    
                    <ol class="breadcrumb">
                        <li><a href="{{ route('partner.dashboard') }}">@lang('admin.dashboard')</a></li>
                        <li class="active">@lang('admin.member.driver_reviews')</li>
                    </ol>
                </div>
            </div>
            <div class="box box-block bg-white">
                <table class="table table-striped table-bordered dataTable" id="provider-review">
                    <thead>
                        <tr>
                            <th>@lang('admin.member.id')</th>
                            <th>@lang('admin.member.request_id')</th>
                            <th>@lang('admin.member.user_name')</th>
                            <th>@lang('admin.member.driver_name')</th>
                            <th>@lang('admin.member.ratings')</th>
                            <th>@lang('admin.member.date_time')</th>
                            <th>@lang('admin.member.comments')</th>
                        </tr>
                    </thead>
                </table>
            </div>
            
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('main/assets/js/rating.js')}}"></script> 
<script>
    function update_content(){
        $('#provider-review').DataTable({
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
                     "url": "{{ route('partner.provider.reviewprovider') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":{ _token: "{{csrf_token()}}"}
                   },
            "columns": [
                { "data": "id" },
                { "data": "request_id" },
                { "data": "user_name" },
                { "data": "provider_name" },
                { "data": "rating" },
                { "data": "date_time" },
                { "data": "comments" },
            ],    
            "createdRow": function (row, data, dataIndex) {

                            // any manipulation in the row element
                           var ratingInput = $(row).find('.rating');
                           $(ratingInput).rating();

                        }
        });
        
    }

    $(window).load(function(){
        update_content();
        
    });
</script>
@endsection