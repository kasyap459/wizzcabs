
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
	<div class="container-fluid">
          	<div class="navbar-wrapper">
            		<div class="navbar-minimize">
              			<button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round">
                			<i class="material-icons text_align-center visible-on-sidebar-regular"><li class="fa fa-ellipsis-v"></li></i>
                			<i class="material-icons design_bullet-list-67 visible-on-sidebar-mini"><i class="fa fa-list-ul" aria-hidden="true"></i></i>
              			</button>
            		</div>
			<a href="{{ route('corporate.dispatch.index') }}" class="btn btn-dribbble waves-effect waves-light"><img src="{{asset('asset/img/dipatch_icon.png')}}">@lang('admin.dispatcher_panel')</a>
			<a href="{{ route('corporate.main') }}" class="btn btn-dribbble waves-effect waves-light"><img src="{{asset('asset/img/trip_icon.png')}}">@lang('admin.triplist.trip_list')</a>
            		<a href="{{ route('corporate.dashboard') }}" class="btn btn-dribbble waves-effect waves-light">Corporate Dashboard</a>
          	</div>
		<button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            		<span class="sr-only">Toggle navigation</span>
            		<span class="navbar-toggler-icon icon-bar"></span>
            		<span class="navbar-toggler-icon icon-bar"></span>
            		<span class="navbar-toggler-icon icon-bar"></span>
          	</button>    
        </div>
</nav>