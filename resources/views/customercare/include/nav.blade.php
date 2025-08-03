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
            <img src="{{img(Auth::guard('customercare')->user()->picture)}}" alt="">
         </div>
         <div class="user-info">
            <a data-toggle="collapse" href="#collapseExample" class="username">
            <span>
            Profile
            <b class="caret"></b>
            </span>
            </a>
            <div class="collapse {{ (request()->is('customercare/profile')) ? 'show' : '' }} {{ (request()->is('customercare/password')) ? 'show' : '' }}" id="collapseExample">
               <ul class="nav">
                  {{--<li class="nav-item {{ (request()->is('customercare/profile')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('customercare.profile') }}">
                    <span class="sidebar-normal"><i class="ti-user"></i> @lang('admin.profile')</span>
                     </a>
                  </li>
                  <li class="nav-item {{ (request()->is('customercare/password')) ? 'active' : '' }}">
                     <a class="nav-link" href="{{ route('customercare.password') }}">
                     <span class="sidebar-normal"><i class="ti-exchange-vertical"></i> @lang('admin.change_password')</span>
                     </a>
                  </li>--}}
                  <li class="nav-item">
                     <a class="nav-link" href="{{ url('/customercare/logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                     <span class="sidebar-normal"><i class="ti-power-off"></i> @lang('admin.sign_out') </span>
                     </a>
                  </li>
		<form id="logout-form" action="{{ url('/customercare/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
               </ul>
           </div>
        </div>
     </div>
      <ul class="nav">
<!--          <li class="nav-item {{ (request()->is('customercare/dashboard')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('customercare.dashboard') }}">
              <i class="ti-home"></i>
              <p> @lang('admin.dashboard')</p>
            </a>
         </li> -->
	<li class="nav-item {{ (request()->is('customercare/usercare')) ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('customercare.usercare') }}">
              <i class="ti-user"></i>
              <p>Enquiry</p>
            </a>
         </li>
         <li class="nav-item">
            <a class="nav-link" href="{{ url('/customercare/logout') }}" onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
              <i class="ti-power-off"></i>
              <p> @lang('admin.logout')</p>
            </a>
         </li>
      </ul>



</div>
<div class="sidebar-background"></div>
</div>