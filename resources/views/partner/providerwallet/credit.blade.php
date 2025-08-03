@extends('partner.layout.base')

@section('title', $page)

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
<style>
span.bmd-form-group.is-filled {
    padding-right: 5%;
}
.form-control:disabled, .form-control[readonly] {
    background-color: #fff !important;
    opacity: 1;
}
</style>
@endsection

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">

            <div class="row bg-title">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                    <h4 class="page-title">{{$page}}</h4>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('partner.dashboard') }}">@lang('admin.dashboard')</a></li>
                        <li class="active">@lang('admin.driver_statement')</li>
                    </ol>
                </div>
            </div>
            <div class="box box-block bg-white">
        <div class="text-center">
                <h3>Total Balance</h3>
                    <img class="avatar mt-2" src="{{asset('asset/img/yourearnings.png')}}" style="width:20%;">
                <h3 class="mt-0">{{ Setting::get('currency') }}<span class="revenue"> {{$wallet}}</span></h3>
                    <a href="#" class="btn btn-outline-info btn-rounded w-min-sm m-l-0-75 waves-effect waves-light bg-info" style="width:20%;">Cashout</a>
                    <hr class="ml-5 mr-5 pl-5 pr-5 mt-5">
            <div class="text-left row" style="margin-left:42%;">
            <form class="" action="{{route('partner.providerwallet.debited')}}" method="POST" enctype="multipart/form-data" role="form">
            {{csrf_field()}}
                <div class="form-group row">
                    <div class="col-xs-9">
                        <input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="debit_amount" id="debit_amount" class="form-control" placeholder="Debit Amount" required>
                    </div>
                <input type="hidden" name="providerid" id="providerid" value="{{ $providerid }}">
                </div>
                </div>
                <button type="submit" id="submit" class="btn btn-rounded w-min-sm m-l-0-75 waves-effect waves-light btn-danger bg-danger"  style="width:20%;">Debit Amount</button>
            </div>
        </form>
            <div class="text-left row" style="margin-left:30%;">
                <div class="col-md-6">
                    <p class="text-black">Add Money</p>
                    <p class="text-gray">Its Quick safe & secure</p>
                </div>
            </div>
            <form class="" action="{{route('partner.providerwallet.store')}}" method="POST" enctype="multipart/form-data" role="form">
            {{csrf_field()}}
            <div class="text-left row" style="margin-left:30%;">
                <div class="col-md-12 row">
                    <label style="position: relative; left:15px;top:9px;margin-right:10px;">{{ Setting::get('currency') }} </label><input type="text"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" id="amount" name="amount" style="border-left:0px;border-right:0px;border-top:0px;width:80px" >
                    <button type="button" class="form-control wbtn1" style="width:80px; margin-right:5%;">599</button>
                    <button type="button" class="form-control wbtn2" style="width:80px; margin-right:5%;">999</button>
                    <button type="button" class="form-control wbtn3" style="width:80px; margin-right:5%;">1999</button>
                </div>
            </div>
            <div class="text-center mt-5">
                <input type="hidden" name="provider_id" id="provider_id" value="{{ $providerid }}">
                <button type="submit" id="submit" class="bg-black text-white">Add Money</button>
            </div>
            </form>



        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.name')</th>
                        <th>@lang('admin.member.email')</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>Mode</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($providerwallets as $index => $providerwallet)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $providerwallet->provider->name }}</td>
                        <td>{{ $providerwallet->provider->email }}</td>
                        <td>{{ $providerwallet->provider->mobile }}</td>
                        <td>{{ $providerwallet->mode }}</td>
                        <td>{{ $providerwallet->amount }}</td>
                        <td>{{ $providerwallet->status }}</td>
                        <td>{{ date("Y-m-d h:i A", strtotime($providerwallet->updated_at))}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.name')</th>
                        <th>@lang('admin.member.email')</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>Mode</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </tfoot>
            </table>
        </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">
    
    var maxdate = {!! json_encode( \Carbon\Carbon::today()->format('Y-m-d\TH:i') ) !!}
    $('#fromdate').datetimepicker({
        format:'Y-m-d',
        timepicker: false,
        maxDate: maxdate
    });
    $('#todate').datetimepicker({
        format:'Y-m-d',
        timepicker: false,
        maxDate: maxdate
    });
</script>
<script>
    $(document).on('click','.wbtn1', function(){
        $(this).css({
                "background-color": "purple",
                "color"     : "#fff",
            "border"    : "0px",
        });
        $("#amount").val($(this).text());
        $(".wbtn2").css({
                "background-color": "#fff",
                "color"     : "#000",
            "border"    : "1px solid #e4e7ea",
        });
        $(".wbtn3").css({
                "background-color": "#fff",
                "color"     : "#000",
            "border"    : "1px solid #e4e7ea",
        });

        });
    $(document).on('click','.wbtn2', function(){
        $(this).css({
                "background-color": "purple",
                "color"     : "#fff",
            "border"    : "0px",
        });
        $("#amount").val($(this).text());
            $(".wbtn1").css({
                "background-color": "#fff",
                "color"     : "#000",
            "border"    : "1px solid #e4e7ea",
        });
        $(".wbtn3").css({
                "background-color": "#fff",
                "color"     : "#000",
            "border"    : "1px solid #e4e7ea",
        });
    });
    $(document).on('click','.wbtn3', function(){
        $(this).css({
                "background-color": "purple",
                "color"     : "#fff",
            "border"    : "0px",
        });
        $("#amount").val($(this).text());
        $(".wbtn2").css({
                "background-color": "#fff",
                "color"     : "#000",
            "border"    : "1px solid #e4e7ea",
        });
        $(".wbtn1").css({
                "background-color": "#fff",
                "color"     : "#000",
            "border"    : "1px solid #e4e7ea",
        });
        });

</script>

@endsection