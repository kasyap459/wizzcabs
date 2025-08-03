@extends('admin.layout.base')

@section('title', 'Add User Rating')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">User Rating</h4><a href="{{ route('admin.user-rating.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">User ratings</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Add User Rating</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
    		<h5>Add Customer Care</h5>
            <form class="form-horizontal" action="{{route('admin.user-rating.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="note" class="col-xs-12 col-form-label">@lang('admin.member.full_name')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ old('ratings') }}" name="ratings" required id="ratings" placeholder="User Notes">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-8">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Add User Ratings</button>
						<a href="{{route('admin.user-rating.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
