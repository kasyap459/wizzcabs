@extends('partner.layout.base')

@section('title', 'Driver Documents ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">@lang('admin.documents'): {{ $Document->document->name }}</h4>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('partner.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.documents')</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <h5 class="mb-1">@lang('admin.member.driver_name'): {{ $Document->provider->name }}</h5>
            <embed src="{{ asset('storage/'.$Document->url) }}" width="70%" height="70%" />

            <div class="row">
                <div class="col-xs-2">
                    <form action="{{ route('partner.provider.document.update', [$Document->provider->id, $Document->document_id]) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <button class="btn btn-success btn-rounded label-left b-a-0 waves-effect waves-light" type="submit"><span class="btn-label"><i class="fa fa-check"></i></span>@lang('admin.member.approve')</button>
                    </form>
                </div>

                <div class="col-xs-3">
                    <form action="{{ route('partner.provider.document.destroy', [$Document->provider->id, $Document->document_id]) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button class="btn btn-danger btn-rounded label-left b-a-0 waves-effect waves-light" type="submit"><span class="btn-label"><i class="fa fa-trash"></i></span>@lang('admin.member.delete')</button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection