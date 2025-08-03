<!-- <input type="text" class="filter-box" id="myInput" onkeyup="myFunction()" placeholder="Search for drivers">
 -->
<div class="sidebar-activity">
    <div class="notifications">
        <table class="table" id="myTable">
        @foreach($drivers as $index => $driver)
            @if($driver->providertype)

            <tr id="drivers" data-id="{{ $driver->id }}" data-status="{{$driver->status}}">
                <td class="drive-font">
                <span style="display: block;">{{ $driver->name }} - {{ $driver->servicename }}</span>
                <span>
                @if($driver->providertype=='android')
                    <img style="width:20px;" src="/asset/img/Android.png">
                    {{ $driver->servicenumber }}                
                    @elseif($driver->providertype=='ios')
                    <img style="width:20px;" src="/asset/img/IOS.png">
                    {{ $driver->servicenumber }}                
                @endif
                </span>
                </td>

                <td>
               @if($driver->status =='riding')
                    <img src="/asset/img/Blue.png">
                @elseif($driver->status =='active')
                    <img src="/asset/img/Green.png">
                @else
                    <img src="/asset/img/Red.png">
                @endif
               </td>
            </tr>
            @endif
            @endforeach
        </table>
    
    </div>
</div>
