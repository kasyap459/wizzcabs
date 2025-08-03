@extends('admin.layout.base')

@section('title', 'Service Types ')

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
                <h4 class="page-title">@lang('admin.member.service_types')</h4><a href="{{ route('admin.service.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.member.add_new_service')</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.list_service_types')</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.service_name')</th>
                        <th>@lang('admin.member.service_image')</th>
                        <th>@lang('admin.member.action')</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($services as $index => $service)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $service->name }}</td>
                        <td>
                            @if($service->image) 
                                <img src="{{$service->image}}" style="height: 50px" >
                            @else
                                N/A
                            @endif
                        </td>
                         <td>@if($service->status ==1)
			            	<a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.service.inactive', $service->id) }}">Active</a>
			               @else
			            	<a class="btn btn-danger btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.service.active', $service->id) }}">Inactive</a>        	
			             @endif
                       </td>
                        <td>
                            <form action="{{ route('admin.service.destroy', $service->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
				@if($admin_type == 1)
                                <a href="#" class="btn btn-success btn-rounded btn-sm label-left b-a-0 waves-effect waves-light @if($admin_type == 1) disabled not-allowed @endif ">
                                    <span class="btn-label"><i class="fa fa-pencil"></i></span> @lang('admin.member.edit')
                                </a>
				@else
					<a href="{{ route('admin.service.edit', $service->id) }}" class="btn btn-success btn-rounded btn-sm label-left b-a-0 waves-effect waves-light @if($admin_type == 1) disabled not-allowed @endif ">
                                    <span class="btn-label"><i class="fa fa-pencil"></i></span> @lang('admin.member.edit')
                                </a>
				@endif
				@if($admin_type == 0) 
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
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.service_name')</th>
                        <th>@lang('admin.member.service_image')</th>
                        <th>@lang('admin.member.action')</th>
                        <th>Status</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
	$( document ).ready(function() {
   		console.log( "ready!" );
		//$('.not-allowed').attr("href", "#");
	});
</script>
@endsection