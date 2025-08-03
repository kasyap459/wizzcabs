@extends('admin.layout.base')

@section('title', 'Account Manager ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">@lang('admin.member.account_manager')</h4><a href="{{ route('admin.account-manager.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.member.add_new_account_manager')</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.list_account_managers')</li>
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
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $index => $account)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $account->name }}</td>
                        <td>{{ $account->email }}</td>
                        <td>{{ $account->mobile }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info btn-sm waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    @lang('admin.member.action')
                                </button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('admin.account-manager.edit', $account->id) }}" class="dropdown-item">
                                        <i class="fa fa-pencil-square-o"></i> @lang('admin.member.edit')
                                    </a>
                                    <form action="{{ route('admin.account-manager.destroy', $account->id) }}" method="POST">
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
                        <th>@lang('admin.member.full_name')</th>
                        <th>@lang('admin.member.email')</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection