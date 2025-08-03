@extends('provider.layout.base')

@section('title', 'Documents ')

@section('styles')
<style>
    input[type=file] {
        display: inline;
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
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">@lang('driver.driver_documents')</h4>
            </div>
        </div>
        <hr>
        <div class="row no-margin ride-detail">
            <div class="col-md-12">
                <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('admin.member.document_type')</th>
                        <th>Upload</th>
                        <th>@lang('admin.member.status')</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $Index => $document)
                    <tr>
                        <td>{{ $Index + 1 }}</td>
                        <td>{{ $document->doc_name }}</td>
                        <td>
                        <form action="{{ route('provider.upload', [$document->id]) }}" method="POST" enctype="multipart/form-data" id="form-upload">
                          {{ csrf_field() }}
                          <input type="file" name="document" accept="application/pdf, image/*" id="upload_document">
                          <button type="submit" class="btn btn-success btn-sm">Upload</button>
                        </form>
                        </td>
                        <td>@foreach($providerdocuments as $Index =>$providerdocument)
                                @if($document->id == $providerdocument->document_id) 
                                    {{ $providerdocument->status }} 
                                @endif 
                            @endforeach
                        </td>
                        <td>
                            <div class="input-group-btn">
                            @foreach($providerdocuments as $Index =>$providerdocument)
                                @if($document->id == $providerdocument->document_id) 
                                   <form action="{{ route('provider.destroy', [$providerdocument->document_id]) }}" method="POST" id="form-delete">
                                       {{ csrf_field() }}
                                       <button class="btn btn-warning btn-sm" type="delete">@lang('admin.member.delete')</button>
                                   </form>
                                @endif 
                            @endforeach
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>@lang('admin.member.document_type')</th>
                        <th>Upload</th>
                        <th>@lang('admin.member.status')</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>
    </div>
@endsection
