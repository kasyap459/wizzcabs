@extends('partner.layout.base')

@section('title', 'Dispatcher ')

@section('content')
<div class="content-area py-1" id="trip-panel">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <div class="row filterbox"> 

	    <div class="form-group col-md-2">
            <select id="tripstatus" onchange="updates()" class="form-control" style="border-color:green;">
                <option value="">@lang('admin.triplist.all_status')</option>
                <option value="SEARCHING">@lang('admin.triplist.searching')</option>
                <option value="SCHEDULED">@lang('admin.triplist.scheduled')</option>
                <option value="COMPLETED">@lang('admin.triplist.completed')</option>
                <option value="CANCELLED">@lang('admin.triplist.cancelled')</option>
            </select>
            </div>
            <div class="form-group col-md-2">
            <select id="servicetype" onchange="updates()" class="form-control" style="border-color:#f44236;">
                <option value="">@lang('admin.triplist.all_service')</option>
                @foreach($services as $index => $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
            </select>
            </div>
            <div class="form-group col-md-2">
            <select id="booking_by" onchange="updates()" class="form-control" style="border-color:#3e70c9;">
                <option value="">@lang('admin.triplist.all_booking')</option>
                <option value="APP">@lang('admin.triplist.mobile_app')</option>
                <option value="WEB">@lang('admin.triplist.web_booking')</option>
                <option value="DISPATCHER">@lang('admin.triplist.dispatcher')</option>
                <option value="STREET">@lang('admin.triplist.street_ride')</option>
                <option value="HOTEL">Hotel</option>
                <option value="CORPORATE">Corporate</option>
            </select>
            </div>
            <div class="form-group col-md-2">
            <select id="upcoming_trip" onchange="updates()" class="form-control" style="border-color:#3e70c9;">
                <option value="">Upcoming Trips</option>
                <option value="15">15 mins</option>
                <option value="30">30 mins</option>
                <option value="45">45 mins</option>
                <option value="60">60 mins</option>
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
                        <th>@lang('admin.triplist.id')</th>
                        <th>@lang('admin.triplist.booking_id')</th>
                        <th>@lang('admin.triplist.booking_time')</th>
                        <th style="width:75px;">@lang('admin.triplist.actual_pickup_time')</th>
                        <th style="width:75px;">@lang('admin.triplist.drop_time')</th>
                        <th>@lang('admin.triplist.passenger_name')</th>
                        <th>@lang('admin.triplist.driver_name')</th>
                        <th>@lang('admin.triplist.vehicle')</th>
                        <th>@lang('admin.triplist.pickup_location')</th>
                        <th>@lang('admin.triplist.drop_location')</th>
                        <th>@lang('admin.triplist.distance')</th>
                        <th>@lang('admin.triplist.fare')/Type</th>
                        <th>@lang('admin.triplist.booking_by')</th>
                        <th>@lang('admin.triplist.cancelled_by')</th>
                        <th>@lang('admin.triplist.status')</th>
                    </tr>
                </thead>
                <tbody id="screen">
    
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.triplist.id')</th>
                        <th>@lang('admin.triplist.booking_id')</th>
                        <th>@lang('admin.triplist.booking_time')</th>
                        <th>@lang('admin.triplist.actual_pickup_time')</th>
                        <th>@lang('admin.triplist.drop_time')</th>
                        <th>@lang('admin.triplist.passenger_name')</th>
                        <th>@lang('admin.triplist.driver_name')</th>
                        <th>@lang('admin.triplist.vehicle')</th>
                        <th>@lang('admin.triplist.pickup_location')</th>
                        <th>@lang('admin.triplist.drop_location')</th>
                        <th>@lang('admin.triplist.distance')</th>
                        <th>@lang('admin.triplist.fare')/Type</th>
                        <th>@lang('admin.triplist.booking_by')</th>
                        <th>@lang('admin.triplist.cancelled_by')</th>
                        <th>@lang('admin.triplist.status')</th>
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

<div id="cancelmodalbox" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">@lang('admin.triplist.cancel_trip')</h4>
      </div>
      <div class="modal-body">
        <p style="text-align: center;">@lang('admin.triplist.sure_cancel_trip')</p>
        <p style="text-align: center;" id="cancelid"></p>
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
        <table id="getcode" class="table table-inverse"></table>
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
                    <td>Tax</td>
                     <td id="tax"></td>
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
           <!-- <div class="col-md-12">
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
            </div>-->
        </div>
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
        format:'d-m-Y',
        timepicker: false,
        maxDate: maxdate
    });
    $('#todate').datetimepicker({
        format:'d-m-Y',
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
        $(document).on('click','#dispatch', function() {
            $("#getcode").empty();
            var latitude = $(this).attr("data-latitude");
            var longitude = $(this).attr("data-longitude"); 
            var service = $(this).attr("data-service");
            var id = $(this).attr("data-id");
            var current =$(this).attr("data-current");
            // if(current == 0){
            $.get('/partner/providers', { 
                latitude: latitude,
                longitude: longitude,
                service_type: service,
            }, function(result) {
                var count = result.total;
                console.log(result);
                if(count > 0){
                var i=0;
                for( i=0 ; i<count; i++ ){
                    $("#getcode").append("<tr><td>"+result.data[i].name +"</td><td>" +result.data[i].service.name+" </td><td>"+result.data[i].mobile +"</td><td><a href='/partner/trips/"+id+'/'+result.data[i].id+"' class='btn btn-success'>@lang('admin.triplist.assign_driver')</a> </td></tr>")
                }
                }
               else{
                    $("#getcode").append("<tr><td>@lang('admin.triplist.no_drivers_found')</td></tr>");
               }
            });

            // }else{
            //    $("#getcode").append("<tr><td>@lang('admin.triplist.request_already_assigned')</td></tr>"); 
            // }
            $("#myModal").modal("toggle");
        });  
</script>
<script>
function updates(){
        var tripstatus = $('#tripstatus').val();
        var servicetype = $('#servicetype').val();
        var booking_by = $('#booking_by').val();
        var fromdate = $('#fromdate').val();
        var todate = $('#todate').val();
        var upcoming_trip = $('#upcoming_trip').val();
        var dataString = "tripstatus="+tripstatus+"&servicetype="+servicetype+"&booking_by="+booking_by+"&fromdate="+fromdate+"&todate="+todate+"&upcoming_trip="+upcoming_trip;
        $.ajax
            ({
              cache: false,
              type: "GET",
              url: "/partner/listall",
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
            }, 2000);
    });
</script>

<script type="text/javascript">

$(document).on('click','#showmap', function() {
        $('#comment_text').show();
        $('#comment').hide();
        $('#last_update').show();

        $("#map").empty();
        $("#showmodalmap").modal("toggle");
        var id = $(this).attr("data-id");
        $.get('/partner/showdetail', {
                    id: id,
                }, function(result) {
                   console.log(result);

        
                  
        var map;
        var zoomLevel = 11;
        
        map = new google.maps.Map(document.getElementById('map'));

        var marker = new google.maps.Marker({
            map: map,
            icon: '/asset/img/marker-start.png',
            anchorPoint: new google.maps.Point(0, -29)
        });

         var markerSecond = new google.maps.Marker({
            map: map,
            icon: '/asset/img/marker-end.png',
            anchorPoint: new google.maps.Point(0, -29)
        });

        var bounds = new google.maps.LatLngBounds();

        source = new google.maps.LatLng(result.s_latitude, result.s_longitude);
        destination = new google.maps.LatLng(result.d_latitude,result.d_longitude);

        marker.setPosition(source);
        markerSecond.setPosition(destination);

        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true, preserveViewport: true});
        directionsDisplay.setMap(map);

        directionsService.route({
            origin: source,
            destination: destination,
            travelMode: google.maps.TravelMode.DRIVING
        }, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                console.log(result);
                directionsDisplay.setDirections(result);

                marker.setPosition(result.routes[0].legs[0].start_location);
                markerSecond.setPosition(result.routes[0].legs[0].end_location);
            }
        });

        bounds.extend(marker.getPosition());
        bounds.extend(markerSecond.getPosition());
        map.fitBounds(bounds);

        $('#name').text(result.user_name); 
        if(result.provider !=null){
            $('#drivername').text(result.provider['name']);
        }else{
            $('#drivername').text('null');
        }
        $('#pickuptime').text(result.assigned_at1);
        $('#droptime').text(result.finished_at1);
        $('#distance').text(result.distance);
        $('#waiting_time').text(result.waiting_time);
        $('#stop_waiting_time').text(result.stop_waiting_time);

        if(result.payment !=null){
            $('#flat_fare').text(result.payment['currency']+ result.payment['flat_fare']);
            $('#base_fare').text(result.payment['currency']+ result.payment['base_fare']);
            $('#total_fare').text(result.payment['currency']+ result.payment['total']);
            $('#distance_fare').text(result.payment['currency']+ result.payment['distance_fare']);
            $('#min_fare').text(result.payment['currency']+ result.payment['min_fare']);
            $('#trip_waiting_fare').text(result.payment['currency']+ result.payment['waiting_fare']);
            $('#stop_waiting_fare').text(result.payment['currency']+ result.payment['stop_waiting_fare']);
            $('#toll_fare').text(result.payment['currency']+ result.payment['toll']);
            $('#extra_fare').text(result.payment['currency']+ result.payment['extra_fare']);
            $('#tax').text(result.payment['currency']+ result.payment['vat']);
         }else{
            $('#flat_fare').text('null');
            $('#base_fare').text('null');
            $('#total_fare').text('null');
            $('#distance_fare').text('null');
            $('#min_fare').text('null');
            $('#trip_waiting_fare').text('null');
            $('#stop_waiting_fare').text('null');
            $('#toll_fare').text('null');
            $('#extra_fare').text('null');
            $('#tax').text('null');
        }     
        $('#pickup').text(result.s_address);
        $('#stop1').text(result.stop1_address);
        $('#stop2').text(result.stop2_address);
        $('#drop').text(result.d_address);
        $('#user_notes').text(result.user_notes);
        var commentinner = 'No comment';
        var updatedateinner = '';
        $('#last_update').hide();
        if(result.comment !=null && result.comment !=''){
            var commentinner = result.comment;
            var updatedateinner = result.updated_at;
            $('#last_update').show();
        }
        $('#comment_text').text(commentinner);
        $('#update_date').text(updatedateinner);
        $('#comment').val(result.comment);
        $('#request_id').val(result.id);
        });
        
        
});    
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ Setting::get('map_key', 'AIzaSyC7urojphmUg5qlseNH99Rojwn9Y-Amc0w') }}&libraries=places" async defer></script>

<script>
    $(document).on('click','#editmodal', function() {
        var id = $(this).attr("data-id");
           $.get('/partner/editdetail/' + id, function( data ) {
                $(".edit-body").html(data);
           });
           $("#editmodalbox").modal("toggle");
    });
    function closemodal(){
        $("#editmodalbox").modal("toggle");
    }
</script>

<script>
    $(document).on('click','#cancelmodal', function() {
        $("#cancelid").empty();
        var id = $(this).attr("data-id");
           $("#cancelid").append("<a href='/partner/canceldetail/"+id+"' class='btn btn-danger'>@lang('admin.triplist.cancel_trip')</a>");
           $("#cancelmodalbox").modal("toggle");
    });
</script>
<script type="text/javascript">
 $(document).on('click','#autotrip', function() {
        var id = $(this).attr("data-id");
           $.get('/partner/autotrip/' + id, function( data ){
                
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
            url: "{{ route('partner.storecomment') }}",
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
@endsection
