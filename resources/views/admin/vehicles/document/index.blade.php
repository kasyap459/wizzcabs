@extends('admin.layout.base')

@section('title', 'Vehicle Documents ')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
<style>
    .viewtable tr{
        margin: 10px 5px;
        display: block;
    }
    .viewtable td:first-child{
        width: 165px;
    }
    .form-check-input {
        margin-left: 0px;
    }
    .margin-left{
        margin-left: 150px;
    }
    form{
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">{{ $vehicle->vehicle_name }} Documents</h4><a href="{{ route('admin.vehicle.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">List Vehicle</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Documents</li>
                </ol>
            </div>
        </div>
        
        <div class="box box-block bg-white">
            <h5 class="mb-1">@lang('admin.member.driver_documents')</h5>
            <table class="table table-striped table-bordered">
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
                        <form action="{{ route('admin.vehicle.document.upload', [$vehicle->id, $document->id]) }}" method="POST" enctype="multipart/form-data" id="form-upload">
                          {{ csrf_field() }}
                          <input type="file" name="document" accept="application/pdf, image/*" id="upload_document">
                          <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                        </form>
                        </td>
                        <td>@foreach($vehicledocuments as $Index =>$vehicledocument)
                                @if($document->id == $vehicledocument->document_id) 
                                    {{ $vehicledocument->status }} 
                                @endif 
                            @endforeach
                        </td>
                        <td>
                            <div class="input-group-btn">
                            @foreach($vehicledocuments as $Index =>$vehicledocument)
                                @if($document->id == $vehicledocument->document_id) 
                                   <a href="{{ route('admin.vehicle.document.edit', [$vehicle->id, $vehicledocument->id]) }}" class="btn btn-sm btn-info btn-rounded b-a-0 waves-effect waves-light">@lang('admin.member.view')</a>
                                   <form action="{{ route('admin.vehicle.document.destroy', [$vehicle->id, $vehicledocument->document_id]) }}" method="POST" id="form-delete">
                                       {{ csrf_field() }}
                                       {{ method_field('DELETE') }}
                                       <button class="btn btn-danger btn-sm btn-rounded b-a-0 waves-effect waves-light" type="delete">@lang('admin.member.delete')</button>
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
