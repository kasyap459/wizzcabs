	<div class="row row-md">
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<div class="box box-block bg-white tile tile-3 m-b-2">
				<div class="t-icon right"><i class="ti-panel text-danger"></i></div>
				<div class="t-content">
					<h5 class="text-uppercase text-danger">@lang('admin.panel.total_no_rides')</h5>
					<h3 class="m-b-0">{{$rides->count()}}</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<div class="box box-block bg-white tile tile-3 m-b-2">
				<div class="t-icon right"><i class="ti-bar-chart text-success"></i></div>
				<div class="t-content">
					<h5 class="text-uppercase text-success">@lang('admin.panel.revenue')</h5>
					<h3 class="m-b-0">{{ currency_amt($revenue) }}</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<div class="box box-block bg-white tile tile-3 m-b-2">
				<div class="t-icon right"><i class="ti-alert text-warning"></i></div>
				<div class="t-content">
					<h5 class="text-uppercase text-warning">@lang('admin.panel.total_cancelled_rides')</h5>
					<h3 class="m-b-0">{{$cancel_rides->count()}}</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<div class="box box-block bg-white tile tile-3 m-b-2">
				<div class="t-icon right"><i class="ti-server text-info"></i></div>
				<div class="t-content">
					<h5 class="text-uppercase text-info">@lang('admin.panel.no_service_types')</h5>
					<h3 class="m-b-0">{{$service}}</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<div class="box box-block bg-white tile tile-3 m-b-2">
				<div class="t-icon right"><i class="ti-user text-primary"></i></div>
				<div class="t-content">
					<h5 class="text-uppercase text-primary">@lang('admin.panel.user_cancelled')</h5>
					<h3 class="m-b-0">{{$user_cancelled}}</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<div class="box box-block bg-white tile tile-3 m-b-2">
				<div class="t-icon right"><i class="ti-car text-success"></i></div>
				<div class="t-content">
					<h5 class="text-uppercase text-success">@lang('admin.panel.provider_cancelled')</h5>
					<h3 class="m-b-0">{{$provider_cancelled}}</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<div class="box box-block bg-white tile tile-3 m-b-2">
				<div class="t-icon right"><i class="ti-info-alt text-danger"></i></div>
				<div class="t-content">
					<h5 class="text-uppercase text-danger">@lang('admin.panel.dispatcher_cancelled')</h5>
					<h3 class="m-b-0">{{$dispatcher_cancelled}}</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<div class="box box-block bg-white tile tile-3 m-b-2">
				<div class="t-icon right"><i class="ti-link text-primary"></i></div>
				<div class="t-content">
					<h5 class="text-uppercase text-primary">No of Carrier</h5>
					<h3 class="m-b-0">{{$partner}}</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<div class="box box-block bg-white tile tile-3 m-b-2">
				<div class="t-icon right"><i class="ti-timer text-danger"></i></div>
				<div class="t-content">
					<h5 class="text-uppercase text-danger">@lang('admin.panel.no_of_scheduled_rides')</h5>
					<h3 class="m-b-0">{{$scheduled_rides}}</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<div class="box box-block bg-white tile tile-3 m-b-2">
				<div class="t-icon right"><i class="ti-comments text-info"></i></div>
				<div class="t-content">
					<h5 class="text-uppercase text-info">@lang('admin.panel.no_of_dispatcher_rides')</h5>
					<h3 class="m-b-0">{{$dispatcher_rides}}</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<div class="box box-block bg-white tile tile-3 m-b-2">
				<div class="t-icon right"><i class="ti-hand-stop text-success"></i></div>
				<div class="t-content">
					<h5 class="text-uppercase text-success">@lang('admin.panel.no_of_street_rides')</h5>
					<h3 class="m-b-0">{{$street_rides}}</h3>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
			<div class="box box-block bg-white tile tile-3 m-b-2">
				<div class="t-icon right"><i class="ti-close text-warning"></i></div>
				<div class="t-content">
					<h5 class="text-uppercase text-warning">@lang('admin.panel.driver_not_accepted')</h5>
					<h3 class="m-b-0">{{$driver_not_accepted}}</h3>
				</div>
			</div>
		</div>
	</div>
	</div>