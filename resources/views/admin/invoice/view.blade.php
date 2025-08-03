@extends('admin.layout.base')

@section('title', 'INVOICE ')

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
</style>
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Carrier Invoice</h4>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Create Invoice</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box printableArea">
                        <h3><b>INVOICE</b> <span class="pull-right">#{{ $invoice->invoice_id }}</span></h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">
                                    <address>
                                        <h3> &nbsp;<b class="text-danger"><img src="{{ Setting::get('site_logo', asset('logo-black.png')) }}" style="width: 140px;"></b></h3>
                                    </address>
                                    <br/><br/>
                                    <h6>Bank account details:</h6>
                                    <p>{{ Setting::get('acc_detail','') }}</p>
                                </div>
                                <div class="pull-right text-right">
                                    <address>
                                        <h3>To,</h3>
                                        <h4 class="font-bold">{{ $invoice->partner->name }},</h4>
                                        <p class="text-muted m-l-30">{{ $invoice->partner->address }}
                                            <br/> {{ $invoice->partner->mobile }},
                                        </p>
                                        <p class="m-t-30"><b>Invoice Date :</b> <i class="fa fa-calendar"></i> {{ $invoice->created_at }}</p>
                                        <p><b>Invoice Period :</b> <i class="fa fa-calendar"></i> {{ $invoice->from_date }} - {{ $invoice->to_date }}</p>
                                    </address>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive m-t-40" style="clear: both;">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Booking ID</th>
                                                <th>Driver name</th>
                                                <th>Payment Mode</th>
                                                <th>VAT</th>
                                                <th>Ride total</th>
                                                <th>Commission({{ $invoice->commission_percent }}%)</th>
                                                <th>Commission VAT({{ $invoice->commission_vat_percent }}%)</th>
                                                <th>Commission to Admin</th>
                                                <th>Carrier total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rides as $index => $ride)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $ride->booking_id }}</td>
                                                <td>{{ $ride->provider->name }}</td>
                                                <td>
                                                    @if($ride->corporate_id !=0)
                                                        CORPORATE
                                                    @else
                                                        {{ $ride->payment_mode }}
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $ride->vat_percent }}%</td>
                                                <td class="text-center">{{ $ride->payment->currency }} {{ $ride->ride_total }}</td>
                                                <td class="text-center">{{ $ride->payment->currency }} {{ $ride->commission }}</td>
                                                <td class="text-center">{{ $ride->payment->currency }} {{ $ride->commission_vat }}</td>
                                                <td class="text-center">{{ $ride->payment->currency }} {{ $ride->commission_total }}</td>
                                                <td class="text-center">{{ $ride->payment->currency }} {{ $ride->carrier_total }}</td>
                                            </tr>
                                            @endforeach
                                            <tr class="black">
                                                <td><b>Total:</b></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td style="width:60px;"><b>{{ currency_amt($customer_vat_total) }}</b></td>
                                                <td><b>{{ currency_amt($invoice->ride_total) }}</b></td>
                                                <td><b>{{ currency_amt($rides->sum('commission')) }}</b></td>
                                                <td><b>{{ currency_amt($rides->sum('commission_vat')) }}</b></td>
                                                <td><b>{{ currency_amt($invoice->commission_total) }}</b></td>
                                                <td><b>{{ currency_amt($invoice->carrier_total) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="10"></td>
                                            </tr>

                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Cash Ride total:</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($invoice->cash_total) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Card Ride total:</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($invoice->card_total) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Corporate Ride total:</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($corporate_total) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Previous Balance :</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($invoice->prev_balance) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Last Payment:</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($invoice->prev_payment) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Current Payment:</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($invoice->current_payment) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Admin to Carrier Payment:</b></td>
                                                <td colspan="2" class="border-none"><b class="black">{{ currency_amt($invoice->admin_pay) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Carrier to Admin payment:</b></td>
                                                <td colspan="2" class="border-none"><b class="black">{{ currency_amt($invoice->carrier_pay) }}</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
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