@extends('admin.layout.base')

@section('title', 'Drivers ')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">

            <div class="row bg-title">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h4 class="page-title">@lang('admin.member.drivers')</h4><a href="{{ route('admin.provider.create') }}"
                        class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.member.add_new_driver')</a>
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
                            {{-- <th>@lang('admin.member.documents_service_type')</th> --}}
                            <th>@lang('admin.member.action')</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection


<div id="formrejectmodal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Enter Reason to Continue</h4>
            </div>
            <div class="modal-body">
                <form action="" method="GET" id="banreasonform" role="form">
                    <input type="hidden" name="status" value="" id="statusfield">
                    <textarea name="message" required id="message" class="form-control" placeholder="Enter Message for provider" rows="3"></textarea>
                    <button type="submit" class="btn btn-info mt-3 btn-block">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        function update_content() {
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
                "ajax": {
                    "url": "{{ route('admin.provider.row') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d._token = "{{ csrf_token() }}";
                    },
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "full_name"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "mobile"
                    },
                    {
                        "data": "total_requests"
                    },
                    {
                        "data": "accepted_requests"
                    },
                    {
                        "data": "cancelled_requests"
                    },
                    // {
                    //     "data": "documents"
                    // },
                    {
                        "data": "action"
                    },
                ]

            });

            $(document).on("click", ".banaccount", function(e) {
                e.preventDefault();
                $("#banreasonform").attr("action", $(this).attr("href"));
                $("#statusfield").val("banned");
                $("#formrejectmodal").modal("show");
            });

            $(document).on("click", ".rejectaccount", function(e) {
                e.preventDefault();
                $("#banreasonform").attr("action", $(this).attr("href"));
                $("#statusfield").val("rejected");
                $("#formrejectmodal").modal("show");
            });

        }

        $(window).load(function() {
            update_content();
        });
    </script>

@endsection
