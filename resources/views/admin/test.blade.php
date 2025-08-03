@extends('admin.layout.base')

@section('title', ' ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">

            <div class="row bg-title">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h4 class="page-title">{{ $title }}</h4>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    
                    <ol class="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    </ol>
                </div>
            </div>
            <div class="box box-block bg-white">
               <h6 class="no-result">In progress</h6>
            </div>
        </div>
    </div>
@endsection