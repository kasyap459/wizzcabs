@extends('user.layout.base')

@section('title', 'Profile ')

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
            <h4 class="page-title">@lang('user.payment')
                @if(Setting::get('CARD') == 1)
                    <a href="{{url('addcard')}}" class="btn btn-success btn-sm">@lang('user.card.add_card')</a>
                @endif
            </h4> 
        </div>
    </div>
    <hr>
    <div class="pay-option">
        <h5><img src="{{asset('asset/userpanel/img/cash-icon.png')}}" style="width: 40px"> <strong>@lang('user.cash')</strong>  </h5>
    </div>
    @if(Setting::get('CARD') == 1)
    @foreach($cards as $card)
    <div class="pay-option" style="width: 75%;">
        <h5>
            <img src="{{asset('asset/userpanel/img/card-icon.png')}}" style="width: 40px"> {{$card->brand}} **** **** **** {{$card->last_four}} 
            @if($card->is_default)
                <a href="#" class="default">@lang('user.card.default')</a>
            @endif 
            <form action="{{url('usercard/destory')}}" method="POST" class="pull-right">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="card_id" value="{{$card->card_id}}">
                <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm" >@lang('user.card.delete')</button>
            </form>
        </h5>
    </div>
    @endforeach
    @endif

@endsection