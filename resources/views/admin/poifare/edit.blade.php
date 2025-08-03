@extends('admin.layout.base')

@section('title', 'Airport transfer Fare ')
@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
<style>
.headtitle{
    /* padding-bottom: 2rem; */
}
.headtitle p{
    font-weight: bold;
    text-align: center;
}
.p_left{
    padding: 0 2px;
}
</style>
@endsection
@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
    
        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Airport transfer Fare</h4><a href="{{ route('admin.poifare.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">List  Fare</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Update Fare</li>
                </ol>
            </div>
        </div>

        <form class="form-horizontal" autocomplete="off" action="{{route('admin.poifare.update', $poifare->id )}}" method="POST" enctype="multipart/form-data" role="form">
        <div class="row">
        <div class="box box-block bg-white">
            <h5>Add Airport transfer Fare</h5>
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PATCH">
            <div class="form-group row">
                <label for="poi_s_addr" class="col-xs-2 col-form-label">POI Source Address</label>
                <div class="col-xs-6">
                    <select name="poi_s_addr" id="poi_s_addr" required="required" class="form-control">
                        <option value="">Source Address</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" @if($location->id == $poifare->poi_s_addr) selected @endif>{{ $location->location_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="poi_d_addr" class="col-xs-2 col-form-label">POI Destination Address</label>
                <div class="col-xs-6">
                    <select name="poi_d_addr" id="poi_d_addr" required="required" class="form-control">
                        <option value="">Destination Address</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" @if($location->id == $poifare->poi_d_addr) selected @endif>{{ $location->location_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="service_type_id" class="col-xs-2 col-form-label">Service Types</label>
                <div class="col-xs-6">
                    <select name="service_type_id" id="service_type_id" required="required" class="form-control">
                        <option value="">Select Service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" @if($service->id == $poifare->service_type_id) selected @endif >{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="reverse_loc" class="col-xs-2 col-form-label">Reverse Location </label>
                <div class="col-xs-6">
                    <select name="reverse_loc" id="reverse_loc" required="required" class="form-control">
                        <option value="0" @if($poifare->reverse_loc ==0) selected @endif>No</option>
                        <option value="1" @if($poifare->reverse_loc ==1) selected @endif>Yes</option>
                    </select>
                </div>
            </div>
        </div>
        </div>
        <div class="row headtitle">
            <div class="box box-block bg-white">
                <h5 style="margin-bottom: 1em;">Mon-Thursday Tariff</h5>
                <div class="row">
                    <div class="col-xs-4"><p></p></div>
                    <div class="col-xs-2"><p>Day Time</p></div>
                    <div class="col-xs-2"><p>Night Time</p></div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Starting Time</label>
                    <div class="col-xs-2">
                        <input type="text" name="t1_stime" id="t1_stime" class="form-control" placeholder="Start time" value="{{ $poifare->t1_stime }}" required>
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="t2_stime" id="t2_stime" class="form-control" placeholder="Start time" value="{{ $poifare->t2_stime }}" required>
                    </div>
<!--                     <div class="col-xs-2">
                        <input type="text" name="t3_stime" id="t3_stime" class="form-control" placeholder="Start time" value="{{ $poifare->t3_stime }}" required>
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="t4_stime" id="t4_stime" class="form-control" placeholder="Start time" value="{{ $poifare->t4_stime }}" required>
                    </div>
 -->                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Ending Time</label>
                    <div class="col-xs-2">
                        <input type="text" name="t1_etime" id="t1_etime" class="form-control" placeholder="End time" value="{{ $poifare->t1_etime }}" required>
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="t2_etime" id="t2_etime" class="form-control" placeholder="End time" value="{{ $poifare->t2_etime }}" required>
                    </div>
<!--                     <div class="col-xs-2">
                        <input type="text" name="t3_etime" id="t3_etime" class="form-control" placeholder="End time" value="{{ $poifare->t3_etime }}" required>
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="t4_etime" id="t4_etime" class="form-control" placeholder="End time" value="{{ $poifare->t4_etime }}" required>
                    </div>
 -->                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Flat Fare ({{ currency() }})</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ $poifare->t1_flat }}" name="t1_flat" required id="t1_flat" placeholder="Flat Fare">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ $poifare->t2_flat }}" name="t2_flat" required id="t2_flat" placeholder="Flat Fare">
                    </div>
<!--                     <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ $poifare->t3_flat }}" name="t3_flat" required id="t3_flat" placeholder="Flat Fare">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ $poifare->t4_flat }}" name="t4_flat" required id="t4_flat" placeholder="Flat Fare">
                    </div>
 -->                </div>
            </div>
            </div>
            <div class="row headtitle">
            <div class="box box-block bg-white">
                <h5 style="margin-bottom: 1em;">Fri-Sun Tariff</h5>
                <div class="row">
                    <div class="col-xs-4"><p></p></div>
                    <div class="col-xs-2"><p>Day Time</p></div>
                    <div class="col-xs-2"><p>Night Time</p></div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Starting Time</label>
                    <div class="col-xs-2">
                        <input type="text" name="t1_s_stime" id="t1_s_stime" class="form-control" placeholder="Start time" value="{{ $poifare->t1_s_stime }}" required>
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="t2_s_stime" id="t2_s_stime" class="form-control" placeholder="Start time" value="{{ $poifare->t2_s_stime }}" required>
                    </div>
<!--                     <div class="col-xs-2">
                        <input type="text" name="t3_s_stime" id="t3_s_stime" class="form-control" placeholder="Start time" value="{{ $poifare->t3_s_stime }}" required>
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="t4_s_stime" id="t4_s_stime" class="form-control" placeholder="Start time" value="{{ $poifare->t4_s_stime }}" required>
                    </div> -->
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Ending Time</label>
                    <div class="col-xs-2">
                        <input type="text" name="t1_s_etime" id="t1_s_etime" class="form-control" placeholder="End time" value="{{ $poifare->t1_s_etime }}" required>
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="t2_s_etime" id="t2_s_etime" class="form-control" placeholder="End time" value="{{ $poifare->t2_s_etime }}" required>
                    </div>
<!--                     <div class="col-xs-2">
                        <input type="text" name="t3_s_etime" id="t3_s_etime" class="form-control" placeholder="End time" value="{{ $poifare->t3_s_etime }}" required>
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="t4_s_etime" id="t4_s_etime" class="form-control" placeholder="End time" value="{{ $poifare->t4_s_etime }}" required>
                    </div>
 -->                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Flat Fare ({{ currency() }})</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ $poifare->t1_s_flat }}" name="t1_s_flat" required id="t1_s_flat" placeholder="Flat Fare">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ $poifare->t2_s_flat }}" name="t2_s_flat" required id="t2_s_flat" placeholder="Flat Fare">
                    </div>
<!--                     <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ $poifare->t3_s_flat }}" name="t3_s_flat" required id="t3_s_flat" placeholder="Flat Fare">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ $poifare->t4_s_flat }}" name="t4_s_flat" required id="t4_s_flat" placeholder="Flat Fare">
                    </div>
 -->                </div>
                
            </div>
            </div>
            <div class="row">
                <div class="box box-block bg-white">
                <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Update Fare</button>
                        <a href="{{ route('admin.poifare.index') }}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
                </div>
               
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">
    
    var maxdate = {!! json_encode( \Carbon\Carbon::today()->format('Y-m-d\TH:i') ) !!}
    $('#t1_stime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#t2_stime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#t3_stime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#t4_stime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });

    $('#t1_etime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#t2_etime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#t3_etime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#t4_etime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });


     $('#t1_s_stime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#t2_s_stime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#t3_s_stime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#t4_s_stime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });

    $('#t1_s_etime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#t2_s_etime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#t3_s_etime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#t4_s_etime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
</script>
@endsection