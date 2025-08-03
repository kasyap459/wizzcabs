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
	                    <li class="active">@lang('admin.driver_statement')</li>
	                </ol>
	            </div>
	        </div>

            <div class="box box-block bg-white">
            	<div style="text-align: center;padding: 20px;color: blue;font-size: 20px;">
            		<p><strong>
            			<span class="text-danger">@lang('admin.member.over_all_earning') : {{ Setting::get('currency') }}<i class="revenue"></i></span>
            			
            		</strong></p>
            	</div>

            	<div class="row">
					

	            	<div class="col-lg-4 col-md-6 col-xs-12">
						<div class="box box-block tile tile-2 bg-danger m-b-2">
							<div class="t-icon right"><i class="ti-receipt"></i></div>
							<div class="t-content">
								<h1 class="m-b-1 ride_count"></h1>
								<h6 class="text-uppercase">@lang('admin.member.total_of_rides')</h6>
							</div>
						</div>
					</div>


					<div class="col-lg-4 col-md-6 col-xs-12">
						<div class="box box-block tile tile-2 bg-success m-b-2">
							<div class="t-icon right"><i class="ti-bar-chart"></i></div>
							<div class="t-content">
								<h1 class="m-b-1">{{ Setting::get('currency') }}<span class="revenue"></span></h1>
								<h6 class="text-uppercase">@lang('admin.member.revenue')</h6>
							</div>
						</div>
					</div>
					
					<div class="col-lg-4 col-md-6 col-xs-12">
						<div class="box box-block tile tile-2 bg-primary m-b-2">
							<div class="t-icon right"><i class="ti-car"></i></div>
							<div class="t-content">
								<h1 class="m-b-1" id="cancel_count"></h1>
								<h6 class="text-uppercase">@lang('admin.member.cancelled_rides')</h6>
							</div>
						</div>
					</div>

				<div class="row row-md mb-2">
					<div class="col-md-12">
							<div class="box bg-white">
								<div class="box-block clearfix">
									<h5 class="float-xs-left">@lang('admin.member.earnings')</h5>
									<div class="float-xs-right">
										<form action="{{ route('admin.corporate.invoice.create') }}" method="POST">
										{{csrf_field()}}
										<div class="form-group col-md-3">
									        <input type="text" id="fromdate" autocomplete="off" onchange="update_content()" name="from_date" class="form-control" placeholder="From Date" style="border-color:#f59345;">
									    </div>
									    <div class="form-group col-md-3">
									        <input type="text" id="todate" name="to_date" autocomplete="off" onchange="update_content()" class="form-control" placeholder="To Date" style="border-color:#20b9ae;">
									    </div>
									    <div class="form-group col-md-2">
											<select name="tripstatus" id="tripstatus" class="form-control" onchange="update_content()" style="border-color:#3e70c9;">	
												<option value="">All Status</option>
												<option value="COMPLETED">COMPLETED</option>
												<option value="CANCELLED">CANCELLED</option>
												<option value="SCHEDULED">SCHEDULED</option>
											</select>
										</div>
									    <div class="form-group col-md-2">
											<select name="payment_mode" id="payment_mode" class="form-control" onchange="update_content()" style="border-color:#f44236;">	
												<option value="">Corporate</option>
												<!-- <option value="CASH">CASH</option> -->
												<!-- <option value="CARD">CARD</option> -->
											</select>

											<input type="hidden" name="corporate_id" value="{{ $corporateid }}" id="corporateid">
										</div>
										<div class="form-group col-md-2">
											<button type="submit">INVOICE</button>
										</div>
										</form>
									</div>
								</div>
								<table class="table table-striped table-bordered dataTable" id="table-statement">
						                <thead>
						                   <tr>
						                   		<th>@lang('admin.member.id')</th>
												<th>@lang('admin.member.booking_id')</th>
												<th>@lang('admin.member.picked_up')</th>
												<th>@lang('admin.member.dropped')</th>
												<th>@lang('admin.member.request_details')</th>
												<th>@lang('admin.member.dated_on')</th>
												<th>@lang('admin.member.status')</th>
												<th>@lang('admin.member.payment_mode')</th>
												<th>@lang('admin.member.total')</th>
											</tr>
						                </thead>
						            </table>		  
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
    	var payment_mode =$('#payment_mode').val();
        $('#table-statement').DataTable({
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
                     "url": "{{ route('admin.corporate.corporate-content') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":function ( d ) {
				            d.payment = $('#payment_mode').val();
				            d.fromdate = $('#fromdate').val();
				            d.todate = $('#todate').val();
				            d.corporateid = $('#corporateid').val();
				            d.tripstatus = $('#tripstatus').val();
				            d._token = "{{csrf_token()}}";
				        },
				     "dataSrc": function ( json ) {
				            $('.ride_count').text(json.recordsFiltered);
				            $('#cancel_count').text(json.cancel_rides);
				            $('.revenue').text(json.revenue);
				            $('.percentage').text(json.percentage);
				            return json.data;
	
				        }
                   },
            "columns": [
                { "data": "id" },
                { "data": "booking_id" },
                { "data": "s_address" },
                { "data": "d_address" },
                { "data": "detail" },
                { "data": "created_at" },
                { "data": "status" },
                { "data": "payment_mode" },
                { "data": "total" },
            ]	 

        });
    }

    $(window).load(function(){
    	update_content();
	});
</script>

@endsection