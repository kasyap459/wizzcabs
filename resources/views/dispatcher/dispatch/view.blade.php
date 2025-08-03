<div class="sidebar-chat-window">
	<div class="notifications">
	<div class="n-item">
		<div class="media">
			<div class="n-text"><strong>Name: </strong>
				@if($trip->user_name !='')
                    {{ $trip->user_name }}
                @else
                    No passenger
                @endif
            </div>
            <div class="n-text"><strong>Phone: </strong>
            	@if($trip->user_mobile !='')
                    {{ $trip->user_mobile }}
                @else
                    -
                @endif
            </div>
			<div class="n-text"><strong>From: </strong>{{ \Illuminate\Support\Str::limit(strip_tags($trip->s_address), 40) }}</div>
			<div class="n-text"><strong>To: </strong>{{ \Illuminate\Support\Str::limit(strip_tags($trip->d_address), 40) }}</div>
			<div class="n-text"><strong>Service Type: </strong>@if($trip->service_type)
            {{ $trip->service_type->name }}
            @endif
        	</div>
			<div class="n-text"><strong>Distance: </strong>{{ $trip->distance }} {{ $diskm}}</div>
			<div class="n-text"><strong>Payment Mode: </strong>
				@if($trip->corporate_id !=0)
					CORPORATE
				@else
					{{ $trip->payment_mode }}
				@endif
			</div>
			<div class="n-text"><strong>Dispatch Mode: </strong>
			    @if($trip->current_provider_id ==0)
			   		Manual
				@else
					Auto
				@endif
			</div>
			<div class="n-text">
				@if($trip->status =='SCHEDULED')
					<strong>Scheduled at: </strong>{{ $trip->schedule_at }}
				@else
					<strong>Created at: </strong>{{ $trip->created_at }}
				@endif
			</div>
			@if($trip->status =='CANCELLED')
			<div class="n-text">
				<strong>Cancelled: </strong>
				@if($trip->cancelled_by =='USER')
                    Cancelled by User
                    @endif
                    @if($trip->cancelled_by =='PROVIDER')
                    Cancelled by Driver
                    @endif
                    @if($trip->cancelled_by =='DISPATCHER')
                    Cancelled by Dispatcher
                    @endif
                    @if($trip->cancelled_by =='REJECTED')
                    All Drivers Rejected
                    @endif
                    @if($trip->cancelled_by =='NODRIVER')
                    No Drivers Found
                    @endif
			</div>
			@endif		

			<div class="n-text">
				@if($trip->status =='COMPLETED')
					<span class="label label-table label-success"> {{ $trip->status }} </span>
				@elseif($trip->status =='CANCELLED')
					<span class="label label-table label-danger"> {{ $trip->status }} </span>
				@elseif($trip->status =='SEARCHING')
					<span class="label label-table label-warning"> {{ $trip->status }} </span>
				@elseif($trip->status =='SCHEDULED')
					<span class="label label-table label-primary"> {{ $trip->status }} </span>
				@else
					<span class="label label-table label-info"> {{ $trip->status }} </span>
				@endif
			</div>
			<input type="hidden" name="s_lat" id="s_latitude" value="{{ $trip->s_latitude }}">
			<input type="hidden" name="s_long" id="s_longitude" value="{{ $trip->s_longitude }}">
			<input type="hidden" name="d_lat" id="d_latitude" value="{{ $trip->d_latitude }}">
			<input type="hidden" name="d_long" id="d_longitude" value="{{ $trip->d_longitude }}">
		</div>
	</div>
</div>
</div>
@if($trip->provider_id ==0 || $trip->status =='CANCELLED')
<div class="box-bottom">
	<div class="s-title">
		<div class="box-block">
			Nearest Drivers
		</div>
	</div>
</div>
<table class="table">
	<tr>
		<th>Name</th>
		<th>Service Type</th>
		<th>Assign</th>
	</tr>
	@if($Providers->count() > 0)
		@foreach($Providers as $index => $Provider)
		<tr>
			<th>{{ $Provider->name }}<br>
				({{ $Provider->mobile }})</th>
			<th>{{ $Provider->vehicle_no }} <br>({{ $Provider->service_name }})</td>
			<th><button class="btn btn-sm btn-success" id="assign" data-id="{{ $trip->id }}" data-provider="{{ $Provider->id }}">Assign</button></th>
		</tr>
		@endforeach
	@else
	<tr>
		<td colspan="2" class="center">No Drivers Found</td>
	</tr>
	@endif
@endif