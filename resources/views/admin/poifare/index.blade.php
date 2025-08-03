@extends('admin.layout.base')

@section('title', 'Airport transfer Fare ')
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
                <h4 class="page-title">Airport transfer Fare</h4><a href="{{ route('admin.poifare.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">Add Fare</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">List Fare</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                       <th>ID</th>
                       <th>Source Address</th>
                       <th>Destination Address</th>
                       <th>Service Type</th>
                       <th>Reverse</th>
                       <th>Status</th>
                       <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($poifares as $index => $poifare)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($poifare->location_source)
                                {{ $poifare->location_source->location_name }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($poifare->location_dest)
                                {{ $poifare->location_dest->location_name }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $poifare->service_type->name }}</td>
                        <td>@if($poifare->reverse_loc ==0)
                                No
                            @else
                                Yes
                            @endif
                        </td>
                        <td>@if($poifare->status ==1)
				@if(auth()->user()->admin_type == 1)
                                <a class="btn btn-success btn-rounded btn-sm waves-effect waves-light disabled not-allowed" href="#">Active</a>
                    		@else
				<a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.poifare.inactive', $poifare->id) }}">Active</a>
				@endif 
                            @else
				@if(auth()->user()->admin_type == 1)
                                <a class="btn btn-danger btn-rounded btn-sm waves-effect waves-light disabled not-allowed" href="#">Inactive</a>
                            	@else
				<a class="btn btn-danger btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.poifare.active', $poifare->id) }}">Inactive</a>
				@endif
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.poifare.destroy', $poifare->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                @if(auth()->user()->admin_type == 1)
                                <a href="#" class="btn btn-success btn-rounded btn-sm label-left b-a-0 waves-effect waves-light disabled not-allowed">
                                    <span class="btn-label"><i class="fa fa-pencil"></i></span> @lang('admin.member.edit')
                                </a>
				@else
				<a href="{{ route('admin.poifare.edit', $poifare->id) }}" class="btn btn-success btn-rounded btn-sm label-left b-a-0 waves-effect waves-light">
                                    <span class="btn-label"><i class="fa fa-pencil"></i></span> @lang('admin.member.edit')
                                </a>
				@endif
				@if(auth()->user()->admin_type == 0) 
                                <button class="btn btn-danger btn-sm btn-rounded label-left b-a-0 waves-effect waves-light" onclick="return confirm('Are you sure?')">
                                    <span class="btn-label"><i class="fa fa-trash"></i></span>  @lang('admin.member.delete')
                                </button>
				@endif
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                       <th>ID</th>
                       <th>Source Address</th>
                       <th>Destination Address</th>
                       <th>Service Type</th>
                       <th>Reverse</th>
                       <th>Status</th>
                       <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection