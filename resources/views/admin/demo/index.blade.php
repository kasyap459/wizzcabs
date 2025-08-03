@extends('admin.layout.base')

@section('title', 'Demo ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Demo Accounts</h4><a href="{{ route('admin.demo.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">Create demo</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">List Demo</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.full_name')</th>
                        <th>@lang('admin.member.email')</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>Password</th>
                        <th>Seller Email ID</th>
                        <th>Status</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($demos as $index => $demo)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $demo->name }}</td>
                        <td>{{ $demo->email }}</td>
                        <td>{{ $demo->phone }}</td>
                        <td>{{ $demo->password }}</td>
                        <td>{{ $demo->seller_email }}</td>
                        <td>
                            @if(Carbon\Carbon::now() > $demo->expires_at)
                                <a class="btn btn-danger btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.demo.renue', $demo->id) }}">Expired</a>
                            @else
                                <a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.demo.expire', $demo->id) }}">Active</a>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.demo.destroy', $demo->id) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <a href="{{ route('admin.demo.show', $demo->id) }}" class="btn btn-success"> View</a>
                                <button class="btn btn-danger" onclick="return confirm('Are you sure?')">@lang('admin.member.delete')</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.full_name')</th>
                        <th>@lang('admin.member.email')</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>Password</th>
                        <th>Seller Email ID</th>
                        <th>Status</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection