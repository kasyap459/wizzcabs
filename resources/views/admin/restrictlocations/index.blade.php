@extends('admin.layout.base')

@section('title', 'Restricted Area ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Restricted Area List</h4><a href="{{ route('admin.restrict-location.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">Add Restricted Area</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">List Restricted Area</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Location name</th>
                        <th>Restrict Area</th>
                        <th>Starting Time</th>
                        <th>Ending Time</th>
                        <th>Status</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($restricts as $index => $restrict)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($restrict->location)
                                {{ $restrict->location->location_name }}
                            @else
                                -
                            @endif
                        </td>
                        <td>@if($restrict->restrict_area ==1)
                                All
                            @elseif($restrict->restrict_area ==2)
                                Pick up
                            @else
                                Drop off
                            @endif
                        </td>
                        <td>{{ $restrict->s_time }}</td>
                        <td>{{ $restrict->e_time }}</td>
                        <td>@if($restrict->status ==1)
                                <a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.restrict-location.inactive', $restrict->id) }}">Active</a>
                            @else
                                <a class="btn btn-danger btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.restrict-location.active', $restrict->id) }}">Inactive</a>
                            @endif
                        </td>
                        <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-info btn-sm waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                @lang('admin.member.action')
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('admin.restrict-location.edit', $restrict->id) }}" class="dropdown-item">
                                    <i class="fa fa-pencil-square-o"></i> @lang('admin.member.edit')
                                </a>
                                <form action="{{ route('admin.restrict-location.destroy', $restrict->id) }}" method="POST">
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
                        <th>Location name</th>
                        <th>Restrict Area</th>
                        <th>Starting Time</th>
                        <th>Ending Time</th>
                        <th>Status</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection