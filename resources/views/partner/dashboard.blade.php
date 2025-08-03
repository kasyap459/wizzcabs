@extends('partner.layout.base')

@section('title', 'Dashboard ')

@section('styles')
  <link rel="stylesheet" href="{{asset('main/vendor/chartist/chartist.min.css')}}">
  <link rel="stylesheet" href="{{asset('main/vendor/morris/morris.css')}}">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
  <style>
    .form-control{
      line-height: 1.50;
      border-radius: 26px;
      border: 1px solid;
      height: 31px;
      max-width: 92%;
    }
    .breadcrumbb{
      background: 0 0;
      margin-bottom: 0;
      float: right;
      padding: 0;
     /* margin-top: 8px;*/
    }
    .breadcrumbb > li {
    display: inline-block;
    }
.tile h6 {
    font-weight: bold !important;
}
.h1, .h2, .h3, .h4, body, h1, h2, h3, h4, h5, h6 {
    font-weight: bold !important;
}
  </style>
@endsection

@section('content')

<div class="content-area py-1">
<div class="container-fluid">
  <div class="row bg-title">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">@lang('admin.dashboard')</h4>
        </div>
        <div class="col-lg-8 col-sm-8 col-md-8 col-xs-12">
            
            <ol class="breadcrumbb">
                <li><input type="text" id="fromdate" onchange="update_content()" class="form-control" placeholder="From Date" style="border-color:green;"></li>
                <li><input type="text" id="todate" onchange="update_content()" class="form-control" placeholder="To Date" style="border-color:#f59345;"></li>
            </ol>
        </div>
    </div>
	<div id="content">
    
	</div>
  <div class="box box-block bg-white">
    <h5 class="m-b-1">Trip Summary</h5>
    <div id="multiple" class="chart-container"></div>
  </div>
  <div class="box box-block bg-white" id="cancel_donut">
    <h5 class="m-b-1">Cancelled Trips</h5>
    <div id="donut" class="chart-container"></div>
  </div>
  <div class="box box-block bg-white">
    <h5 class="m-b-1">Total Trips</h5>
    <div id="bar" class="chart-container"></div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="{{asset('main/vendor/chartist/chartist.min.js')}}"></script>
<script type="text/javascript" src="{{asset('main/vendor/raphael/raphael.min.js')}}"></script>
<script type="text/javascript" src="{{asset('main/vendor/morris/morris.min.js')}}"></script>
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
        var fromdate = $('#fromdate').val();
        var todate = $('#todate').val();
        var dataString = "fromdate="+fromdate+"&todate="+todate;
        $.ajax
            ({
              cache: false,
              type: "GET",
              url: "{{ route('partner.content') }}",
              headers: { 'X-CSRF-Token' : window.Laravel['csrfToken'] },
              data: dataString,
              success: function(data)
              {
                 $('#content').html(data);
              }
            });
    }

$(window).load(function(){
    update_content();
});
</script>
<script>

</script>
<script>
  /* =================================================================
    Multiple lines chart
================================================================= */

Morris.Area({
    element: 'multiple',
    //data: ,
    xkey: 'date',
    ykeys: ['completed', 'cancelled', 'revenue'],
    labels: ['Completed', 'Cancelled', 'Revenue'],
    pointSize: 3,
    fillOpacity: 0,
    pointStrokeColors:['#f44236', '#43b968', '#20b9ae'],
    behaveLikeLine: true,
    gridLineColor: '#e0e0e0',
    lineWidth: 1,
    hideHover: 'auto',
    lineColors: ['#f44236', '#43b968', '#20b9ae'],
    xLabelFormat: function (ts) {
                    var d = new Date(ts);
                    return d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear();
                  },
    resize: true,
    dateFormat: function (ts) {
                    var d = new Date(ts);
                    return d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear();
                  }   
});

/* =================================================================
    Bar chart
================================================================= */

Morris.Bar({
    element: 'bar',
   // data: ,
    xkey: 'date',
    ykeys: ['app', 'dispatcher', 'street'],
    labels: ['App Rides', 'Dispatcher Rides', 'Street Rides'],
    barColors:['#43b968', '#f59345', '#20b9ae'],
    barSizeRatio: 1,
    hideHover: 'auto',
    gridLineColor: '#ddd',
    xLabelAngle: 0,
    resize: true,
});

/* =================================================================
    Donut chart
================================================================= */
var user = '';
var dispatcher = '';
var driver = '';
var rejected = '';
if(user != 0 || dispatcher != 0 || driver != 0 || rejected != 0){
  Morris.Donut({
    element: 'donut',
    data: [{
        label: "User Cancelled",
        value: user,

    }, {
        label: "Dispatcher Cancelled",
        value: dispatcher
    }, {
        label: "Driver Cancelled",
        value: driver
    },{
        label: "Rejected Trips",
        value: rejected
    }],
    resize: true,
    colors:['#3e70c9', '#5bc0de', '#a567e2', '#43b968']
});
}else{
  $('#cancel_donut').hide();
}
</script>
@endsection