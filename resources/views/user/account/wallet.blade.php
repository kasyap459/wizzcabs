@extends('user.layout.base')

@section('title', 'Wallet ')

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
            <h4 class="page-title">@lang('user.my_wallet')</h4> 
        </div>
    </div>
    <hr>
    <div class="wallet">
        <h4 class="amount">
            <span class="price">{{currency_amt(Auth::user()->wallet_balance)}}</span>
            <span class="txt">@lang('user.in_your_wallet')</span>
        </h4>
    </div>
    @if(Setting::get('CARD') == 1)
    <div class="col-md-8 col-md-push-2">
        <form action="{{url('add/money')}}" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="number" class="form-control" name="amount" placeholder="Enter Amount" >
            </div>
            @if($cards->count() > 0)
                <select class="form-control" name="card_id">
                  @foreach($cards as $card)
                    <option @if($card->is_default == 1) selected @endif value="{{$card->card_id}}">{{$card->brand}} **** **** **** {{$card->last_four}}</option>
                  @endforeach
                </select>
            @else
                <p>Please <a href="{{url('payment')}}">add card</a> to continue</p>
            @endif
            <button type="submit" class="full-primary-btn">@lang('user.add_money')</button> 
        </form>
    </div>
    @endif
@endsection