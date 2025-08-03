@extends('admin.layout.base')

@section('title', $page)

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">

            <div class="row bg-title">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                    <h4 class="page-title">{{$page}}</h4>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                        <li class="active">@lang('admin.driver_statement')</li>
                    </ol>
                </div>
            </div>

            <div class="box box-block bg-white">
            	<div class="row">
						<div class="row row-md mb-2">
							<div class="col-md-12">
									<div class="box bg-white">
										<div class="box-block clearfix">
											<h5 class="float-xs-left">@lang('admin.member.earnings')</h5>
											<div class="float-xs-right">
											</div>
										</div>

										@if(count($Providers) != 0)
								            <table class="table table-striped table-bordered dataTable" id="statementprovider-list" style="width: 100%;">
								                <thead>
								                   <tr>
								                   		<th>@lang('admin.member.id')</th>
														<th>@lang('admin.member.driver_name')</th>
														<th>@lang('admin.member.mobile')</th>
														<th>@lang('admin.member.status')</th>
														<th>@lang('admin.member.total_rides')</th>
														<th>Earnings</th>
														<th>@lang('admin.member.joined_at')</th>
														<th>@lang('admin.member.details')</th>
													</tr>
								                </thead>
								            </table>
								            @else
								            <h6 class="no-result">@lang('admin.member.no_results_found')</h6>
								            @endif 

									</div>
								</div>

							</div>

            	</div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function update_content(){
        $('#statementprovider-list').DataTable({
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
                     "url": "{{ route('admin.statement-providerlist') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":{ _token: "{{csrf_token()}}"}
                   },
            "columns": [
                { "data": "id" },
                { "data": "provider_name" },
                { "data": "mobile" },
                { "data": "status" },
                { "data": "total_rides" },
                { "data": "total" },
                { "data": "joined_at" },
                { "data": "details" },
            ]    

        });
    }

    $(window).load(function(){
        update_content();
    });
</script>
@endsection