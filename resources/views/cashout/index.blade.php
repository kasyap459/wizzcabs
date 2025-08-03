@extends('admin.layout.base')

@section('title', 'Cashout List ')

@section('content')
<div class="content-area py-1" id="trip-panel">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <div class="row filterbox"> 

	    <div class="form-group col-md-2">
            <select id="cashouttatus" onchange="updates()" class="form-control" style="border-color:green;">
                <option value="">@lang('admin.triplist.all_status')</option>
                <option value="REQUESTED">Requested</option>
                <option value="APPROVED">Approved</option>
                <option value="REJECTED">Rejected</option>
                
            </select>
            </div>
           
           
            <div class="form-group col-md-2">
            <input type="text" id="fromdate" onchange="updates()" class="form-control" placeholder="@lang('admin.triplist.from_date')" style="border-color:#f59345;">
            </div>
            <div class="form-group col-md-2">
            <input type="text" id="todate" onchange="updates()" class="form-control" placeholder="@lang('admin.triplist.to_date')" style="border-color:#20b9ae;">
            </div>
            </div>
            <table class="table table-striped table-bordered dataTable" style="width: 100% !important;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Request ID</th> 
                        <th>Driver Name</th>
                        <th>Driver Email</th>
                        <th>Driver Mobile</th>
                        <th>Requested Time</th>
                        <th>Amount</th>
                        <th>Status</th>
        
                    </tr>
                </thead>
                <tbody id="screen">
    
                </tbody>
                <tfoot>
                <tr>
                        <th>ID</th>
                        <th>Request ID</th> 
                        <th>Driver Name</th>
                        <th>Driver Email</th>
                        <th>Driver Mobile</th>
                        <th>Requested Time</th>
                        <th>Amount</th>
                        <th>Status</th>
    
                    </tr>
                </tfoot>
            </table>
    
      </div>
    </div>
</div>

<div id="editmodalbox" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">@lang('admin.triplist.edit_trip')</h4>
      </div>
      <div class="modal-body edit-body">

      </div>
      <div class="modal-footer">
        
      </div>
    </div>
</div>
</div>

<div id="showroutemodalbox" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Driver Routes</h4>
      </div>
      <div class="modal-body route-body">

      </div>
      <div class="modal-footer">
        
      </div>
    </div>
</div>
</div>

<div id="cancelmodalbox" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Reject Cashout Request </h4>
      </div>
      <div class="modal-body">
        <p style="text-align: center;">Are you sure want to reject request</p>
        <p style="text-align: center;" id="cancelid"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.triplist.close')</button>
      </div>
    </div>
</div>
</div>


<div id="approvemodalbox" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Approve Cashout Request </h4>
      </div>
      <div class="modal-body">
        <p style="text-align: center;">Are you sure want to Approve Cashout Request</p>
        <p style="text-align: center;" id="approveid"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.triplist.close')</button>
      </div>
    </div>
</div>
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">@lang('admin.triplist.assign_driver')</h4>
      </div>
      <div class="modal-body">
        <table id="getcode" class="table"></table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.triplist.close')</button>
      </div>
    </div>
</div>
</div>

<div id="showmodalmap" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">@lang('admin.triplist.trip_details')</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
            <table class="table first">
                <tr>
                    <td>@lang('admin.triplist.passenger_name')</td>
                    <td id="name"></td>  
                </tr>
                <tr>
                     <td>@lang('admin.triplist.driver_name')</td>
                     <td id="drivername"></td> 
                </tr>
                <tr>
                    <td>@lang('admin.triplist.pickup_time')</td>
                    <td id="pickuptime"></td>
                </tr>
                <tr>
                    <td>@lang('admin.triplist.drop_time')</td>
                     <td id="droptime"></td>
                </tr>
                <tr>
                    <td>@lang('admin.triplist.distance')</td>
                     <td id="distance"></td>
                </tr>
                <tr>
                    <td>Flat Fare</td>
                     <td id="flat_fare"></td>
                </tr>
                <tr>
                    <td>Base Fare</td>
                     <td id="base_fare"></td>
                </tr>
                <tr>
                    <td>Distance Fare</td>
                     <td id="distance_fare"></td>
                </tr>
                <tr>
                    <td>Minute Fare</td>
                     <td id="min_fare"></td>
                </tr>
                <tr>
                    <td>Onboarding Waiting Fare</td>
                     <td id="trip_waiting_fare"></td>
                </tr>
                <tr>
                    <td>Stop Waiting Fare</td>
                     <td id="stop_waiting_fare"></td>
                </tr>
                <tr>
                    <td>Toll Fare</td>
                     <td id="toll_fare"></td>
                </tr>
                <tr>
                    <td>Extra Fare</td>
                     <td id="extra_fare"></td>
                </tr>
                <tr>
                    <td>Tips</td>
                     <td id="tip_fare"></td>
                </tr>
                <tr>
                    <td>Tax</td>
                     <td id="tax"></td>
                </tr>
                <tr>
                    <td>Discount</td>
                     <td id="discount"></td>
                </tr>
                <tr>
                    <td>Total fare</td>
                     <td id="total_fare"></td>
                </tr>
                
            </table>
            </div>
            <div class="col-md-6">
            <table class="table second">
                <tr>
                    <td><b>@lang('admin.triplist.pickup_location'):</b> <br><span id="pickup"></span></td>  
                </tr>
                <tr>
                     <td><b>Stop1 Location:</b><br><span id="stop1"></span></td> 
                </tr>
                <tr>
                     <td><b>Stop2 Location:</b><br><span id="stop2"></span></td> 
                </tr>
                <tr>
                     <td><b>@lang('admin.triplist.drop_location'):</b><br><span id="drop"></span></td> 
                </tr>
                <tr>
                     <td><b>Onboard waiting Time:</b><br><span id="waiting_time"></span></td> 
                </tr>
                <tr>
                     <td><b>Stops waiting Time:</b><br><span id="stop_waiting_time"></span></td> 
                </tr>
                <tr>
                     <td><b>User notes for driver :</b><br><span id="user_notes"></span></td> 
                </tr>
            </table>
            </div>
<!--             <div class="col-md-12">
                <p style="margin: 0;"><b>Comment about passenger:</b></p>
                <form action="{{route('admin.storecomment')}}" method="POST" id="sendcomment" role="form" >
                    {{csrf_field()}}
                    <p id="comment_text">No comments</p>
                    <p id="last_update"><b>Last update:</b> <span id="update_date"></span></p>
                    <textarea name="comment" id="comment" cols="70" rows="2" style="margin-bottom: 5px;width: 98%;height: 48px;display:none"></textarea>
                    <input type="hidden" name="request_id" id="request_id" value="">
                    <button type="button" id="editor" class="btn btn-sm btn-danger">Edit</button>
                    <button type="submit" class="btn btn-sm btn-success">Save</button>
                </form>
                <br>
            </div>
 -->        </div>
        <div id="map"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.triplist.close')</button>
      </div>
    </div>
</div>
</div>

@endsection
@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
<link rel="stylesheet" href="{{asset('main/vendor/toastr/toastr.min.css')}}">
<style>
    table.table-bordered.dataTable tbody th, table.table-bordered.dataTable tbody td{
        font-size: 12px;
    }
    tbody{
        word-break: break-all;
    }
    .container-fluid {
    padding-left: 2px;
    padding-right: 2px;
    padding-bottom: 15px;
    }
    .table td, .table th {
        padding: 5px 1px;
        text-align: center;
        vertical-align: middle;
        border-top: 1px solid #eceeef;
    }
    .table th{
        font-size: 13px;
    }
    .table td span{
        padding: 8px 10px;
        float: none;
        font-size: 11px;
        border-radius: 5px;
        cursor: pointer;
    }
    .custom-class:hover{
        cursor: not-allowed;
    }
    .form-control[readonly] {
    background-color: #fff;
    opacity: 1;
    }
    .form-control{
        line-height: 1.50;
        border-radius: 15px;
        border:2px solid;
    }
    #map{
        width: 100%;
        height: 340px;
	margin-top: 0px;
    }
    #showmodalmap .table td{
        border: none;
        text-align: left;
    }
    #showmodalmap .first td:first-child{
        font-weight: bold;
    }
    #showmodalmap .second td{
        font-size: 13px;
    }
    #showmodalmap .second td span{
        padding: 0px;
        font-size: 13px;
    }
    .filterbox{
    padding: 15px 0px;
    margin-bottom: 15px;
    border-bottom: 2px solid #3e70c9;
    }
    .filterbox .form-group{
        margin-bottom: 0px;
    }
    .icontrip{
        font-size: 20px;
    }
    .form-group {
        margin-bottom: 0rem;
    }
    .form-control {
        display: block;
        width: 100%;
        padding: 0.3rem .75rem;
        font-size: 1rem;
    }
    .pac-container {
        z-index: 10000 !important;
    }
    .tag-dark {
    background-color: #1d443b;
    }
    .hover {
      display:none;
    }
    .hoverable:hover .normal {
      display:none;
    }
    .hoverable:hover .hover {
      display:inline;  /* CHANGE IF FOR BLOCK ELEMENTS */
    }
    .modal.fade .modal-dialog {
    	transition: transform .3s ease-out;
    	transform: translateY(-10%) !important;
    }
</style>
<script type="text/javascript" src="{{asset('main/vendor/jquery/jquery-1.12.3.min.js')}}"></script>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="{{asset('main/vendor/toastr/toastr.min.js')}}"></script>
<script type="text/javascript">
    
    var maxdate = {!! json_encode( \Carbon\Carbon::today()->format('Y-m-d\TH:i') ) !!}
    var current_time = {!! json_encode( \Carbon\Carbon::now()->format('Y-m-d H:i:s') ) !!}
   /* $("#fromdate").flatpickr({
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    });
    $("#todate").flatpickr({
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    });*/
    //var calendar = new CalendarPopup('#fromdate');
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

<script type="text/javascript">
    $(document).ready(function() {
        $("body").addClass("compact-sidebar");
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<script>
function updates(){
        var cashouttatus = $('#cashouttatus').val();
        var fromdate = $('#fromdate').val();
        var todate = $('#todate').val();
    
        var dataString = "cashouttatus="+cashouttatus+"&fromdate="+fromdate+"&todate="+todate;
        $.ajax
            ({
              cache: false,
              type: "GET",
              url: "cashout_listall",
              data: dataString,
              success: function(data)
              {
                 $('#screen').html(data);
              }
            });
    }

$(document).ready(function(){
    updates();
    setInterval(function(){
            updates();
            }, 5000);
    });
</script>



<script>
    $(document).on('click','#cancelmodal', function() {
        $("#cancelid").empty();
        var id = $(this).attr("data-id");
           $("#cancelid").append("<a href='reject/cashout/"+id+"' class='btn btn-danger'>Reject</a>");
           $("#cancelmodalbox").modal("toggle");
    });

    $(document).on('click','#approvemodal', function() {
        $("#approveid").empty();
        var id = $(this).attr("data-id");
           $("#approveid").append("<a href='approve/cashout/"+id+"' class='btn btn-success'>Approve</a>");
           $("#approvemodalbox").modal("toggle");
    });
</script>
<script type="text/javascript">
 $(document).on('click','#autotrip', function() {
        var id = $(this).attr("data-id");
           $.get('autotrip/' + id, function( data ){
                
           });
           
    });
</script>
<script>
    $('#editor').click(function(){
        $('#comment_text').hide();
        $('#comment').show();
        $('#last_update').hide();
    })
    $("#sendcomment").submit(function(stay){
       var formdata = $(this).serialize(); // here $(this) refere to the form its submitting
        $.ajax({
            type: 'POST',
            url: "{{ route('dispatcher.storecomment') }}",
            data: formdata, // here $(this) refers to the ajax object not form
            success: function (data) {
               $('#comment_text').show();
               $('#comment_text').text($('#comment').val());
               $('#comment').hide();
               $('#last_update').show();
               $('#update_date').text(current_time);
               if($('#comment').val() ==''){
                    $('#last_update').hide();
                    $('#comment_text').text('No comment');
               }
            },
        });
        stay.preventDefault(); 
    });
</script>
<script type="text/javascript">
    	$(document).ready(function() {
        	$("body").addClass("compact-sidebar");
        	$('[data-toggle="tooltip"]').tooltip();
		$('.large-sidebar').addClass('sidebar-mini');
    	});
	$(document).on('click','#minimizeSidebar', function() {
		if($('.sidebar-mini:visible').length)
			$('.large-sidebar').removeClass('sidebar-mini');
  		else
        		$('.large-sidebar').addClass('sidebar-mini');        
	});
</script>

@endsection
