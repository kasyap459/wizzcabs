@extends('admin.layout.base')

@section('title', 'Drivers ')
@section('styles')
<style>
	ul.dropdown-menu.show {
    		top: 76px !important;
	}
	ul.dropdown-menu {
    		top: 76px !important;
	}
</style>
@endsection
@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
    
        <div class="row bg-title">

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <a href="{{ route('admin.providerlist') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">Driver List</a>
            </div>

           {{--  <h5> <b>Name  </b> : {{$provider[0]->provider->name}}  , <b>Email  </b> :  {{$provider[0]->provider->email}} , <b> Mobile  </b> :  {{$provider[0]->provider->mobile}} </h5>  --}}
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
              
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Mobile No</th>
                        <th>Earnings</th>
                        <th>Tip</th>
                        <th>Total Fare</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($provider as $index => $details)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $details->provider->name }}</td>
                        <td>{{ $details->provider->email }} </td>
                        <td>{{ $details->provider->mobile }} </td>
                        <td> @if($details->payment) {{$details->payment->currency }} {{ $details->payment->earnings }}  @endif</td>
                        <td> @if($details->payment)  {{ $details->payment->tip_fare }}  @endif</td>
                        <td> @if($details->payment)  {{$details->payment->currency }}{{ $details->payment->total }}  @endif</td>
                       
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                    <th>@lang('admin.member.id')</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Mobile No</th>
                        <th>Earnings</th>
                        <th>Tip</th>
                        <th>Total Fare</th>

                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</div>
@endsection

@section('scripts')


@endsection