@extends('admin.layout.base')

@section('title', 'Pages ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Pages</h4><a href="{{ route('admin.page.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">List Page</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Add Page</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <h5>Create Page</h5>
            <div className="row">
                <form action="{{ route('admin.page.store') }}" method="POST">

                    <div class="form-group row">
                    <label for="page_title" class="col-xs-12 col-form-label">Page Title</label>
                    <div class="col-xs-12">
                        <input class="form-control" type="text" value="{{ old('page_title') }}" name="page_title" required id="page_title" placeholder="Page Title">
                    </div>
                    </div>

                    {{ csrf_field() }}
                    <div class="form-group row">
                    <label for="page_title" class="col-xs-12 col-form-label">Content</label>
                    <div class="col-xs-12">
                        
                            <textarea name="content" id="myeditor">{{ Setting::get('page_privacy') }}</textarea>
                        
                    </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label for="zipcode" class="col-xs-12 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('myeditor');
    CKEDITOR.replace('termeditor');
</script>
@endsection