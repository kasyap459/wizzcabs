@extends('admin.layout.base')

@section('title', 'Add Fare Model ')
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
.row {
    display: block !important;
}
.not-allowed {
     		pointer-events: auto !important;
     		cursor: not-allowed !important;
	}
</style>
@endsection
@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
    
        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Fare Management</h4><a href="{{ route('admin.faremodel.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">List Fare model</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Add fare model</li>
                </ol>
            </div>
        </div>
    <form class="form-horizontal" autocomplete="off" action="{{route('admin.faremodel.store')}}" method="POST" enctype="multipart/form-data" role="form">
    <div class="row">
        <div class="box box-block bg-white">
            <h5>Add Fare Model</h5>
                {{ csrf_field() }}
            <!-- <div class="form-group row">
                <label for="country_id" class="col-xs-2 col-form-label">Country</label>
                <div class="col-xs-6">
                    <select name="country_id" id="country_id" required="required" class="form-control">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->countryid }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div> -->
            
            <div class="form-group row">
                <label for="timezoner" class="col-xs-12 col-form-label">Service Types</label>
                <div class="col-xs-8">
                <select name="service_type_id" id="service_type_id" required="required" class="form-control">
                    <option value="">Select Service</option>
                    @foreach($services as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>                   
               </div>
            </div>

            <div class="form-group row">
                <label for="s1_enable" class="col-xs-12 col-form-label">Surge Hour (One)</label>
                <div class="col-xs-8">
                    <select class="form-control" id="s1_enable" name="s1_enable">
                        <option value="1">Enable</option>
                        <option value="0">Disable</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="seller_email" class="col-xs-12 col-form-label">Surge Hour (Two)</label>
                <div class="col-xs-8">
                    <select class="form-control" id="s2_enable" name="s2_enable">
                        <option value="1">Enable</option>
                        <option value="0">Disable</option>
                    </select>
                </div>
            </div>
        </div>
        </div>
        <div class="row headtitle">
            <div class="box box-block bg-white">
                <div class="row">
                    <div class="col-md-6"><h5 style="margin-bottom: 1em;">Mon-Thursday Tariff</h5></div>
                    <div class="col-md-6"><p id="overlapp_txt"></p></div>
                </div>
                <div class="row">
                    <div class="col-xs-4"><p></p></div>
                    <div class="col-xs-2"><p>Day Time</p></div>
                    <div class="col-xs-2"><p>Night Time</p></div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Starting Time</label>
                    <div class="col-xs-2">
			<input type="text" name="t1_stime" class="form-control" id="settime" placeholder="Start time" value="{{ old('t1_stime') }}" step='1' min="00:00:00" max="24:00:00" required onfocus="this.type='time'">
                        <!--<input type="text" name="t1_stime" id="settime" class="form-control" placeholder="Start time" value="{{ old('t1_stime') }}" required onchange="overlapp()">
                    --></div>
                    <div class="col-xs-2">
                        <input type="text" name="t2_stime" class="form-control" placeholder="Start time" value="{{ old('t2_stime') }}" step='1' min="00:00:00" max="24:00:00" required onfocus="this.type='time'">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Ending Time</label>
                    <div class="col-xs-2">
                        <input type="text" name="t1_etime" class="form-control" placeholder="End time" value="{{ old('t1_etime') }}" step='1' min="00:00:00" max="24:00:00" required onfocus="this.type='time'">
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="t2_etime" class="form-control" placeholder="End time" value="{{ old('t2_etime') }}" step='1' min="00:00:00" max="24:00:00" required onfocus="this.type='time'">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Base Price ({{ currency() }})</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t1_base') }}" name="t1_base" required id="t1_base" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" placeholder="Base Price">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t2_base') }}" name="t2_base" required id="t2_base" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" placeholder="Base Price">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Base Distance ({{ Setting::get('distance_unit') }})</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t1_base_dist') }}" name="t1_base_dist"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required id="t1_base_dist" placeholder="Base Distance">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t2_base_dist') }}" name="t2_base_dist"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required id="t2_base_dist" placeholder="Base Distance">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Distance Price ({{ currency() }})</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t1_distance') }}" name="t1_distance" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required id="t1_distance" placeholder="Distance Price">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t2_distance') }}" name="t2_distance" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required id="t2_distance" placeholder="Distance Price">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Minute Price ({{ currency() }})</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t1_minute') }}" name="t1_minute" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required id="t1_minute" placeholder="Minute price">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t2_minute') }}" name="t2_minute" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required id="t2_minute" placeholder="Minute price">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Free Trip Waiting per min(mintues)</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t1_base_wait') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="t1_base_wait" required id="t1_base_wait" placeholder="Free Trip Waiting">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t2_base_wait') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="t2_base_wait" required id="t2_base_wait" placeholder="Free Trip Waiting">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Trip Waiting Price ({{ currency() }}) per min</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t1_waiting') }}" name="t1_waiting" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required id="t1_waiting" placeholder="Waiting price">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t2_waiting') }}" name="t2_waiting" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required id="t2_waiting" placeholder="Waiting price">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Free Stops Waiting per min(mintues)</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t1s_base_wait') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="t1s_base_wait" required id="t1s_base_wait" placeholder="Free Stops Waiting per">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t2s_base_wait') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="t2s_base_wait" required id="t2s_base_wait" placeholder="Free Stops Waiting per">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Stop Waiting Price ({{ currency() }}) per min</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('s1_waiting') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="s1_waiting" required id="s1_waiting" placeholder="Stop Waiting price">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('s2_waiting') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  name="s2_waiting" required id="s2_waiting" placeholder="Stop Waiting price">
                    </div>
                </div>
<!--                 <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Cancellation Charge ({{ currency() }})</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t1_cancel') }}" name="t1_cancel" required id="t1_cancel" placeholder="Cancellation Charge">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t2_cancel') }}" name="t2_cancel" required id="t2_cancel" placeholder="Cancellation Charge">
                    </div>
                </div>
 -->            </div>
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
                        <input type="text" name="t1_s_stime" class="form-control" placeholder="Start time" value="{{ old('t1_s_stime') }}" step='1' min="00:00:00" max="24:00:00" required onfocus="this.type='time'">
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="t2_s_stime" class="form-control" placeholder="Start time" value="{{ old('t2_s_stime') }}" step='1' min="00:00:00" max="24:00:00" required onfocus="this.type='time'">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Ending Time</label>
                    <div class="col-xs-2">
                        <input type="text" name="t1_s_etime" class="form-control" placeholder="End time" value="{{ old('t1_s_etime') }}" step='1' min="00:00:00" max="24:00:00" required onfocus="this.type='time'">
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="t2_s_etime" class="form-control" placeholder="End time" value="{{ old('t2_s_etime') }}" step='1' min="00:00:00" max="24:00:00" required onfocus="this.type='time'">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Base Price ({{ currency() }})</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t1_s_base') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="t1_s_base" required id="t1_s_base" placeholder="Base Price">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t2_s_base') }}"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="t2_s_base" required id="t2_s_base" placeholder="Base Price">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Base Distance ({{ Setting::get('distance_unit') }})</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t1_s_base_dist') }}" name="t1_s_base_dist"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required id="t1_s_base_dist" placeholder="Base Distance">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t2_s_base_dist') }}" name="t2_s_base_dist"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required id="t2_s_base_dist" placeholder="Base Distance">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Distance Price ({{ currency() }})</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t1_s_distance') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="t1_s_distance" required id="t1_s_distance" placeholder="Distance Price">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t2_s_distance') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="t2_s_distance" required id="t2_s_distance" placeholder="Distance Price">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Minute Price ({{ currency() }})</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t1_s_minute') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="t1_s_minute" required id="t1_s_minute" placeholder="Minute price">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t2_s_minute') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="t2_s_minute" required id="t2_s_minute" placeholder="Minute price">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Free Trip Waiting per min(mintues)</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t3_base_wait') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="t3_base_wait" required id="t3_base_wait" placeholder="Free Trip Waiting">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t4_base_wait') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  name="t4_base_wait" required id="t4_base_wait" placeholder="Free Trip Waiting">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Trip Waiting Price ({{ currency() }}) per min</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t1_s_waiting') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="t1_s_waiting" required id="t1_s_waiting" placeholder="Waiting price">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t2_s_waiting') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="t2_s_waiting" required id="t2_s_waiting" placeholder="Waiting price">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Free Stops Waiting per min(mintues)</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t3s_base_wait') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="t3s_base_wait" required id="t3s_base_wait" placeholder="Free Stops Waiting per">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t4s_base_wait') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  name="t4s_base_wait" required id="t4s_base_wait" placeholder="Free Stops Waiting per">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Stop Waiting Price ({{ currency() }}) per min</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('s3_waiting') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="s3_waiting" required id="s3_waiting" placeholder="Stop Waiting price">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('s4_waiting') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="s4_waiting" required id="s4_waiting" placeholder="Stop Waiting price">
                    </div>
                </div>
<!--                 <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Cancellation Charge ({{ currency() }})</label>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t1_s_cancel') }}" name="t1_s_cancel" required id="t1_s_cancel" placeholder="Cancellation Charge">
                    </div>
                    <div class="col-xs-2">
                       <input class="form-control" type="text" value="{{ old('t2_s_cancel') }}" name="t2_s_cancel" required id="t2_s_cancel" placeholder="Cancellation Charge">
                    </div>
                </div>
 -->                <div class="row">
                    <div class="col-xs-4"><p></p></div>
                    <div class="col-xs-2"><p>Surge Hour (One)</p></div>
                    <div class="col-xs-2"><p>Surge Hour (Two)</p></div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Starting Time</label>
                    <div class="col-xs-2">
                        <input type="text" name="s1_stime" class="form-control" placeholder="Start time" value="{{ old('s1_stime') }}" step='1' min="00:00:00" max="24:00:00" required onfocus="this.type='time'">
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="s2_stime" class="form-control" placeholder="Start time" value="{{ old('s2_stime') }}" step='1' min="00:00:00" max="24:00:00" required onfocus="this.type='time'">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Ending Time</label>
                    <div class="col-xs-2">
                        <input type="text" name="s1_etime" class="form-control" placeholder="End time" value="{{ old('s1_etime') }}" step='1' min="00:00:00" max="24:00:00" required onfocus="this.type='time'">
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="s2_etime" class="form-control" placeholder="End time" value="{{ old('s2_etime') }}" step='1' min="00:00:00" max="24:00:00" required onfocus="this.type='time'">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-4 col-form-label">Percentage</label>
                    <div class="col-xs-2">
                        <input type="text" name="s1_percent" id="s1_percent" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" placeholder="percentage" value="{{ old('s1_percent') }}" required>
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="s2_percent" id="s2_percent" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" placeholder="percentage" value="{{ old('s2_percent') }}" required>
                    </div>
                </div>           
             </div>

            </div>

            <div class="row">
                <div class="box box-block bg-white">
		@if(auth()->user()->admin_type != 0) 
                <button type="button" class="btn btn-success disabled not-allowed"> <i class="fa fa-check"></i> Add Fare Model</button>
		@else
		<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Add Fare Model</button>
		@endif
                        <a href="{{ route('admin.faremodel.index') }}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
                </div>
               
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">
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

    $('#s1_stime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#s2_stime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#s1_etime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#s2_etime').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });

    function overlapp(){
        var dateRanges = [];
        if($('#t1_stime').val()!=''){ dateRanges.push(sec_convert($('#t1_stime').val())); }
        if($('#t1_etime').val()!=''){ dateRanges.push(sec_convert($('#t1_etime').val())); }
        if($('#t2_stime').val()!=''){ dateRanges.push(sec_convert($('#t2_stime').val())); }
        if($('#t2_etime').val()!=''){ dateRanges.push(sec_convert($('#t2_etime').val())); }
        if($('#t3_stime').val()!=''){ dateRanges.push(sec_convert($('#t3_stime').val())); }
        if($('#t3_etime').val()!=''){ dateRanges.push(sec_convert($('#t3_etime').val())); }
        if($('#t4_stime').val()!=''){ dateRanges.push(sec_convert($('#t4_stime').val())); }
        if($('#t4_etime').val()!=''){ dateRanges.push(sec_convert($('#t4_etime').val())); }
        if($('#s1_stime').val()!=''){ dateRanges.push(sec_convert($('#s1_stime').val())); }
        if($('#s2_stime').val()!=''){ dateRanges.push(sec_convert($('#s2_stime').val())); }
        if($('#s1_etime').val()!=''){ dateRanges.push(sec_convert($('#s1_etime').val())); }
        if($('#s2_etime').val()!=''){ dateRanges.push(sec_convert($('#s2_etime').val())); }

        var length = dateRanges.length - 1;
        for (var i = 1; i < length; i++){
          var previous = dateRanges[i-1];
          var current = dateRanges[i];
          if(previous > current){
            $('#overlapp_txt').text('Time is overlapping');
          }else{
            $('#overlapp_txt').text('');
          }
        }
    }

    function sec_convert(data){
        var hms = data; 
        var a = hms.split(':');
        var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]); 
    }
</script>
@endsection