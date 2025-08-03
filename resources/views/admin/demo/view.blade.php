@extends('admin.layout.base')

@section('title', 'Demo Credentials ')

@section('styles')
<style>
    h3 {
        line-height: 30px;
        font-size: 21px;
        color: #2b2b2b;
        font-weight: normal;
    }
    b {
      font-weight: 600;
    }
    h4 {
        line-height: 22px;
        font-size: 18px;
    }
    .black{
        color: #000 !important;
    }
    .text-muted {
        color: #8d9ea7 !important;
    }
    .m-l-30 {
        margin-left: 30px!important;
    }
    .text-right {
        text-align: right!important;
    }
    .border-none{
        border: none !important;
    }
    .white-box p{
        margin-bottom: 5px;
    }
</style>
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Demo</h4>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Demo credentials</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box printableArea">
                        <h4>Account Credentials</h4>
                        <hr>
                        <p><b>Hi {{ $demo->name }},</b></p>
                     <p>We are delighted to have you as a member of our Application. If you have any questions please contact at <a href="mailto:{{ Setting::get('contact_email', 'info@unicotaxi.com') }}" style="color: rgb(42, 132, 166); text-decoration: none">{{ config('app.email', 'info@unicotaxi.com') }}</a></p><br>

                      <p><b>Admin  Login Details :</b></p>
                      <p><b>Link:</b> <a href="https://demo.unicotaxi.com/admin/login">https://demo.unicotaxi.com/admin/login</a></p> 
                      <p><b>Email:</b> {{ $demo->email }}</p>
                      <p><b>Password:</b> ​{{ $demo->password }}</p><br>
                      
                      <p><b>Dispatcher Login Details :</b></p>
                      <p><b>Link:</b> <a href="https://demo.unicotaxi.com/dispatcher/login">https://demo.unicotaxi.com/dispatcher/login</a></p> 
                      <p><b>Email:</b> {{ $demo->email }}</p>
                      <p><b>Password:</b> ​{{ $demo->password }}</p><br>

                      <p><b>Corporate Login Details :</b></p>
                      <p><b>Link:</b> <a href="https://demo.unicotaxi.com/corporate/login"> https://demo.unicotaxi.com/corporate/login</a></p>
                      <p><b>Email:</b> {{ $demo->email }}</p>
                      <p><b>Password:</b> ​{{ $demo->password }}</p><br>

                      <p><b>Sub Company Login Details :</b></p>
                      <p><b>Link:</b> <a href="https://demo.unicotaxi.com/partner/login">https://demo.unicotaxi.com/partner/login</a></p>
                      <p><b>Email:</b> {{ $demo->email }}</p>
                      <p><b>Password:</b> ​{{ $demo->password }}</p><br>

                      <p><b>Hotel Login Details :</b></p>
                      <p><b>Link:</b> <a href="https://demo.unicotaxi.com/hotel/login">https://demo.unicotaxi.com/hotel/login</a></p>
                      <p><b>Email:</b> {{ $demo->email }}</p>
                      <p><b>Password:</b> ​{{ $demo->password }}</p><br>

                      <p><b>Customercare Login Details :</b></p>
                      <p><b>Link:</b> <a href="https://demo.unicotaxi.com/customercare/login">https://demo.unicotaxi.com/customercare/login</a></p>
                      <p><b>Email:</b> {{ $demo->email }}</p>
                      <p><b>Password:</b> ​{{ $demo->password }}</p><br>

                      <p><b>Account Login Details :</b></p>
                      <p><b>Link:</b> <a href="https://demo.unicotaxi.com/account/login">https://demo.unicotaxi.com/account/login</a></p>
                      <p><b>Email:</b> {{ $demo->email }}</p>
                      <p><b>Password:</b> ​{{ $demo->password }}</p><br>

                      <p><b>Passenger Login Details :</b></p>
                      <p><b>Link:</b> <a href="https://demo.unicotaxi.com/login">https://demo.unicotaxi.com/login</a></p>
                      <p><b>Email:</b> {{ $demo->email }}</p>
                      <p><b>Password:</b> ​{{ $demo->password }}</p><br>

                      <p><b>Driver Login Details :</b></p>
                      <p><b>Link:</b> <a href="https://demo.unicotaxi.com/provider/login">https://demo.unicotaxi.com/provider/login</a></p>
                      <p><b>Email:</b> {{ $demo->email }}</p>
                      <p><b>Password:</b> ​{{ $demo->password }}</p><br>

                      <p><b>Location & Time Zone :</b></p>
                      <p><b>Location:</b> {{ $country->name }}</p>
                      <p><b>Time Zone:</b> {{ $demo->timezone }}</p><br>
                      <p><b>Date:</b> {{date("Y-m-d h:i A", strtotime($demo->created_at))}}</p><br>

                      <p>Thanks,</p>
                      <p>{{ Setting::get('site_title', 'Unicotaxi') }} </p>
                      
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="clearfix"></div>
                    <hr>
                    <div class="text-right">
                        <button id="print" class="btn btn-default btn-outline" type="button"> <span><i class="fa fa-print"></i> Print</span> </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{asset('main/assets/js/jquery.PrintArea.js')}}" type="text/JavaScript"></script>
<script>
$(document).ready(function() {
    $("#print").click(function() {
        var mode = 'iframe'; //popup
        var close = mode == "popup";
        var options = {
            mode: mode,
            popClose: close
        };
        $("div.printableArea").printArea(options);
    });
});
</script>
@endsection