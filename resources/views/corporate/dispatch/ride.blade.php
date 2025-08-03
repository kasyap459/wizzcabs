<div class="sidebar-activity">
    <div class="notifications">
    	<table class="table">
    		<tr>
    			<th>Status</th>
    			<th>Street</th>
    			<th>Mode</th>
    			<th></th>
    		</tr>
    		@foreach($trips as $index => $trip)
            <tr data-id="{{ $trip->id }}" id="trips">
                <td>@if($trip->status =='COMPLETED')
                        <span class="label label-table label-success"> {{ $trip->status }} </span>
                        <br>{{ $trip->created_at }}
                    @elseif($trip->status =='CANCELLED')
                        <span class="label label-table label-danger"> {{ $trip->status }} </span>
                        <br>{{ $trip->created_at }}
                    @elseif($trip->status =='SEARCHING')
                        <span class="label label-table label-warning"> {{ $trip->status }} </span>
                        <br>{{ $trip->created_at }}
                    @elseif($trip->status =='SCHEDULED')
                        <span class="label label-table label-primary"> {{ $trip->status }} </span>
                        <br>{{ $trip->schedule_at }}
                    @else
                        <span class="label label-table label-info"> {{ $trip->status }} </span>
                        <br>{{ $trip->created_at }}
                    @endif
                </td>
                <td> {{ $trip->s_address }} </td>
                <td>@if($trip->provider_id ==0)
                        Manual
                    @else
                        Auto
                    @endif
                    <br>
                    {{ $trip->booking_by }}
                </td>
                <td></td>
            </tr>
            @endforeach
    	</table>
	</div>
</div>
