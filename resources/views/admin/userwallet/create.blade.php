@extends('admin.layout.base')

@section('title', 'User Wallets')

@section('styles')

<link rel="stylesheet" href="{{asset('main/vendor/multi-select/css/multi-select.css')}}">
<style>
	input[type="checkbox"]{
	  width: 20px !important; 
	  height: 20px !important;
	}
	.searchboxs{
		width: 100%;
	    margin-bottom: 9px;
	    border: 1px solid #d2cece;
	    border-radius: 2px;
	    padding: 4px 10px;
	}
</style>
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <h4 class="page-title">User Wallets</h4><a href="{{ route('admin.userwallet.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">User Wallets</a>
            </div>
        </div>

    	<div class="box box-block bg-white">
            <form class="form-horizontal" action="{{route('admin.userwallet.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="select_drivers" class="col-xs-12 col-form-label">Select Users</label>
            		<div class="col-xs-10">
						<a href="#" class="btn btn-info btn-rounded" id='select-all'>@lang('admin.member.select_all')</a>
						<a href="#" class="btn btn-warning btn-rounded" id='deselect-all'>@lang('admin.member.clear_all')</a>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-xs-10">
					<select id='public-methods' name="users[]" multiple='multiple'>
					  @foreach($Users as $index => $user)
					  <option value='{{$user->id}}'>{{$user->email}} ({{ Setting::get('currency') }}{{$user->wallet_balance}})</option>
					  @endforeach
					</select>
				</div>
				</div>
				<div class="form-group row">
					<label for="push_content" class="col-xs-12 col-form-label">Amount</label>
					<div class="col-xs-9">
						<input type="text"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="amount" id="amount" class="form-control" required>
					</div>
				</div>
				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> @lang('admin.member.send_message')</button>
						<a href="{{route('admin.push.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>
			</form>
		</div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.name')</th>
                        <th>@lang('admin.member.email')</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>wallet</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userwallets as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td> {{ $user->mobile }}</td>
                        <td> {{ Setting::get('currency') }} {{ $user->wallet_balance }}</td>
                        <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-info btn-sm waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                @lang('admin.member.action')
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('admin.credit', $user->id) }}" class="dropdown-item">
                                    <i class="fa fa-pencil-square-o"></i>Credited
                                </a>
                                <a href="{{ route('admin.userwallet.edit', $user->id) }}" class="dropdown-item">
                                    <i class="fa fa-pencil-square-o"></i>Debited
                                </a>
                            </div>
                        </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.name')</th>
                        <th>@lang('admin.member.email')</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>wallet</th>
                        <th>@lang('admin.member.action')</th>
                    </tr>
                </tfoot>
            </table>
        </div>


    </div>
</div>

@endsection


@section('scripts')
<script type="text/javascript" src="{{asset('main/assets/js/jquery.quicksearch.js')}}"></script>
<script type="text/javascript" src="{{asset('main/vendor/multi-select/js/jquery.multi-select.js')}}"></script>
<script>
	$('#select-all').click(function(){
	  $('#public-methods').multiSelect('select_all');
	  return false;
	});
	$('#deselect-all').click(function(){
	  $('#public-methods').multiSelect('deselect_all');
	  return false;
	});
	$('#public-methods').multiSelect({
	selectableHeader: "<input type='text' class='search-input searchboxs' autocomplete='off' placeholder='Search here'>",
	selectionHeader: "<input type='text' class='search-input searchboxs' autocomplete='off' placeholder='Search here'>",
	afterInit: function(ms){
	var that = this,
	    $selectableSearch = that.$selectableUl.prev(),
	    $selectionSearch = that.$selectionUl.prev(),
	    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
	    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

	that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
	.on('keydown', function(e){
	  if (e.which === 40){
	    that.$selectableUl.focus();
	    return false;
	  }
	});

	that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
	.on('keydown', function(e){
	  if (e.which == 40){
	    that.$selectionUl.focus();
	    return false;
	  }
	});
	},
	afterSelect: function(){
	this.qs1.cache();
	this.qs2.cache();
	},
	afterDeselect: function(){
	this.qs1.cache();
	this.qs2.cache();
	}
	});
</script>
@endsection