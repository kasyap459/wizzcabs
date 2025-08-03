@extends('admin.layout.base')

@section('title', 'User Notes')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">User Notes</h4><a href="{{ route('admin.user-note.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">Add User Notes</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">User Notes</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>User Note</th>
                        @if(Auth::guard('admin')->user()->admin_type ==0)
                        <th>Status</th>
                        <th>@lang('admin.member.action')</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($usernotes as $index => $usernote)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $usernote->notes }}</td>
                        @if(Auth::guard('admin')->user()->admin_type ==0)
                        <td>
                            @if($usernote->status ==1)
                                <a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.user-note.inactive', $usernote->id) }}">Active</a>
                            @else
                                <a class="btn btn-danger btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.user-note.active', $usernote->id) }}">Inactive</a>
                            @endif
                        </td>
                        <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-info btn-sm waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                @lang('admin.member.action')
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('admin.user-note.edit', $usernote->id) }}" class="dropdown-item">
                                    <i class="fa fa-pencil-square-o"></i> @lang('admin.member.edit')
                                </a>
                                <form action="{{ route('admin.user-note.destroy', $usernote->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="dropdown-item" onclick="return confirm('Are you sure?')"> <i class="fa fa-trash"></i>  @lang('admin.member.delete')</button>
                                </form>
                            </div>
                        </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.mobile')</th>
                        @if(Auth::guard('admin')->user()->admin_type ==0)
                        <th>Status</th>
                        <th>@lang('admin.member.action')</th>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection