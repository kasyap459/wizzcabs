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
                <h4 class="page-title">Corporate Invoice</h4>
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
                                        <h4 class="font-bold">{{ $corporate->display_name }},</h4>
                                        <p class="text-muted m-l-30">{{ $corporate->address }}
                                            <br/> {{ $corporate->mobile }},
                                        </p>
                                        <p class="m-t-30"><b>Invoice Date :</b> <i class="fa fa-calendar"></i> {{ $now->format('Y-m-d') }}</p>
                                        <p><b>Invoice Period :</b> <i class="fa fa-calendar"></i> {{ $from_date }} - {{ $to_date }}</p>
                                    </address>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive m-t-40" style="clear: both;">>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Booking ID</th>
                                                <th>Customer name</th>
                                                <th>Customer mobile</th>
                                                <th>Payment Mode</th>
                                                <th>VAT</th>
                                                <th>Ride total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rides as $index => $ride)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $ride->booking_id }}</td>
                                                <td>{{ $ride->user_name }}</td>
                                                <td>{{ $ride->user_mobile }}</td>
                                                <td>
                                                    @if($ride->corporate_id !=0)
                                                        CORPORATE
                                                    @else
                                                        {{ $ride->payment_mode }}
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $ride->vat_percent }}%</td>
                                                <td class="text-center">{{ $ride->payment->currency }} {{ $ride->payment->total }}</td>
                                            </tr>
                                            @endforeach
                                            <tr class="black">
                                                <td colspan="6" class="text-right"><b>Sub - Total amount:</b></td>
                                                <td colspan="1"><b>{{ currency_amt($ride_total) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="7"></td>
                                            </tr>
                                            
                                            <tr>
                                                <td colspan="5" class="text-right border-none"><b>Previous Balance :</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($prev_balance) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right border-none"><b>Last Payment:</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($prev_payment) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right border-none"><b>Current Payment:</b></td>
                                                <td colspan="2" class="border-none"><b>{{ currency_amt($current_payment) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right border-none"><b>Total :</b></td>
                                                <td colspan="2" class="border-none"><b class="black">{{ currency_amt($current_payment) }}</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12">
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
        var corporate_id = {{ $corporate->id }};
        var invoice_id = '{{ $invoice_id }}';
        var ride_count = parseFloat('{{ $ride_count }}');
        var ride_total = parseFloat('{{ $ride_total }}');
        var prev_balance = parseFloat('{{ $prev_balance }}');
        var prev_payment = parseFloat('{{ $prev_payment }}');
        var current_payment = parseFloat('{{ $current_payment }}');
        var from_date = '{{ $from_date }}';
        var to_date = '{{ $to_date }}';

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "{{ route('admin.corporate.invoice.store') }}",
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            type: 'POST',
            data: {
                    corporate_id: corporate_id,
                    invoice_id: invoice_id,
                    from_date: from_date,
                    to_date: to_date,
                    ride_count: ride_count,
                    ride_total: ride_total,
                    prev_balance: prev_balance,
                    prev_payment: prev_payment,
                    current_payment: current_payment,
                    },
            success: function(data) {
                window.location.href = "{{ route('admin.corporateinvoicelist') }}";
            }
        });
    });
</script>
@endsection