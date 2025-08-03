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
            <img src="{{img(Auth::guard('dispatcher')->user()->picture)}}" alt="">
         </div>
         <div class="user-info">
            <a data-toggle="collapse" href="#collapseExample" class="username">
            <span>
            Profile
            <b class="caret"></b>
            </span>
            </a>
            <div class="collapse {{ (request()->is('dispatcher/profile')) ? 'show' : '' }} {{ (request()->is('dispatcher/password')) ? 'show' : '' }}" id="collapseExample">
               <ul class="nav">
                  <li class="nav-item {{ (request()->is('dispatcher/profile')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('dispatcher.profile') }}">
                    <span class="sidebar-normal"><i class="ti-user"></i> @lang('admin.account_settings')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('dispatcher/password')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('dispatcher.password') }}">
                     <span class="sidebar-normal"><i class="ti-exchange-vertical"></i> @lang('admin.change_password')</span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="{{ url('/dispatcher/logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                     <span class="sidebar-normal"><i class="ti-power-off"></i> @lang('admin.sign_out') </span>
                     </a>
                  </li>
		<form id="logout-form" action="{{ url('/dispatcher/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
               </ul>
           </div>
        </div>
     </div>
      <ul class="nav">
         <li class="nav-item {{ (request()->is('dispatcher')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('dispatcher.index') }}">
              <img src="{{asset('asset/img/dipatch_icon.png')}}" style="width: 24px;float: left;margin-left: 5px;margin-right: 15px;text-align: center;">
              <p> @lang('admin.dispatcher_panel')</p>
            </a>
         </li>
	<li class="nav-item {{ (request()->is('dispatcher/main')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('dispatcher.main') }}">
              <img src="{{asset('asset/img/trip_icon.png')}}" style="width: 24px;float: left;margin-left: 5px;margin-right: 15px;text-align: center;">
              <p> @lang('admin.triplist.trip_list')</p>
            </a>
         </li>
      </ul>
</div>
<div class="sidebar-background"></div>
</div>