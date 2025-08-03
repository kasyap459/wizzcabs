@extends('admin.layout.base')

@section('title', $page)

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
@endsection

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
                        <li class="active">Sub-company Statement</li>
                    </ol>
                </div>
            </div>

            <div class="box box-block bg-white">
            	<div class="row">
                    <div class="col-lg-4 col-md-6 col-xs-12">
                        <div class="box box-block tile tile-2 bg-danger m-b-2">
                            <div class="t-icon right"><i class="ti-receipt"></i></div>
                            <div class="t-content">
                                <h1 class="m-b-1 carrier_count">0</h1>
                                <h6 class="text-uppercase">Total No. Of Sub-company</h6>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-6 col-xs-12">
                        <div class="box box-block tile tile-2 bg-success m-b-2">
                            <div class="t-icon right"><i class="ti-bar-chart"></i></div>
                            <div class="t-content">
                                <h1 class="m-b-1 ride_count">0</h1>
                                <h6 class="text-uppercase">TOTAL NO. OF RIDES</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-xs-12">
                        <div class="box box-block tile tile-2 bg-primary m-b-2">
                            <div class="t-icon right"><i class="ti-car"></i></div>
                            <div class="t-content">
                                <h1 class="m-b-1">{{ Setting::get('currency') }}<span class="revenue"></span></h1>
                                <h6 class="text-uppercase">@lang('admin.member.revenue')</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="box-block clearfix">
                            <h5 class="float-xs-left">@lang('admin.member.earnings')</h5>
                            <div class="float-xs-right">
                                <div class="form-group col-md-6">
                                    <input type="text" id="fromdate" autocomplete="off" onchange="update_content()" class="form-control" placeholder="From Date" style="border-color:#f59345;">
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" id="todate" autocomplete="off" onchange="update_content()" class="form-control" placeholder="To Date" style="border-color:#20b9ae;">
                                </div>
                            </div>
                        </div>
                    </div>
						<div class="row row-md mb-2">
							<div class="col-md-12">
									<div class="box bg-white">
										@if(count($Partners) != 0)
								            <table class="table table-striped table-bordered dataTable" id="statementpartner-list">
								                <thead>
								                   <tr>
								                   		<th>@lang('admin.member.id')</th>
														<th>Sub-company Name</th>
														<th>@lang('admin.member.mobile')</th>
														<th>@lang('admin.member.status')</th>
														<th>@lang('admin.member.total_rides')</th>
														<th>@lang('admin.member.total')</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">
    var maxdate = {!! json_encode( \Carbon\Carbon::today()->format('Y-m-d\TH:i') ) !!}
    $('#fromdate').datetimepicker({
        format:'Y-m-d',
        timepicker: false,
        maxDate: maxdate
    });
    $('#todate').datetimepicker({
        format:'Y-m-d',
        timepicker: false,
        maxDate: maxdate
    });

</script>
<script>
    function update_content(){
        $('#statementpartner-list').DataTable({
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
                     "url": "{{ route('admin.statement-partnerlist') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":function ( d ) {
                            d.fromdate = $('#fromdate').val();
                            d.todate = $('#todate').val();
                            d._token = "{{csrf_token()}}";
                     },
                     "dataSrc": function ( json ) {
                            $('.carrier_count').text(json.recordsFiltered);
                            $('.ride_count').text(json.ride_count);
                            $('.revenue').text(json.revenue);
                            return json.data;
                      }
                   },
            "columns": [
                { "data": "id" },
                { "data": "partner_name" },
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