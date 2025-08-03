@extends('admin.layout.base')

@section('title', 'Update Dispatcher ')

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
                    <li class="active">Edit Invoice</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
			<h5>Update Invoice</h5>

            <form class="form-horizontal" action="{{ route('admin.invoiceupdate', $invoice->id) }}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="paid" class="col-xs-2 col-form-label">Invoice ID</label>
					<div class="col-xs-6">
						<p>{{ $invoice->invoice_id }}</p>
					</div>
				</div>
				<div class="form-group row">
					<label for="paid" class="col-xs-2 col-form-label">No of Rides</label>
					<div class="col-xs-6">
						<p>{{ $invoice->ride_count }}</p>
					</div>
				</div>
				<div class="form-group row">
					<label for="paid" class="col-xs-2 col-form-label">Rides Total</label>
					<div class="col-xs-6">
						<p>{{ currency_amt($invoice->ride_total) }}</p>
					</div>
				</div>
				<div class="form-group row">
					<label for="paid" class="col-xs-2 col-form-label">Current Payment</label>
					<div class="col-xs-6">
						<p>{{ currency_amt($invoice->current_payment) }}</p>
					</div>
				</div>
				<div class="form-group row">
					<label for="paid" class="col-xs-2 col-form-label">Previous Payment</label>
					<div class="col-xs-6">
						<p>{{ currency_amt($invoice->prev_payment) }}</p>
					</div>
				</div>
				<div class="form-group row">
					<label for="paid" class="col-xs-2 col-form-label">Previous Balance</label>
					<div class="col-xs-6">
						<p>{{ currency_amt($invoice->prev_balance) }}</p>
					</div>
				</div>
				<div class="form-group row">
					<label for="paid" class="col-xs-2 col-form-label">Total</label>
					<div class="col-xs-6">
						<p>{{ currency_amt($invoice->total) }}</p>
					</div>
				</div>
				<div class="form-group row">
					<label for="paid" class="col-xs-2 col-form-label">Paid Amount</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" value="{{ $invoice->paid }}" name="paid" required id="paid" placeholder="Paid">
					</div>
				</div>

				<div class="form-group row">
					<label for="balance" class="col-xs-2 col-form-label">Balance Amount</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" value="{{ $invoice->balance }}" name="balance" required id="balance" placeholder="Balance">
					</div>
				</div>
				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-8">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Update Invoice</button>
						<a href="{{route('admin.invoicelist')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
