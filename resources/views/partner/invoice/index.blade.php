@extends('partner.layout.base')

@section('title', 'Invoice ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Carrier Invoice</h4>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('partner.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Invoice List</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <h5 class="mb-1">
                Invoice
            </h5>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Invoice ID</th>
                        <th>Carrier Name</th>
                        <th>No. of Rides</th>
                        <th>Ride Total</th>
                        <th>Percent</th>
                        <th>Current payment</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $index => $invoice)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $invoice->invoice_id }}</td>
                        <td>{{ $invoice->partner->name }}</td>
                        <td>{{ $invoice->ride_count }}</td>
                        <td>{{ currency_amt($invoice->ride_total) }}</td>
                        <td>{{ $invoice->commission_percent }}</td>
                        <td>{{ currency_amt($invoice->current_payment) }}</td>
                        <td>{{ currency_amt($invoice->paid) }}</td>
                        <td>{{ currency_amt($invoice->balance) }}</td>
                        <td>{{ $invoice->created_at }}</td>
                        <td>
                            <a href="{{ route('partner.invoiceview', $invoice->id) }}" class="btn btn-info">@lang('admin.member.view')</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Invoice ID</th>
                        <th>Carrier Name</th>
                        <th>No. of Rides</th>
                        <th>Total</th>
                        <th>Percent</th>
                        <th>Current Payment</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection