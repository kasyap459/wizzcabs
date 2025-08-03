@extends('partner.layout.base')

@section('title', 'Provider Wallet')
@section('styles')
<style>
.perfect-scrollbar-on .main-panel, .perfect-scrollbar-on .sidebar {
    height: auto !important;
    max-height: none !important;
}
</style>

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <h4 class="page-title">Provider Wallet</h4>
            </div>
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
<!--                         <th>@lang('admin.member.action')</th>
 -->                    </tr>
                </thead>
                <tbody>
                    @foreach($providers as $index => $provider)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><a href="{{ route('partner.provider.credit', $provider->id) }}" id="addwallet" data-id="{{ $provider->id }}">{{$provider->name}}</a>
                         </td>
                        <td>{{ $provider->email }}</td>
                        <td> {{ $provider->mobile }}</td>
                        <td>${{ $provider->wallet_balance }}</td>
                        <td>
<!--                         <div class="btn-group" role="group">
                            <button type="button" class="btn btn-info btn-sm waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                @lang('admin.member.action')
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('partner.provider.credit', $provider->id) }}" class="dropdown-item">
                                    <i class="fa fa-pencil-square-o"></i>Credited
                                </a>
                                <a href="{{ route('partner.provider.credit', $provider->id) }}" class="dropdown-item">
                                    <i class="fa fa-pencil-square-o"></i>Debited
                                </a>
                            </div>
                        </div>
 -->                        </td>
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
<!--                         <th>@lang('admin.member.action')</th>
 -->                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
