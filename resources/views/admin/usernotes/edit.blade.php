@extends('admin.layout.base')

@section('title', 'Update User Notes')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">User Notes</h4><a href="{{ route('admin.user-note.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">User Notes</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Update User Notes</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
			<h5>Update User Ratings</h5>

            <form class="form-horizontal" action="{{route('admin.user-note.update', $usernote->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">
				
				<div class="form-group row">
					<label for="name" class="col-xs-12 col-form-label">Rating Name</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ $usernote->notes }}" name="notes" required id="ratings" placeholder="User Notes">
					</div>
				</div>
				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-8">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Update User Notes</button>
						<a href="{{route('admin.user-note.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
