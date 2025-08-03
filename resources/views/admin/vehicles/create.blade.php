@extends('admin.layout.base')

@section('title', 'Add Vehicle ')
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
                <h4 class="page-title">Vehicle Management</h4><a href="{{ route('admin.vehicle.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">List Vehicle</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Add vehicle</li>
                </ol>
            </div>
        </div>
        <div class="box box-block bg-white">
            <form class="form-horizontal" autocomplete="off" action="{{route('admin.vehicle.store')}}" method="POST" enctype="multipart/form-data" role="form">
                <h5>Add Vehicle</h5>
                    {{ csrf_field() }}
                <div class="form-group row">
                    <label for="vehicle_name" class="col-xs-3 col-form-label">Vehicle ID</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ old('vehicle_name') }}" name="vehicle_name" required id="vehicle_name" placeholder="Vehicle ID">
                    </div>
                </div>

                 <div class="form-group row">
                    <label for="vehicle_no" class="col-xs-3 col-form-label">Vehicle number</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ old('vehicle_no') }}" name="vehicle_no" required id="vehicle_no" placeholder="Number plate">
                    </div>
                </div> 
                <div class="form-group row">
                    <label for="seat" class="col-xs-3 col-form-label">Seat Capacity</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="number" value="{{ old('seat') }}" name="seat" required id="seat" placeholder="Seat Capacity">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="location_id" class="col-xs-3 col-form-label">Geo Location</label>
                    <div class="col-xs-6">
                        <select name="location_id" id="location_id" required="required" class="form-control">
                            <option value="">Select Location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="partner_sec">
                    <label for="partner_id" class="col-xs-3 col-form-label">Carrier Name</label>
                    <div class="col-xs-6">
                        <select name="partner_id" required="required" id="partner_id" class="form-control">
                            <option value="">Select Carrier</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="service_type_id" class="col-xs-3 col-form-label">Service Types</label>
                    <div class="col-xs-6">
                        <select name="service_type_id" id="service_type_id" required="required" class="form-control">
                            <option value="">Select Service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="vehicle_owner" class="col-xs-3 col-form-label">Vehicle Owner</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ old('vehicle_owner') }}" name="vehicle_owner" id="vehicle_owner" placeholder="Vehicle Owner">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="vehicle_model" class="col-xs-3 col-form-label">Vehicle Model</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ old('vehicle_model') }}" name="vehicle_model" id="vehicle_model" placeholder="Vehicle Model">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="vehicle_manufacturer" class="col-xs-3 col-form-label">Vehicle Manufacturer</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ old('vehicle_manufacturer') }}" name="vehicle_manufacturer" id="vehicle_manufacturer" placeholder="Vehicle Manufacturer">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="manufacturing_year" class="col-xs-3 col-form-label">Manufacturer Year</label>
                    <div class="col-xs-6">
                        <input class="form-control yearpicker" type="number" value="{{ old('manufacturing_year') }}" name="manufacturing_year" id="manufacturing_year" placeholder="Manufacturer Year">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="vehicle_brand" class="col-xs-3 col-form-label">Vehicle Brand</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ old('vehicle_brand') }}" name="vehicle_brand" id="vehicle_brand" placeholder="Vehicle Brand">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="vehicle_color" class="col-xs-3 col-form-label">Vehicle color</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ old('vehicle_color') }}" name="vehicle_color" id="vehicle_color" placeholder="Vehicle color">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="insurance_no" class="col-xs-3 col-form-label">Insurance Number</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ old('insurance_no') }}" name="insurance_no" id="insurance_no" placeholder="Insurance Number">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="insurance_exp" class="col-xs-3 col-form-label">Insurance expire date</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ old('insurance_exp') }}" name="insurance_exp" id="insurance_exp" placeholder="Insurance expire date">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="handicap_access" class="col-xs-3 col-form-label">Handicap accessibility</label>
                    <div class="col-xs-6">
                        <select name="handicap_access" id="handicap_access" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="travel_pet" class="col-xs-3 col-form-label">Travel with Pet</label>
                    <div class="col-xs-6">
                        <select name="travel_pet" id="travel_pet" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="station_wagon" class="col-xs-3 col-form-label">station wagon</label>
                    <div class="col-xs-6">
                        <select name="station_wagon" id="station_wagon" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="booster_seat" class="col-xs-3 col-form-label">Booster Seat available</label>
                    <div class="col-xs-6">
                        <select name="booster_seat" id="booster_seat" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="child_seat" class="col-xs-3 col-form-label">Child Seat</label>
                    <div class="col-xs-6">
                        <select name="child_seat" id="child_seat" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="booster_count" class="col-xs-3 col-form-label">Booster Seat Count</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="number" value="{{ old('booster_count') }}" name="booster_count" id="booster_count" placeholder="Booster Seat Count">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="vehicle_image" class="col-xs-3 col-form-label">Vehicle Image</label>
                    <div class="col-xs-6">
                        <input type="file" accept="image/*" name="vehicle_image" class="dropify form-control-file" id="vehicle_image" aria-describedby="fileHelp">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="custom_field1" class="col-xs-3 col-form-label">Custom field 1</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ old('custom_field1') }}" name="custom_field1" id="custom_field1" placeholder="Custom field 1">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="custom_field2" class="col-xs-3 col-form-label">Custom field 2</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ old('custom_field2') }}" name="custom_field2" id="custom_field2" placeholder="Custom field 2">
                    </div>
                </div>
                <div class="row">
                    <label for="" class="col-xs-3 col-form-label"></label>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i>Add Vehicle</button>
                        <a href="{{ route('admin.vehicle.index') }}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a> 
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script src="{{asset('main/assets/js/yearpicker.js')}}"></script>
<script type="text/javascript">
    var maxdate = {!! json_encode( \Carbon\Carbon::today()->format('Y-m-d\TH:i') ) !!}
    $('#insurance_exp').datetimepicker({
        format:'Y-m-d',
        timepicker: false,
        MaxDate: maxdate
    });
</script>
@endsection