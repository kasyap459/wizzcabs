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
            <img src="{{img(Auth::guard('corporate')->user()->picture)}}" alt="">
         </div>
         <div class="user-info">
            <a data-toggle="collapse" href="#collapseExample" class="username">
            <span>
            Profile
            <b class="caret"></b>
            </span>
            </a>
            <div class="collapse {{ (request()->is('corporate/profile')) ? 'show' : '' }} {{ (request()->is('corporate/password')) ? 'show' : '' }}" id="collapseExample">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('corporate/profile')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('corporate.profile') }}">
                    <span class="sidebar-normal"><i class="ti-user"></i> @lang('admin.profile')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('corporate/password')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('corporate.password') }}">
                     <span class="sidebar-normal"><i class="ti-exchange-vertical"></i> @lang('admin.change_password')</span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="{{ url('/corporate/logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                     <span class="sidebar-normal"><i class="ti-power-off"></i> @lang('admin.sign_out') </span>
                     </a>
                  </li>
		<form id="logout-form" action="{{ url('/corporate/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
               </ul>
           </div>
        </div>
     </div>
      <ul class="nav">
         <li class="nav-item {{ (request()->is('corporate/dashboard')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('corporate.dashboard') }}">
              <i class="ti-home"></i>
              <p> @lang('admin.dashboard')</p>
            </a>
         </li>
	<li class="nav-item {{ (request()->is('corporate/group')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('corporate.group.index') }}">
              <i class="fa fa-users" aria-hidden="true"></i>
              <p> Groups</p>
            </a>
         </li>
	 <li class="nav-item {{ (request()->is('corporate/user')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('corporate.user.index') }}">
              <i class="fa fa-user-plus"></i>
              <p> Employees</p>
            </a>
         </li>

         <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#statement">
               <i class="ti-package"></i>
               <p>@lang('admin.statements')
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('corporate/statement')) ? 'show' : '' }} {{ (request()->is('corporate/statement/today')) ? 'show' : '' }}
			{{ (request()->is('corporate/statement/monthly')) ? 'show' : '' }} {{ (request()->is('corporate/statement/yearly')) ? 'show' : '' }}	" id="statement">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('corporate/statement')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('corporate.ride.statement') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.overall_ride_statments')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('corporate/statement/today')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('corporate.ride.statement.today') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.daily_statement')</span>
                     </a>
                  </li>
		  <li class="nav-item {{ (request()->is('corporate/statement/monthly')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('corporate.ride.statement.monthly') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.monthly_statement')</span>
                     </a>
                  </li>
		  <li class="nav-item {{ (request()->is('corporate/statement/yearly')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('corporate.ride.statement.yearly') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.yearly_statement')</span>
                     </a>
                  </li>
               </ul>
            </div>
         </li>
	 <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#settings">
               <i class="ti-settings"></i>
               <p>Settings
                  <b class="caret"></b>
               </p>
            </a>
            <div class="collapse {{ (request()->is('corporate/profile')) ? 'show' : '' }} {{ (request()->is('corporate/password')) ? 'show' : '' }}" id="settings">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('corporate/profile')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('corporate.profile') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.account_settings')</span>
                     </a>
                  </li>
		  <li class="nav-item {{ (request()->is('corporate/password')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('corporate.password') }}">
                     <span class="sidebar-normal"><i class="fa fa-circle" aria-hidden="true" style="color:#fff;font-size:8px;"></i>@lang('admin.change_password')</span>
                     </a>
                  </li>
               </ul>
            </div>
         </li>
	 <li class="nav-item">
            <a class="nav-link" href="{{ url('/corporate/logout') }}" onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
              <i class="ti-power-off"></i>
              <p> @lang('admin.logout')</p>
            </a>
         </li>
      </ul>



</div>
<div class="sidebar-background"></div>
</div>