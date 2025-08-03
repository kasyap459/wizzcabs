                @foreach($trips as $index => $trip)
                     @if($trip->status == 'SCHEDULED') 
                   <tr style="background-color:#ccc">
                        <td>{{ $index + 1 }}</td>
                        <td><a href="#" id="showmap" data-id="{{ $trip->id }}">{{ $trip->booking_id }}</a> <br><a href="#" id="showroute" class="icontrip" data-id="{{ $trip->id }}"><i class="fa fa-map-marker"></i></a></td>
                        <td>{{  date("Y-m-d h:i A", strtotime($trip->assigned_at)) }}</td>
                        <td>@if($trip->status == 'SCHEDULED') 
                            {{  date("Y-m-d h:i A", strtotime($trip->schedule_at))}} 
                            @else 
                            {{ $trip->started_at }} 
                            @endif
                        </td>
                        <td>@if($trip->finished_at)
                            {{  date("Y-m-d h:i A", strtotime($trip->finished_at))}} 
                            @else 
                            {{ $trip->finished_at }}
                            @endif
                        </td>
                        <td>
                            @if($trip->user_name !=null)
                            {{ $trip->user_name }}
                            @endif
                            @if($trip->user_mobile !=null)
                            <br> {{ $trip->user_mobile }}
                            @endif
                        </td>
                        <td>
                            @if($trip->provider) 
                            {{ $trip->provider->name }}
                            @elseif($trip->currentprovider)
                            {{ $trip->currentprovider->name }}
                            @else
                            No driver
                            @endif
                        </td>
                        <td>
                            @if($trip->service_type)
                            {{ $trip->service_type->name }}
                            @endif
                        </td>
                        <td>{{ $trip->s_address }} </td>
                        <td>{{ $trip->d_address }} </td>
                        <td>{{ $trip->distance }} {{ $diskm}}</td>
                        <td style="width:100px">
                            @if($trip->payment)
                            {{ $trip->payment->total }} {{ $trip->payment->currency}}
                            @else
                            {{ currency_amt($trip->estimated_fare) }}
                            @endif
                            <br>
                            @if($trip->fare_type ==1 || $trip->fare_type ==2)
                                (Fixed)
                            @else
                                (Distance)
                            @endif
                        </td>
                        <td>
                            @if($trip->booking_by =='APP')
                                Mobile App
                          @php
                              $device_type=App\Models\User::where('id',$trip->user_id)->first();
                              if($device_type){
                              echo "(".$device_type->device_type.")";
                             }
                          @endphp
                             @endif
                             @if($trip->booking_by =='WEB')
                                Web Booking
                             @endif
                             @if($trip->booking_by =='STREET')
                                Street Ride
                           
                             @endif
                             @if($trip->booking_by =='DISPATCHER')
                                Dispatcher
                             @endif
                             @if($trip->booking_by =='HOTEL')
                                Hotel
                             @endif
                             @if($trip->booking_by =='CORPORATE')
                                Corporate
                             @endif

                        </td>
                        <td>
                            @if($trip->cancelled_by =='USER')
                            User
                            @endif
                            @if($trip->cancelled_by =='PROVIDER')
                            Driver
                            @endif
                            @if($trip->cancelled_by =='DISPATCHER')
                            Dispatcher
                            @endif
                            @if($trip->cancelled_by =='REJECTED')
                            All Drivers Rejected
                            @endif
                            @if($trip->cancelled_by =='NODRIVER')
                            No Drivers Found
                            @endif
                            @if($trip->cancel_reason !=null)
                            ({{ $trip->cancel_reason }})
                            @endif
                        </td>
                        <td>
                            @if($trip->status == 'CANCELLED')
                            <span class="tag  pull-right @if($trip->current_provider_id == 0) hoverable @endif" id="dispatch" data-latitude="{{ $trip->s_latitude }}" data-longitude="{{ $trip->s_longitude }}" data-service="{{ $trip->service_type_id }}" data-id="{{ $trip->id }}" data-current="{{ $trip->current_provider_id }}"> <span class="normal tag-danger">{{$trip->status}}</span><span class="hover tag-dark">DISPATCH &nbsp;</span></span><br>
                            <a href="#" id="editmodal" class="icontrip" data-id="{{ $trip->id }}"><i class="fa fa-edit"></i></a>
                            <a href="#" class="icontrip custom-class"><i class="fa fa-times"></i></a>
                            @elseif($trip->status == 'COMPLETED')
                            <span class="tag tag-success pull-right custom-class"> {{$trip->status}} </span><br>
                            <a href="#" class="icontrip custom-class"><i class="fa fa-edit"></i></a>
                            <a href="#" class="icontrip custom-class"><i class="fa fa-times"></i></a>

                            @elseif($trip->status == 'SEARCHING')
                                <span class="tag  pull-right @if($trip->current_provider_id == 0) custom-class @endif" id="dispatch1" data-latitude="{{ $trip->s_latitude }}" data-longitude="{{ $trip->s_longitude }}" data-service="{{ $trip->service_type_id }}" data-id="{{ $trip->id }}" data-current="{{ $trip->current_provider_id }}"> <span class="normal tag-warning custom-class">{{$trip->status}}</span><span class="hover tag-dark">SEARCHING</span></span><br>
                                <a href="#" id="editmodal1" class="icontrip custom-class" data-id="{{ $trip->id }}"><i class="fa fa-edit"></i></a>
                                <a href="#" class="icontrip" data-id="{{ $trip->id }}" id="cancelmodal"><i class="fa fa-times"></i></a>
                            
                            @elseif($trip->status == 'ACCEPTED')
                                <span class="tag  pull-right" data-latitude="{{ $trip->s_latitude }}" data-longitude="{{ $trip->s_longitude }}" data-service="{{ $trip->service_type_id }}" data-id="{{ $trip->id }}" data-current="{{ $trip->current_provider_id }}"> <span class="normal tag-warning">{{$trip->status}}</span></span><br>
                                <a href="#" id="editmodal" class="icontrip" data-id="{{ $trip->id }}"><i class="fa fa-edit"></i></a>
                                <a href="#" class="icontrip" data-id="{{ $trip->id }}" id="cancelmodal"><i class="fa fa-times"></i></a>
                                <a href="#" class="icontrip" data-id="{{ $trip->id }}" id="completemodal"><i class="glyphicon glyphicon-ok"></i></a>
                            @elseif($trip->status == 'SCHEDULED')
                                <span class="tag  pull-right @if($trip->current_provider_id == 0) hoverable @endif" id="dispatch" data-latitude="{{ $trip->s_latitude }}" data-longitude="{{ $trip->s_longitude }}" data-service="{{ $trip->service_type_id }}" data-id="{{ $trip->id }}" data-current="{{ $trip->current_provider_id }}"><span class="normal tag-primary">{{$trip->status}}</span><span class="hover tag-dark">DISPATCH</span></span><br>
                                <a href="#" id="editmodal" class="icontrip" data-id="{{ $trip->id }}"><i class="fa fa-edit"></i></a>
                                <a href="#" class="icontrip" data-id="{{ $trip->id }}" id="cancelmodal"><i class="fa fa-times"></i></a>

                            @else
                            <span class="tag tag-info pull-right" id="editmodal" data-id="{{ $trip->id }}"> {{$trip->status}} </span><br>
                            <a href="#" id="editmodal" class="icontrip" data-id="{{ $trip->id }}"><i class="fa fa-edit"></i></a>
                            <a href="#" class="icontrip" data-id="{{ $trip->id }}" id="cancelmodal"><i class="fa fa-times"></i></a>
                                <a href="#" class="icontrip" data-id="{{ $trip->id }}" id="completemodal"><i class="glyphicon glyphicon-ok"></i></a>                            @endif
                        </td>
                    </tr>
                    @elseif(($trip->status != 'SCHEDULED'))
                   <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><a href="#" id="showmap" data-id="{{ $trip->id }}">{{ $trip->booking_id }}</a> <br><a href="#" id="showroute" class="icontrip" data-id="{{ $trip->id }}"><i class="fa fa-map-marker"></i></a></td>
                        <td>{{  date("Y-m-d h:i A", strtotime($trip->assigned_at)) }}</td>
                        <td>@if($trip->status == 'SCHEDULED') 
                            {{  date("Y-m-d h:i A", strtotime($trip->schedule_at))}} 
                            @elseif($trip->started_at) 
                            {{  date("Y-m-d h:i A", strtotime($trip->started_at))}} 
                            @endif
                        </td>
                        <td>@if($trip->finished_at)
                            {{  date("Y-m-d h:i A", strtotime($trip->finished_at))}} 
                            @else 
                            {{ $trip->finished_at }}
                            @endif
                        </td> 
                        <td>
                            @if($trip->user_name !=null)
                            {{ $trip->user_name }}
                            @endif
                            @if($trip->user_mobile !=null)
                            <br> {{ $trip->user_mobile }}
                            @endif
                        </td> 
                        <td>
                            @if($trip->provider) 
                            {{ $trip->provider->name }}
                            @elseif($trip->currentprovider) 
                            {{ $trip->currentprovider->name }}
                            @else
                            No driver
                            @endif
                        </td>
                        <td>
                            @if($trip->service_type)
                            {{ $trip->service_type->name }}
                            @endif
                        </td>
                        <td>{{ $trip->s_address }} </td>
                        <td>{{ $trip->d_address }} </td>
                        <td>{{ $trip->distance }} {{ $diskm}}</td>
                        <td style="width:100px">
                            @if($trip->payment)
                            {{ $trip->payment->total }} {{ $trip->payment->currency}}
                            @else
                            {{ currency_amt($trip->estimated_fare) }}
                            @endif
                            <br>
                            @if($trip->fare_type ==1 || $trip->fare_type ==2)
                                (Fixed)
                            @else
                                (Distance)
                            @endif
                        </td>
                        <td>

                            @if($trip->booking_by =='APP')
                                Mobile App
                          @php
                              $device_type=App\Models\User::where('id',$trip->user_id)->first();
                              if($device_type){
                              echo "(".$device_type->device_type.")";
                             }
                          @endphp
                             @endif
                             @if($trip->booking_by =='WEB')
                                Web Booking
                             @endif
                             @if($trip->booking_by =='STREET')
                                Street Ride
                            
                             @endif
                             @if($trip->booking_by =='DISPATCHER')
                                Dispatcher
                             @endif
                             @if($trip->booking_by =='HOTEL')
                                Hotel
                             @endif
                             @if($trip->booking_by =='CORPORATE')
                                Corporate
                             @endif

                        </td>
                        <td>
                            @if($trip->cancelled_by =='USER')
                            User
                            @endif
                            @if($trip->cancelled_by =='PROVIDER')
                            Driver
                            @endif
                            @if($trip->cancelled_by =='DISPATCHER')
                            Dispatcher
                            @endif
                            @if($trip->cancelled_by =='REJECTED')
                            All Drivers Rejected
                            @endif
                            @if($trip->cancelled_by =='NODRIVER')
                            No Drivers Found
                            @endif
                            @if($trip->cancel_reason !=null)
                            ({{ $trip->cancel_reason }})
                            @endif
                        </td>
                        <td>
                           
                            @if($trip->status == 'CANCELLED')
                            <span class="tag  pull-right @if($trip->current_provider_id == 0) hoverable @endif" id="dispatch" data-latitude="{{ $trip->s_latitude }}" data-longitude="{{ $trip->s_longitude }}" data-service="{{ $trip->service_type_id }}" data-id="{{ $trip->id }}" data-current="{{ $trip->current_provider_id }}"> <span class="normal tag-danger">{{$trip->status}}</span><span class="hover tag-dark">DISPATCH &nbsp;</span></span><br>
                            <a href="#" id="editmodal" class="icontrip" data-id="{{ $trip->id }}"><i class="fa fa-edit"></i></a>
                            <a href="#" class="icontrip custom-class"><i class="fa fa-times"></i></a>

                            @elseif($trip->status == 'COMPLETED')
                            <span class="tag tag-success pull-right custom-class"> {{$trip->status}} </span><br>
                            <a href="#" class="icontrip custom-class"><i class="fa fa-edit"></i></a>
                            <a href="#" class="icontrip custom-class"><i class="fa fa-times"></i></a>

                            @elseif($trip->status == 'SEARCHING')
                                <span class="tag  pull-right @if($trip->current_provider_id == 0) custom-class @endif" id="dispatch1" data-latitude="{{ $trip->s_latitude }}" data-longitude="{{ $trip->s_longitude }}" data-service="{{ $trip->service_type_id }}" data-id="{{ $trip->id }}" data-current="{{ $trip->current_provider_id }}"> <span class="normal tag-warning custom-class">{{$trip->status}}</span><span class="hover tag-dark">SEARCHING</span></span><br>
                                <a href="#" id="editmodal1" class="icontrip custom-class" data-id="{{ $trip->id }}"><i class="fa fa-edit"></i></a>
                                <a href="#" class="icontrip" data-id="{{ $trip->id }}" id="cancelmodal"><i class="fa fa-times"></i></a>
                            
                            @elseif($trip->status == 'ACCEPTED')
                                <span class="tag  pull-right" data-latitude="{{ $trip->s_latitude }}" data-longitude="{{ $trip->s_longitude }}" data-service="{{ $trip->service_type_id }}" data-id="{{ $trip->id }}" data-current="{{ $trip->current_provider_id }}"> <span class="normal tag-warning">{{$trip->status}}</span></span><br>
                                <a href="#" id="editmodal" class="icontrip" data-id="{{ $trip->id }}"><i class="fa fa-edit"></i></a>
                                <a href="#" class="icontrip" data-id="{{ $trip->id }}" id="cancelmodal"><i class="fa fa-times"></i></a>
                                <a href="#" class="icontrip" data-id="{{ $trip->id }}" id="completemodal"><i class="glyphicon glyphicon-ok"></i></a>


                            @elseif($trip->status == 'SCHEDULED')
                                <span class="tag  pull-right @if($trip->current_provider_id == 0) hoverable @endif" id="dispatch" data-latitude="{{ $trip->s_latitude }}" data-longitude="{{ $trip->s_longitude }}" data-service="{{ $trip->service_type_id }}" data-id="{{ $trip->id }}" data-current="{{ $trip->current_provider_id }}"><span class="normal tag-primary">{{$trip->status}}</span><span class="hover tag-dark">DISPATCH</span></span><br>
                                <a href="#" id="editmodal" class="icontrip" data-id="{{ $trip->id }}"><i class="fa fa-edit"></i></a>
                                <a href="#" class="icontrip" data-id="{{ $trip->id }}" id="cancelmodal"><i class="fa fa-times"></i></a>

                            @else
                            <span class="tag tag-info pull-right" id="editmodal" data-id="{{ $trip->id }}"> {{$trip->status}} </span><br>
                            <a href="#" id="editmodal" class="icontrip" data-id="{{ $trip->id }}"><i class="fa fa-edit"></i></a>
                            <a href="#" class="icontrip" data-id="{{ $trip->id }}" id="cancelmodal"><i class="fa fa-times"></i></a>
                            <a href="#" class="icontrip" data-id="{{ $trip->id }}" id="completemodal"><i class="glyphicon glyphicon-ok"></i></a>

                            @endif
                        </td>
                    </tr>         
                      @endif
                @endforeach
                
                
            


