@extends('account.layout.base')

@section('title', 'Dashboard ')

@section('styles')
	<link rel="stylesheet" href="{{asset('main/vendor/jvectormap/jquery-jvectormap-2.0.3.css')}}">
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
      /*margin-top: 8px;*/
    }
    .breadcrumbb > li {
    display: inline-block;
    }
.container-fluid::after {
    content: "";
    display: none;
    clear: both;
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
                <li><input type="text" id="fromdate" onchange="update_content()" class="form-control" placeholder="From Date" autocomplete="off" style="border-color:green;"></li>
                <li><input type="text" id="todate" onchange="update_content()" class="form-control" placeholder="To Date" autocomplete="off" style="border-color:#f59345;"></li>
            </ol>
        </div>
    </div>
	<div id="content">
    
	</div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>

<script type="text/javascript">
    
    var maxdate = {!! json_encode( \Carbon\Carbon::today()->format('Y-m-d\TH:i') ) !!}
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
<script>
function update_content(){
        var fromdate = $('#fromdate').val();
        var todate = $('#todate').val();
        var dataString = "fromdate="+fromdate+"&todate="+todate;
        $.ajax
            ({
              cache: false,
              type: "GET",
              url: "content",
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
@endsection