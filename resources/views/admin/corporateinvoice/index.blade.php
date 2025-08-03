@extends('admin.layout.base')

@section('title', 'Invoice ')
@section('styles')
<style type="text/css">
div.dataTables_wrapper div.dataTables_filter label {
    display: inline-block !important;
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
                    <li class="active">Invoice List</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <h5 class="mb-1">
                Invoice
            </h5>
<!--             <a href="{{ route('admin.ride.statement.corporate') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Create Invoice</a>
 -->            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Invoice ID</th>
                        <th>Corporate Name</th>
                        <th>No. of Rides</th>
                        <th>Ride Total</th>
                        <th>Total</th>
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
                        <td>@if($invoice->corporate)
                            {{ $invoice->corporate->display_name }}
                            @endif
                        </td>
                        <td>{{ $invoice->ride_count }}</td>
                        <td>{{ currency_amt($invoice->ride_total) }}</td>
                        <td>{{ currency_amt($invoice->total) }}</td>
                        <td>{{ currency_amt($invoice->paid) }}</td>
                        <td>{{ currency_amt($invoice->balance) }}</td>
                        <td>{{ $invoice->created_at }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info btn-sm waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    @lang('admin.member.action')
                                </button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('admin.corporateinvoiceview', $invoice->id) }}" class="dropdown-item">
                                        <i class="fa fa-pencil-square-o"></i> @lang('admin.member.view')
                                    </a>
                                    <a href="{{ route('admin.corporateinvoiceedit', $invoice->id) }}" class="dropdown-item">
                                        <i class="fa fa-pencil-square-o"></i> @lang('admin.member.edit')
                                    </a>
                                    <form action="{{ route('admin.corporateinvoicedelete', $invoice->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <button class="dropdown-item" onclick="return confirm('Are you sure?')"> <i class="fa fa-trash"></i>  @lang('admin.member.delete')</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Invoice ID</th>
                        <th>Corporate Name</th>
                        <th>No. of Rides</th>
                        <th>Total</th>
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
@section('scripts')
<script type="text/javascript">
    $(window).load(function(){
    	$("#table-2_filter").append('<a href="https://www.crowncabva.com/admin/statement/corporate" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Create Invoice</a>');	
    });
</script>

@endsection