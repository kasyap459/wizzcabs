@extends('corporate.layout.base')

@section('title', 'Add Group ')

@section('styles')
<link rel="stylesheet" href="{{asset('main/vendor/ion.rangeSlider/css/ion.rangeSlider.css')}}">
<link rel="stylesheet" href="{{asset('main/vendor/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css')}}">
<style type="text/css">
    .m-b-30{
        margin-bottom: 30px;
    }
    .action-btn{
        color: black;
        padding-right: 8px;
        text-decoration: underline;
    }
</style>
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Groups</h4><a href="{{ route('corporate.group.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">List Group</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('corporate.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Add Group</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
    		<h5>Add Group</h5>
            <form class="form-horizontal" action="{{route('corporate.group.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="group_name" class="col-xs-12 col-form-label">Group Name</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ old('group_name') }}" name="group_name" required id="group_name" placeholder="Group Name">
					</div>
				</div>

                <div class="form-group row">
                    <label for="name" class="col-xs-12 col-form-label">Payment Mode</label>
                    <div class="col-xs-12">
                        <div class="col-md-4">
                            <label>
                            <input type="radio" name="payment_mode" value="AUTOPAY"> <span style="font-weight: bold"> Auto-Paid By Company</span><br><span style="font-size: 12px;">Ride bills are automatically deducted from your corporate account</span>
                            </label>
                        </div>
                        <!-- <div class="col-md-4">
                            <label>
                            <input type="radio" name="payment_mode" value="REIMBURSED"> <span style="font-weight: bold"> Pay & Get Reimbursed</span><br><span style="font-size: 12px;">Employee pay for their rides and claim reimbursements later</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label>
                            <input type="radio" name="payment_mode" value="SELFPAY"> <span style="font-weight: bold">  Self Pay</span><br><span style="font-size: 12px;">Ride bills are automatically deducted from your account</span>
                            </label>
                        </div> -->
                    </div>
                </div>
                <!-- <div class="form-group row">
                    <label for="group_name" class="col-xs-12 col-form-label">Ride Service Types</label>
                    <div class="col-xs-8">
                        @foreach($services as $service)
                        <label class="form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ride_service_type[]" id="inlineCheckbox3" value="{{ $service->id }}"> {{ $service->name }}
                        </label>
                        @endforeach
                    </div>
                </div> -->
                 <!-- <div class="form-group row">
                    <label for="group_name" class="col-xs-12 col-form-label">Days</label>
                    <div class="col-xs-8">
                        <label class="form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allowed_days[]" id="Sun" value="Sun"> Sun
                        </label>
                        <label class="form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allowed_days[]" id="Mon" value="Mon"> Mon
                        </label>
                        <label class="form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allowed_days[]" id="Tue" value="Tue"> Tue
                        </label>
                        <label class="form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allowed_days[]" id="Wed" value="Wed"> Wed
                        </label>
                        <label class="form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allowed_days[]" id="Thr" value="Thr"> Thr
                        </label>
                        <label class="form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allowed_days[]" id="Fri" value="Fri"> Fri
                        </label>
                        <label class="form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allowed_days[]" id="Sat" value="Sat"> Sat
                        </label>
                    </div>
                                 </div> -->
                <!-- <div class="form-group row time_range">
                    <label for="group_name" class="col-xs-12 col-form-label">Time Range</label>
                    <div class="col-xs-8 m-b-30">
                        <input type="text" name="time_range[]" id="range_default">
                    </div> 
                </div>
                <div class="form-group row">
                    <div class="col-xs-8">
                        <p class="pull-right"><a class="action-btn" id="add_range" href="#">Add Rages</a> <a class="action-btn" id="remove_range" href="#"> Remve Rages</a></p>
                    </div>
                </div> -->
				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-8">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Add Group</button>
						<a href="{{route('corporate.group.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection


@section('scripts')
<script type="text/javascript" src="{{asset('main/vendor/ion.rangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#range_default").ionRangeSlider({
        type: "double",
        min: 0,
        max: 24,
        force_edges: true,
        postfix: " hrs",
    });

    $("#add_range").click(function () {
        var div_lengh = $(".time_range > .m-b-30").length;
        if(div_lengh < 3){
            var random_num = Math.random().toString(36).substring(7);
            $(".time_range").append("<div class='col-xs-8 m-b-30'><input type='text' name='time_range[]' id='"+random_num+"'></div>");
            $("#"+random_num).ionRangeSlider({
                type: "double",
                min: 0,
                max: 24,
                force_edges: true,
                postfix: " hrs",
            });
        }
    });

    $("#remove_range").click(function () {
        var div_lengh = $(".time_range > .m-b-30").length;
        if(div_lengh >1){
            $('.time_range').children().last().remove();
        }
    });
});

</script>
@endsection