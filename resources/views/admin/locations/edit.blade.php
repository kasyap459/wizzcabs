@extends('admin.layout.base')

@section('title', 'Add Location')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	
    	<div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Zone management</h4><a href="{{ route('admin.location.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">List Location</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Update Location</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
    		<div class="row">
    			<div class="col-md-3">
					<h5 style="margin-bottom: 2em;">Update Location</h5>
		            <form class="form-horizontal" action="{{route('admin.location.update', $location->id )}}" method="POST" enctype="multipart/form-data" role="form">
		            	{{csrf_field()}}
            			<input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" class="form-control" name="tlatitude" id="tlatitude" value="{{ $location->tlatitude }}">
                        <input type="hidden" class="form-control" name="tlongitude" id="tlongitude" value="{{ $location->tlongitude }}">
                        <input type="hidden" class="form-control" name="clatitude" id="clatitude" value="{{ $location->clatitude }}">
                        <input type="hidden" class="form-control" name="clongitude" id="clongitude" value="{{ $location->clongitude }}">

						<div class="form-group row">
							<label for="location_name" class="col-xs-12 col-form-label">Location Name</label>
							<div class="col-xs-12">
								<input class="form-control" type="text" value="{{ $location->location_name }}" name="location_name" required id="location_name" placeholder="Location Name">
							</div>
						</div>
						<div class="form-group row">
							<label for="iCountry" class="col-xs-12 col-form-label">Country</label>
							<div class="col-xs-12">
								<select name="iCountry" id="iCountry" class="form-control" onchange="getGeoCounty(this.value);">
									<option value="">Select Country</option>
									@foreach($countries as $country)
										<option value="{{ $country->name }}" @if($country->countryid == $location->country_id) Selected @endif>{{ $country->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="zipcode" class="col-xs-12 col-form-label"></label>
							<div class="col-xs-12">
								<button type="submit" class="btn btn-success" onclick="return IsEmpty();"> <i class="fa fa-check"></i> Update</button>
								<a href="{{route('admin.location.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-9">
						<div class="google-map-wrap">
                            <input id="pac-input" type="text" placeholder="Enter Location For More Focus" style="padding:4px;width: 200px;margin-top: 5px;">
                            <div id="map-canvas" class="google-map" style="width:100%; height:500px;"></div>
                        </div>
                        <div style="text-align: center;margin-top: 5px;">
                            <button class="btn btn-danger" id="delete-button">Delete Selected Shape</button>
                        </div>
				</div>
			</div>
		</div>
    </div>
</div>

@endsection

@section('scripts')

<script src="//maps.google.com/maps/api/js?sensor=fasle&key={{ Setting::get('map_key') }}&libraries=places,drawing" type="text/javascript"></script>
<script>

    function IsEmpty() {
        if ((document.forms['location_form'].tlatitude.value === "") || (document.forms['location_form'].tlongitude.value === ""))
        {
            alert("Please select/draw the area on map shown in right hand side.");
            return false;
        }
        return true;
    }
    var drawingManager;
    var selectedShape;
    function clearSelection() {
        if (selectedShape) {
            if (typeof selectedShape.setEditable == 'function') {
                selectedShape.setEditable(false);
            }
            selectedShape = null;
        }
    }
    function deleteSelectedShape() {
        if (selectedShape) {
            selectedShape.setMap(null);
            $('#tlatitude').val("");
            $('#tlongitude').val("");
        }
    }
    function updateCurSelText(shape) {
        var latt = "";
        var longi = "";
        if (typeof selectedShape.getPath == 'function') {
            for (var i = 0; i < selectedShape.getPath().getLength(); i++) {
                var latlong = selectedShape.getPath().getAt(i).toUrlValue().split(",");
                latt += (latlong[0]) + ",";
                longi += (latlong[1]) + ",";
            }
        }
        $('#tlatitude').val(latt);
        $('#tlongitude').val(longi);
    }
    function setSelection(shape, isNotMarker) {
        clearSelection();
        selectedShape = shape;
        if (isNotMarker)
            shape.setEditable(true);
        updateCurSelText(shape);
    }
    function getGeoCounty(Countryname) {
        var geocoder = new google.maps.Geocoder();
        var address = Countryname;
        var lat, long;
        geocoder.geocode({'address': address}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK)
            {
                lat = results[0].geometry.location.lat();
                $('#clatitude').val(lat);
                long = results[0].geometry.location.lng();
                $('#clongitude').val(long);
                var tlat = $("#tlatitude").val();
                var tlong = $("#tlatitude").val();
                if (tlat == '' && tlong == '') {
                    play();
                }
            }
        });
    }
    /////////////////////////////////////
    var map;
    var searchBox;
    var placeMarkers = [];
    var input;
    /////////////////////////////////////
    function initialize() {
        var myLatLng = new google.maps.LatLng("", "");
        map = new google.maps.Map(document.getElementById('map-canvas'), {
            zoom: 5,
            center: myLatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: false,
            zoomControl: true
        });
        var polyOptions = {
          editable: true,
          strokeColor: '#ff0404',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#ff0404',
          fillOpacity: 0.35
        };

        // Creates a drawing manager attached to the map that allows the user to draw
        // markers, lines, and shapes.
        drawingManager = new google.maps.drawing.DrawingManager({
          drawingMode: google.maps.drawing.OverlayType.POLYGON,
          polylineOptions: {
            editable: true
          },
          drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_RIGHT,
                drawingModes: ['polygon', 'polyline']
          },
          rectangleOptions: polyOptions,
          circleOptions: polyOptions,
          polygonOptions: polyOptions,
          polylineOptions:polyOptions,
          map: map
        });

        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
          //~ if (e.type != google.maps.drawing.OverlayType.MARKER) {
            var isNotMarker = (e.type != google.maps.drawing.OverlayType.MARKER);
            // Switch back to non-drawing mode after drawing a shape.
            drawingManager.setDrawingMode(null);
            // Add an event listener that selects the newly-drawn shape when the user
            // mouses down on it.
            var newShape = e.overlay;
            newShape.type = e.type;
            google.maps.event.addListener(newShape, 'click', function() {
              setSelection(newShape, isNotMarker);
            });
            google.maps.event.addListener(newShape, 'drag', function() {
              updateCurSelText(newShape);
            });
            google.maps.event.addListener(newShape, 'dragend', function() {
              updateCurSelText(newShape);
            });
            setSelection(newShape, isNotMarker);
          //~ }// end if
        });

        google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
        google.maps.event.addListener(map, 'click', clearSelection);
        google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);
        google.maps.event.addListener(map, 'bounds_changed', function () {
            var bounds = map.getBounds();
        });
        //~ initSearch(); ============================================
        // Create the search box and link it to the UI element.
        input = /** @type {HTMLInputElement} */(//var
                document.getElementById('pac-input'));
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);
        //searchBox = new google.maps.places.SearchBox((input));
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);
        // Listen for the event fired when the user selects an item from the
        // pick list. Retrieve the matching places for that item.
        var marker = new google.maps.Marker({
            map: map
        });
        autocomplete.addListener('place_changed', function () {
            marker.setVisible(false);
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }
            // If the place has a geometry, then present it on a map.
            placeMarkers = [];
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(14);
            }
            // Create a marker for each place.
            marker = new google.maps.Marker({
                map: map,
                title: place.name,
                position: place.geometry.location
            });
            marker.setIcon(({
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            }));
            marker.setVisible(true);
        });
        /*        google.maps.event.addListener(searchBox, 'places_changed', function() {
         var places = searchBox.getPlaces();
         
         if (places.length == 0) {
         return;
         }
         for (var i = 0, marker; marker = placeMarkers[i]; i++) {
         marker.setMap(null);
         }
         
         // For each place, get the icon, place name, and location.
         placeMarkers = [];
         var bounds = new google.maps.LatLngBounds();
         for (var i = 0, place; place = places[i]; i++) {
         var image = {
         url: place.icon,
         size: new google.maps.Size(71, 71),
         origin: new google.maps.Point(0, 0),
         anchor: new google.maps.Point(17, 34),
         scaledSize: new google.maps.Size(25, 25)
         };
         
         // Create a marker for each place.
         var marker = new google.maps.Marker({
         map: map,
         icon: image,
         title: place.name,
         position: place.geometry.location
         });
         
         placeMarkers.push(marker);
         bounds.extend(place.geometry.location);
         }
         
         map.fitBounds(bounds);
         map.setZoom(14);
         });*/
        //~ EndSearch(); ============================================    
        // Polygon Coordinates
        var tlongitude = $('#tlongitude').val();
        var tlatitude = $('#tlatitude').val();
        var Country = $("#iCountry").val();
        if (Country != "" && (tlongitude == "" || tlatitude == "")) {
            getGeoCounty(Country);
            myLatLng = new google.maps.LatLng($("#clatitude").val(), $("#clongitude").val());
            map.fitBounds(myLatLng);
        } else {
            if (tlongitude != "" || tlatitude != "") {
                var tlat = tlatitude.split(",");
                var tlong = tlongitude.split(",");
                var triangleCoords = [];
                var bounds = new google.maps.LatLngBounds();
                for (var i = 0, len = tlat.length; i < len; i++) {
                    if (tlat[i] != "" || tlong[i] != "") {
                        triangleCoords.push(new google.maps.LatLng(tlat[i], tlong[i]));
                        var point = new google.maps.LatLng(tlat[i], tlong[i]);
                        bounds.extend(point);
                    }
                }
                // Styling & Controls
                myPolygon = new google.maps.Polygon({
                    paths: triangleCoords,
                    draggable: false, // turn off if it gets annoying
                    editable: true,
                    strokeColor: '#ff0404',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#ff0404',
                    fillOpacity: 0.35
                });
                map.fitBounds(bounds);
                myPolygon.setMap(map);
                //google.maps.event.addListener(myPolygon, "dragend", getPolygonCoords);
                google.maps.event.addListener(myPolygon.getPath(), "insert_at", getPolygonCoords);
                //google.maps.event.addListener(myPolygon.getPath(), "remove_at", getPolygonCoords);
                google.maps.event.addListener(myPolygon.getPath(), "set_at", getPolygonCoords);
                google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteEditShape);
            }
        }
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    function deleteEditShape() {
        if (myPolygon) {
            myPolygon.setMap(null);
        }
        $('#tlatitude').val("");
        $('#tlongitude').val("");
    }
    function play() {
        var pt = new google.maps.LatLng($("#clatitude").val(), $("#clongitude").val());
        map.setCenter(pt);
        map.setZoom(5);
    }
    //Display Coordinates below map
    function getPolygonCoords() {
        var len = myPolygon.getPath().getLength();
        var latt = "";
        var longi = "";
        for (var i = 0; i < len; i++) {
            var latlong = myPolygon.getPath().getAt(i).toUrlValue().split(",");
            latt += (latlong[0]) + ",";
            longi += (latlong[1]) + ",";
        }
        $('#tlatitude').val(latt);
        $('#tlongitude').val(longi);
    }

        </script>

@endsection