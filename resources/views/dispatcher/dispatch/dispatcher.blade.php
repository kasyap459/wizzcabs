@extends('dispatcher.dispatch.layout.base')

@section('title', 'Dispatcher ')

@section('styles')
<style type="text/css">
     /* For Firefox */
    input[type='number'] {
        -moz-appearance:textfield;
    }
    /* Webkit browsers like Safari and Chrome */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .ui-autocomplete {
        font-weight: bold;
        width: 300px;
        font-size: 12px;
    }
    .ui-autocomplete.source:hover {
        font-weight: bold;
    }
    .ui-autocomplete-loading {
        background: white url("{{asset('main/assets/img/anim_load.gif')}}") right center no-repeat;
        transition: none;
    }
    .infoBox:after{
      content: "";
      position: absolute;
      bottom: -15px;
      left: 125px;
      border-width: 15px 15px 0;
      border-style: solid;
      border-color: #208bf9 transparent;
      display: block;
      width: 0;
    }
    .modal {
        left:auto;
        right:0;
        width: 555px;
        padding-left: 17px !important;
    }
  .modal-dialog {
    max-width: 550px;
    margin: 0px !important;
  }


   .bg-title {
      background: #ffffff08;
      overflow: hidden;
      padding: 4px 24px 3px;
      margin-bottom: 0px;
      position: absolute;
      top: 85px;
      left: 20px;
      z-index: 3;
    }
    .add-trip{
      background: #ffffff08;
      overflow: hidden;
      padding: 4px 4px 3px;
      margin-bottom: 0px;
      position: absolute;
      top: 110px;
      right: 0;
      z-index: 3;
    }
.alert-msg{
      overflow: hidden;
      padding: 4px 4px 3px;
      margin-bottom: 0px;
      position: absolute;
     right: 0;
      z-index: 3;
    }

    .driver-details{
      background: #ffffff08;
      overflow: hidden;
      padding: 4px 4px 3px;
      margin-bottom: 0px;
      position: absolute;
      top: 110px;
      left: 0;
      z-index: 3;
    }
    .form-check-input{
      margin-left: 0px;
    }
    #drivers{
        cursor: pointer;
    }
    #fare_calc,#c_fare_calc{
        color: #01a079;
        margin-bottom: 0px;
    }
    #fare_calc b{
        font-weight: 400;
        line-height: 24px;
        letter-spacing: 1px;
    }
    #c_fare_calc b{
        font-weight: 400;
        line-height: 24px;
        letter-spacing: 1px;
    }
.driver-options.opened {
    left: 0 !important;
/*    box-shadow: 5px 1px 40px rgba(0, 0, 0, 0.1);
*/    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    overflow: hidden;
    padding: 2px;
    display: block;
    width: 300px;
}
.checkbox {
    opacity: 0;
    position: absolute;
}

.label {
    /*background-color: #f0f0f0;*/
    border-radius: 50px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 5px;
    color: #333333;
    padding-bottom: 0px;
    margin-bottom: 0px;
    position: relative;
    border: 1px solid #e4dede;
    height: 23px;
    width: 47px;
    transform: scale(1.5);
}

.label .ball {
    background-color: #27AB18;
    border-radius: 50%;
    position: absolute;
    top: -2px;
    left: 0px;
    height: 23px;
    width: 22px;
    z-index: -1;
    transform: translateX(0px);
    transition: transform 0.2s linear;
}

.checkbox:checked + .label .ball {
    transform: translateX(24px);
}


.fa-calendar {
    color: #000;
}

.fa-car {
    color: #000;
}
/* Steps */
.step {
  position: relative;
  min-height: 4rem;
  color: gray;
  top: 0px;
}
/*.step + .step {
  margin-top: 1.5em
}*/
.step > div:first-child {
  position: static;
  height: 0;
}
.step > div:not(:first-child) {
  margin-left: 1.5em;
}
.step.step-active .circle {
  background-color: #CC0000 !important;
}

/* Circle */
.circle {
  background: #27AB18 !important;
  position: relative;
  width: 10px;
  height: 10px;
  top:18px;
  border-radius: 100%;
  color: #fff;
  text-align: center;
  box-shadow: 0 0 0 3px #fff;
}

/* Vertical Line */
.circle:after {
  content: ' ';
  position: absolute;
  display: block;
  top: -10px;
  right: 50%;
  bottom: 1px;
  left: 50%;
  height: 23px;
  width: 1px;
  transform: scale(1, 2);
  transform-origin: 50% -100%;
  background-color: #27AB18;
  z-index: 0;
}
.step:nth-child(2) .circle:after {
  display: none !important;
}
.step:nth-child(2) .circle {
  top: 15px !important;
}

/* Stepper Titles */
.title {
  line-height: 1.5em;
  font-weight: bold;
}
.caption {
  font-size: 0.8em;
}
.carouselbtn {
    top: 24px;
    margin-left: -7px;
}
.inner-addon {
  position: relative;
}

/* style glyph */
.inner-addon .glyphicon {
  position: absolute;
  padding: 10px;
  pointer-events: none;
}

#dismiss {
    width: 26px;
    height: 38px;
    line-height: 38px;
    text-align: center;
    background: #0099cc;
    color: #fff;
    position: absolute;
    top: 0px;
    right: -26px;
    cursor: pointer;
    -webkit-transition: all 0.3s;
    -o-transition: all 0.3s;
    transition: all 0.3s;
}

#dismiss:hover {
    background: #fff;
    color: #7386D5;
}
#dismisss {
    width: 26px;
    height: 38px;
    line-height: 38px;
    text-align: center;
    cursor: pointer;
    -webkit-transition: all 0.3s;
    -o-transition: all 0.3s;
    transition: all 0.3s;
    background: #0099cc;
    color: #fff;
    overflow: hidden;
    margin-bottom: 0px;
    position: absolute;
    top: 150px;
    left: 0;
    z-index: 3;
}

#dismisss:hover {
    background: #fff;
    color: #7386D5;
}

/* align glyph */
.left-addon .glyphicon  { left:  0px;}
.right-addon .glyphicon { right: 0px;}

/* add padding  */
.left-addon input  { padding-left:  30px; }
.right-addon input { padding-right: 30px; }
#morefield {
    cursor: pointer;
    border-left: none !important;
    border-right: 1px solid #e4e7ea;
    border-bottom-right-radius: 5px;
    border-top-right-radius: 5px;
}
#morefield2 {
    cursor: pointer;
    border-left: none !important;
    border-right: 1px solid #e4e7ea;
    border-bottom-right-radius: 5px;
    border-top-right-radius: 5px;
}
#morefield3 {
    cursor: pointer;
    border-left: none !important;
    border-right: 1px solid #e4e7ea;
    border-bottom-right-radius: 5px;
    border-top-right-radius: 5px;
}
/*input#stop2_address {
    border-bottom-right-radius: 5px;
    border-top-right-radius: 5px;
}*/
#d_latitude {
    border-right: 0;
}
#d_address {
    border-right: 0;
}

.modal-title {
    margin: 0;
    padding-top: 5px;
    padding-left: 35px;
    line-height: 1.5;
    color: #000000;
}
.modal-body {
    position: relative;
    padding: 15px;
    padding-bottom: 5px;
    padding-top: 5px;
}
.modal-content {
    border: none !important;
    border-radius: 10px !important;
}
.modal-header {
  border: 0;
  padding: 0;
  position: relative;
   padding: 0 !important;
    padding-top: 2px;
    padding-bottom: 0px;
     border-bottom: none !important;
}
.close {
    float: left;
    padding-left: -90px;
    font-size: 1rem !important;
    font-weight: 700;
    line-height: 1;
    color: #000;
    /*text-shadow: 0 1px 0 #fff;*/
    opacity: 1;
}
.site-sidebar-second .sidebar-group {
    font-size: 13px;
    padding: 5px 0;
}
.circle {
    background: #00c292;
    position: relative;
    width: 10px;
    height: 10px;
    top: 18px;
    /* line-height: 1.5em; */
    border-radius: 100%;
    color: #fff;
    text-align: center;
    box-shadow: 0 0 0 3px #fff;
}
.carousel-control.left , .carousel-control.right{
  background: none !important;
  color: #000;
 }
.carousel-control.right {
background: none !important;
  color: #000;

}
.modal:before {
  content: '';
  height: 100%;
  vertical-align: middle;
  margin-left: -4px;
}
.modal-header .close {
  margin: 0;
  position: absolute;
  top: -10px;
  left: -10px;
  width: 25px;
  height: 25px;
  border-radius: 25px;
  background-color: #000000;
  color: #fff;
  font-size: 14px !important;
  opacity: 1;
  z-index: 10;
}
.site-sidebar-second .sidebar-group {
    padding-top: 2px;
}
.form-control {
  background-color:#FBFBFB;
    height: 30px;
    font-size: 13px !important;
border-radius: 5px;
}
.input-group-addon {
    padding: 0rem 0.75rem;
}
input#stop1_address {
    border-right: none !important;
}
input#stop2_address {
    border-right: none !important;
}
.create-box {
    padding-left: 1.5rem !important;
    padding-right: 0rem !important;
}
#corporate_name {
    background-image: linear-gradient(45deg, transparent 71%, black 50%),
    linear-gradient(135deg, black 25%, transparent 35%) !important;
    background-position: calc(100% - 10px) 12px,
    calc(100% - 0px) 12px,
    40px 0;
    background-size: 8px 8px,
    10px 8px;
    background-repeat: no-repeat;
    -webkit-appearance: none;
    -moz-appearance: none;
}
.driver-options img {
    opacity: 5 !important;
}
.filter-box {
    width: 163px;
    float: left;
    height:28px !important;
  }
.notification {
	position: absolute;
	top: 12px;
	/* border: 1px solid #FFF; */
	right: 25px;
	font-size: 9px;
	background: #f44336;
	color: #FFFFFF;
	min-width: 20px;
	padding: 0px 5px;
	height: 20px;
	border-radius: 10px;
	text-align: center;
	line-height: 19px;
	vertical-align: middle;
	display: block;
}
.notify {
	/*padding: 5px;*/
	font-size: 20px;
	padding-right: 33px;
	padding-left: 33px;
	color: #f9db08;
}
@media screen and (max-width: 540px) {
  .notification {
	right: 87px;
  }

}
.skin-4 .site-header .navbar {
    background-color: #000 !important;
    border-color: #000 !important;
}
.navbar-nav .nav-item {
    float: right;
}
.site-header .navbar-nav .buttons {
    margin-top: 17px;
    border: 2px solid #ffffff !important;
    color: #4d1a52;
    background: #fff;
}
/*.driver-options {
  top: 160px;
  bottom: 68px;
}*/
.large-sidebar .site-header .navbar {
   height: 60px;
}
.site-header .navbar-nav .buttons {
    margin-top: 17px;
}
.navbar .avatar {
    top: -6px;
}
.form-control:focus {
	box-shadow: none;
	border-color: #d6d6d6;
}
#map1{
width: 100%;
height: 340px;
}
.col-sm-10{
  float:left;
  width:130.333333%;
}

/* circle1 css */
.step.step-active .circle1 {
  background-color: #CC0000 !important;
}
.circle1 {
  background: #27AB18 !important;
  position: relative;
  width: 10px;
  height: 10px;
  top:18px;
  border-radius: 100%;
  color: #fff;
  text-align: center;
  box-shadow: 0 0 0 3px #fff;
}

/* Vertical Line */
.circle1:after {
  content: ' ';
  position: absolute;
  display: block;
  top: -55px;
  right: 50%;
  bottom: 1px;
  left: 50%;
  height: 68px;
  width: 1px;
  transform: scale(1, 2);
  transform-origin: 50% -100%;
  background-color: #27AB18;
  z-index: 0;
}
.step:nth-child(2) .circle1:after {
  display: none !important;
}
.step:nth-child(2) .circle1 {
  top: 15px !important;
}
.circle1 {
    background: #00c292;
    position: relative;
    width: 10px;
    height: 10px;
    top: 18px;
    /* line-height: 1.5em; */
    border-radius: 100%;
    color: #fff;
    text-align: center;
    box-shadow: 0 0 0 3px #fff;
}


/* circle2 css */
.step.step-active .circle2 {
  background-color: #CC0000 !important;
}
.circle2 {
  background: #27AB18 !important;
  position: relative;
  width: 10px;
  height: 10px;
  top:18px;
  border-radius: 100%;
  color: #fff;
  text-align: center;
  box-shadow: 0 0 0 3px #fff;
}

/* Vertical Line */
.circle2:after {
  content: ' ';
  position: absolute;
  display: block;
  top: -30px;
  right: 50%;
  bottom: 1px;
  left: 50%;
  height: 43px;
  width: 1px;
  transform: scale(1, 2);
  transform-origin: 50% -100%;
  background-color: #27AB18;
  z-index: 0;
}
.step:nth-child(2) .circle2:after {
  display: none !important;
}
.step:nth-child(2) .circle2 {
  top: 15px !important;
}
.circle2 {
    background: #00c292;
    position: relative;
    width: 10px;
    height: 10px;
    top: 18px;
    /* line-height: 1.5em; */
    border-radius: 100%;
    color: #fff;
    text-align: center;
    box-shadow: 0 0 0 3px #fff;
}
.dropdown-toggle::after {
        display: none !important;
 }
.dropdown-menu {
    min-width: 11.7rem;
}
a.buttons.btn.btn-sm.w-min-sm.m-l-0-75.waves-effect.waves-light {
    background: #231f20;
    border-color: #231f20 !important;
    color: #fff;
}
button.Zebra_DatePicker_Icon {
    top: 10px !important;
}
.has-search .form-control {
    padding-left: 2.375rem;
}
.has-search .form-control-feedback {
    position: absolute;
    z-index: 2;
    display: block;
    width: 2.375rem;
    height: 2.375rem;
    line-height: 1.600rem;
    text-align: center;
    pointer-events: none;
    color: #aaa;
}
.fixed-header .site-content {
    padding-top: 60px !important;
}
</style>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{asset('main/ZebraDatetimePicker/css/default/zebra_datepicker.min.css')}}" type="text/css">
<link rel="stylesheet" href="{{asset('main/vendor/toastr/toastr.min.css')}}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
@endsection

@section('content')
<div class="site-sidebar-second custom-scroll custom-scroll-dark">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tab-1" role="tab">Create</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-2" role="tab">Trips</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-3" role="tab">Corporate</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-4" role="tab" style="display:none;">Settings</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab-1" role="tabpanel">
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">�</button>
                <span id="message-error"></span>
            </div>
	

            @include('dispatcher.dispatch.create')
        </div>
        <div class="tab-pane ride_list" id="tab-2" role="tabpanel">
        </div>
        <div class="tab-pane" id="tab-3" role="tabpanel">
            <div class="sidebar-chat animated fadeIn">
              <form action="" id="corporate_trip">
                <div class="sidebar-group">
                    <div class="create-box text-black">
                      <select name="corporate_id" id="corporate_id" class="form-control">
                            <option value="">Select Corporate</option>
                        @foreach($corporates as $corporate)
                            <option value="{{ $corporate->id }}">{{ $corporate->display_name }}</option>
                        @endforeach
                      </select>
                      <p class="error-field corporate_id">Please select corporate</p>
                    </div>
                    <div class="create-box text-black">
                      <select name="c_service_type" id="c_service_type" class="form-control">
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="create-box text-black">
                      <select name="c_count" id="c_count" class="form-control">
                          <option value="1">Choose number of taxi</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6</option>
                          <option value="7">7</option>
                          <option value="8">8</option>
                          <option value="9">9</option>
                          <option value="10">10</option>
                      </select>
                      <p class="error-field c_count">Please choose no. taxi</p>
                    </div>
                    <div class="create-box text-black">
                      <input type="text" class="form-control" onfocus="corporateinit()" id="c_address" name="c_address" placeholder="Pickup Location">
                      <input type="hidden" name="c_latitude" id="c_latitude">
                      <input type="hidden" name="c_longitude" id="c_longitude">
                      <p class="error-field c_address">Pickup location required</p>
                    </div>
                    <div class="create-box text-black">
                      <input type="text" class="form-control" onfocus="corporateinit()" id="cd_address" name="cd_address" placeholder="Drop Location">
                      <input type="hidden" name="cd_latitude" id="cd_latitude">
                      <input type="hidden" name="cd_longitude" id="cd_longitude">
                      <p class="error-field cd_address">Drop location required</p>
                    </div>
                    <div class="create-box text-black">
                        <input type="text" class="form-control" id="c_name" name="c_name" placeholder="Name">
                        <p class="error-field c_name">name is required</p>
                    </div>
                    <div class="create-box text-black">
                        <input type="text" class="form-control" id="c_mobile" name="c_mobile" placeholder="Phone Number">
                        <p class="error-field mobile">Phone Number is required</p>
                    </div>
                    <div class="create-box text-black">
                        <select class="form-control" id="c_trip_types" name="c_trip_types">
                            <option value="1">Ride Now</option>
                            <option value="2">Scheduled Ride</option>
                        </select>
                    </div>
                    <div class="create-box text-black" id="c_schedule_time_block">
                        <input type="text" class="form-control" id="c_schedule_time" name="c_schedule_time" placeholder="Schedule Time">
                    </div>
                    <div class="create-box text-black">
                      <input type="text" class="form-control" id="c_message" name="c_message" placeholder="Remarks">
                    </div>
                    <div class="create-box text-black">
                        <button type="button" class="dropbtn" id="c_add_cate">
                            <i class="fa fa-long-arrow-right"></i> Extra
                        </button>
                    </div>
                    <div class="create-box text-black" id="c_drop_pane" style="display: none; padding-top: 10px;">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="c_pet" name="c_pet" value="1">
                            <label class="form-check-label" for="c_pet">Travelling with Pet</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="c_wagon" name="c_wagon" value="1">
                            <label class="form-check-label" for="c_wagon">Station Wagon</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="c_booster" name="c_booster" value="1">
                            <label class="form-check-label" for="c_booster">Booster Seat</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="c_childseat" name="c_childseat" value="1">
                            <label class="form-check-label" for="c_childseat">Child Seat</label>
                        </div>
                    </div>
                    <div class="create-box text-black">
                        <p id="c_fare_calc"></p>
                    </div>
                    <div class="create-box text-black">
                        <button type="button" id="clear" class="btn btn-sm btn-info waves-effect waves-light" onclick=>CLEAR</button>
                        <button type="submit" id="submit" class="btn btn-sm pull-right btn-success waves-effect waves-light submitter" onclick=>SUBMIT</button>
                    </div>
                </div>
              </form>
            </div> 
        </div>
        <!--<div class="tab-pane" id="tab-4" role="tabpanel">
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">�</button>
                <span id="message-success"></span>
            </div>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">�</button>
                <span id="message-success"></span>
            </div>
            <div id="loader" class="center">
                <i class="fa fa-circle-o-notch fa-spin" style="font-size:24px;color:#a377b1;"></i>
            </div>
            <div id="viewer"></div>
        </div>-->
    </div>
</div>
<!-- driver options -->
<div class="driver-option" style="">
	<div class="" style="background: white;z-index: 1000 !important;opacity: 10;">
   		<div class="has-search">
      			<span class="fa fa-search form-control-feedback"></span>
      			<input onkeyup="myFunction()" class="form-control" id="myInput" type="text"  style="z-index: 1000 !important;border-radius:0px;" placeholder="Search for drivers">
    		</div> 
	</div>
	<div id="dismiss">
    		<i class="fa fa-caret-left"></i>
	</div>
	<table class="table" style="margin-bottom:0px;">
    		<tr>
    			<th>Driver Details</th>
    			<th>Status</th>
    		</tr>
	</table>
</div>

<div class="driver-options custom-scroll custom-scroll-dark opened">
	<div class="" style="background: white;z-index: 1000 !important;opacity: 10;">
  </div>
    <div class="driver_list" style="padding:10px;padding-left:0px;"></div>
<!--     <div class="driver_list" style="padding:10px;"></div>
 -->
</div>

@include('dispatcher.dispatch.layout.partials.header')
<div class="site-content">
	
    	<div class="row bg-title">
          <!--<select class="form-control filter-box" id="choose_corporate" onchange="driver_updates()">
              <option value="">Select Company</option>
              @foreach($corporates as $corporate)
                  <option value="{{ $corporate->id }}">{{ $corporate->display_name }}</option>
              @endforeach
          </select>
            <select class="form-control filter-box" id="choose_service" onchange="driver_updates()">
                <option value="">Vehicle Category</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
            </select>
            <select class="form-control filter-box" id="driver_status" onchange="driver_updates()" style="display: none;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="offline">Offline</option>
                <option value="riding">Riding</option>
            </select>-->

		<!--<select class="form-control filter-box" id="seat_count">
                <option value="">Select Seats</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
            </select>
            <select class="form-control filter-box" id="driver_category" onchange="driver_updates()">
                <option value="">Driver Category</option>
                <option value="travel_pet">Animals</option>
                <option value="station_wagon">Station wagon</option>
                <option value="booster_seat">Booster seat</option>
            </select>
        
            <select class="form-control filter-box" id="driver_status" onchange="driver_updates()">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="offline">Offline</option>
                <option value="riding">Riding</option>
            </select>-->
    </div>
    <div class="row alert-msg">
   	<div class="alert alert-success">
        	<button type="button" class="close" data-dismiss="alert">X</button>
         	<span id="message-success"></span>
    	</div>
   </div>
    <div id="dismisss" class="driverdetailbtn">
        <i class="fa fa-caret-right"></i>
    </div>
    <div class="row add-trip">
    <a href="#"><img id="add_trip" src="/asset/img/Add-trip.png" alt="add_trip" /></a>
    </div>
    <div id="map">
    </div>
</div>
<div id="livemodalmap" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">@lang('admin.triplist.trip_details')</h4>
      </div>
      <div class="modal-body">
        <div id="map1"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.triplist.close')</button>
      </div>
    </div>
</div>
</div>

<div id="AutoAssignModal" class="modal fade" role="dialog" style="left: 380px;top:150px;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close autoassignclosebtn" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">@lang('admin.triplist.assign_driver')</h4>
      </div>
      <div class="modal-body">
        <table id="getcode" class="table"></table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default autoassignclosebtn" data-dismiss="modal">@lang('admin.triplist.close')</button>
      </div>
    </div>
</div>
</div>
@endsection

@section('scripts')

<script src="https://maps.googleapis.com/maps/api/js?key={{ Setting::get('map_key', 'AIzaSyC7urojphmUg5qlseNH99Rojwn9Y-Amc0w') }}&libraries=places" ></script>
<script type="text/javascript" src="{{asset('main/assets/js/markerwithlabel.js')}}"></script>
<script type="text/javascript" src="{{asset('main/assets/js/infobox.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>-->
<script type="text/javascript" src="{{asset('main/vendor/toastr/toastr.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/zebra_pin@2.0.0/dist/zebra_pin.min.js"></script>
<script src="{{asset('main/ZebraDatetimePicker/zebra_datepicker.min.js')}}"></script>
<script type="text/javascript">
    $(window).load(function(){
        worldMapInitialize();
        $('.alert').hide();
    });
    window.vx = {!! json_encode([
        "minDate" => \Carbon\Carbon::today()->format('d-m-Y g:i A'),
        "maxDate" => \Carbon\Carbon::today()->addDays(6)->format('d-m-Y g:i A'),
    ]) !!}
    $('#schedule_time').Zebra_DatePicker({
        datepicker : false,
        ampm: true, // FOR AM/PM FORMAT
        format : 'd-m-Y g:i A',
	direction: [window.vx.minDate, window.vx.maxDate],
    });
    $('.Zebra_DatePicker_Icon_Wrapper').removeAttr('style');
    $("#c_schedule_time").datetimepicker({
 	datepicker : false,
        ampm: true, // FOR AM/PM FORMAT
        format : 'd-m-Y g:i A',
	direction: [window.vx.minDate, window.vx.maxDate],
    });

    $('#add_cate').click(function(){
        $('#drop_pane').slideToggle("fast");
    });
    $('#c_add_cate').click(function(){
        $('#c_drop_pane').slideToggle("fast");
    });
    $('#trip_types').on('change', function() {
        var val = this.value;
        if(val ==2){
            $('#schedule_time_block').show();
        }else{
            $('#schedule_time_block').hide();
        }
    });
    $('#c_trip_types').on('change', function() {
        var val = this.value;
        if(val ==2){
            $('#c_schedule_time_block').show();
        }else{
            $('#c_schedule_time_block').hide();
        }
    });
    $('#clear').click(function(){
        $('.alert').hide();
        $('input[type=text]').val('').removeAttr('selected');
        $('#service_type').val('');
        $('#trip_types').val('1');
        $('#trip_types').trigger("change");
        $("#pet").prop('checked', false);
        $("#wagon").prop('checked', false);
        $("#booster").prop('checked', false);
        $("#childseat").prop('checked', false);
        $('#drop_pane').slideUp("fast");
        $('#fare_calc').text('');
        worldMapInitialize();
    });
    $('#cancel').click(function(){
      $('.alert').hide();
        $('input[type=text]').val('').removeAttr('selected');
        $('#service_type').val('');
        container = $('.site-sidebar-second');
        container.removeClass('opened');
        $('.template-options').show();
        $('#fare_calc').text('');
        worldMapInitialize();
    });

</script>
<script>
    $( "#mobile" ).autocomplete({
          source : '/dispatcher/dispatch/users-phone',
          minlenght:3,
          autoFill:true,
          select:function(e,ui){
            $('#mobile').val(ui.item.value);
            $('#email').val(ui.item.email);
            $('#name').val(ui.item.name);
          }
    });
    $( "#email" ).autocomplete({
          source : '/dispatcher/dispatch/users-email',
          minlenght:3,
          autoFill:true,
          select:function(e,ui){
            $('#email').val(ui.item.value);
            $('#mobile').val(ui.item.phone);
            $('#name').val(ui.item.name);
          }
    });
    $( "#corporate_name" ).autocomplete({
          source : '/dispatcher/dispatch/corporate-user',
          minlenght:3,
          autoFill:true,
          select:function(e,ui){
            $('#corporate_name').val(ui.item.value);
            $('#email').val(ui.item.email);
            $('#mobile').val(ui.item.phone);
            $('#name').val(ui.item.name);
          }
    });
     $("#create_trip").submit(function(event){
      $('.alert').hide();
        event.preventDefault();
        event.stopPropagation();
        var require = 0;
        if($('#first_name').val() == ''){
            $('.first_name').show();
            require = 1;
        }else{
            $('.first_name').hide();
        }
    
        if($('#email').val() == ''){
            $('.email').show();
            require = 1;
        }else{
            $('.email').hide();
        }

        if($('#mobile').val() == ''){
            $('.mobile').show();
            require = 1;
        }else{
            $('.mobile').hide();
        }
        if($('#s_address').val() == ''){
            $('.s_address').show();
            require = 1;
        }else{
            $('.s_address').hide();
        }
        if($('#d_address').val() == ''){
            $('.d_address').show();
            require = 1;
        }else{
            $('.d_address').hide();
        }
        if($("#provider_auto_assign").prop("checked") == false){
		$("#provider_auto_assign").val('false');         
        }else{
            $("#provider_auto_assign").val('true');
        }
        if(require == 1){
            return false;
        }
	var service = $('#service_type').val();
        $.ajax({
            url: '/dispatcher/dispatch',
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            type: 'POST',
            data: $("#create_trip").serialize(),
            success: function(data) {
              if(data.success ==1){
		$('#clear').trigger("click");
                $('.alert-success').show().delay(5000).fadeOut(); 
            		$("#message-success").append(data.message);
                            activeTab('tab-1');
            		$("#myModal .close").trigger("click");
		viewer(data.id);
		            if($("#provider_auto_assign").prop("checked") == false){
                $("#getcode").empty();
            		var latitude = $("#s_address").val();
            		var longitude = $("#d_address").val();
			          var id = data.id; 
            		var current = data.current;
                // var service = $('#service_type').val();

            			$.get('dispatcher/providers', { 
                			//latitude: 11.02974830,
                			//longitude: 76.94634310,
                			service_type: service,
            			}, function(result) {
                			var count = result.total;
                			console.log(result);
                			if(count > 0){
                				var i=0;
                				for( i=0 ; i<count; i++ ){
          							$("#getcode").append("<tr><td>"+result.data[i].name +"</td><td>" +result.data[i].service.name+" </td><td>"+result.data[i].mobile +"</td><td><a href='dispatcher/trips/"+id+'/'+result.data[i].id+"' class='btn btn-success'>@lang('admin.triplist.assign_driver')</a> </td></tr>")
                				}
                			}
               				else{
                    				$("#getcode").append("<tr><td>@lang('admin.triplist.no_drivers_found')</td></tr>");
               				}
            			});

        			$("#AutoAssignModal").modal("toggle");
        			$("div").removeClass("modal-backdrop");
                    	}else{
        			location.reload();
        		}
                      }else{
                        $('.alert-danger').show();
                        $("#message-error").html(data.message);
                      }
		$("#create_trip").trigger("reset");
              /*location.reload();*/

            }
        });
    });

    $("#corporate_trip").submit(function(event){
      $('.alert').hide();
        event.preventDefault();
        event.stopPropagation();
        var require = 0;
        if($('#corporate_id').val() == ''){
            $('.corporate_id').show();
            require = 1;
        }else{
            $('.corporate_id').hide();
        }
        
        if($('#c_count').val() == ''){
            $('.c_count').show();
            require = 1;
        }else{
            $('.c_count').hide();
        }

        if($('#c_address').val() == ''){
            $('.c_address').show();
            require = 1;
        }else{
            $('.c_address').hide();
        }
        if($('#cd_address').val() == ''){
            $('.cd_address').show();
            require = 1;
        }else{
            $('.cd_address').hide();
        }
        if(require == 1){
            return false;
        }
        $.ajax({
            url: '/dispatcher/dispatch/corporate',
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            type: 'POST',
            data: $("#corporate_trip").serialize(),
            success: function(data) {
              if(data.success ==1){
                $('#clear').trigger("click");
                $('.alert-success').show();
                $("#message-success").html(data.message);
                activeTab('tab-2');
                ride_updates();
              }else{
                $('.alert-danger').show();
                $("#message-error").html(data.message);
              }
            }
        });
    });

    $('#service_type').on('change', function() {
        fare_calculation();
    });
    $('#c_service_type').on('change', function() {
        c_fare_calculation();
    });
    function fare_calculation(){
        var val = $('#service_type').val();
        var s_latitude1 = $('#s_latitude').val();
        var s_longitude1 = $('#s_longitude').val();
        var d_latitude1 = $('#d_latitude').val();
        var d_longitude1 = $('#d_longitude').val();
        if(s_latitude1 !='' && s_latitude1 !='' && val !=''){
            $.ajax({
                url: '/dispatcher/dispatch/fare',
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
                type: 'POST',
                data: {
                        s_latitude: s_latitude1,
                        s_longitude: s_longitude1,
                        d_latitude: d_latitude1,
                        d_longitude: d_longitude1,
                        service_type: val
                        },
                success: function(data) {
                    $('#fare_calc').html('<b>Estimated Fare</b><table><tr><td><b>Fare type</b></td><td>:'+data[5]+'</td></tr><tr><td><b>Fare</b></td><td>:'+data[4]+data[2]+'</td></tr><tr><td><b>Distance</b></td><td>:'+data[0]+' '+data[3]+'</td></tr><tr><td><b>Time</b></td><td>:'+data[1]+' min</td></tr></table>');
                }
            });
        }
    }

    function c_fare_calculation(){
        var c_val = $('#c_service_type').val();
        var c_latitude1 = $('#c_latitude').val();
        var c_longitude1 = $('#c_longitude').val();
        var cd_latitude1 = $('#cd_latitude').val();
        var cd_longitude1 = $('#cd_longitude').val();
        if(c_latitude1 !='' && c_latitude1 !='' && c_val !=''){
            $.ajax({
                url: '/dispatcher/dispatch/fare',
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
                type: 'POST',
                data: {
                        s_latitude: c_latitude1,
                        s_longitude: c_longitude1,
                        d_latitude: cd_latitude1,
                        d_longitude: cd_longitude1,
                        service_type: c_val
                        },
                success: function(data) {
                    $('#c_fare_calc').html('<b>Estimated Fare</b><table><tr><td><b>Fare type</b></td><td>:'+data[5]+'</td></tr><tr><td><b>Fare</b></td><td>:'+data[4]+data[2]+'</td></tr><tr><td><b>Distance</b></td><td>:'+data[0]+' '+data[3]+'</td></tr><tr><td><b>Time</b></td><td>:'+data[1]+' min</td></tr></table>');
                }
            });
        }
    }

    $(document).on('click','#trips', function() {
        $('.alert').hide();
        var id = $(this).attr("data-id");
            activeTab('tab-4');
            viewer(id);
        });

    function viewer(id){
        $("#loader").show();
        $("#viewer").empty();
        $.get('/dispatcher/dispatch/viewtrip/' +id, function( data ) {
                $("#viewer").html(data);
                $("#loader").hide();
           });
    }
    $(document).on('click','#assign', function() {
        var id = $(this).attr("data-id");
        var provider_id = $(this).attr("data-provider");
        $.get('/dispatcher/dispatch/assign/' +id+'/'+provider_id, function( data ) {
                activeTab('tab-2');
                ride_updates();
           });
    });
    function driver_updates(){
      var status = $('#driver_status').val();
      var driver_category = $('#driver_category').val();
      var seat_count = $('#seat_count').val();
      var data_driverString = "status="+status+"&driver_category="+driver_category+"&seat_count="+seat_count;
      var choose_corporate = $('#choose_corporate').val();
      var choose_service = $('#choose_service').val();

        $.ajax
            ({
              cache: false,
              data: data_driverString,
              headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
              type: "GET",
              url: "/dispatcher/dispatch/driver-list",
              success: function(data)
              {
                
                 $('.driver_list').html(data);
              }
            });
    }
    function ride_updates(){
        $.ajax
            ({
              cache: false,
              type: "GET",
              url: "/dispatcher/dispatch/ride-list",
              success: function(data)
              {
                 $('.ride_list').html(data);
              }
            });
    }
    function activeTab(tab){
        $('.nav-tabs a[href="#' + tab + '"]').tab('show');
    };
    $(document).ready(function(){
    driver_updates();
    ride_updates()
    setInterval(function(){
            driver_updates();
            }, 5000);
    setInterval(function(){
            ride_updates();
            }, 8000);
    });
</script>
<!-- <script>
    function driver_movement(){
        $.ajax
            ({
              cache: false,
              headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
              type: "GET",
              url: "/dispatcher/driver-movement",
              success: function(data)
              {
                jQuery.each(data, $.proxy(function(index, element) {
                    toastr.options = {
                        positionClass: 'toast-bottom-right',
                        timeOut: 10000
                    };
                    toastr.error(element+' driver not moving');
                }, this));
              }
            });
    }
    $(document).ready(function(){
        driver_movement();
        setInterval(function(){
            driver_movement();
        }, 30000);
    });   
</script>
 --><script>

    var map;
    var users;
    var providers;
    var latitude;
    var longitude;
    var zooming;
    var datas = [];
    var ajaxMarkers = [];
    var mapMarkers = [];
    var googleMarkers = [];
    var mapIcons = {
      active: "{{asset('asset/img/Car.png')}}",
      riding: "{{asset('asset/img/Car.png')}}",
      offline: "{{asset('asset/img/Car.png')}}",
      person: "{{asset('asset/img/personicon.png')}}",
    }
    var mapIcons1 = {
        active: "{{asset('asset/img/Green.png')}}",
        riding: "{{asset('asset/img/Blue.png')}}",
        offline: "{{asset('asset/img/Red.png')}}",
    }
    var map, mapMarkers = [];
    var source, destination;
    var s_input, d_input;
    var stop1_input, stop2_input;
    var s_latitude, s_longitude;
    var d_latitude, d_longitude;
    var stop1_latitude, stop1_longitude;
    var stop2_latitude, stop2_longitude;  
      var distance;
      var seconds;

    var infoDefault = [];
    var infoWindows = [];
    var interval;
    var worldinterval;
    var checker =0;
    
    function worldMapInitialize() {
        clearInterval(interval);
        clearInterval(worldinterval);
        var checker =0;
        for (var i = 0; i < googleMarkers.length; i++ ) {
            googleMarkers[i].setMap(null);
        }
        googleMarkers.length = 0;
        for (var i=0;i<infoDefault.length;i++) {
            infoDefault[i].close();
        }
        infoDefault.length = 0;

        latitude = parseFloat("{{ Setting::get('address_lat') }}");
        longitude = parseFloat("{{ Setting::get('address_long') }}");
        zooming = parseInt("{{ Setting::get('zoom') }}");
            
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: latitude, lng: longitude},
            zoom: zooming,
            mapTypeControl: true,
              mapTypeControlOptions: {
                  style: google.maps.MapTypeControlStyle.SMALL,
                  position: google.maps.ControlPosition.BOTTOM_LEFT,
                  mapTypeIds: ['roadmap', 'satellite']
              },
              zoomControl: true,
              zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL,
                position: google.maps.ControlPosition.BOTTOM_RIGHT
              }                 
        });
        ajaxMapData();
      interval = setInterval(ajaxMapData, 5000);

        var mapelement = document.getElementById('map');

        // var legend = document.createElement('div');
        // legend.setAttribute("id", "legend");
        // mapelement.append(legend);

        // var div = document.createElement('div');
        // div.className = ""; 
        // div.innerHTML = '<button class="btn-warning"><img src="' + mapIcons['person'] + '"> ' + '   Person ' + ' <input type="checkbox" name="filter_list" value="person"></button><button class="btn-success" style="margin-left:2px;"><img src="' + mapIcons['active'] + '"> ' + 'Acitve' + ' <input type="checkbox" name="filter_list" value="active">';
        // legend.appendChild(div);

        
        // var div = document.createElement('div');
        // div.style = "margin-top: 10px;"; 
        // div.innerHTML = '<button class="btn-primary"><img src="' + mapIcons['riding'] + '"> ' + 'Riding' + ' <input type="checkbox" name="filter_list" value="riding"></button><button class="btn-danger" style="margin-left:2px;"><img src="' + mapIcons['offline'] + '"> ' + 'Offline' + ' <input type="checkbox" name="filter_list" value="offline"></button>';
        // legend.appendChild(div);

        // map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);
        // worldinterval = setInterval(worldMapInitialize, 120000);
    }

    function ajaxMapData(){
        var filterarray = new Array();
        var filters;
        $("input:checkbox[name=filter_list]:checked").each(function(){
            filterarray.push($(this).val());
        });
        if(filterarray.length >0){
            filters = filterarray;
        }else{
            filters = ["active", "riding", "offline"];
        }
        var dataString = "filters="+filters;

        $.ajax({
            url: "{{ route('dispatcher.dispatch.map') }}",
            dataType: "JSON",
            data: dataString,
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            type: "GET",
            success: function(data) {
                for (var i = 0; i < googleMarkers.length; i++ ) {
                    googleMarkers[i].setMap(null);
                }
                googleMarkers.length = 0;
                
                for (var i=0;i<infoDefault.length;i++) {
                    infoDefault[i].close();
                }
                infoDefault.length = 0;
                
                ajaxMarkers = data;
                jQuery.each(ajaxMarkers, $.proxy(function(index, element) {
                    if(checker ==0 || checker ==element.id){
                    var lat = parseFloat(element.latitude);
                    var lng = parseFloat(element.longitude);
                    var map_pos = {lat: lat, lng: lng};
                    var  status  = element.status;
                    if (!status){
                       return;
                    }
                    // if(element.status==)
                    var marker = new MarkerWithLabel({
                       position: new google.maps.LatLng(lat, lng),
                       icon: mapIcons1[element.status],
                       draggable: false,
                       raiseOnDrag: true,
                       map: map,
                       labelAnchor: new google.maps.Point(22, 0),
                       labelClass: '', // the CSS class for the label
                       labelStyle: {opacity: 1.0},
                       markerid:element.id
                    });

                    mapMarkers[element.id] = marker;
                    googleMarkers.push(marker);
                    
                    google.maps.event.addListener(marker, 'click', function() {
                        checker = marker.markerid;
                        for (var i=0;i<infoWindows.length;i++) {
                            infoWindows[i].close();
                        }
                        geocodeLatLng(map, element, marker);
                        ajaxMapData();
                        map.setZoom(19);
                    });

                    google.maps.event.addListener(map, "click", function(event) {
                        checker = 0;
                        for (var i=0;i<infoWindows.length;i++) {
                            infoWindows[i].close();
                        }
                        map.setZoom(10);
                    });

                    }
                }, this));

            }
        });
    }

    function geocodeLatLng(map, element, marker) {
        //alert(element.latitude);
      var geocoder = new google.maps.Geocoder;
      var latlng = {lat: parseFloat(element.latitude), lng: parseFloat(element.longitude)};
      geocoder.geocode({'location': latlng}, function(results, status) {
        if (status === 'OK') {
          if (results[0]) {
            var addr = results[0].formatted_address;
          } else {
            var addr ='';
          }
          var boxText = document.createElement("div");
          boxText.style.cssText = "border: 1px solid #208bf9; color: white; background: #208bf9; padding: 5px; border-radius: 8px; font-size: 14px;";
          boxText.innerHTML = "<b>"+element.name+" ("+element.dial_code + element.mobile+") - "+ element.vehicle_no+" ("+element.vehicle_model+")</b><br>"+addr+"<br>";

          var myOptions = {
             content: boxText,
             disableAutoPan: false,
             maxWidth: 0,
             pixelOffset: new google.maps.Size(-143, -130),
             zIndex: null,
             boxStyle: { 
              opacity: 0.75,
              width: "280px"
             },
             infoBoxClearance: new google.maps.Size(1, 1),
             closeBoxURL: "",
             isHidden: false,
             pane: "floatPane",
             enableEventPropagation: false
          };
          //map.setZoom(18);
          var ib = new InfoBox(myOptions);
          ib.open(map, marker);
          infoWindows.push(ib); 
          
        } else {
          // window.alert('Geocoder failed due to: ' + status);
        }
      });
    }

    // function initrip(){
    //     s_input = document.getElementById('s_address');
    //     d_input = document.getElementById('d_address');
    //     stop1_input = document.getElementById('stop1_address');
    //     stop2_input = document.getElementById('stop2_address');

    //     s_latitude = document.getElementById('s_latitude');
    //     s_longitude = document.getElementById('s_longitude');

    //     stop1_latitude = document.getElementById('stop1_latitude');
    //     stop1_longitude = document.getElementById('stop1_longitude');

    //     stop2_latitude = document.getElementById('stop2_latitude');
    //     stop2_longitude = document.getElementById('stop2_longitude');

    //     d_latitude = document.getElementById('d_latitude');
    //     d_longitude = document.getElementById('d_longitude');
        
    //     distance = document.getElementById('distance');

    //     var autocomplete_source = new google.maps.places.Autocomplete(s_input);
    //     autocomplete_source.bindTo('bounds', map);

    //     /*autocomplete_source.setFields(
    //         ['address_components','geometry','icon','name','formatted_address']);*/

    //     /*autocomplete_source.setComponentRestrictions(
    //         {'country': ['se','in']});*/

    //     var autocomplete_stop1 = new google.maps.places.Autocomplete(stop1_input);
    //     autocomplete_stop1.bindTo('bounds', map);

    //     var autocomplete_stop2 = new google.maps.places.Autocomplete(stop2_input);
    //     autocomplete_stop2.bindTo('bounds', map);

    //     var autocomplete_destination = new google.maps.places.Autocomplete(d_input);
    //     autocomplete_destination.bindTo('bounds', map);

    //     /*autocomplete_destination.setFields(
    //         ['address_components','geometry','icon','name','formatted_address']);*/
        
    //     /*autocomplete_destination.setComponentRestrictions(
    //         {'country': ['se','in']});*/

    //     var directionsService = new google.maps.DirectionsService;
    //     var directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true});

    //     autocomplete_source.addListener('place_changed', function(event) {
    //         var place = autocomplete_source.getPlace();
    //         if (place.hasOwnProperty('place_id')) {
    //             if (!place.geometry) {
    //                     // window.alert("Autocomplete's returned place contains no geometry");
    //                     return;
    //             }
    //             s_latitude.value = place.geometry.location.lat();
    //             s_longitude.value = place.geometry.location.lng();
    //             source = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
    //         }
    //     });

    //     autocomplete_destination.addListener('place_changed', function(event) {
    //         var place = autocomplete_destination.getPlace();
    //         if (place.hasOwnProperty('place_id')) {
    //             if (!place.geometry) {
    //                     // window.alert("Autocomplete's returned place contains no geometry");
    //                     return;
    //             }
    //             d_latitude.value = place.geometry.location.lat();
    //             d_longitude.value = place.geometry.location.lng();
    //             destination = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
    //             updateRoute();

    //           var val = $('#service_type').val();
    //           var s_latitude1 = $('#s_latitude').val();
    //           var s_longitude1 = $('#s_longitude').val();
    //           var d_latitude1 = $('#d_latitude').val();
    //           var d_longitude1 = $('#d_longitude').val();
    //           if(s_latitude1 !='' && s_latitude1 !='' && val !=''){
    //               $.ajax({
    //                   url: '/dispatcher/dispatch/fare',
    //                   dataType: 'json',
    //                   headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
    //                   type: 'POST',
    //                   data: {
    //                           s_latitude: s_latitude1,
    //                           s_longitude: s_longitude1,
    //                           d_latitude: d_latitude1,
    //                           d_longitude: d_longitude1,
    //                           service_type: val
    //                           },
    //                   success: function(data) {
    //                       $('.currency_symbol').text(data[4]);
    //                       $('.estimate_fare').text(data[2]);
    //                       $('.drop_off').text(data[6]);
    //                   }
    //               });
    //           }



    //         }
    //     });

    //     function updateRoute() {

    //         latitude = parseFloat("{{ Auth::guard('dispatcher')->user()->dispatch_lat }}");
    //         longitude = parseFloat("{{ Auth::guard('dispatcher')->user()->dispatch_long }}");
    //         zooming = parseInt("{{ Auth::guard('dispatcher')->user()->dispatch_zoom }}");
                
    //         map = new google.maps.Map(document.getElementById('map'), {
    //             center: {lat: latitude, lng: longitude},
    //             zoom: zooming,
    //             mapTypeControl: true,
    //               mapTypeControlOptions: {
    //                   style: google.maps.MapTypeControlStyle.SMALL,
    //                   position: google.maps.ControlPosition.TOP_CENTER,
    //                   mapTypeIds: ['roadmap', 'satellite']
    //               },
    //               zoomControl: true,
    //               zoomControlOptions: {
    //                 style: google.maps.ZoomControlStyle.SMALL,
    //                 position: google.maps.ControlPosition.BOTTOM_CENTER
    //               }
                  
                     
    //         });

    //         var marker = new google.maps.Marker({
    //             map: map,
    //             icon: '/asset/img/marker-start.png',
    //             anchorPoint: new google.maps.Point(0, -29)
    //         });

    //          var markerSecond = new google.maps.Marker({
    //             map: map,
    //             icon: '/asset/img/marker-end.png',
    //             anchorPoint: new google.maps.Point(0, -29)
    //         });

    //         directionsDisplay.setMap(null);
    //         directionsDisplay.setMap(map);
             
    //         directionsService.route({
    //             origin: source,
    //             destination: destination,
    //             travelMode: google.maps.TravelMode.DRIVING,
    //             // unitSystem: google.maps.UnitSystem.IMPERIAL,
    //         }, function(result, status) {
    //             if (status == google.maps.DirectionsStatus.OK) {
    //                 directionsDisplay.setDirections(result);
    //                 marker.setPosition(result.routes[0].legs[0].start_location);
    //                 markerSecond.setPosition(result.routes[0].legs[0].end_location);
    //                 distance.value = result.routes[0].legs[0].distance.value / 1000;
    //                 fare_calculation();
    //             }
    //         });
    //     }
    // }

</script>
<script>
    function initrip(){

     // $("input").keyup(function() {
     // if($(this).val().length >2) {
        s_input = document.getElementById('s_address');
        d_input = document.getElementById('d_address');
        stop1_input = document.getElementById('stop1_address');
        stop2_input = document.getElementById('stop2_address');
        stop3_input = document.getElementById('stop3_address');

        s_latitude = document.getElementById('s_latitude');
        s_longitude = document.getElementById('s_longitude');

        d_latitude = document.getElementById('d_latitude');
        d_longitude = document.getElementById('d_longitude');
        
         s1_latitude = document.getElementById('stop1_latitude');
         s1_longitude = document.getElementById('stop1_longitude');

         s2_latitude = document.getElementById('stop2_latitude');
         s2_longitude = document.getElementById('stop2_longitude');

         s3_latitude = document.getElementById('stop3_latitude');
         s3_longitude = document.getElementById('stop3_longitude');

        distance = document.getElementById('distance');
        seconds = document.getElementById('seconds');

        // latitude = 42.88668185451463;
        // longitude = -76.17038047904786;
        // zooming = 10;
            
            latitude = parseFloat("{{ Auth::guard('dispatcher')->user()->dispatch_lat }}");
            longitude = parseFloat("{{ Auth::guard('dispatcher')->user()->dispatch_long }}");
            zooming = parseInt("{{ Auth::guard('dispatcher')->user()->dispatch_zoom }}");

         var autocomplete_stop3 = new google.maps.places.Autocomplete(stop3_input);
         autocomplete_stop3.setComponentRestrictions({'country': ['ind']});

        // var service = new google.maps.places.PlacesService(map);
        // var des_service = new google.maps.places.PlacesService(map);
        // var s1_service = new google.maps.places.PlacesService(map);
        // var s2_service = new google.maps.places.PlacesService(map);
        // var s3_service = new google.maps.places.PlacesService(map);

        var marker = new google.maps.Marker({
            map: map,
            draggable: true,
            anchorPoint: new google.maps.Point(0, -29),
            icon: '/asset/img/marker-start.png'
        });

        var markerSecond = new google.maps.Marker({
            map: map,
            draggable: true,
            anchorPoint: new google.maps.Point(0, -29),
            icon: '/asset/img/marker-end.png'
        });

        var marker3 = new google.maps.Marker({
            map: map,
            draggable: true,
            anchorPoint: new google.maps.Point(0, -29),
            icon: '/asset/img/marker-end.png'
        });

        var marker4 = new google.maps.Marker({
            map: map,
            draggable: true,
            anchorPoint: new google.maps.Point(0, -29),
            icon: '/asset/img/marker-end.png'
        });

        var marker5 = new google.maps.Marker({
            map: map,
            draggable: true,
            anchorPoint: new google.maps.Point(0, -29),
            icon: '/asset/img/marker-end.png'
        });

        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true});

        google.maps.event.addListener(map, 'click', updateMarker);
        google.maps.event.addListener(map, 'click', updateMarkerSecond);
        google.maps.event.addListener(map, 'click', updateMarker_stop1);
        google.maps.event.addListener(map, 'click', updateMarker_stop2);
        google.maps.event.addListener(map, 'click', updateMarker_stop3);

        google.maps.event.addListener(marker, 'dragend', updateMarker);
        google.maps.event.addListener(markerSecond, 'dragend', updateMarkerSecond);
        google.maps.event.addListener(marker3, 'dragend', updateMarker_stop1);
        google.maps.event.addListener(marker4, 'dragend', updateMarker_stop2);
        google.maps.event.addListener(marker5, 'dragend', updateMarker_stop3);

        window.autocomplete_source =false;
        var s_input = document.getElementById('s_address');

        $('#s_address').keyup(function(e) {
            if ($('#s_address').val().length < 4) {
                console.log(s_input.value.length);
                e.stopImmediatePropagation()
            } else {
                if (window.autocomplete_source == false) {
                    window.autocomplete_source = new google.maps.places.Autocomplete(s_input);
                    google.maps.event.addListener(autocomplete_source, 'place_changed', function() {
                    marker.setVisible(false);
                    var place = autocomplete_source.getPlace();
                    if (place.hasOwnProperty('place_id')) {
                        if (!place.geometry) {
                            window.alert("Autocomplete's returned place contains no geometry");
                            return;
                        }
                        updateSource(place.geometry.location);
                    } else {
                        service.textSearch({
                            query: place.name
                        }, function(results, status) {
                            if (status == google.maps.places.PlacesServiceStatus.OK) {
                                updateSource(results[0].geometry.location);
                                s_input.value = results[0].formatted_address;
                            }
                        });
                    }
                    });
                }
            }
        });

        window.autocomplete_destination =false;
        var d_input = document.getElementById('d_address');

        $('#d_address').keyup(function(e) {
            if ($('#d_address').val().length < 4) {
                e.stopImmediatePropagation()
            } else {
                if (window.autocomplete_destination == false) {
                    window.autocomplete_destination = new google.maps.places.Autocomplete(d_input);
                    google.maps.event.addListener(autocomplete_destination, 'place_changed', function() {
                    markerSecond.setVisible(false);
                    var place = autocomplete_destination.getPlace();
                    if (place.hasOwnProperty('place_id')) {
                        if (!place.geometry) {
                            window.alert("Autocomplete's returned place contains no geometry");
                            return;
                        }
                        updateDestination(place.geometry.location);
                    } else {
                        des_service.textSearch({
                            query: place.name
                        }, function(results, status) {
                            if (status == google.maps.places.PlacesServiceStatus.OK) {
                                updateDestination(results[0].geometry.location);
                                d_input.value = results[0].formatted_address;
                            }
                        });
                    }
                      var val = $('#service_type').val();
                      var s_latitude = $('#s_latitude').val();
                      var s_longitude = $('#s_longitude').val();
                      var d_latitude = $('#d_latitude').val();
                      var d_longitude = $('#d_longitude').val();
                      var distance = $('#distance').val();
                      var seconds = $('#seconds').val();
                      if(s_latitude !='' && s_latitude !='' && val !=''){
                          $.ajax({
                              url: '/dispatcher/dispatch/fare',
                              dataType: 'json',
                              headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
                              type: 'POST',
                              data: {
                                      s_latitude: s_latitude,
                                      s_longitude: s_longitude,
                                      d_latitude: d_latitude,
                                      d_longitude: d_longitude,
                                      service_type: val,
                                      distance: distance,
                                      seconds: seconds,
                                      },
                              success: function(data) {
                                  $('.currency_symbol').text(data[4]);
                                  $('.estimate_fare').text(data[2]);
                                  $('.drop_off').text(data[6]);
                              }
                          });
                      }

                    });
                }
            }
        });

        window.autocomplete_stop1 =false;
        stop1_input = document.getElementById('stop1_address');

        $('#stop1_address').keyup(function(e) {
            if ($('#stop1_address').val().length < 4) {
                e.stopImmediatePropagation()
            } else {
                if (window.autocomplete_stop1 == false) {
                    window.autocomplete_stop1 = new google.maps.places.Autocomplete(stop1_input);
                    google.maps.event.addListener(autocomplete_stop1, 'place_changed', function() {
                    marker.setVisible(false);
                    var place = autocomplete_stop1.getPlace();
                  if (place.hasOwnProperty('place_id')) {
                  if (!place.geometry) {
                      window.alert("Autocomplete's returned place contains no geometry");
                      return;
                  }
                  updatestop1(place.geometry.location);
                  }else {
                  des_service.textSearch({
                      query: place.name
                  }, function(results, status) {
                      if (status == google.maps.places.PlacesServiceStatus.OK) {
                          updatestop1(results[0].geometry.location);

                          d_input.value = results[0].formatted_address;
                      }
                  });
                  }
              });
            }
          }
        });


        window.autocomplete_stop2 =false;
        stop2_input = document.getElementById('stop2_address');

        $('#stop2_address').keyup(function(e) {
            if ($('#stop2_address').val().length < 4) {
                e.stopImmediatePropagation()
            } else {
                if (window.autocomplete_stop2 == false) {
                    window.autocomplete_stop2 = new google.maps.places.Autocomplete(stop2_input);
                    google.maps.event.addListener(autocomplete_stop2, 'place_changed', function() {
                    var place = autocomplete_stop2.getPlace();
                    if (place.hasOwnProperty('place_id')) {
                        if (!place.geometry) {
                            window.alert("Autocomplete's returned place contains no geometry");
                            return;
                        }
                        updatestop2(place.geometry.location);
                    } else {
                        des_service.textSearch({
                            query: place.name
                        }, function(results, status) {
                            if (status == google.maps.places.PlacesServiceStatus.OK) {
                                updatestop2(results[0].geometry.location);
                                d_input.value = results[0].formatted_address;
                            }
                        });
                    }
              });
            }
          }
        });


        autocomplete_stop3.addListener('place_changed', function(event) {
            markerSecond.setVisible(false);
            var place = autocomplete_stop3.getPlace();
            if (place.hasOwnProperty('place_id')) {
                if (!place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }
                updatestop3(place.geometry.location);
            } else {
                des_service.textSearch({
                    query: place.name
                }, function(results, status) {
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        updatestop3(results[0].geometry.location);

                        //console.log('destination', results[0]);
                        d_input.value = results[0].formatted_address;
                    }
                });
            }
        });
        function updateSource(location) {
            map.panTo(location);
            marker.setPosition(location);
            marker.setVisible(true);
            map.setZoom(15);
            updateSourceForm(location.lat(), location.lng());
            if(destination != undefined) {
                updateRoute();
            }
        }

        function updateDestination(location) {
            map.panTo(location);
            markerSecond.setPosition(location);
            markerSecond.setVisible(true);
            updateDestinationForm(location.lat(), location.lng());
            updateRoute();
        }

        function updatestop1(location) {
            map.panTo(location);
            marker3.setPosition(location);
            marker3.setVisible(true);
            updatestop1Form(location.lat(), location.lng());
            // updateRoute();
        }

        function updatestop2(location) {
            map.panTo(location);
            marker4.setPosition(location);
            marker4.setVisible(true);
            updatestop2Form(location.lat(), location.lng());
            // updateRoute();
        }

        function updatestop3(location) {
            map.panTo(location);
            marker5.setPosition(location);
            marker5.setVisible(true);
            updatestop3Form(location.lat(), location.lng());
            // updateRoute();
        }

        function updateRoute() {
            directionsDisplay.setMap(null);
            directionsDisplay.setMap(map);

            directionsService.route({
                origin: source,
                destination: destination,
                travelMode: google.maps.TravelMode.DRIVING,
                // unitSystem: google.maps.UnitSystem.IMPERIAL,
            }, function(result, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(result);

                    marker.setPosition(result.routes[0].legs[0].start_location);
                    markerSecond.setPosition(result.routes[0].legs[0].end_location);

                    distance.value = result.routes[0].legs[0].distance.value / 1000;
                    seconds.value = result.routes[0].legs[0].duration.value / 60;
                }
            });

        }

        function updateSourceForm(lat, lng) {
            s_latitude.value = lat;
            s_longitude.value = lng;

            source = new google.maps.LatLng(lat, lng);
        }

        function updateDestinationForm(lat, lng) {
            d_latitude.value = lat;
            d_longitude.value = lng;
            destination = new google.maps.LatLng(lat, lng);
        }


        function updatestop1Form(lat, lng) {
            s1_latitude.value = lat;
            s1_longitude.value = lng;
            destination = new google.maps.LatLng(lat, lng);
        }

        function updatestop2Form(lat, lng) {
            s2_latitude.value = lat;
            s2_longitude.value = lng;
            destination = new google.maps.LatLng(lat, lng);
        }

        function updatestop3Form(lat, lng) {
            s3_latitude.value = lat;
            s3_longitude.value = lng;
            destination = new google.maps.LatLng(lat, lng);
        }

        function updateMarker(event) {

            marker.setVisible(true);
            marker.setPosition(event.latLng);

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'latLng': event.latLng}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        s_input.value = results[0].formatted_address;
                        s_state.value = '';
                        s_country.value = '';
                        s_city.value = '';
                        s_pin.value = '';
                    } else {
                        alert('No Address Found');
                    }
                } else {
                    // alert('Geocoder failed due to: ' + status);
                    location.reload();
                }
            });

            updateSource(event.latLng);
        }

        function updateMarkerSecond(event) {

            markerSecond.setVisible(true);
            markerSecond.setPosition(event.latLng);

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'latLng': event.latLng}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        d_input.value = results[0].formatted_address;
                        d_state.value = '';
                        d_country.value = '';
                        d_city.value = '';
                        d_pin.value = '';
                    } else {
                        alert('No Address Found');
                    }
                } else {
                    // alert('Geocoder failed due to: ' + status);
                    location.reload();
                }
            });

            updateDestination(event.latLng);
        }

        function updateMarker_stop1(event) {

            markerSecond.setVisible(true);
            markerSecond.setPosition(event.latLng);

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'latLng': event.latLng}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        d_input.value = results[0].formatted_address;
                        d_state.value = '';
                        d_country.value = '';
                        d_city.value = '';
                        d_pin.value = '';
                    } else {
                        alert('No Address Found');
                    }
                } else {
                    // alert('Geocoder failed due to: ' + status);
                    location.reload();
                }
        });
        updatestop1(event.latLng);
        }

        function updateMarker_stop2(event) {

        markerSecond.setVisible(true);
        markerSecond.setPosition(event.latLng);

        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'latLng': event.latLng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    d_input.value = results[0].formatted_address;
                    d_state.value = '';
                    d_country.value = '';
                    d_city.value = '';
                    d_pin.value = '';
                } else {
                    alert('No Address Found');
                }
            } else {
                // alert('Geocoder failed due to: ' + status);
                location.reload();
            }
        });
        updatestop2(event.latLng);
        }


        function updateMarker_stop3(event) {

        markerSecond.setVisible(true);
        markerSecond.setPosition(event.latLng);

        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'latLng': event.latLng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    d_input.value = results[0].formatted_address;
                    d_state.value = '';
                    d_country.value = '';
                    d_city.value = '';
                    d_pin.value = '';
                } else {
                    alert('No Address Found');
                }
            } else {
                // alert('Geocoder failed due to: ' + status);
                location.reload();
            }
        });
        updatestop3(event.latLng);
    }
 //   }
 // });

    }
