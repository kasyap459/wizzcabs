<form action="{{route('corporate.storedetail', $request->id )}}" method="POST" id="senddata" role="form" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="PATCH">
            <div class="form-group row">
                    <div class="col-xs-12">
                        <label for="name" class="col-form-label">@lang('admin.member.name')</label>
                        <input class="form-control" type="text" value="{{ !empty($request->user_name) ? $request->user_name : ' ' }}" name="name" required id="name" placeholder="@lang('admin.member.name')" disabled>
                    </div>
            </div>
            <div class="form-group row">
                    <div class="col-xs-6">
                        <label for="first_name" class="col-form-label">@lang('admin.member.email')</label>
                        <input class="form-control" type="text" value="{{ !empty($request->user->email) ? $request->user->email : ' ' }}" name="email" required id="email" placeholder="@lang('admin.member.email')" disabled>
                    </div>
                    <div class="col-xs-6">
                        <label for="first_name" class="col-form-label">@lang('admin.member.mobile_number')</label>
                        <input class="form-control" type="text" value="{{ !empty($request->user_mobile) ? $request->user_mobile : ' ' }}" name="mobile" required id="mobile" placeholder="@lang('admin.member.mobile_number')" disabled>
                    </div>
            </div>
            <div class="form-group row">
                    <div class="col-xs-6">
                        <label for="s_address" class="col-form-label">@lang('admin.member.pickup_address')</label>
                        <input class="form-control" onfocus="initMap()" type="text" value="{{ !empty($request->s_address) ? $request->s_address : ' ' }}" name="s_address" required id="s_address" placeholder="@lang('admin.member.pickup_address')" @if($request->status !='SEARCHING' && $request->status !='SCHEDULED' && $request->status !='CANCELLED') readonly @endif>
                        <input type="hidden" name="s_latitude" id="s_latitude" value="{{ $request->s_latitude }}" >
                        <input type="hidden" name="s_longitude" id="s_longitude" value="{{ $request->s_longitude }}">
                    </div>
                    <div class="col-xs-6">
                        <label for="d_address" class="col-form-label">@lang('admin.member.drop_address')</label>
                        <input class="form-control" onfocus="initMap()" type="text" value="{{ !empty($request->d_address) ? $request->d_address : ' ' }}" name="d_address" required id="d_address" placeholder="@lang('admin.member.drop_address')">
                        <input type="hidden" name="d_latitude" id="d_latitude" value="{{ $request->d_latitude }}">
                        <input type="hidden" name="d_longitude" id="d_longitude" value="{{ $request->d_longitude }}">
                        <input type="hidden" value="{{ !empty($request->status) ? $request->status : ' ' }}" name="status" id="status">
                    </div>
            </div>
            <div class="form-group row">
                    <div class="col-xs-6">
                        <label for="schedule_at" class="col-form-label">@lang('admin.member.schedule_time')</label>
                        <input class="form-control" type="text" value="{{ !empty($request->schedule_at) ? $request->schedule_at : ' ' }}" name="schedule_at" id="schedule_at" placeholder="@lang('admin.member.schedule_time')">

                    </div>
                    <div class="col-xs-6">
                        <label for="service_type_id" class="col-form-label">@lang('admin.member.service_type')</label>
                        <select name="service_type_id" id="service_type_id" class="form-control">
                            @foreach($services as $index => $service)
                            <option value="{{ $service->id }}" @if($request->service_type_id == $service->id) selected @endif>{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="form-group row">
                    <div class="col-xs-6">
                        <label for="service_type_id" class="col-form-label"></label>
                        <button type="submit" class="btn btn-danger btn-block waves-effect waves-light" data-dismiss="modal">@lang('admin.member.close')</button>
                    </div>
                    <div class="col-xs-6">
                        <label for="service_type_id" class="col-form-label"></label>
                        <button type="submit" onfocus="initMap()" class="btn btn-success btn-block waves-effect waves-light">@lang('admin.member.update')</button>
                    </div>
            </div>
        </form>

    <script>
    window.vx = {!! json_encode([
        "minDate" => \Carbon\Carbon::today()->format('Y-m-d\TH:i'),
        "maxDate" => \Carbon\Carbon::today()->addDays(30)->format('Y-m-d\TH:i'),
    ]) !!}
    $('#schedule_at').datetimepicker({
            minDate: window.vx.minDate,
            maxDate: window.vx.maxDate,
            format:'Y-m-d H:i',
    });
    new Switchery(document.getElementById('provider_auto_assign'));
    </script>
   
    <script type="text/javascript">
  function initMap() {

    var originInput = document.getElementById('s_address');
    var destinationInput = document.getElementById('d_address');
    var originLatitude = document.getElementById('s_latitude');
    var originLongitude = document.getElementById('s_longitude');
    var destinationLatitude = document.getElementById('d_latitude');
    var destinationLongitude = document.getElementById('d_longitude');

    var originAutocomplete = new google.maps.places.Autocomplete(
            originInput);

    originAutocomplete.setComponentRestrictions(
            {'country': ['se']});

    var destinationAutocomplete = new google.maps.places.Autocomplete(
            destinationInput);

    destinationAutocomplete.setComponentRestrictions(
            {'country': ['se']});
    
    originAutocomplete.addListener('place_changed', function(event) {
        var place = originAutocomplete.getPlace();

        if (place.hasOwnProperty('place_id')) {
            if (!place.geometry) {
                    // window.alert("Autocomplete's returned place contains no geometry");
                    return;
            }
            originLatitude.value = place.geometry.location.lat();
            originLongitude.value = place.geometry.location.lng();
        } else {
            service.textSearch({
                    query: place.name
            }, function(results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    originLatitude.value = results[0].geometry.location.lat();
                    originLongitude.value = results[0].geometry.location.lng();
                }
            });
        }
    });


    destinationAutocomplete.addListener('place_changed', function(event) {
        var place = destinationAutocomplete.getPlace();

        if (place.hasOwnProperty('place_id')) {
            if (!place.geometry) {
                // window.alert("Autocomplete's returned place contains no geometry");
                return;
            }
            destinationLatitude.value = place.geometry.location.lat();
            destinationLongitude.value = place.geometry.location.lng();
        } else {
            service.textSearch({
                query: place.name
            }, function(results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    destinationLatitude.value = results[0].geometry.location.lat();
                    destinationLongitude.value = results[0].geometry.location.lng();
                }
            });
        }
    });

  }

  $("#senddata").submit(function(stay){
   var formdata = $(this).serialize(); // here $(this) refere to the form its submitting
    $.ajax({
        type: 'POST',
        url: "{{ route('corporate.storedetail', $request->id ) }}",
        data: formdata, // here $(this) refers to the ajax object not form
        success: function (data) {
           closemodal();
        },
    });
    stay.preventDefault(); 
});
    </script>