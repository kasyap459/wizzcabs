@extends('admin.layout.base')

@section('title', $page)

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
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
                        <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                        <li class="active">@lang('admin.driver_statement')</li>
                    </ol>
                </div>
            </div>

            <div class="box box-block bg-white">
                <div style="text-align: center;padding: 20px;color: blue;font-size: 20px;">
                    <p><strong>
                        <span class="text-danger">Current Wallet : {{ Setting::get('currency') }}<i class="revenue">{{$wallet}}</i></span>
                    </strong></p>
                </div>

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
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userwallets as $index => $userwallet)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $userwallet->user->first_name }}</td>
                        <td>{{ $userwallet->user->email }}</td>
                        <td>{{ $userwallet->user->mobile }}</td>
                        <td>{{ $userwallet->mode }}</td>
                        <td>{{ $userwallet->amount }}</td>
                        <td>{{ date("Y-m-d h:i A", strtotime($userwallet->updated_at))}}
                        </td>
                        <td>
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
                        <th>Mode</th>
                        <th>Amount</th>
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

@endsection