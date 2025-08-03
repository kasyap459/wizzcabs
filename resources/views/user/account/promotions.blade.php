@extends('user.layout.base')

@section('title', 'Promotion ')

@section('styles')
<style type="text/css">
    .form-control {
        margin-bottom: 10px;
    }
    .profile-img-blk{
        margin-bottom: 10px;
    }
    label{
        padding-top: 10px;
    }
</style>
@endsection

@section('content')
    
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if(Session::has('flash_error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ Session::get('flash_error') }}
        </div>
    @endif


    @if(Session::has('flash_success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ Session::get('flash_success') }}
        </div>
    @endif

    <div class="row no-margin">
        <div class="col-md-12">
            <h4 class="page-title">Promocode / Gift Coupons</h4> 
        </div>
    </div>
    <hr>

    <h5 class="btm-border"><strong>Add Promocode</strong></h5>
    <form id="payment-form" action="{{ route('promocodes.store') }}" method="POST" >
        {{ csrf_field() }}
        <div class="row no-margin" id="card-payment">
            <div class="form-group col-md-6 col-sm-6">
                <input autocomplete="off" name="promocode" required type="text" class="form-control" placeholder="@lang('user.add_promocode')">
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <button type="submit" class="btn btn-default">@lang('user.add_promocode')</button>
            </div>
        </div>
    </form>
    <hr>
    <h5 class="btm-border"><strong>Applied Promocodes</strong></h5>
    @forelse($promocodes as $promo)
    <div class="pay-option" style="width: 75%;">
        <h5>
            <img src="{{asset('asset/userpanel/img/low-cost.png')}}" style="width: 25px;"> {{$promo->promocode->promo_code}}
            <a href="#" class="default">{{$promo->status}}</a>
        </h5>
    </div>
    @empty
    <div class="pay-option">
        <h6 class="text-center">No promotions applied.</h6>
    </div>
    @endforelse
    <hr>
    <h5 class="btm-border"><strong>Available Promocodes</strong></h5>

    
    <table class="table table-responsive">
        <tr>
            <th>ID</th>
            <th>Promocode</th>
            <th>Discount</th>
        </tr>
        @forelse($available as $index => $avail)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $avail->promo_code }}</td>
            <td>{{ $avail->discount }}
                @if($avail->discount_type =='percent')
                    %
                @else
                    Flat
                @endif
            </td>
        </tr>
        @endforeach
    </table>
    
@endsection