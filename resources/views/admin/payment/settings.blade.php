@extends('admin.layout.base')

@section('title', 'Payment Settings ')

@section('styles')
<style>
    .custom-forms .form-control{
        width: 79%;
        display: inline-block;
    }
    .custom-forms .form-group span{
        padding-top: 7px;
        padding-left: 5px;
    }
    .input-group {
    	display: table !important;
    }

</style>
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="page-title">@lang('admin.settings')</h4>
                <a href="{{ route('admin.settings') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.member.site_settings')</a>
                <a href="{{ route('admin.settings.payment') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light active">@lang('admin.payment_settings')</a>
<!--                 <a href="#" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.account_settings')</a>
                <a href="#" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.change_password')</a>
 -->            </div>
        </div>

        <form action="{{route('admin.settings.payment.store')}}" method="POST">
                {{csrf_field()}}
        <div class="panel panel-info">
            <div class="panel-heading">@lang('admin.member.payment_modes')</div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                    <blockquote class="card-blockquote">
                        <i class="fa fa-3x fa-cc-stripe pull-right"></i>
                        <div class="form-group row">
                            <div class="col-xs-4">
                                <label for="stripe_secret_key" class="col-form-label">
                                    Stripe (Card Payments)
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <input @if(Setting::get('CARD') == 1) checked  @endif  name="CARD" id="stripe_check" onchange="cardselect()" type="checkbox" class="js-switch" data-color="#43b968">
                            </div>
                        </div>
                        <div id="card_field" @if(Setting::get('CARD') == 0)  @endif>
                            <div class="form-group row">
                                <label for="stripe_secret_key" class="col-xs-4 col-form-label">Stripe Secret key</label>
                                <div class="col-xs-8">
	   			    <div class="input-group" id="stripe_secret_key">
                                    	<input class="form-control" type="password" value="{{Setting::get('stripe_secret_key', '') }}" name="stripe_secret_key" id="stripe_secret_key"  placeholder="Stripe Secret key" >
                                    	<div class="input-group-addon">
        					<a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
      				    	</div>
				    </div>
				</div>
                            </div>
                            <div class="form-group row">
                                <label for="stripe_publishable_key" class="col-xs-4 col-form-label">Stripe Publishable key</label>
                                <div class="col-xs-8">
				    <div class="input-group" id="stripe_publish_key">
                                    	<input class="form-control" type="password" value="{{Setting::get('stripe_publishable_key', '') }}" name="stripe_publishable_key" id="stripe_publishable_key"  placeholder="Stripe Publishable key" >
                                    	<div class="input-group-addon">
        					<a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
      				    	</div>
				    </div>
				</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-4">
                                <label for="cash-payments" class="col-form-label">
                                    Cash Payments
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <input @if(Setting::get('CASH') == 1) checked  @endif name="CASH" id="cash-payments" onchange="cash_disable()" type="checkbox" class="js-switch" data-color="#43b968">
                            </div>
                        </div>
                    </blockquote>
                </div>
            </div>
        </div>
        <div class="panel panel-info custom-forms" style="margin-bottom: 64px;">
            <div class="panel-heading">Trip Settings</div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                <blockquote class="card-blockquote">
                        <!-- <div class="form-group row">
                            <label for="auto_assign" class="col-xs-3 col-form-label">Auto Assign Driver</label>
                            <div class="col-xs-8">
                                <select class="form-control" id="auto_assign" name="auto_assign">
                                    <option value="1" @if(Setting::get('auto_assign', 0) == 1) selected @endif>Enable</option>
                                    <option value="0" @if(Setting::get('auto_assign', 0) == 0) selected @endif>Disable</option>
                                </select>
                            </div>
                        </div> -->
<!--                         <div class="form-group row">
                            <label for="acc_detail" class="col-xs-3 col-form-label">Account Details</label>
                            <div class="col-xs-8">
                                <textarea class="form-control" name="acc_detail" id="acc_detail" cols="30" rows="10">{{ Setting::get('acc_detail', '')  }}</textarea>
                            </div>
                        </div>
 -->                       
 
                      <!-- <div class="form-group row">
                            <label for="driver_min_wallet" class="col-xs-3 col-form-label">Driver Minimum wallet balance </label>
                            <div class="col-xs-8">
                               <input class="form-control" type="text" value="{{ Setting::get('driver_min_wallet', '0')  }}" step="0.01" name="driver_min_wallet" required id="driver_min_wallet" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  placeholder="Driver Minimum wallet balance">
                            </div>
                        </div> -->

                        <!-- <div class="form-group row">
                            <label for="commision_trip_accept" class="col-xs-3 col-form-label">Commission for Driver Trip Accept </label>
                            <div class="col-xs-8">
                               <input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ Setting::get('commision_trip_accept', '0')  }}" step="0.01" name="commision_trip_accept" required id="commision_trip_accept" placeholder="Commission for Driver Trip Accept">
                            </div>
                        </div> -->


                           <!-- <div class="form-group row">
                            <label for="user_cancel_fee" class="col-xs-3 col-form-label">User Trip Cancellation amount</label>
                            <div class="col-xs-8">
                               <input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ Setting::get('user_cancel_fee', '0')  }}" step="0.01" name="user_cancel_fee" required id="user_cancel_fee" placeholder="User Trip Cancellation amount">
                            </div>
                        </div> -->
                        <!-- <div class="form-group row">
                            <label for="driver_cancel_fee" class="col-xs-3 col-form-label">Driver Trip Cancellation amount</label>
                            <div class="col-xs-8">
                               <input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ Setting::get('driver_cancel_fee', '0')  }}" step="0.01" name="driver_cancel_fee" required id="driver_cancel_fee" placeholder="Driver Trip Cancellation amount">
                            </div>
                        </div> -->
                        <!-- <div class="form-group row">
                            <label for="card_payment_fee" class="col-xs-3 col-form-label">Card payment fee (%)</label>
                            <div class="col-xs-8">
                               <input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ Setting::get('card_payment_fee', '0')  }}" step="0.01" name="card_payment_fee" required id="card_payment_fee" placeholder="Card payment fee (%)">
                            </div>
                        </div> -->


                           <div class="form-group row">
                            <label for="vat_percent" class="col-xs-3 col-form-label">Tax Percentage(%)</label>
                            <div class="col-xs-8">
                               <input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ Setting::get('vat_percent', '0')  }}" step="0.01" name="vat_percent" required id="vat_percent" placeholder="VAT Percentage">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="commission_enable" class="col-xs-3 col-form-label">Commission Enable</label>
                            <div class="col-xs-8">
                                <select class="form-control" id="commission_enable" name="commission_enable">
                                    <option value="1" @if(Setting::get('commission_enable', 0) == 1) selected @endif>Enable</option>
                                    <option value="0" @if(Setting::get('commission_enable', 0) == 0) selected @endif>Disable</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="commission_percentage" class="col-xs-3 col-form-label">@lang('admin.member.commission_percentage')(%)</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="text"
                                    value="{{ Setting::get('commission_percentage', '0') }}"
                                    id="commission_percentage"
                                    name="commission_percentage"
                                    min="0"
                                    max="100"
                                    placeholder="Commission percentage">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="booking_prefix" class="col-xs-3 col-form-label">@lang('admin.member.booking_id_prefix')</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="text"
                                    value="{{ Setting::get('booking_prefix', '0') }}"
                                    id="booking_prefix"
                                    name="booking_prefix"
                                    min="0"
                                    max="4"
                                    placeholder="Booking ID Prefix">
                            </div>
                        </div>
                <div class="form-group row">
                    <label for="fare_edit" class="col-xs-3 col-form-label">Edit Trip Fare</label>
                    <div class="col-xs-8">
                        <select class="form-control" id="fare_edit" name="fare_edit">
                            <option value="1" @if(Setting::get('fare_edit', 0) == 1) selected @endif>Enable</option>
                            <option value="0" @if(Setting::get('fare_edit', 0) == 0) selected @endif>Disable</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="display: none;">
                    <label for="toll_charge" class="col-xs-3 col-form-label" >Toll Charge</label>
                    <div class="col-xs-8">
                        <select class="form-control" id="toll_charge" name="toll_charge">
                            <option value="1" @if(Setting::get('toll_charge', 0) == 1) selected @endif>Enable</option>
                            <option value="0" @if(Setting::get('toll_charge', 0) == 0) selected @endif>Disable</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="display: none;">
                    <label for="tip_enable" class="col-xs-3 col-form-label" >Tips Enable</label>
                    <div class="col-xs-8">
                        <select class="form-control" id="tip_enable" name="tip_enable" >
                            <option value="1" @if(Setting::get('tip_enable', 0) == 1) selected @endif>Enable</option>
                            <option value="0" @if(Setting::get('tip_enable', 0) == 0) selected @endif>Disable</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="tip_field">
                    <label for="percentage1" class="col-xs-3 col-form-label">Tips Percentage(%)</label>
                    <div class="col-xs-1">
                        <input class="form-control perstyle" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ Setting::get('percentage1', 0)  }}" name="percentage1" required id="percentage1" placeholder="Percentage 1" disabled>    
                    </div>
                    <div class="col-xs-1">
                        <input class="form-control perstyle" type="number" value="{{ Setting::get('percentage2', 0)  }}" name="percentage2" required id="percentage2" placeholder="Percentage 2" disabled>    
                    </div>
                    <div class="col-xs-1">
                        <input class="form-control perstyle" type="number" value="{{ Setting::get('percentage3', 0)  }}" name="percentage3" required id="percentage3" placeholder="Percentage 3" disabled>    
                    </div>
                    <div class="col-xs-1">
                        <input class="form-control perstyle" type="number" value="{{ Setting::get('percentage4', 0)  }}" name="percentage4" required id="percentage4" placeholder="Percentage 4" disabled>    
                    </div>
                    <div class="col-xs-1">
                        <input class="form-control perstyle" type="number" value="{{ Setting::get('percentage5', 0)  }}" name="percentage5" required id="percentage5" placeholder="Percentage 5" disabled>    
                    </div>
                </div>
            <div class="form-group row">
                            <label for="base_price" class="col-xs-3 col-form-label">
                                @lang('admin.member.currency') ( <strong>{{ Setting::get('currency', '$')  }} </strong>)
                            </label>
                            <div class="col-xs-8">
                                <select name="currency" class="form-control" required>
                                    <option @if(Setting::get('currency') == "AUD") selected @endif value="AUD"> Australian Dollar (AUD)</option>
                                <!-- <option @if(Setting::get('currency') == "TZS") selected @endif value="TZS">Tanzanian Shilling (TZS)</option>
                                 <option @if(Setting::get('currency') == "R") selected @endif value="R">South Africa (ZAR)</option>
                                    <option @if(Setting::get('currency') == "$") selected @endif value="$">US Dollar (USD)</option>
				                   <option @if(Setting::get('currency') == "CAD") selected @endif value="CAD">Canada (CAD)</option>
                                    <option @if(Setting::get('currency') == "₹") selected @endif value="₹"> Indian Rupee (INR)</option>
                                    <option @if(Setting::get('currency') == "د.ك") selected @endif value="د.ك">Kuwaiti Dinar (KWD)</option>
                                    <option @if(Setting::get('currency') == "د.ب") selected @endif value="د.ب">Bahraini Dinar (BHD)</option>
                                    <option @if(Setting::get('currency') == "kr") selected @endif value="kr"> Swedish krona (SEK)</option>
                                    <option @if(Setting::get('currency') == "﷼") selected @endif value="﷼">Omani Rial (OMR)</option>
                                    <option @if(Setting::get('currency') == "£") selected @endif value="£">British Pound (GBP)</option>
                                    <option @if(Setting::get('currency') == "€") selected @endif value="€">Euro (EUR)</option>
                                    <option @if(Setting::get('currency') == "CHF") selected @endif value="CHF">Swiss Franc (CHF)</option>
                                    <option @if(Setting::get('currency') == "ل.د") selected @endif value="ل.د">Libyan Dinar (LYD)</option>
                                    <option @if(Setting::get('currency') == "B$") selected @endif value="B$">Bruneian Dollar (BND)</option>
                                    <option @if(Setting::get('currency') == "S$") selected @endif value="S$">Singapore Dollar (SGD)</option>
                                    <option @if(Setting::get('currency') == "AU$") selected @endif value="AU$"> Australian Dollar (AUD)</option>
                                    <option @if(Setting::get('currency') == "MXN") selected @endif value="MXN"> Mexican Dollar (MXN)</option> -->
                                </select>
                            </div>
                        </div>
<!--                         <div class="form-group row">
                            <label for="feature_time" class="col-xs-3 col-form-label">Show future Trip(Hour)</label>
                            <div class="col-xs-8">
                               <input class="form-control" type="number" value="{{ Setting::get('feature_time', '24')  }}" name="feature_time" required id="feature_time" placeholder="Show Feature Trip">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notification_time" class="col-xs-3 col-form-label">Notification for Scheduled Trip (Min)</label>
                            <div class="col-xs-8">
                               <input class="form-control" type="number" value="{{ Setting::get('notification_time', '30')  }}" name="notification_time" required id="notification_time" placeholder="Notification time">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="manual_time" class="col-xs-3 col-form-label">Driver Manual Dispatch TIme (Min)</label>
                            <div class="col-xs-8">
                               <input class="form-control" type="number" value="{{ Setting::get('manual_time', '60')  }}" name="manual_time" required id="manual_time" placeholder="Manual Dispatch TIme">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="close_time" class="col-xs-3 col-form-label">Trip Closing Time(Min)</label>
                            <div class="col-xs-8">
                               <input class="form-control" type="number" value="{{ Setting::get('close_time', '5')  }}" name="close_time" required id="close_time" placeholder="Manual Dispatch TIme">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cancel_percent" class="col-xs-3 col-form-label">Cancellation Percentage</label>
                            <div class="col-xs-8">
                               <input class="form-control" type="number" value="{{ Setting::get('cancel_percent', '0')  }}" name="cancel_percent" required id="cancel_percent" placeholder="Cancellation Percentage">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dispatch_algorithm" class="col-xs-3 col-form-label">Enable Dispatching Algorithm</label>
                            <div class="col-xs-8">
                               <select name="dispatch_algorithm" id="dispatch_algorithm" class="form-control">
                                   <option value="1" @if(Setting::get('dispatch_algorithm') == 1) selected  @endif>Yes</option>
                                   <option value="0" @if(Setting::get('dispatch_algorithm') == 0) selected  @endif>No</option>
                               </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="close_time" class="col-xs-3 col-form-label">Dispatching Distance & Time</label>
                            <div class="col-xs-3">
                               Time
                            </div>
                            <div class="col-xs-3">
                               Distance
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="close_time" class="col-xs-3 col-form-label">Slot 1</label>
                            <div class="col-xs-3">
                               <input class="form-control" type="number" value="{{ Setting::get('time_1', '10')  }}" name="time_1" required id="time_1"><span>Sec</span>
                            </div>
                            <div class="col-xs-3">
                               <input class="form-control" type="number" value="{{ Setting::get('distance_1', '500')  }}" name="distance_1" required id="distance_1"><span>Meter</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="close_time" class="col-xs-3 col-form-label">Slot 2</label>
                            <div class="col-xs-3">
                               <input class="form-control" type="number" value="{{ Setting::get('time_2', '20')  }}" name="time_2" required id="time_2"><span>Sec</span>
                            </div>
                            <div class="col-xs-3">
                               <input class="form-control" type="number" value="{{ Setting::get('distance_2', '1')  }}" name="distance_2" required id="distance_2"><span>Km</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="close_time" class="col-xs-3 col-form-label">Slot 3</label>
                            <div class="col-xs-3">
                               <input class="form-control" type="number" value="{{ Setting::get('time_3', '30')  }}" name="time_3" required id="time_3">
                               <span>Sec</span>
                            </div>
                            <div class="col-xs-3">
                               <input class="form-control" type="number" value="{{ Setting::get('distance_3', '2')  }}" name="distance_3" required id="distance_3"><span>Km</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="close_time" class="col-xs-3 col-form-label">Slot 4</label>
                            <div class="col-xs-3">
                               <input class="form-control" type="number" value="{{ Setting::get('time_4', '40')  }}" name="time_4" required id="time_4">
                               <span>Sec</span>
                            </div>
                            <div class="col-xs-3">
                               <input class="form-control" type="number" value="{{ Setting::get('distance_4', '3')  }}" name="distance_4" required id="distance_4"><span>Km</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="close_time" class="col-xs-3 col-form-label">Slot 5</label>
                            <div class="col-xs-3">
                               <input class="form-control" type="number" value="{{ Setting::get('time_5', '50')  }}" name="time_5" required id="time_5">
                               <span>Sec</span>
                            </div>
                            <div class="col-xs-3">
                               <input class="form-control" type="number" value="{{ Setting::get('distance_5', '4')  }}" name="distance_5" required id="distance_5"><span>Km</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="close_time" class="col-xs-3 col-form-label">Slot 6</label>
                            <div class="col-xs-3">
                               <input class="form-control" type="number" value="{{ Setting::get('time_6', '60')  }}" name="time_6" required id="time_6">
                               <span>Sec</span>
                            </div>
                            <div class="col-xs-3">
                               <input class="form-control" type="number" value="{{ Setting::get('distance_6', '5')  }}" name="distance_6" required id="distance_6"><span>Km</span>
                            </div>
                        </div>
 -->                    </blockquote>
                </div>
            </div>
        </div>

<!--         <div class="panel panel-info" style="margin-bottom: 64px;">
            <div class="panel-heading">@lang('admin.member.payment_settings')</div>
            <div class="panel-wrapper collapse in" aria-expanded="true">
                <div class="panel-body">
                <blockquote class="card-blockquote"> -->
<!--                         <div class="form-group row">
                            <label for="booking_prefix" class="col-xs-3 col-form-label">@lang('admin.member.booking_id_prefix')</label>
                            <div class="col-xs-8">
                                <input class="form-control"
                                    type="text"
                                    value="{{ Setting::get('booking_prefix', '0') }}"
                                    id="booking_prefix"
                                    name="booking_prefix"
                                    min="0"
                                    max="4"
                                    placeholder="Booking ID Prefix">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="base_price" class="col-xs-3 col-form-label">
                                @lang('admin.member.currency') ( <strong>{{ Setting::get('currency', '$')  }} </strong>)
                            </label>
                            <div class="col-xs-8">
                                <select name="currency" class="form-control" required>
                                    <option @if(Setting::get('currency') == "kr") selected @endif value="kr"> Swedish krona (SEK)</option>
                                    <option @if(Setting::get('currency') == "$") selected @endif value="$">US Dollar (USD)</option>
                                    <option @if(Setting::get('currency') == "₹") selected @endif value="₹"> Indian Rupee (INR)</option>
                                    <option @if(Setting::get('currency') == "د.ك") selected @endif value="د.ك">Kuwaiti Dinar (KWD)</option>
                                    <option @if(Setting::get('currency') == "د.ب") selected @endif value="د.ب">Bahraini Dinar (BHD)</option>
                                    <option @if(Setting::get('currency') == "﷼") selected @endif value="﷼">Omani Rial (OMR)</option>
                                    <option @if(Setting::get('currency') == "£") selected @endif value="£">British Pound (GBP)</option>
                                    <option @if(Setting::get('currency') == "€") selected @endif value="€">Euro (EUR)</option>
                                    <option @if(Setting::get('currency') == "CHF") selected @endif value="CHF">Swiss Franc (CHF)</option>
                                    <option @if(Setting::get('currency') == "ل.د") selected @endif value="ل.د">Libyan Dinar (LYD)</option>
                                    <option @if(Setting::get('currency') == "B$") selected @endif value="B$">Bruneian Dollar (BND)</option>
                                    <option @if(Setting::get('currency') == "S$") selected @endif value="S$">Singapore Dollar (SGD)</option>
                                    <option @if(Setting::get('currency') == "AU$") selected @endif value="AU$"> Australian Dollar (AUD)</option>
                                </select>
                            </div>
                        </div>
 -->                       
                         <div class="form-group row">
                            <label for="zipcode" class="col-xs-12 col-form-label"></label>
                            <div class="col-xs-10">
                                <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('admin.member.update_site_settings')</button>
                                <a href="{{route('admin.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
                            </div>
                        </div>
 <!--                     </blockquote>

                </div>
            </div>
        </div>
 -->        
        </form>
        
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
function cash_disable(){
    if($('#cash-payments').is(":checked")) {
        
    } else {
        if($('#stripe_check').is(":checked")) {
            $("#card_field").fadeIn(700);
        } else {
            $('#cash-payments').trigger('click');
        }
    }
}
function cardselect()
{
    if($('#stripe_check').is(":checked")) {
        $("#card_field").fadeIn(700);
    } else {
        if($('#cash-payments').is(":checked")) {
            $("#card_field").fadeOut(700);
        } else {
            $('#cash-payments').trigger('click');
            $("#card_field").fadeOut(700);
        }
    }
}

$(document).ready(function(){
    $check = $('#tip_enable').val();
       if($check == 1){
            $('#tip_field').fadeIn();
       }else{
            $('#tip_field').fadeOut();
       }
    
    $("#tip_enable").bind("change keyup", function(event){
       $val = $(this).val();
       if($(this).val() == 1){
            $('#tip_field').fadeIn();
       }else{
            $('#tip_field').fadeOut();
       }
    });

});
</script>
<script>
$(document).ready(function() {
    $("#stripe_secret_key a").on('click', function(event) {
        event.preventDefault();
        // if($('#stripe_secret_key input').attr("type") == "text"){
        //     $('#stripe_secret_key input').attr('type', 'password');
        //     $('#stripe_secret_key i').addClass( "fa-eye-slash" );
        //     $('#stripe_secret_key i').removeClass( "fa-eye" );
        // }else if($('#stripe_secret_key input').attr("type") == "password"){
        //     $('#stripe_secret_key input').attr('type', 'text');
        //     $('#stripe_secret_key i').removeClass( "fa-eye-slash" );
        //     $('#stripe_secret_key i').addClass( "fa-eye" );
        // }
    });
    $("#stripe_publish_key a").on('click', function(event) {
        event.preventDefault();
        // if($('#stripe_publish_key input').attr("type") == "text"){
        //     $('#stripe_publish_key input').attr('type', 'password');
        //     $('#stripe_publish_key i').addClass( "fa-eye-slash" );
        //     $('#stripe_publish_key i').removeClass( "fa-eye" );
        // }else if($('#stripe_publish_key input').attr("type") == "password"){
        //     $('#stripe_publish_key input').attr('type', 'text');
        //     $('#stripe_publish_key i').removeClass( "fa-eye-slash" );
        //     $('#stripe_publish_key i').addClass( "fa-eye" );
        // }
    });
});
</script>
@endsection