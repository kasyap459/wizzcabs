@extends('admin.layout.base')

@section('title', 'Scheduled Rides ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">

            <div class="row bg-title">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h4 class="page-title">@lang('admin.scheduled_rides')</h4>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    
                    <ol class="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                        <li class="active">@lang('admin.scheduled_rides')</li>
                    </ol>
                </div>
            </div>
            <div class="box box-block bg-white">
                @if(count($requests) != 0)
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>@lang('admin.member.id')</th>
                            <th>@lang('admin.member.booking_id')</th>
                            <th>@lang('admin.member.user_name')</th>
                            <th>@lang('admin.member.driver_name')</th>
                            <th>@lang('admin.member.date_time')</th>
                            <th>@lang('admin.member.status')</th>
                            <th>@lang('admin.member.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($requests as $index => $request)
                        <tr>
                            <td>{{$index + 1}}</td>

                            <td>{{$request->id}}</td>
                            <td>{{$request->user_name}}</td>
                            <td>
                                @if($request->provider)
                                    {{$request->provider->name}}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $request->schedule_at }}</td>
                            <td>
                                {{$request->status}}
                            </td>
                            <td>
                                <div class="input-group-btn">
                                  <button type="button" class="btn btn-info waves-effect waves-light dropdown-toggle" data-toggle="dropdown">@lang('admin.member.action')
                                    <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-default"><i class="fa fa-search"></i> @lang('admin.member.more_details')</a>
                                    </li>
                                  </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>@lang('admin.member.id')</th>
                            <th>@lang('admin.member.booking_id')</th>
                            <th>@lang('admin.member.user_name')</th>
                            <th>@lang('admin.member.driver_name')</th>
                            <th>@lang('admin.member.date_time')</th>
                            <th>@lang('admin.member.status')</th>
                            <th>@lang('admin.member.action')</th>
                        </tr>
                    </tfoot>
                </table>
                @else
                    <h6 class="no-result">No results found</h6>
                @endif 
            </div>
            
        </div>
    </div>
@endsection