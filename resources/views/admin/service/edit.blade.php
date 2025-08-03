@extends('admin.layout.base')

@section('title', 'Update Service Type')
@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
<style>
.headtitle{
    /* padding-bottom: 2rem; */
}
.headtitle p{
    font-weight: bold;
    text-align: center;
}
.p_left{
    padding: 0 2px;
}
.row {
    display: block !important;
}
.vertical-center {
  -ms-transform: translateY(220px);
  transform: translateY(220px);
}
</style>
@endsection
@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
    
        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">@lang('admin.member.service_types')</h4><a href="{{ route('admin.service.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.list_service_types')</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.member.update_service')</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <form class="form-horizontal" autocomplete="off" action="{{route('admin.service.update', $service->id )}}" method="POST" enctype="multipart/form-data" role="form">
                <h5>@lang('admin.member.add_service_type')</h5>
                    {{ csrf_field() }}
                <input type="hidden" name="_method" value="PATCH">
                <div class="form-group row">
                    <label for="name" class="col-xs-12 col-form-label">@lang('admin.member.service_name')</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ $service->name }}" name="name" required id="name" placeholder="@lang('admin.member.service_name')">
                    </div>
                </div>

                 <div class="form-group row">
                    <label for="seats_available" class="col-xs-12 col-form-label">Seats Available</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{  $service->seats_available  }}" name="seats_available" required id="seats_available" placeholder="Seats Available">
                    </div>
                </div>
                
                <div class="form-group row">
                
                <label for="image" class="col-xs-12 col-form-label">@lang('admin.member.service_image')</label>
                <div class="col-xs-8">
                    @if(isset($service->image))
                    <img style="height: 120px; width: 120px; margin-bottom: 15px; border-radius:2em;" src="{{ $service->image }}">
                    @endif
                    <input type="file" accept="image/*" name="image" class="dropify form-control-file" id="image" aria-describedby="fileHelp">
                </div>
		<div class="col-xs-2 vertical-center">
                        <a href="#" data-toggle="modal" class="imginfo"><img src="/asset/img/Info.svg" alt="Image Instruction" style="width:22px;"/></a>
                </div>
                <div class="row">
                    <label for="picture" class="col-xs-12 col-form-label">@lang('admin.member.description')</label>
                    <div class="col-xs-8">
                    <textarea class="form-control" name="description" required id="description" placeholder="@lang('admin.member.description')" rows="4">{{ $service->description }}</textarea>
                    </div>
                </div> 
                <label for="description_image" class="col-xs-12 col-form-label">Description image</label>
                <div class="col-xs-8">
                    @if(isset($service->description_image))
                    <img style="height: 120px; width: 120px; margin-bottom: 15px; border-radius:2em;" src="{{ $service->description_image }}">
                    @endif
                    <input type="file" accept="image/*" name="description_image" class="dropify form-control-file" id="description_image" aria-describedby="fileHelp">
                </div>
		<div class="col-xs-2 vertical-center">
                        <a href="#" data-toggle="modal" class="imginfo"><img src="/asset/img/Info.svg" alt="Image Instruction" style="width:22px;"/></a>
                </div>
            </div>
                <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('admin.member.update_service')</button>
                <a href="{{ route('admin.service.index') }}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>   
            </form>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="waletmodal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Image Upload Condition</h4>
      </div>   
        <div class="modal-body">
            <label for="ex2">The service image size is (1024 x 1024). This size is suitable for mobile devices and all sizes of screen views. </label>
      </div>
      <!--<div class="modal-footer">
         <button type="button" data-dismiss="modal" class="close btn btn-sm justify mx-auto">Close</button>
         </div>-->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@section('scripts')
<script>
    $(document).on('click','.imginfo', function() {
        $('#waletmodal').modal("show");
        });
</script>
@endsection
