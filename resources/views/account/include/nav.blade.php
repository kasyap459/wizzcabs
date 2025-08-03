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
            <img src="{{img(Auth::guard('account')->user()->picture)}}" alt="">
         </div>
         <div class="user-info">
            <a data-toggle="collapse" href="#collapseExample" class="username">
            <span>
            Profile
            <b class="caret"></b>
            </span>
            </a>
            <div class="collapse {{ (request()->is('account/profile')) ? 'show' : '' }} {{ (request()->is('account/password')) ? 'show' : '' }}" id="collapseExample">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('account/profile')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('account.profile') }}">
                    <span class="sidebar-normal"><i class="ti-user"></i> @lang('admin.profile')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('account/password')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('account.password') }}">
                     <span class="sidebar-normal"><i class="ti-exchange-vertical"></i> @lang('admin.change_password')</span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="{{ url('/account/logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                     <span class="sidebar-normal"><i class="ti-power-off"></i> @lang('admin.sign_out') </span>
                     </a>
                  </li>
		<form id="logout-form" action="{{ url('/account/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
               </ul>
           </div>
        </div>
     </div>
      <ul class="nav">
         <li class="nav-item {{ (request()->is('account/dashboard')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('account.dashboard') }}">
              <i class="ti-home"></i>
              <p> @lang('admin.dashboard')</p>
            </a>
         </li>
	<li class="nav-item {{ (request()->is('account/statement')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('account.ride.statement') }}">
              <i class="fa fa-circle" style="font-size: 12px;"></i>
              <p> @lang('admin.overall_ride_statments')</p>
            </a>
         </li>
	 <li class="nav-item {{ (request()->is('account/statement/provider')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('account.ride.statement.provider') }}">
              <i class="fa fa-circle" style="font-size: 12px;"></i>
              <p> @lang('admin.driver_statement')</p>
            </a>
         </li>
	 <li class="nav-item {{ (request()->is('account/statement/today')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('account.ride.statement.today') }}">
              <i class="fa fa-circle" style="font-size: 12px;"></i>
              <p> @lang('admin.daily_statement')</p>
            </a>
         </li>
         <li class="nav-item {{ (request()->is('account/statement/monthly')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('account.ride.statement.monthly') }}">
              <i class="fa fa-circle" style="font-size: 12px;"></i>
              <p> @lang('admin.monthly_statement')</p>
            </a>
         </li>
         <li class="nav-item {{ (request()->is('account/statement/yearly')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('account.ride.statement.yearly') }}">
              <i class="fa fa-circle" style="font-size: 12px;"></i>
              <p> @lang('admin.yearly_statement')</p>
            </a>
         </li>

	 <li class="nav-item">
            <a class="nav-link" href="{{ url('/account/logout') }}" onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
              <i class="ti-power-off"></i>
              <p> @lang('admin.logout')</p>
            </a>
         </li>
      </ul>



</div>
<div class="sidebar-background"></div>
</div>
