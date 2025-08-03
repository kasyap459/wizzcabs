<div class="site-header">
	<nav class="navbar navbar-dark">
		<ul class="nav navbar-nav">
			<li class="nav-item m-r-1 hidden-lg-up">
				<a class="nav-link collapse-button" href="#">
					<i class="ti-menu"></i>
				</a>
			</li>
		</ul>
		<ul class="nav navbar-nav float-xs-right">
			<li class="nav-item dropdown">
				<a class="nav-link" href="#" data-toggle="dropdown" aria-expanded="false">
					<div class="avatar box-32">
						<img src="{{img(Auth::guard('corporate')->user()->picture)}}" alt="">
					</div>
				</a>
				<div class="dropdown-menu dropdown-menu-right animated flipInY">
					<a class="dropdown-item" href="{{route('corporate.profile')}}">
						<i class="ti-user mr-0-5"></i> @lang('admin.profile')
					</a>
					<a class="dropdown-item" href="{{route('corporate.password')}}">
						<i class="ti-settings mr-0-5"></i> @lang('admin.change_password')
					</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="{{ url('/corporate/logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();"><i class="ti-power-off mr-0-5"></i> @lang('admin.sign_out')</a>
				</div>
				<form id="logout-form" action="{{ url('/corporate/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
			</li>
		</ul>
		<ul class="nav navbar-nav float-xs-right">
			<li class="nav-item dropdown" id="notedata">
				<a class="nav-link" href="#" data-toggle="dropdown" aria-expanded="false">
					<div class="notify">
						<i class="glyphicon glyphicon-bell"></i>
						<span class="notification">{{ $notifies->count() }}</span>
					</div>
				</a>
				<div class="dropdown-menu notifyitem dropdown-menu-right animated flipInY" style="overflow-y: scroll;max-height: 300px;">
	 				@if(count($notifies) < 1)
					    <a class="dropdown-item" href="#">
    						<i class="ti-info-alt mr-0-5"></i> No new notification
    					</a>                                      
					@else
	 				@foreach ($notifies as $request)
    					<a class="dropdown-item notifydata text-danger" href="#">
    						<span style="display: none;">{{ $request->id }}</span>
							<i class="ti-info-alt mr-0-5 text-danger"></i> {{ $request->type }}
							<p class="text-black">{{ $request->title }}</p>						
						</a>
					@endforeach
					<a class="dropdown-item text-danger bg-secondary clear-note" style="display: block;background: #ab8ce4;width: auto;" href="#" >
    						<p class="text-black" style="margin-bottom: 0px;text-align: center;">Clear All</p>					
					</a>
					@endif					
				</div>
			</li>
		</ul>		<div class="navbar-toggleable-sm collapse" id="collapse-1">
			<ul class="nav navbar-nav">
				<!--<li class="nav-item">
					<a href="{{ route('corporate.dispatch.index') }}" class="buttons btn btn-rounded w-min-sm m-l-0-75 waves-effect waves-light site-sidebar-second-toggle" data-toggle="collapse">Add</a>
				</li>-->
				<li class="nav-item">
					<a href="{{ route('corporate.main') }}" class="buttons btn btn-sm w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.triplist.trip_list')</a>
				</li>
				<li class="nav-item">
					<a href="{{ route('corporate.schedule') }}" class="buttons btn btn-sm w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.triplist.scheduled_list')</a>
				</li>
				<li class="nav-item">
					<a href="{{ route('corporate.dashboard') }}" class="buttons btn btn-sm w-min-sm m-l-0-75 waves-effect waves-light">Corporate Dashboard</a>
				</li>
			<select class="filter-box" style="margin-top: 18px;background-color: #296738;color: #fff;" id="choose_corporate" onchange="driver_updates()">
		              <option value="">Select Company</option>
		              @foreach($corporates as $corporate)
		                  <option value="{{ $corporate->id }}">{{ $corporate->display_name }}</option>
		              @endforeach
		          </select>

		            <select class="filter-box" style="margin-top: 18px;background-color: #296738;color: #fff;"  id="choose_service" onchange="driver_updates()">
		                <option value="">Vehicle Category</option>
		                @foreach($services as $service)
		                    <option value="{{ $service->id }}">{{ $service->name }}</option>
		                @endforeach
		            </select>
            		    <input type="hidden" id="driver_status">
	   		    <div class="dropdown filter-box" id="driverallstatus" style="position: absolute;margin-top: 17px;padding-left: 0px;">
  				<button class="btn filter-box btn-secondary btn-sm dropdown-toggle ds" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="background-color: #296738;color: #fff;background-color: #296738;font-family: inherit;font-weight: inherit;font-size: inherit;border-color: #767676;text-align: left;">
    					All Status
					<i class="fa fa-chevron-down" style="line-height: 1.5;font-weight: 900;position: absolute;right: 7px;font-size: 11px;"></i>
  				</button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="margin-top:5px;">
    					<a class="dropdown-item driverallstatusbtn" href="#"><img src="{{asset('asset/img/Green.png')}}"  width="30"> <span style="vertical-align: middle;">Active</span></a>
    					<a class="dropdown-item driverallstatusbtn" href="#"><img src="{{asset('asset/img/Red.png')}}"  width="30"> <span style="vertical-align: middle;">Offline</span></a>
    					<a class="dropdown-item driverallstatusbtn" href="#"><img src="{{asset('asset/img/Blue.png')}}"  width="30"> <span style="vertical-align: middle;">Riding</span></a>
    					<a class="dropdown-item driverallstatusbtn" href="#"><img src="{{asset('asset/img/Person.png')}}"  width="30"> <span style="vertical-align: middle;">Person</span></a>
  				</div>
	   		     </div>
			</ul>
		</div>
	</nav>
</div>