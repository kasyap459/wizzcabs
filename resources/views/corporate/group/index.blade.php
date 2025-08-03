@extends('corporate.layout.base')

@section('title', 'List Group ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Groups</h4><a href="{{ route('corporate.group.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">Add Groups</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('corporate.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">List Groups</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Group Name</th>
                        <th>Payment Mode</th>
                       <!--  <th>Ride Service Type</th>
                       <th>Days</th> -->
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach($Groups as $index => $Group)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $Group->group_name }}</td>
                        <td>{{ $Group->payment_mode }}</td>
                        <!-- <td>@if(!empty($Group->ride_service_type))
                                @foreach($Group->ride_service_type as $ride_service)
                                    {{ service_name($ride_service) }},
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if(!empty($Group->allowed_days))
                                @foreach($Group->allowed_days as $allowed_days)
                                    {{ $allowed_days }},
                                @endforeach
                            @else
                                -
                            @endif
                        </td> -->
                        <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-info btn-sm waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                @lang('admin.member.action')
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('corporate.group.edit', $Group->id) }}" class="dropdown-item">
                                    <i class="fa fa-pencil-square-o"></i> @lang('admin.member.edit')
                                </a>
                                <form action="{{ route('corporate.group.destroy', [$Group->id]) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="dropdown-item" onclick="return confirm('Are you sure?')"> <i class="fa fa-trash"></i>  @lang('admin.member.delete')</button>
                                </form>
                            </div>
                        </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Group Name</th>
                        <th>Payment Mode</th>
                        <!-- <th>Ride Service Type</th>
                        <th>Days</th> -->
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection