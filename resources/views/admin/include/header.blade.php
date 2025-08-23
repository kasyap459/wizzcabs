
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
	<div class="container-fluid">
          	<div class="navbar-wrapper">
            		<div class="navbar-minimize">
              			<button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round">
                			<i class="material-icons text_align-center visible-on-sidebar-regular"><li class="fa fa-ellipsis-v"></li></i>
                			<i class="material-icons design_bullet-list-67 visible-on-sidebar-mini"><i class="fa fa-list-ul" aria-hidden="true"></i></i>
              			</button>
            		</div>
              		{{-- <a  href="{{ route('admin.dispatch.index') }}" class="btn btn-dribbble"><img src="{{asset('asset/img/dipatch_icon.png')}}">Dispatcher Panel</a> --}}
              		<a  href="{{ route('admin.main') }}" class="btn btn-dribbble"><img src="{{asset('asset/img/trip_icon.png')}}">Trip List</a>
          	</div>
		<div class="navbar-wrapper">
		<ul class="nav navbar-nav float-xs-right">
			@php
				$today = Carbon\Carbon::now()->format('Y-m-d').'%';
                                $requests = DB::table('web_notifies')
                                            ->orderBy('id', 'desc')
                                            ->where('status', '0')
                                            ->where('created_at', 'like', $today)
                                            ->get();
            		@endphp
			<li class="nav-item dropdown" id="notedata">
				<a class="nav-link" href="#" data-toggle="dropdown" aria-expanded="false">
					<div class="notify">
						<i class="glyphicon glyphicon-bell"></i>
						<span class="notification">{{ $requests->count() }}</span>
					</div>
				</a>
				 <div class="dropdown-menu notifyitem dropdown-menu-right animated flipInY">
	 				@if(count($requests) < 1)
					    <a class="dropdown-item" href="#">
    						<i class="ti-info-alt mr-0-5"></i> No new notification
    					</a>                                      
					@else
	 				@foreach ($requests as $request)
    					<a class="dropdown-item notifydata text-danger" href="#" style="padding-bottom:0px !important;">
    						<span style="display: none;">{{ $request->id }}</span>
							<span><i class="ti-info-alt mr-0-5 text-danger"></i> {{ $request->type }}<br/>
							<p class="text-black">{{ $request->title }}</p>	</span>					
						</a>
					@endforeach
					<a class="dropdown-item text-danger bg-secondary clear-note" style="display: block;background: #ab8ce4;width: auto;" href="#" >
    						<p class="text-black" style="margin-bottom: 0px;text-align: center;">Clear All</p>					
					</a>
					@endif					
				</div>
			</li>
		</ul>
		</div>
		<button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            		<span class="sr-only">Toggle navigation</span>
            		<span class="navbar-toggler-icon icon-bar"></span>
            		<span class="navbar-toggler-icon icon-bar"></span>
            		<span class="navbar-toggler-icon icon-bar"></span>
          	</button>    
        </div>
</nav>