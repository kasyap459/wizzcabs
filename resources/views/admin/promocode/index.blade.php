@extends('admin.layout.base')

@section('title', 'Promocodes ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="row bg-title">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h4 class="page-title">@lang('admin.member.promocodes')</h4><a href="{{ route('admin.promocode.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.member.add_new_promocode')</a>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                        <li class="active">@lang('admin.list_promocodes')</li>
                    </ol>
                </div>
            </div>

            <div class="box box-block bg-white">
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>@lang('admin.member.id')</th>
                            <th>@lang('admin.member.promocode') </th>
                            <th>@lang('admin.member.discount') </th>
                            <th>Discount Type </th>
                            <th>User Type </th>
                            <th>Starting at</th>
                            <th>@lang('admin.member.expiration')</th>
                            <th>@lang('admin.member.status')</th>
                            <th>Valid Count</th>
                            <th>@lang('admin.member.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($promocodes as $index => $promo)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$promo->promo_code}}</td>
                            <td>{{$promo->discount}}</td>
                            <td>@if($promo->discount_type =='flat')
                                Flat
                                @else
                                Percentage
                                @endif
                            </td>
                            <td>@if($promo->user_type =='all')
                                All Users
                                @else
                                New Users
                                @endif
                            </td>
                            <td>
                                {{date('d-m-Y',strtotime($promo->starting_at))}}
                            </td>
                            <td>
                                {{date('d-m-Y',strtotime($promo->expiration))}}
                            </td>
                            <td>
                                @if(date("Y-m-d") <= $promo->expiration)
                                    <span class="label label-table label-primary">@lang('admin.member.valid')</span>
                                @else
                                    <span class="label label-table label-warning">@lang('admin.member.expiration')</span>
                                @endif
                            </td>
                            <td>
                                {{$promo->use_count}}
                            </td>
                            <td>
                                <form action="{{ route('admin.promocode.destroy', $promo->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <a href="{{ route('admin.promocode.edit', $promo->id) }}" class="btn btn-success btn-rounded label-left b-a-0 waves-effect waves-light"><span class="btn-label"><i class="fa fa-pencil"></i></span> @lang('admin.member.edit')</a>
                                    <button class="btn btn-danger btn-rounded label-left b-a-0 waves-effect waves-light" onclick="return confirm('Are you sure?')"><span class="btn-label"><i class="fa fa-trash"></i></span> @lang('admin.member.delete')</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>@lang('admin.member.id')</th>
                            <th>@lang('admin.member.promocode') </th>
                            <th>@lang('admin.member.discount') </th>
                            <th>Discount Type </th>
                            <th>User Type </th>
                            <th>Starting at</th>
                            <th>@lang('admin.member.expiration')</th>
                            <th>@lang('admin.member.status')</th>
                            <th>Valid Count</th>
                            <th>@lang('admin.member.action')</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection