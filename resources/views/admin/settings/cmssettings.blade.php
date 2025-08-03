@extends('admin.layout.base')

@section('title', 'CMS Settings ')

@section('styles')
<style type="text/css">
	.display {
		display: none;
	}
</style>
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="page-title">CMS Settings</h4>
            </div>
           <!--  <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
               <ol class="breadcrumb">
                   <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                   <li class="active">@lang('admin.member.site_settings')</li>
               </ol>
           </div> -->
        </div>

    	<div class="box box-block bg-white">
			<!-- <h5>@lang('admin.member.site_settings')</h5> -->

            <form class="form-horizontal" action="{{ route('admin.cms-settings.store') }}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Stop title</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ Setting::get('stop_title','Please keep stops to 3 minutes or less')  }}" name="stop_title" id="stop_title" placeholder="stop title" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Stop Description</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ Setting::get('stop_description','As a courtesy for your driver\'s time,Please limit each stop to 3 minutes or less,otherwise your fare may change')  }}" name="stop_description" id="stop_description" placeholder="stop description" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Payment Description</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ Setting::get('payment_description','Total fare may change due in case of any route or destination changes of if your ride takes longer due to traffic or other factors')  }}" name="payment_description" id="payment_description" placeholder="payment description" >
                    </div>
                </div>
		<div class="form-group row">
			<label for="zipcode" class="col-xs-2 col-form-label"></label>
			<div class="col-xs-8">
				<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Update CMS Settings</button>
			</div>
		</div>
	     </form>
	</div>
    </div>
</div>
@endsection

@section('scripts')
@endsection