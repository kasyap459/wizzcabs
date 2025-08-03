@extends('admin.layout.base')

@section('title', 'Update Driver ')

@section('styles')
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
  <link rel="stylesheet" href="{{asset('main/vendor/select2/dist/css/select2.min.css')}}">
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">@lang('admin.member.drivers')</h4><a href="{{ route('admin.provider.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.list_drivers')</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.member.update_driver')</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <h5 style="margin-bottom: 2em;">@lang('admin.member.update_driver')</h5>

            <form class="form-horizontal" action="{{route('admin.provider.update', $provider->id )}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <input type="hidden" name="_method" value="PATCH">
                <div class="form-group row">
                    <label for="name" class="col-xs-3 col-form-label">@lang('admin.member.name')</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ $provider->name }}" name="name" required id="name" placeholder="@lang('admin.member.name')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-xs-3 col-form-label">@lang('admin.member.email')</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="email" required name="email" value="{{ $provider->email }}" id="email" placeholder="@lang('admin.member.email')">
                    </div>
                </div>
                

                 <div class="form-group row">
                    <label for="country_id" class="col-xs-3 col-form-label">Country</label>
                    <div class="col-xs-6">
                        <select name="country_id" id="country_id" class="form-control" data-plugin="select2">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->countryid }}" @if($country->countryid == $provider->country_id) selected @endif>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="gender" class="col-xs-3 col-form-label">Gender</label>
                    <div class="col-xs-6">
                        <select name="gender" id="gender" class="form-control">
                            <option value="">Select Gender</option>
                            <option value="Male"  @if($provider->gender == 'Male') selected @endif>Male</option>
                            <option value="Female" @if($provider->gender == 'Female') selected @endif>Female</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    
                    <label for="picture" class="col-xs-3 col-form-label">@lang('admin.member.picture')</label>
                    <div class="col-xs-6">
                    @if(isset($provider->avatar))
                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" 

                             src="{{$provider->avatar }}">
                    @endif
                        <input type="file" accept="image/*" name="avatar" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="mobile" class="col-xs-3 col-form-label">@lang('admin.member.mobile')</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ $provider->mobile }}" name="mobile" required id="mobile" placeholder="@lang('admin.member.mobile')">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="col-xs-3 col-form-label">Address</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ $provider->address }}" name="address" id="address" placeholder="Address">
                    </div>
                </div>
<!--                 <div class="form-group row">
                    <label for="partner_id" class="col-xs-3 col-form-label">Carrier Name</label>
                    <div class="col-xs-6">
                        <select name="partner_id" required="required" id="partner_id" class="form-control">
                            <option value="0">Select Carrier</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}" @if($partner->id == $provider->partner_id) selected @endif>{{ $partner->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="service_type_id" class="col-xs-3 col-form-label">Service Types</label>
                    <div class="col-xs-6">
                        <select name="service_type_id" id="service_type_id" required="required" class="form-control">
                            <option value="">Select Service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="allowed_service" class="col-xs-3 col-form-label">Allowed service Types</label>
                    <div class="col-xs-6">
                        <select id="allowed_service" name="allowed_service[]" required="required" class="form-control" data-plugin="select2" multiple="multiple">
                            <option value="0">All service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="language" class="col-xs-3 col-form-label">Language</label>
                    <div class="col-xs-6">
                        <select id="language" name="language[]" required="required" class="form-control" data-plugin="select2" multiple="multiple">
                            <option value="1">English</option>
                            <option value="2">Spanish</option>
                            <option value="3">French</option>
                            <option value="4">Korean</option>
                            <option value="5">Russian</option>
                            <option value="6">German</option>
                            <option value="7">Portuguese</option>
                            <option value="8">Italian</option>
                            <option value="9">Urdu</option>
                            <option value="10">Chinese</option>
                            <option value="11">Tagalog</option>
                            <option value="12">Vietnamese</option>
                        </select>
                    </div>
                </div>
 -->                <div class="form-group row">
                    <label for="acc_no" class="col-xs-3 col-form-label">Bank account number</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ $provider->acc_no }}" name="acc_no" id="acc_no" placeholder="Bank account number">
                    </div>
                </div>
<!--                 <div class="form-group row">
                    <label for="license_no" class="col-xs-3 col-form-label">License number</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ $provider->license_no }}" name="license_no" required id="license_no" placeholder="License number">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="license_expire" class="col-xs-3 col-form-label">License expire date</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ $provider->license_expire }}" name="license_expire" required id="license_expire" placeholder="License expire date">
                    </div>
                </div>
 --><!--                 <div class="form-group row">
                    <label for="custom_field1" class="col-xs-3 col-form-label">Custom field 1</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ $provider->custom_field1 }}" name="custom_field1" id="custom_field1" placeholder="Custom field 1">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="custom_field2" class="col-xs-3 col-form-label">Custom field 2</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ $provider->custom_field2 }}" name="custom_field2" id="custom_field2" placeholder="Custom field 2">
                    </div>
                </div>
 -->                <div class="form-group row">
                    <label for="zipcode" class="col-xs-3 col-form-label"></label>
                    <div class="col-xs-8">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('admin.member.update_driver')</button>
                        <a href="{{route('admin.provider.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="{{asset('main/vendor/select2/dist/js/select2.min.js')}}"></script>
<script type="text/javascript">
    var mindate = {!! json_encode( \Carbon\Carbon::today()->format('Y-m-d\TH:i') ) !!}
    $('#license_expire').datetimepicker({
        format:'Y-m-d',
        timepicker: false,
        minDate: mindate
    });

    $('[data-plugin="select2"]').select2($(this).attr('data-options'));
    $('#allowed_service').val([{{ $provider->allowed_service }}]).trigger('change');
    $('#language').val([{{ $provider->language }}]).trigger('change');
   
</script>
@endsection