</script>

<script>
    function corporateinit() {
        var corporate_input, corporatedes_input;
        var c_latitude, c_longitude;
        var cd_latitude, cd_longitude;

        corporate_input = document.getElementById('c_address');
        corporatedes_input = document.getElementById('cd_address');

        c_latitude = document.getElementById('c_latitude');
        c_longitude = document.getElementById('c_longitude');

        cd_latitude = document.getElementById('cd_latitude');
        cd_longitude = document.getElementById('cd_longitude');
        
        distance = document.getElementById('distance');
        seconds = document.getElementById('seconds');

        var autocomplete_source = new google.maps.places.Autocomplete(corporate_input);

        /*autocomplete_source.setFields(
            ['address_components','geometry','icon','name','formatted_address']);*/

        autocomplete_source.setComponentRestrictions(
            {'country': ['se','in']});

        var autocomplete_destination = new google.maps.places.Autocomplete(corporatedes_input);
    
        /*autocomplete_destination.setFields(
            ['address_components','geometry','icon','name','formatted_address']);*/
        
        autocomplete_destination.setComponentRestrictions(
            {'country': ['se','in']});

        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true});

        autocomplete_source.addListener('place_changed', function(event) {
            var place = autocomplete_source.getPlace();
            if (place.hasOwnProperty('place_id')) {
                if (!place.geometry) {
                        // window.alert("Autocomplete's returned place contains no geometry");
                        return;
                }
                c_latitude.value = place.geometry.location.lat();
                c_longitude.value = place.geometry.location.lng();
            }
        });

        autocomplete_destination.addListener('place_changed', function(event) {
            var place = autocomplete_destination.getPlace();
            if (place.hasOwnProperty('place_id')) {
                if (!place.geometry) {
                        // window.alert("Autocomplete's returned place contains no geometry");
                        return;
                }
                cd_latitude.value = place.geometry.location.lat();
                cd_longitude.value = place.geometry.location.lng();
                c_fare_calculation();
            }
        });
    }
</script>

<!-- <script>
    $('<div class="item"><img src="https://dev.unicotaxi.com/uploads/2cc62009f584748c531d07f8753dcd809e5cc0d4.png" style="width: 70px;margin-left: 46px;"><span style="float: revert;padding: 11px;font-weight:bold;font-size:17px;">Sedan</span><span style="float: inherit;padding: 17px;"><i class="fa fa-user" aria-hidden="true"> 3</i></span><strong><span style="float: inherit;padding: 85px;font-size=24">134</span></strong><div class="carousel-caption"></div>   </div>').appendTo('.carousel-inner');
    $('<li data-target="#carousel-example-generic" data-slide-to=" "></li>').appendTo('.carousel-indicators');

  
  $('.item').first().addClass('active');
  $('.carousel-indicators > li').first().addClass('active');
  $('#carousel-example-generic').carousel();

</script>
 -->
 <script type="text/javascript">
    $('.carousel-inner').slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: false,
    swipeToSlide: true,
    infinite: false
});

.on("mousewheel", function (event) {
    event.preventDefault();
    if (event.deltaX > 0 || event.deltaY < 0) {
        $('.slick-next').click();
    } else if (event.deltaX < 0 || event.deltaY > 0) {
        $('.slick-prev').click();
    }
});
</script>

<script>
    $(document).on('click','#drivers', function(){
        var id = $(this).attr("data-id");
        google.maps.event.trigger(mapMarkers[id],'click');
    });
    /*$('.driver-options').hide();*/
    /*$(document).on('click','.driverdetailbtn', function(){
        /*$('.driver-options').toggle(function() {
            $('.driverdetailbtn').css({"margin-left": "0px"},{"padding-left": "0px"});
        }, function() {
            $('.driverdetailbtn').css({"margin-left": "301px"},{"padding-left": "0px"});
        });
        $('.driver-options').toggle(function(ev) {
          $('.driverdetailbtn').css({"margin-left": "300px"},{"padding-left": "0px"});
        }, function(ev) {
          $('.driverdetailbtn').css({"margin-left": "301px"},{"padding-left": "0px"});
        });
    });*/
	$('.driverdetailbtn').hide();
        $(document).on('click','.driverdetailbtn', function(){
            $('.driver-options').toggle();
	    $('.driver-option').toggle();
            $('.driverdetailbtn').hide();
        });
        $(document).on('click','#dismiss', function(){
            $('.driver-options').toggle();
	    $('.driver-option').toggle();
            $('.driverdetailbtn').show();
        });


</script>
<script>
$('#myModal').modal('show'); 
$("div").removeClass("modal-backdrop");
$(document).ready(function () {
  $("#add_trip").click(function () {
    $('#myModal').modal('show'); 
    $("div").removeClass("modal-backdrop");
  });
  $('.stop1').hide();
  $('.stop2').hide();
  $("#morefield2").click(function(){
      $(".stop1").hide();
      $('.circle').removeClass("circle1");
      $('.circle').addClass("circle2");
      if($(".stop2").is(":visible") || $(".stop1").is(":visible")){
          //alert("The paragraph  is visible.");
      }else{
          $('.circle').removeClass("circle2");
      }
});
$("#morefield3").click(function(){
      $(".stop2").hide();
      $('.circle').removeClass("circle1");
      $('.circle').addClass("circle2");
      if($(".stop2").is(":visible") || $(".stop1").is(":visible")){
           //alert("The paragraph  is visible.");
      }else{
	 $('.circle').removeClass("circle2");
      }
});

  $("#morefield").click(function(){
          $(".stop1").show();
          $(".stop2").show();
	  $('.circle').addClass("circle1");
	  if($(".stop2").is(":visible") || $(".stop1").is(":visible")){
           $('.circle').addClass("circle1");
	   $('.circle').removeClass("circle2");
          }
});
/*var totalItems = $('#carousel-example-generic .carousel-item').length;
var currentIndex1 = $('#carousel-example-generic .carousel-item.active').index() + 1;*/
var currentIndex = $('#carousel-example-generic .carousel-item.active #service-id').html();
$("#service_type").val(currentIndex); 
$(".next").click(function(){
var currentIndex = $('#carousel-example-generic .carousel-item.active').next().html();
if(currentIndex == undefined){
	var currentIndex = $('#carousel-example-generic .carousel-item').first().html();
}
var v1 = $(currentIndex);
var v2 = v1.find('#service-id').html();
$("#service_type").val(v2); 

              var val = $('#service_type').val();
              var s_latitude1 = $('#s_latitude').val();
              var s_longitude1 = $('#s_longitude').val();
              var d_latitude1 = $('#d_latitude').val();
              var d_longitude1 = $('#d_longitude').val();
              if(s_latitude1 !='' && s_latitude1 !='' && val !=''){
                  $.ajax({
                      url: '/dispatcher/dispatch/fare',
                      dataType: 'json',
                      headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
                      type: 'POST',
                      data: {
                              s_latitude: s_latitude1,
                              s_longitude: s_longitude1,
                              d_latitude: d_latitude1,
                              d_longitude: d_longitude1,
                              service_type: val
                              },
                      success: function(data) {
                          $('.currency_symbol').text(data[4]);
                          $('.estimate_fare').text(data[2]);
                          $('.drop_off').text(data[6]);
                      }
                  });
              }

});

$(".prev").click(function(){

var currentIndex = $('#carousel-example-generic .carousel-item.active').prev().html();
if(currentIndex == undefined){
	var currentIndex = $('#carousel-example-generic .carousel-item').last().html();
}
var v1 = $(currentIndex);
var v2 = v1.find('#service-id').html();
$("#service_type").val(v2); 

              var val = $('#service_type').val();
              var s_latitude1 = $('#s_latitude').val();
              var s_longitude1 = $('#s_longitude').val();
              var d_latitude1 = $('#d_latitude').val();
              var d_longitude1 = $('#d_longitude').val();
             

              if(s_latitude1 !='' && s_latitude1 !='' && val !=''){
                  $.ajax({
                      url: '/dispatcher/dispatch/fare',
                      dataType: 'json',
                      headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
                      type: 'POST',
                      data: {
                              s_latitude: s_latitude1,
                              s_longitude: s_longitude1,
                              d_latitude: d_latitude1,
                              d_longitude: d_longitude1,
                              service_type: val
                              },
                      success: function(data) {
                          $('.currency_symbol').text(data[4]);
                          $('.estimate_fare').text(data[2]);
                          $('.drop_off').text(data[6]);
                      }
                  });
              }
});
            
});
</script>
<script type="text/javascript">
const chk = document.getElementById('chk');

var switchStatus = true;
$('#pic1').attr('src', '/asset/img/whitecar.png');
chk.addEventListener('change', () => {
    var chkPassport = document.getElementById("chk");

        if (chkPassport.checked) {
            $('#schedule_time_block').show();
		$('#pic1').attr('src', '/asset/img/caricon-trip.png');
		$('#pic2').attr('src', '/asset/img/calendaricon-white.png');
		
        } else {
            $('#schedule_time_block').hide();
		$('#pic1').attr('src', '/asset/img/whitecar.png');
		$('#pic2').attr('src', '/asset/img/sheduleicon-trip.png');

        }

    document.body.classList.toggle('dark');
});

// SOCIAL PANEL JS
const floating_btn = document.querySelector('.floating-btn');
const close_btn = document.querySelector('.close-btn');
const social_panel_container = document.querySelector('.social-panel-container');


floating_btn.addEventListener('click', () => {
    social_panel_container.classList.toggle('visible')
});

close_btn.addEventListener('click', () => {
    social_panel_container.classList.remove('visible')
});
</script>
<script>
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
<script type="text/javascript">
$(document).ready(function() {
  var timer;
  $('#driverallstatus').hover(function() {
    clearTimeout(timer);
    $('#driverallstatus').addClass('open');
    
  }, function() {
     timer = setTimeout(function() {
      $('#driverallstatus').removeClass("open");
    }, 200);
    
  });
});
</script>
<script>
	$(document).on('click','.driverallstatusbtn', function(){
            var a = $(this).find('span').text().toLowerCase();
	    $('.ds').html($(this).html());
	    $('.ds').find('img').width(20);
	    $('.ds').append('<i class="fa fa-chevron-down" style="line-height: 1.9;font-weight: 900;position: absolute;right: 7px;font-size: 11px;"></i>');
	    $('.dropdown-toggle::after').css("margin-left", "30px !important");
	    $('#driver_status').val(a);
	    driver_updates();
        });
	$(document).on('click','.notifydata', function(){
      		var a = $(this).find('span').text();
      		var count = $(".notification").text();
      		var b = parseInt($(".notification").text());
      		var c = b-1; 
      		$(this).remove();
		$.ajax({
            		type: "GET",
            		dataType: "json",
            		headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            		url: '/dispatcher/webnotify',
            		data: {'id': a},
            		success: function(data){
              		console.log(data.success+b);
              		$(this).remove();
              		$(".notification").html(b-1);
              		if(c <= 0){
				$('.notifyitem').html('');
                		$('.notifyitem').append('<a class="dropdown-item" href="#"><i class="ti-info-alt mr-0-5"></i> No new notification</a>');
              		}
            		}
      		});
      
    	});
	$('.clear-note').click(function(){
   	  $.ajax({
                url: "{{ url('/dispatcher/clear-notify') }}",
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
                type: 'POST',
                data: '',
                success: function(data) {
                    if(data){
                       $(".notification").html(0);
                       $('.notifyitem').html('');
                       $('.notifyitem').append('<a class="dropdown-item" href="#"><i class="ti-info-alt mr-0-5"></i> No new notification</a>');
                    }
                }
            });
 	});
	$(document).on('click','.autoassignclosebtn', function(){
		location.reload();
	});
</script>
@endsection

