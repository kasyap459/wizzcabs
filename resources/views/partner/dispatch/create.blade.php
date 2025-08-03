<!-- .modal -->
<div class="modal right fade" id="myModal" style="top: 70px;padding-left: 17px; overflow-x: hidden;overflow-y: auto !important;">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button> 
            <strong>
               <h4 class="modal-title">New Booking</h4>
            </strong>
         </div>
         <div class="modal-body">
            <form action="" id="create_trip">
               <div class="sidebar-group">
                  <!-- <h6>ADD NEW TRIP</h6> -->
                  <div class="step create-box text-black">
                     <div>
                        <label class="col-3"></label>
                        <div class="circle d-flex justify-content-center"></div>
                     </div>
                     <div>
                        <label for="name" class="col-sm-10" style="padding-left:0px;">Pickup Location</label>
                        <input type="text" class="form-control" onfocus="initrip()" id="s_address" name="s_address" >
                        <input type="hidden" name="s_latitude" id="s_latitude">
                        <input type="hidden" name="s_longitude" id="s_longitude">
                        <p class="error-field s_address">Pickup location required</p>
                     </div>
                     <div class="input-group stop1" style="width:auto !important;padding-top:15px;">
			<input type="text" class="form-control" onfocus="initrip()" name="stop1_address" id="stop1_address" placeholder="Stop1 Location">
                        <input type="hidden" name="stop1_latitude" id="stop1_latitude">
                        <input type="hidden" name="stop1_longitude" id="stop1_longitude">
                        <span class="input-group-addon" id="morefield2"><img style="width:27px" src="{{asset('asset/img/minusicon.png')}}"></span>
                     </div>
                     
                     <!-- 									<label for="gender" class="col-3">Stop2 Location</label>
                        -->							      
                     <div class="input-group stop2" style="width:auto !important;padding-top:15px;">
			<input type="text" class="form-control" onfocus="initrip()" name="stop2_address" id="stop2_address"
                           placeholder="Stop2 Location">
                        <input type="hidden" name="stop2_latitude" id="stop2_latitude">
                        <input type="hidden" name="stop2_longitude" id="stop2_longitude">
                        <span class="input-group-addon" id="morefield3"><img style="width:27px" src="{{asset('asset/img/minusicon.png')}}"></span>
                     </div>
                  </div>
                  <div class="step step-active create-box text-black">
                     <div>
                        <label class="col-3"></label>
                        <div class="circle d-flex justify-content-center"></div>
                     </div>
                     <div>
                        <label for="name" class="col-sm-10" style="padding-left:0px;">Dropoff Location</label>
                        <div class="input-group">
                           <input type="text" class="form-control" onfocus="initrip()" id="d_address" name="d_address">
                           <input type="hidden" name="d_latitude" id="d_latitude">
                           <input type="hidden" name="d_longitude" id="d_longitude">
                           <span class="input-group-addon" id="morefield"><img style="width:27px" src="{{asset('asset/img/plusicon.png')}}"></span>
                           <input type="hidden" name="distance" id="distance">
                           <input type="hidden" name="seconds" id="seconds">
                        </div>
			<p class="error-field d_address">Drop location required</p>
                        <!-- 									<label for="gender" class="col-3">Stop1 Location</label>
                           -->
                     </div>
                     <div>
                        <div class="">
                        </div>
                     </div>
                  </div>
                  <!-- <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                     <ol class="carousel-indicators"></ol>
                     <div class="carousel-inner"></div>
                     <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                       <span class="glyphicon glyphicon-chevron-left carouselbtn"></span>
                     </a>
                     <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                       <span class="glyphicon glyphicon-chevron-right carouselbtn"></span>
                     </a>
                     </div> -->
                  <!-- <div class="car-detail">
                     @foreach($services as $service)
                     <div class="car-radio">
                         <input type="radio" 
                             name="service_type"
                             value="{{$service->id}}"
                             id="service_{{$service->id}}"
                             @if ($loop->first) checked="checked" @endif>
                         <label for="service_{{$service->id}}">
                             <div class="car-radio-inner">
                                 <div class="img"><img src="{{image($service->image)}}"></div>
                                 <div class="name"><span>{{$service->name}}</span></div>
                             </div>
                         </label>
                     </div>
                     @endforeach
                     </div> -->
                  <div class="num"></div>
                  <label class="create-box" style="margin-bottom:0px !important;">Vehicle Type:</label>
                  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="false">
                     <div class="carousel-inner" >
                        @foreach($services as $index =>$image)
                        <div class="carousel-item {{$index == 0 ? 'active' : '' }}">
                           <div class="carousel-container">
                              <div class="container">
                                 <!--<img src="{{image($image->image)}}" style="width: 70px;margin-left: 46px;"><span id="service-id" style="display:none">{{$image->id}}</span><span id="service-name" style="float: revert;padding: 11px;font-weight:bold;font-size:17px;">{{$image->name}}</span><span style="float: inherit;padding: 17px;"><i class="fa fa-user" aria-hidden="true">{{$image->seats_available}}</i></span><strong><span class="currency_symbol" style="float: inherit;font-size=24">$</span></strong>
                                    <span class="estimate_fare" style="float: inherit"></span></strong>
                                          <p style="margin-left: 128px;margin-bottom: auto">Drop off</p><strong><span class="drop_off" style="margin-left: 128px"></span></strong>
                                        -->
                                 <div class="row">
                                    <div class="col-md-4">
                                       <img src="{{image($image->image)}}" style="width:100px;margin-left: 46px;">
                                    </div>
                                    <div class="col-md-5" style="top: 25px;">
                                       <div style="padding-bottom: 3px;">
                                          <span id="service-id" style="display:none">{{$image->id}}</span>
                                          <span id="service-name" style="font-weight:bold;font-size:19px;color: #000000;">{{$image->name}} <i class="fa fa-user" aria-hidden="true" style="font-size: 14px;padding-left: 15px;"> {{$image->seats_available}}</i></span>
                                       </div>
                                       <div>
                                          <span class="drop_off"></span> Dropoff
                                       </div>
                                    </div>
                                    <div class="col-md-3" style="text-align: left;top: 35px;padding:0px;">
                                       <strong><span style="font-size:17px;" class="currency_symbol"></span>
                                       <span style="font-size:17px;" class="estimate_fare"></span></strong>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @endforeach
                     </div>
                     <a class="left carousel-control prev" href="#carousel-example-generic" role="button" data-slide="prev">
                        <!--<span class="icon-prev" aria-hidden="true"></span>-->
                        <i class="fa fa-caret-left" aria-hidden="true" style="position: absolute;top: 50%;margin-top: -15px;color: #000;"></i>
                        <span class="sr-only">Previous</span>
                     </a>
                     <a class="right carousel-control next" href="#carousel-example-generic" role="button" data-slide="next">
                        <!--<span class="icon-next" aria-hidden="true"></span>-->
                        <i class="fa fa-caret-right" aria-hidden="true" style="position: absolute;top: 50%;margin-top: -15px;color: #000;"></i>
                        <span class="sr-only">Next</span>
                     </a>
                  </div>
                  <div class="create-box text-black" style="display:none;">
                     <input type="hidden" class="form-control" id="service_type" name="service_type" placeholder="Service Name">
                  </div>
                  <div class="row create-box text-black" style="padding-top: 0px">
                     <div class="col-md-6" style="padding: 5px 12px;">
                        <label for="name" class="col-sm-7" style="padding: 5px; margin-right: -30px;margin-bottom: 0px;">Select Ride</label>
                        <div class="container vh-100 d-flex justify-content-center align-items-center">
                           <div class="one-quarter col-sm-5" id="switch">
                              <input type="checkbox" class="checkbox" id="chk" />
                              <label class="label" for="chk">
                                 <i class=""><img style="width:15px;margin-top:-10px;" id="pic1" src="{{asset('asset/img/whitecar.png')}}"></i>
                                 <i class=""><img style="width:15px;margin-top:-7px;margin-left: 7px;" id="pic2" src="{{asset('asset/img/sheduleicon-trip.png')}}"></i>
                                 <div class="ball"></div>
                              </label>
                           </div>
                        </div>
                     </div>
                        <div class="col-md-6" style="display: flex;padding-left: 8px; /*padding-right: 8px;*/">
                              <!--<label for="name" class="col-sm-10" style="padding:10px;padding-left: 0px;padding-right: 0px;margin-bottom: 0px;padding-bottom: 0px;">Select Corporate</label>-->
                        <!--<input type="text" class="form-control" id="corporate_name" name="corporate_name" placeholder="Corporate ID" style="width: 65px;height: 33px;padding-left: 0px; margin-left: -15px;">-->
                        <!--<select class="form-control" id="corporate_name" name="corporate_name" style="width: 165px;height: 30px;padding-left: 0px; margin-top: 1px; margin-left: -15px;">-->
<!-- 			<select class="form-control" id="corporate_id" name="corporate_id" style="height: 30px;">
                           <option value="">Select Corporate</option>
                        @foreach($corporates as $corporate)
                            <option value="{{ $corporate->id }}">{{ $corporate->display_name }}</option>
                        @endforeach
									
                        </select>
 -->                     </div>
                  </div>
                  <div class="create-box text-black" id="schedule_time_block">
                     <input type="text" class="form-control" id="schedule_time" name="schedule_time" placeholder="Schedule Time">
                  </div>
                  <!-- 							<div class="create-box text-black" style="padding: 0rem 3rem">
                     <p id="fare_calc"></p>
                     </div>
                     -->							
                  <div class="row create-box text-black">
                     <div class="col-md-6">
                        <label for="name" class="col-sm-10" style="padding:10px;padding-left: 0px;padding-right: 0px;margin-bottom: 0px;padding-bottom: 0px;">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="">
                        <p class="error-field first_name">First Name is required</p>
                     </div>
                     <div class="col-md-6" style="padding-left:8px;">
                        <label for="name" class="col-sm-10" style="padding:10px;padding-left: 0px;padding-right: 0px;margin-bottom: 0px;padding-bottom: 0px;">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="">
                        <p class="error-field last_name">Last Name is required</p>
                     </div>
                  </div>
                  <div class="row create-box text-black">
                     <div class="col-md-6">
                        <label for="name" class="col-sm-10" style="padding:10px;padding-left: 0px;padding-right: 0px;margin-bottom: 0px;padding-bottom: 0px;">Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="">
                        <p class="error-field email">Email id is required</p>
                     </div>
                     <div class="col-md-6" style="padding-left:8px;">
                        <label for="name" class="col-sm-10" style="padding:10px;padding-left: 0px;padding-right: 0px;margin-bottom: 0px;padding-bottom: 0px;">Phone Number</label>
                        <input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" id="mobile" name="mobile" placeholder="">
                        <p class="error-field mobile">Phone Number is required</p>
                     </div>
                  </div>
                  <div class="create-box text-black">
                     <label for="name" class="col-sm-10" style="padding:10px;padding-left: 0px;padding-right: 0px;margin-bottom: 0px;padding-bottom: 0px;">Notes for driver</label>
                     <input type="text" class="form-control" id="driver_message" name="message" placeholder="">
                  </div>
                  <div class="create-box text-black">
                     <label class="text-truncate">Auto Assign Driver</label>
                     <div class="ss-checkbox"><input type="checkbox" id="provider_auto_assign" name="provider_auto_assign" @if(Setting::get('auto_assign', 0) == 1) checked @endif class="js-switch" data-color="#f59345" /></div>
                  </div>
                  <div class="create-box text-black text-center mx-auto" style="text-align: center;padding-top:7px;">
                     <!--<button type="button" id="clear" class="btn btn-sm btn-info waves-effect waves-light" style="background-color: #27AB18;" onclick=>CLEAR</button>-->
                     <button type="submit" id="submit" class="btn btn-sm justify mx-auto waves-effect waves-light submitter" style="background-color: #27AB18;color:#fff;" onclick=>Create Trip</button>
                     <button type="button" id="cancel" class=" btn btn-sm waves-effect waves-light" style="background-color: red;color: #fff;" onclick=>Cancel</button>
                  </div>
               </div>
            </form>
         </div>
         <div class="modal-footer" style="display: none;">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                               
         </div>
      </div>
   </div>
</div>