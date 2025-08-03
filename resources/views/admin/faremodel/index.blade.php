@extends('admin.layout.base')

@section('title', 'Fare Model ')

@section('styles')
<style>
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
                <h4 class="page-title">Fare Management</h4><a href="{{ route('admin.faremodel.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">Add Fare Model</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">List Fare model</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                       <th>ID</th>
                       <th>Service Image</th>
                       <th>Service Type</th>
                       <!-- <th>Status</th> -->
                       <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($faremodels as $index => $faremodel)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><img src="{{$faremodel->image}}" style="height: 50px" ></td>
                        <td>{{ $faremodel->servicename }}</td>
                        <!-- <td>@if($faremodel->status ==1)
				@if(auth()->user()->admin_type == 1)
                                <a class="btn btn-success btn-rounded btn-sm waves-effect waves-light " href="#">Active</a>
                    		@else
				<a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.faremodel.inactive', $faremodel->id) }}">Active</a>
				@endif
			     @else
				@if(auth()->user()->admin_type == 1)
                                <a class="btn btn-danger btn-rounded btn-sm waves-effect waves-light disabled not-allowed" href="#">Inactive</a>
                            	@else
				<a class="btn btn-danger btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.faremodel.active', $faremodel->id) }}">Inactive</a>
                            	@endif
			     @endif</td> -->
                        <td>
                            <form action="{{ route('admin.faremodel.destroy', $faremodel->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
				@if(auth()->user()->admin_type == 1)
                                <a href="#" class="btn btn-success btn-rounded btn-sm label-left b-a-0 waves-effect waves-light disabled not-allowed">
                                    <span class="btn-label"><i class="fa fa-pencil"></i></span> @lang('admin.member.edit')
                                </a>
				@else
				<a href="{{ route('admin.faremodel.edit', $faremodel->id) }}" class="btn btn-success btn-rounded btn-sm label-left b-a-0 waves-effect waves-light">
                                    <span class="btn-label"><i class="fa fa-pencil"></i></span> @lang('admin.member.edit')
                                </a>
				@endif
				
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                       <th>ID</th>
                       <th>Service Image</th>
                       <th>Service Type</th>
                       <!-- <th>Status</th> -->
                       <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection