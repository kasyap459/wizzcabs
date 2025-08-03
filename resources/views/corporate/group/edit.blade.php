@extends('corporate.layout.base')

@section('title', 'Edit Group ')

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
                    <li class="active">Edit Group</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
    		<h5>Edit Group</h5>
            <form class="form-horizontal" action="{{route('corporate.group.update', $Group->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">

				<div class="form-group row">
					<label for="group_name" class="col-xs-12 col-form-label">Group Name</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ $Group->group_name }}" name="group_name" required id="group_name" placeholder="Group Name">
					</div>
				</div>

                <div class="form-group row">
                    <label for="name" class="col-xs-12 col-form-label">Payment Mode</label>
                    <div class="col-xs-12">
                        <div class="col-md-4">
                            <label>
                            <input type="radio" name="payment_mode" @if($Group->payment_mode =='AUTOPAY') checked @endif value="AUTOPAY"> <span style="font-weight: bold"> Auto-Paid By Company</span><br><span style="font-size: 12px;">Ride bills are automatically deducted from your corporate account</span>
                            </label>
                        </div>
                       
                    </div>
                </div>
                
				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-8">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Update Group</button>
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
/*$(document).ready(function() {
	   
    $("#add_range").click(function () {
        var random_num = Math.random().toString(36).substring(7);
        $(".time_range").append("<div class='col-xs-8 m-b-30'><input type='text' name='time_range[]' id='"+random_num+"'></div>");
        $("#"+random_num).ionRangeSlider({
            type: "double",
            min: 0,
            max: 24,
            force_edges: true,
            postfix: " hrs",
        });
    });

    $("#remove_range").click(function () {
        var div_lengh = $(".time_range > .m-b-30").length;
        if(div_lengh >1){
            $('.time_range').children().last().remove();
        }
    });
});

$(window).load(function(){
	var elements = document.getElementsByClassName("specialclass");
	for(var i=0; i<elements.length; i++) {
	    $(elements[i]).ionRangeSlider({
	        type: "double",
	        min: 0,
	        max: 24,
	        force_edges: true,
	        postfix: " hrs",
	    });
	}
});*/
</script>
@endsection