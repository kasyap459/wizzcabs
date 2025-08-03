@extends('corporate.layout.base')

@section('title', 'Employee ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Employees</h4><a href="{{ route('corporate.user.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">Add Employee</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('corporate.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">List Employee</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Employee Name</th>
                        <th>Email ID</th>
                        <th>Group name</th>
                        <th>Employee Code</th>
                        <th>Employee mobile number</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($Users as $index => $User)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $User->emp_name }}</td>
                        <td>{{ $User->emp_email }}</td>
                        <td>{{ $User->corporate_group->group_name }}</td>
                        <td>{{ $User->emp_code }}</td>
                        <td>{{ $User->emp_phone }}</td>
                        <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-info btn-sm waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                @lang('admin.member.action')
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('corporate.user.edit', $User->id) }}" class="dropdown-item">
                                    <i class="fa fa-pencil-square-o"></i> @lang('admin.member.edit')
                                </a>
                                <form action="{{ route('corporate.user.destroy', $User->id) }}" method="POST">
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
                        <th>Employee Name</th>
                        <th>Email ID</th>
                        <th>Group name</th>
                        <th>Employee Code</th>
                        <th>Employee mobile number</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection