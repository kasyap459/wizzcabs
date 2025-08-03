<div class="sidebar" data-color="rose" data-background-color="black" data-image="{{asset('asset/img/sidebar-1.jpg')}}">
   <div class="logo" style="padding:0px;">
	<a href=" " class="simple-text logo-mini" style="width:auto;"></a>
      	<a href=" " class="simple-text logo-normal" style="float:none;margin-left: 50px;">
	        <img src="{{asset('asset/img/unico1.png')}}" alt="UnicoTaxi" style="height: 100px;padding:10px;">	
	</a>
   </div>
   <div class="sidebar-wrapper">
      <div class="user">
         <div class="photo">
            <img src="{{img(Auth::guard('admin')->user()->picture)}}" alt="">
         </div>
         <div class="user-info">
            <a data-toggle="collapse" href="#collapseExample" class="username">
            <span>
            Profile
            <b class="caret"></b> 
            </span>
            </a>
            <div class="collapse {{ (request()->is('admin/profile')) ? 'show' : '' }} {{ (request()->is('admin/password')) ? 'show' : '' }}" id="collapseExample">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('admin/profile')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.profile') }}">
            			<span class="sidebar-mini">  </span>
                    <span class="sidebar-normal"><i class="ti-user"></i> @lang('admin.profile')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/password')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.password') }}">
                     <span class="sidebar-normal"><i class="ti-exchange-vertical"></i> @lang('admin.change_password')</span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="{{ url('/admin/logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                     <span class="sidebar-normal"><i class="ti-power-off"></i> @lang('admin.sign_out') </span>
                     </a>
                  </li>
               </ul>
            </div>
         </div>
      </div>
      <ul class="nav">
          <li class="nav-item {{ (request()->is('admin/dashboard')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
              <i class="ti-home"></i>
              <p> Dashboard </p>
            </a>
          </li>
       
         <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#pagesExamples">
               <i class="fa fa-globe"></i>
               <p> Zone Management
                  <b class="caret"></b>
               </p>
            </a> 
            <div class="collapse {{(request()->is('admin/location/create')) ? 'show' : '' }}
				{{ (request()->is('admin/location')) ? 'show' : '' }}{{ (request()->is('admin/restrict-location')) ? 'show' : '' }}" id="pagesExamples">
               <ul class="nav">
                  {{--<li class="nav-item {{ (request()->is('admin/country')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.country.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>List Country </span>
                     </a>
                  </li>--}}
                  <li class="nav-item {{ (request()->is('admin/location/create')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.location.create') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Add Location</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/location')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.location.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>List Location </span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/restrict-location')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.restrict-location.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Restrict Location </span>
                     </a>
                  </li>
               </ul>
            </div>
         </li>
         <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#zoneExamples">
               <i class="ti-user"></i>
               <p> @lang('admin.members')
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('admin/user')) ? 'show' : '' }}{{ (request()->is('admin/provider')) ? 'show' : '' }}
		{{ (request()->is('admin/dispatch-manager')) ? 'show' : '' }}{{ (request()->is('admin/partner')) ? 'show' : '' }}
		{{ (request()->is('admin/corporate')) ? 'show' : '' }}{{ (request()->is('admin/account-manager')) ? 'show' : '' }}
		{{ (request()->is('admin/hotel')) ? 'show' : '' }}{{ (request()->is('admin/customer-care')) ? 'show' : '' }}" id="zoneExamples">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('admin/user')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.user.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Passenger</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/provider')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.provider.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.drivers')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/dispatch-manager')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.dispatch-manager.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.dispatcher')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/partner')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.partner.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Sub-company </span>
                     </a>
                  </li>
                   <li class="nav-item {{ (request()->is('admin/corporate')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.corporate.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Corporate</span>
                     </a>
                  </li> 
                  <li class="nav-item {{ (request()->is('admin/account-manager')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.account-manager.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.account_manager')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/hotel')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.hotel.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Hotel/Restaurant </span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/customer-care')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.customer-care.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Customer Care</span>
                     </a>
                  </li>
               </ul>
            </div>
         </li>
         <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#reportsExamples">
               <i class="ti-gallery"></i>
               <p>Reports
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('admin/statement')) ? 'show' : '' }}{{ (request()->is('admin/statement/today')) ? 'show' : '' }}
      		{{ (request()->is('admin/statement/monthly')) ? 'show' : '' }}{{ (request()->is('admin/statement/yearly')) ? 'show' : '' }}
      		{{ (request()->is('admin/statement/provider')) ? 'show' : '' }}{{ (request()->is('admin/statement/corporate')) ? 'show' : '' }}
      		{{ (request()->is('admin/corporateinvoicelist')) ? 'show' : '' }}{{ (request()->is('admin/statement/partner')) ? 'show' : '' }}
      		{{ (request()->is('admin/invoicelist')) ? 'show' : '' }}" id="reportsExamples">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('admin/statement')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.ride.statement') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.overall_ride_statments')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/statement/today')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.ride.statement.today') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.daily_statement')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/statement/monthly')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.ride.statement.monthly') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.monthly_statement')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/statement/yearly')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.ride.statement.yearly') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.yearly_statement') </span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/statement/provider')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.ride.statement.provider') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.driver_statement')</span>
                     </a>
                  </li>
                   <li class="nav-item {{ (request()->is('admin/statement/corporate')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.ride.statement.corporate') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Corporate Statement</span>
                     </a>
                  </li>
                 <li class="nav-item {{ (request()->is('admin/corporateinvoicelist')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.corporateinvoicelist') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Corporate Invoice</span>
                     </a>
                  </li>
<!--                   <li class="nav-item {{ (request()->is('admin/statement/partner')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.ride.statement.partner') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Sub-company Statement</span>
                     </a>
                  </li>
 -->                  <li class="nav-item {{ (request()->is('admin/invoicelist')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.invoicelist') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Sub-company Invoice</span>
                     </a>
                  </li>
               </ul>
            </div>
         </li>
         <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#ratingExamples">
               <i class="ti-star"></i>
               <p>@lang('admin.ratings_reviews')
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('admin/review/user')) ? 'show' : '' }}{{ (request()->is('admin/review/provider')) ? 'show' : '' }}" id="ratingExamples">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('admin/review/user')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.user.review') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.user_ratings')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/review/provider')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.provider.review') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.driver_ratings')</span>
                     </a>
                  </li>             
            
               </ul>
            </div>
         </li>
<!--          <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#ratingExamples">
               <i class="ti-bar-chart"></i>
               <p>History
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('admin/requests')) ? 'show' : '' }}{{ (request()->is('admin/scheduled')) ? 'show' : '' }}
         		{{ (request()->is('admin/payment')) ? 'show' : '' }}" id="ratingExamples">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('admin/requests')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.requests.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.request_history')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/scheduled')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.scheduled') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.scheduled_rides')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/payment')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.payment') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.payment_history')</span>
                     </a>
                  </li>
               </ul>
            </div>
         </li>
 -->         <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#notificationExamples">
               <i class="ti-bell"></i>
               <p>Notification
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('admin/sms')) ? 'show' : '' }} {{ (request()->is('admin/mail')) ? 'show' : '' }}
      		{{ (request()->is('admin/push')) ? 'show' : '' }}" id="notificationExamples">
                     <ul class="nav">
                  <li class="nav-item {{ (request()->is('admin/sms')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.sms.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.sms_notification')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/mail')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.mail.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Mail Notification</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/push')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.push.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Push Notification</span>
                     </a>
                  </li>
               </ul>
            </div>
         </li>
         <li class="nav-item {{ (request()->is('admin/service')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.service.index') }}">
              <i class="fa fa-car"></i>
              <p>Service Type</p>
            </a>
          </li>
         <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#servicetypeExamples">
               <i class="fa fa-dollar"></i>
               <p>Fare Management
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('admin/faremodel')) ? 'show' : '' }}
   			{{ (request()->is('admin/locationfare')) ? 'show' : '' }}{{ (request()->is('admin/poifare')) ? 'show' : '' }}" id="servicetypeExamples">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('admin/faremodel')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.faremodel.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Service Type Fare</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/locationfare')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.locationfare.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Location wise fare</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/poifare')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.poifare.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Airport transfer</span>
                     </a>
                  </li>
               </ul>
            </div>
         </li>

            <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#cashout">
               <i class="fa fa-dollar"></i>
               <p>Cash Out
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('admin/cashout')) ? 'show' : '' }}
            {{ (request()->is('admin/cashout')) ? 'show' : '' }}{{ (request()->is('admin/provider')) ? 'show' : '' }}" id="cashout">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('admin/cashout')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ url('admin/cashout') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>New Cashout Request</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/providerlist')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ url('admin/providerlist') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Driver List</span>
                     </a> 
                  </li>
                  <!-- <li class="nav-item {{ (request()->is('admin/poifare')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.poifare.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Reset Driver Earnings</span>
                     </a>
                  </li> -->
               </ul>
            </div>
         </li>
         
          {{-- <li class="nav-item {{ (request()->is('admin/shifts')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.shifts') }}">
              <i class="ti-timer"></i>
              <p>Production Management </p>
            </a>
          </li> --}}
         <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#walletExamples">
               <img src="{{asset('asset/img/wallet_management_icon.png')}}" style="width: 24px;float: left;margin-left: 5px;margin-right: 15px;text-align: center;">
               <p>Wallet Management
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('admin/userwallet')) ? 'show' : '' }}{{ (request()->is('admin/providerwallet')) ? 'show' : '' }}" id="walletExamples">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('admin/userwallet')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.userwallet.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>User Wallet</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/providerwallet')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.providerwallet.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Driver Wallet</span>
                     </a>
                  </li>
               </ul>
            </div>
         </li>
	
         <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#docsExamples">
               <i class="ti-files"></i>
               <p>@lang('admin.documents')
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('admin/document')) ? 'show' : '' }} {{ (request()->is('admin/vehicle-document')) ? 'show' : '' }}
		{{ (request()->is('admin/partner-document')) ? 'show' : '' }} {{ (request()->is('admin/corporate-document')) ? 'show' : '' }}" id="docsExamples">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('admin/document')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.document.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Driver Document</span>
                     </a>
                  </li>
                  {{-- <li class="nav-item {{ (request()->is('admin/vehicle-document')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.vehicledocument.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Vehicle Document</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/partner-document')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.partnerdocument.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Sub-company Document</span>
                     </a>
                  </li> --}}
                  <li class="nav-item {{ (request()->is('admin/corporate-document')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.corporatedocument.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Corporate Document</span>
                     </a>
                  </li>
           </ul>
            </div>
         </li>
         <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#marketingExamples">
               <i class="ti-gift"></i>
               <p>Marketing
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('admin/promocode')) ? 'show' : '' }} {{ (request()->is('admin/refferal')) ? 'show' : '' }}" id="marketingExamples">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('admin/promocode')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ url('admin/promocode') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.promocodes')</span>
                     </a>
                  </li>
               <!--    <li class="nav-item {{ (request()->is('admin/refferal')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.refferal') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Refer & Earn</span>
                     </a>
                  </li> -->
               </ul>
            </div>
         </li>
         <li class="nav-item {{ (request()->is('admin/usercare')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.usercare') }}">
              <img src="{{asset('asset/img/customercare_icon.png')}}" style="width: 24px;float: left;margin-left: 5px;margin-right: 15px;text-align: center;">
              <p>Customer Care Portal</p>
            </a>
          </li>
         <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#settingsExamples">
               <i class="ti-settings"></i>
               <p>Settings
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('admin/settings')) ? 'show' : '' }}{{ (request()->is('admin/settings/payment')) ? 'show' : '' }}
				{{ (request()->is('admin/password')) ? 'show' : '' }}{{ (request()->is('admin/business')) ? 'show' : '' }}{{ (request()->is('admin/page/*/edit')) ? 'show' : '' }}" id="settingsExamples">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('admin/settings')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.settings') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.member.site_settings')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/settings/payment')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.settings.payment') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.payment_settings')</span>
                     </a>
                  </li>
		  <li class="nav-item {{ (request()->is('admin/business')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.business') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Business Settings</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/password')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.password') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.change_password')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('admin/page/*/edit')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.page.edit', 2) }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Legal Settings</span>
                     </a>
                  </li>
               </ul>
            </div>
         </li>
	
	<li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#CMSsettingsExamples">
               <img src="{{asset('asset/img/cms_settings.png')}}" style="width: 24px;float: left;margin-left: 5px;margin-right: 15px;text-align: center;">
               <p>CMS Settings
                  <b class="caret"></b>
               </p>
            </a>
            <div id="CMSsettingsExamples" class="collapse {{ (request()->is('admin/user-note')) ? 'show' : '' }} {{ (request()->is('admin/cms-settings')) ? 'show' : '' }} {{ (request()->is('admin/user-rating')) ? 'show' : '' }}" id="settingsExamples">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('admin/user-note')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.user-note.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Trip Notes</span>
                     </a>
                  </li>
		  <li class="nav-item {{ (request()->is('admin/user-rating')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('admin.user-rating.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Trip Ratings </span>
                     </a>
                  </li>
		   <li class="nav-item {{ (request()->is('admin/cms-settings')) ? 'active' : '' }}">
                     <a class="nav-link" href="cms-settings">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Other Contents </span>
                     </a>
                  </li>
               </ul>
            </div>
         </li>


          <li class="nav-item ">
            <a class="nav-link" href="{{ url('/admin/logout') }}" onclick="event.preventDefault();
               document.getElementById('logout-form').submit();">
              <i class="ti-power-off"></i>
              <p>@lang('admin.logout')</p>
            </a>
	    <form id="logout-form" action="{{ url('/admin/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
            </form>
          </li>

      </ul>
   </div>
   <div class="sidebar-background"></div>
</div>