@extends('admin.layout.base')

@section('title', 'Sub-company ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Sub-company</h4><a href="{{ route('admin.partner.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">Add Sub-company</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">List Sub-company</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.full_name')</th>
                        <th>Sub-company name</th>
                        <th>@lang('admin.member.email')</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>Status</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($partners as $index => $partner)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $partner->name }}</td>
                        <td>{{ $partner->carrier_name}}</td>
                        <td>{{ $partner->email }}</td>
                        <td>{{ $partner->dial_code }} {{ $partner->mobile }}</td>
                        <td>@if($partner->status ==1)
                                <a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.partner.inactive', $partner->id) }}">Active</a>
                            @else
                                <a class="btn btn-danger btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.partner.active', $partner->id) }}">Inactive</a>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info btn-sm waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    @lang('admin.member.action')
                                </button>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">
                                        <i class="fa fa-files-o"></i> @lang('admin.member.view')
                                    </a>
                                    <a href="{{ route('admin.partner.edit', $partner->id) }}" class="dropdown-item">
                                        <i class="fa fa-pencil-square-o"></i> @lang('admin.member.edit')
                                    </a>
                                    <a href="{{ route('admin.partner.document.index', $partner->id ) }}" class="dropdown-item">
                                        <i class="fa fa-cloud-upload"></i> Documents
                                    </a>
                                    <form action="{{ route('admin.partner.destroy', $partner->id) }}" method="POST">
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
                        <th>Sub-company name</th>
                        <th>@lang('admin.member.email')</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>Status</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection