@extends('admin.layout.base')

@section('title', 'Companies ')

@section('content')
 <?php
 
$dataPoint = $dataPoints;
 

 
?>
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">{{$provider_name}}</h4>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.list_companies')</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <div class="row filterbox"> 

           <!--  <a href="{{ route('admin.dispatch.index') }}" style="margin-right: 1em;" class="btn btn-primary pull-right load">@lang('admin.triplist.dispatch_panel')</a> -->
            <div class="form-group col-md-3">
                <br><br><br><br>
                <span style="top:50px;"><b>FROM DATE : <span style="color: blue;" id="frm_frm">{{$fromdate}}</span></b></span>
                <br><br><br><br><br><br><br><br><br>
                <b><span style="top:50px !important;">Availability : <span style="color: green;">{{$availability}}</span></span></b>

            </div>
            <div class="form-group col-md-6">
                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
            </div>
            <div class="form-group col-md-3"></div>
            <br><br><br><br>
            <b><span style="top:50px !important;">TO DATE : <span style="color: blue;" id="to_to">{{$todate}}</span></span></b>
            <br><br><br><br><br><br><br><br><br>
            <b><span style="top:50px !important;">Break Taken : <span style="color: red;">{{$break}}</span></span></b>
            
            </div>
            <div class="row filterbox"> 

           <!--  <a href="{{ route('admin.dispatch.index') }}" style="margin-right: 1em;" class="btn btn-primary pull-right load">@lang('admin.triplist.dispatch_panel')</a> -->
           <form method="get">
            <div class="form-group col-md-3"></div>
            <div class="form-group col-md-3">
            <input type="hidden" name="provider_id" id="provider_id" value="{{$provider_id}}">
            <input type="text" id="fromdate" name="fromdate" class="form-control" placeholder="@lang('admin.triplist.from_date')" value="{{$fromdate}}" style="border-color:#f59345;" autocomplete="off">
            </div>
            <div class="form-group col-md-3">
            <input type="text" id="todate" name="todate" class="form-control" placeholder="@lang('admin.triplist.to_date')" value="{{$todate}}" style="border-color:#20b9ae;" autocomplete="off">
            </div>
            <div class="form-group col-md-3">
                <button class="btn btn-success" id="sub">Get</button>
                <!-- <input class="btn btn-success" type="submit" name="submit" value="Get"> -->
            </div>
            </form>
            </div>
            
            <div id="shift_screen">
                <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Date</th>
                        <th>Available</th>
                        <th>Break</th>
                       <!--  <th>Shift Out Time</th> -->
                    </tr>
                </thead>
                <tbody>
                    
                
                    @foreach($newprovide as $index => $shift) 

                   
                    
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><a href="{{route('admin.provider.shiftdetails', [$provider_id, $shift['date']])}}">{{$shift['date']}}</a> </td>
                        <td>{{$shift['available']}}</td>
                        <td>{{$shift['break']}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Date</th>
                        <th>Shift In Time</th>
                        <th>Shift Out Time</th>
                       <!--  <th>Shift Out Time</th> -->
                    </tr>
                </tfoot>
            </table>
            
                    
                
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script src="{{asset('asset/js/canvas.js')}}"></script>
<script type="text/javascript">
    
    var maxdate = {!! json_encode( \Carbon\Carbon::today()->format('Y-m-d\TH:i') ) !!}
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

<script>
// function updates(){
//         // var tripstatus = $('#tripstatus').val();
//         // var servicetype = $('#servicetype').val();
//         var dataPoints = [];
//         var id = $('#provider_id').val();
//         var fromdate = $('#fromdate').val();
//         var todate = $('#todate').val();
//         var dataString = "fromdate="+fromdate+"&todate="+todate+"&id="+id;
//         //alert(id);
//         $.ajax
//             ({
//               cache: false,
//               type: "GET",
//               url: "../../listallshift",
//               data: dataString,
//               success: function(data)
//               {
//                  $('#shift_screen').html(data);
//                  $('#frm_frm').html($('#frm').val());
//                  $('#to_to').html($('#to').val());

//                  var ids = $('#provider_id').val();
//                  var dataStrings = "id="+ids;
//                 // $.ajax
//                 //     ({
//                 //           cache: false,
//                 //           type: "GET",
//                 //           url: "../../listallshiftmonth",
//                 //           data: dataStrings,
//                 //           success: function(data)
//                 //           {
                              
//                 //              <?php
//                 //             $dataPoints=json_decode('data');
//                 //             ?>
//                 //           }
//                 //         });
                 


//               }
//             });
//     }
$(document).ready(function(){
    //updates();

    $("#sub").click(function(){

        var id = $('#provider_id').val();
        var fromdate = $('#fromdate').val();
        var todate = $('#todate').val();
        var url = "https://dev.unicotaxi.com/admin/production-management/"+id+"/shift?fromdate="+fromdate+"&todate="+todate;

       // window.location.href = url;
        window.location.replace(url);

    });
     

    });

</script>

<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
    //theme: "light2",
    animationEnabled: true,
    title: {
        text: "Shift Statistics"
    },

    toolTip:{   
            content: "{label}: {msg}"      
        },
    data: [{
        type: "doughnut",
        startAngle:  270,
        indexLabel: "{msg}",
        yValueFormatString: false,
        showInLegend: false,
        legendText: "{label} : {msg}",
        //indexLabelPlacement: null,
        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
    }]
});
chart.render();
 
}
$(document).ready(function(){
    //alert($('.canvasjs-chart-credit').html());
$('a.canvasjs-chart-credit').hide();

 });
</script>
@endsection