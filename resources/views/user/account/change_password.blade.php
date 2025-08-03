@extends('user.layout.base')

@section('title', 'Profile ')

@section('styles')
<style type="text/css">
    .form-control {
        margin-bottom: 10px;
    }
    label{
        padding-top: 10px;
    }
</style>
@endsection

@section('content')
    
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(Session::has('flash_error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ Session::get('flash_error') }}
        </div>
    @endif


    @if(Session::has('flash_success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ Session::get('flash_success') }}
        </div>
    @endif

    <div class="row no-margin">
        <div class="col-md-12">
            <h4 class="page-title">@lang('user.profile.change_password')</h4> 
        </div>
    </div>
    <hr>
    <form action="{{url('change/password')}}" method="post">
            {{ csrf_field() }}
        <div class="form-group">
            <label class="col-md-4">@lang('user.profile.old_password')</label>
            <div class="col-md-6">
                <input type="password" name="old_password" class="form-control" placeholder="@lang('user.profile.old_password')">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4">@lang('user.profile.password')</label>
            <div class="col-md-6">
                <input type="password" name="password" class="form-control" placeholder="@lang('user.profile.password')">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4">@lang('user.profile.confirm_password')</label>
            <div class="col-md-6">
                <input type="password" name="password_confirmation" class="form-control" placeholder="@lang('user.profile.confirm_password')">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4"></label>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">@lang('user.profile.change_password')</button>
            </div>
        </div>

    </form>

@endsection