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
        color: #8d9ea7!important;
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
                        <h3><b>INVOICE</b> <span class="pull-right">#{{ $invoice_id }}</span></h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">
                                    <address>
                                        <h3> &nbsp;<b class="text-danger"><img src="{{ Setting::get('site_logo', asset('logo-black.png')) }}" style="width: 140px;"></b></h3>
                                    </address>
                                    <br><br>
                                    <h6>Bank account details:</h6>
                                    <p>{{ Setting::get('acc_detail','') }}</p>
                                </div>
                                <div class="pull-right text-right">
                                    <address>
                                        <h3>To,</h3>
                                        <h4 class="font-bold">{{ $partner->name }},</h4>
                                        <p class="text-muted m-l-30">{{ $partner->address }}
                                            <br/> {{ $partner->mobile }},
                                        </p>
                                        <p class="m-t-30"><b>Invoice Date :</b> <i class="fa fa-calendar"></i> {{ $now->format('Y-m-d') }}</p>
                                        <p><b>Invoice Period :</b> <i class="fa fa-calendar"></i> {{ $from_date }} - {{ $to_date }}</p>
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
                                                <th>Commission({{ $commission_percent }}%)</th>
                                                <th>Commission VAT({{ $commission_vat_percent }}%)</th>
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
                                                <td><b>{{ currency_amt($overall_ride_total) }}</b></td>
                                                <td><b>{{ currency_amt($rides->sum('commission')) }}</b></td>
                                                <td><b>{{ currency_amt($rides->sum('commission_vat')) }}</b></td>
                                                <td><b>{{ currency_amt($overall_commission_total) }}</b></td>
                                                <td><b>{{ currency_amt($overall_carrier_total) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="10"></td>
                                            </tr>

                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Cash Ride total:</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($cash_total) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Card Ride total:</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($card_total) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Corporate Ride total:</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($corporate_total) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Previous Balance :</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($prev_balance) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Last Payment:</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($prev_payment) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Current Payment:</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($current_payment) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Admin to Carrier Payment:</b></td>
                                                <td colspan="2" class="border-none"><b class="black">{{ currency_amt($admin_pay) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-right border-none"><b>Carrier to Admin payment:</b></td>
                                                <td colspan="2" class="border-none"><b class="black">{{ currency_amt($carrier_pay) }}</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12">
                               
                                <div class="clearfix"></div>
                                <hr>
                                <div class="text-right">
                                    <button class="btn btn-danger" type="submit" id="send_carrier"> Save </button>
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
<script>
    $(document).on('click', '#send_carrier', function() {
        var partner_id = {{ $partner->id }};
        var invoice_id = '{{ $invoice_id }}';
        var ride_count = parseFloat('{{ $ride_count }}');
        var cash_total = parseFloat('{{ $cash_total }}');
        var card_total = parseFloat('{{ $card_total }}');
        var ride_total = parseFloat('{{ $overall_ride_total }}');
        var commission_total = parseFloat('{{ $overall_commission_total }}');
        var carrier_total = parseFloat('{{ $overall_carrier_total }}');
        var prev_balance = parseFloat('{{ $prev_balance }}');
        var prev_payment = parseFloat('{{ $prev_payment }}');
        var current_payment = parseFloat('{{ $current_payment }}');
        var admin_pay = parseFloat('{{ $admin_pay }}');
        var carrier_pay = parseFloat('{{ $carrier_pay }}');
        var from_date = '{{ $from_date }}';
        var to_date = '{{ $to_date }}';
        var vat_percent = parseFloat('{{ $vat_percent }}');
        var commission_percent = parseFloat('{{ $commission_percent }}');
        var commission_vat_percent = parseFloat('{{ $commission_vat_percent }}');

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "{{ route('admin.partner.invoice.store') }}",
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            type: 'POST',
            data: {
                    partner_id: partner_id,
                    invoice_id: invoice_id,
                    ride_count: ride_count,
                    cash_total: cash_total,
                    card_total: card_total,
                    ride_total: ride_total,
                    commission_total: commission_total,
                    carrier_total: carrier_total,
                    prev_balance: prev_balance,
                    prev_payment: prev_payment,
                    current_payment: current_payment,
                    admin_pay: admin_pay,
                    carrier_pay: carrier_pay,
                    from_date: from_date,
                    to_date: to_date,
                    vat_percent: vat_percent,
                    commission_percent: commission_percent,    
                    commission_vat_percent: commission_vat_percent,
                },
            success: function(data) {
                window.location.href = "{{ route('admin.invoicelist') }}";
            }
        });
    });
</script>
@endsection