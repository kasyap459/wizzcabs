@extends('admin.layout.base')

@section('title', 'Dispatcher ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Pages</h4><a href="{{ route('admin.page.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light"> Add Pages</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">List page</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Title</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $index => $page)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $page->page_title }}</td>
                        
                        <td>
                        <form action="{{ route('admin.page.destroy', $page->id) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <a href="{{ route('admin.page.edit', $page->id) }}" class="btn btn-success btn-rounded label-left b-a-0 waves-effect waves-light"><span class="btn-label"><i class="fa fa-pencil"></i></span> @lang('admin.member.edit')</a>
                                <button class="btn btn-danger btn-rounded label-left b-a-0 waves-effect waves-light" onclick="return confirm('Are you sure?')"><span class="btn-label"><i class="fa fa-trash"></i></span> @lang('admin.member.delete')</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Title</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection