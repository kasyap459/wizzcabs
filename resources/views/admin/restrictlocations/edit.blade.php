@extends('admin.layout.base')

@section('title', 'Add Location')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	
    	<div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Restricted Area</h4><a href="{{ route('admin.restrict-location.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">List Restricted Area</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Update Restricted Area</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
			<h5 style="margin-bottom: 2em;">Update Restricted Area</h5>
            <form class="form-horizontal" action="{{route('admin.restrict-location.update', $restrict->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
    			<input type="hidden" name="_method" value="PATCH">
                <div class="form-group row">
                    <label for="location_id" class="col-xs-12 col-form-label">Geo Location</label>
                    <div class="col-xs-4">
                        <select name="location_id" id="location_id" class="form-control" required="required">
                            <option value="">Select Area</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" @if($location->id == $restrict->location_id) selected @endif>{{ $location->location_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="restrict_area" class="col-xs-12 col-form-label">Restrict Area</label>
                    <div class="col-xs-4">
                        <select name="restrict_area" id="restrict_area" required="required" class="form-control">
                            <option value="1" @if($restrict->restrict_area ==1) selected @endif>All</option>
                            <option value="2" @if($restrict->restrict_area ==2) selected @endif>Pick up</option>
                            <option value="3" @if($restrict->restrict_area ==3) selected @endif>Drop off</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-xs-12 col-form-label">Time</label>
                    <div class="col-xs-2">
                        <input type="text" name="s_time" id="s_time" class="form-control" placeholder="Start time" value="{{ $restrict->s_time }}" required>
                    </div>
                    <div class="col-xs-2">
                        <input type="text" name="e_time" id="e_time" class="form-control" placeholder="Ending time" value="{{ $restrict->e_time }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="zipcode" class="col-xs-12 col-form-label"></label>
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Update</button>
                        <a href="{{route('admin.restrict-location.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
                    </div>
                </div>
			</form>
		</div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">
    var maxdate = {!! json_encode( \Carbon\Carbon::today()->format('Y-m-d\TH:i') ) !!}
    $('#s_time').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
    $('#e_time').datetimepicker({
         datepicker:false,
         format:'H:i:s',
         step:5
    });
</script>
@endsection