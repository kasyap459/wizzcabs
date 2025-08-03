@extends('admin.layout.base')

@section('title', 'Documents ')

@section('styles')
<style type="text/css">
    textarea{
        height: auto !important;
    }
    .form-inline {
    	display: block !important;
    }
    form {
    	margin-bottom: 0px !important;
    }

</style>
@endsection

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">

            <div class="row bg-title">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h4 class="page-title">@lang('admin.member.documents')</h4>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                        <li class="active">@lang('admin.member.documents')</li>
                    </ol>
                </div>
            </div>

            <div class="box box-block bg-white">
                <h5>Carrier Document list</h5>
                <form class="form-horizontal" action="{{ route('admin.document.carrierstore')}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <div class="row">
                <div class="col-md-12">  
                    <div class="form-group row">
                        <label for="doc_name" class="col-xs-12 col-form-label">Document name</label>
                        <div class="col-xs-8">
                            <input class="form-control" type="text" value="{{ old('doc_name') }}" name="doc_name" required id="doc_name" placeholder="Document name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-xs-12 col-form-label">Description</label>
                        <div class="col-xs-8">
                            <textarea class="form-control" name="description" id="description" rows="10">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i>Add document</button>
                            <a href="{{route('admin.document.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
                        </div>
                    </div>
                </div>
                </div>
                </form>
                <table class="table table-striped table-bordered dataTable" id="carrierdoc">
                    <thead>
                        <tr>
                            <th>@lang('admin.member.id')</th>
                            <th>@lang('admin.member.document_name')</th>
                            <th>@lang('admin.member.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($carrierlists as $index => $carrierlist)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$carrierlist->doc_name}}</td>
                            <td>
                                <form action="{{ route('admin.document.carrierdestroy', $carrierlist->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <a href="#" id="carriermodal" data-id="{{ $carrierlist->id }}" data-doc="{{ $carrierlist->doc_name }}" data-desc="{{ $carrierlist->description }}" class="btn btn-sm btn-success btn-rounded label-left b-a-0 waves-effect waves-light"><span class="btn-label"><i class="fa fa-pencil"></i></span> @lang('admin.member.edit')</a>
                                    <button class="btn btn-danger btn-sm btn-rounded label-left b-a-0 waves-effect waves-light" onclick="return confirm('Are you sure?')"><span class="btn-label"><i class="fa fa-trash"></i></span> @lang('admin.member.delete')</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>@lang('admin.member.id')</th>
                            <th>@lang('admin.member.document_name')</th>
                            <th>@lang('admin.member.action')</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

<div id="carriermodalbox" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Document</h4>
      </div>
      <div class="edit-body">
            <form class="form-horizontal" action="{{ route('admin.document.carrierupdate') }}" method="POST" enctype="multipart/form-data" role="form">
                <div class="modal-body edit-body">
                {{csrf_field()}}
                <div class="form-group row">
                    <div class="col-xs-10">
                        <input type="hidden" name="model_carrier_id" id="model_carrier_id">
                        <input class="form-control" type="text" name="modal_carrier" required id="modal_carrier" placeholder="name" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-10">
                        <label for="description" class="col-xs-12 col-form-label">Description</label>
                        <textarea class="form-control" name="modal_carrier_desc" id="modal_carrier_desc" rows="10"></textarea>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.triplist.close')</button>
                </div>
            </form>
      </div>
    </div>
  </div>
</div>


@endsection

@section('scripts')
<script>
        $('#carrierdoc').DataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "bAutoWidth": false 
        });
        
    $(document).on('click','#carriermodal', function() {
        var id = $(this).attr("data-id");
        var doc = $(this).attr("data-doc");
        var description = $(this).attr("data-desc");
        $("#carriermodalbox").modal("toggle");
        $("#model_carrier_id").val(id);
        $("#modal_carrier").val(doc);
        $("#modal_carrier_desc").val(description);
    });
</script>
@endsection