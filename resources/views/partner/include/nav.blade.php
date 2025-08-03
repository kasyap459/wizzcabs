<div class="sidebar" data-color="rose" data-background-color="black" data-image="{{asset('asset/img/sidebar-1.jpg')}}">
   <div class="logo" style="padding:0px;">
	<a href=" " class="simple-text logo-mini" style="width:auto;"></a>
      	<a href=" " class="simple-text logo-normal" style="float:none;margin-left: 45px;">
	        <img src="{{asset('asset/img/unico1.png')}}" alt="UnicoTaxi" style="height: 90px;">	
	</a>
   </div>
   <div class="sidebar-wrapper">
      <div class="user">
         <div class="photo">
            <img src="{{img(Auth::guard('partner')->user()->picture)}}" alt="">
         </div>
         <div class="user-info">
            <a data-toggle="collapse" href="#collapseExample" class="username">
            <span>
            Profile
            <b class="caret"></b>
            </span>
            </a>
            <div class="collapse {{ (request()->is('partner/profile')) ? 'show' : '' }} {{ (request()->is('partner/password')) ? 'show' : '' }}" id="collapseExample">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('partner/profile')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('partner.profile') }}">
                    <span class="sidebar-normal"><i class="ti-user"></i> @lang('admin.profile')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('partner/password')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('partner.password') }}">
                     <span class="sidebar-normal"><i class="ti-exchange-vertical"></i> @lang('admin.change_password')</span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="{{ url('/partner/logout') }}"
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
          <li class="nav-item {{ (request()->is('partner/dashboard')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('partner.dashboard') }}">
              <i class="ti-home"></i>
              <p>@lang('admin.dashboard')</p>
            </a>
          </li>
          <li class="nav-item {{ (request()->is('partner/provider')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('partner.provider.index') }}">
              <i class="ti-user"></i>
              <p>@lang('admin.drivers')</p>
            </a>
          </li>
<!-- 	  <li class="nav-item {{ (request()->is('partner/vehicle')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('partner.vehicle.index') }}">
              <i class="fa fa-car"></i>
              <p>Vehicle</p>
            </a>
          </li>
          <li class="nav-item {{ (request()->is('partner/assignlist')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('partner.assignlist') }}">
              <i class="ti-car"></i>
              <p>Assign Vehicle</p>
            </a>
          </li>
	  <li class="nav-item {{ (request()->is('partner/map')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('partner.map.index') }}">
              <i class="ti-map-alt"></i>
              <p>@lang('admin.map')</p>
            </a>
          </li>
	  <li class="nav-item {{ (request()->is('partner/requests')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('partner.requests.index') }}">
              <i class="fa fa-paper-plane" aria-hidden="true"></i>
              <p>@lang('admin.requests')</p>
            </a>
          </li>
	  <li class="nav-item {{ (request()->is('partner/cancelled')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('partner.cancelled') }}">
              <i class="ti-close"></i>
              <p>Cancelled Rides</p>
            </a>
          </li>
 -->         <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#zoneExamples">
               <i class="ti-gallery"></i>
               <p> Reports
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('partner/statement')) ? 'show' : '' }}{{ (request()->is('partner/statement/provider')) ? 'show' : '' }}
		{{ (request()->is('partner/invoicelist')) ? 'show' : '' }}" id="zoneExamples">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('partner/statement')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('partner.ride.statement') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i> @lang('admin.overall_ride_statments')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('partner/statement/provider')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('partner.ride.statement.provider') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.driver_statement')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('partner/invoicelist')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('partner.invoicelist') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Invoice</span>
                     </a>
                  </li>
               </ul>
            </div>
         </li>
         <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#walletExamples">
               <img src="{{asset('asset/img/wallet_management_icon.png')}}" style="width: 24px;float: left;margin-left: 5px;margin-right: 15px;text-align: center;">
               <p>Wallet Management
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('partner/providerwallet')) ? 'show' : '' }}" id="walletExamples">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('partner/providerwallet')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('partner.providerwallet.index') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>Provider Wallet</span>
                     </a>
                  </li>
               </ul>
            </div>
         </li>
	 <li class="nav-item {{ (request()->is('partner/review/provider')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('partner.provider.review') }}">
              <i class="fa fa-star" aria-hidden="true"></i>
              <p>@lang('admin.driver_ratings')</p>
            </a>
          </li>
	 <li class="nav-item {{ (request()->is('partner/profile')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('partner.profile') }}">
              <i class="ti-user"></i>
              <p>@lang('admin.account_settings')</p>
            </a>
          </li>
<!-- 	 <li class="nav-item {{ (request()->is('partner/password')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('partner.password') }}">
              <i class="fa fa-key" aria-hidden="true"></i>
              <p>@lang('admin.change_password')</p>
            </a>
          </li>

 -->         <li class="nav-item ">
            <a class="nav-link" href="{{ url('/partner/logout') }}" onclick="event.preventDefault();
               document.getElementById('logout-form').submit();">
              <i class="ti-power-off"></i>
              <p>@lang('admin.logout')</p>
            </a>
	    <form id="logout-form" action="{{ url('/partner/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
            </form>
          </li>

      </ul>
   </div>
   <div class="sidebar-background"></div>
</div>