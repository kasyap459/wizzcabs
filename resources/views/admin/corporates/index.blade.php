@extends('admin.layout.base')

@section('title', 'Corporate ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Corporate</h4><a href="{{ route('admin.corporate.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">Add Corporate</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">List Corporate</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Legal name</th>
                        <th>Display name</th>
                        <th>@lang('admin.member.email')</th>
                        <th>Secondary email</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>PAN number</th>
                        <th>Status</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($corporates as $index => $corporate)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $corporate->legal_name }}</td>
                        <td>{{ $corporate->display_name}}</td>
                        <td>{{ $corporate->email }}</td>
                        <td>{{ $corporate->secondary_email }}</td>
                        <td>{{ $corporate->dial_code }} {{ $corporate->mobile }}</td>
                        <td>{{ $corporate->pan_no}}</td>
                        <td>@if($corporate->status ==1)
                                <a class="btn btn-success btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.corporate.inactive', $corporate->id) }}">Active</a>
                            @else
                                <a class="btn btn-danger btn-rounded btn-sm waves-effect waves-light" href="{{ route('admin.corporate.active', $corporate->id) }}">Inactive</a>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info btn-sm waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    @lang('admin.member.action')
                                </button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('admin.corporate.edit', $corporate->id) }}" class="dropdown-item">
                                        <i class="fa fa-pencil-square-o"></i> @lang('admin.member.edit')
                                    </a>
                                    <a href="{{ route('admin.corporate.document.index', $corporate->id ) }}" class="dropdown-item">
                                        <i class="fa fa-cloud-upload"></i> Documents
                                    </a>
                                    <form action="{{ route('admin.corporate.destroy', $corporate->id) }}" method="POST">
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
                        <th>Legal name</th>
                        <th>Display name</th>
                        <th>@lang('admin.member.email')</th>
                        <th>Secondary email</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>PAN number</th>
                        <th>Status</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection