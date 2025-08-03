 <head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
 <style>   
     html,
        body,
        #map {
          height: 100%;
          margin: 0px;
          padding: 0px
        }
        
        .map-control-button {
          height: 25px;
          width: 25px;
          background: #f4f4f4;
          padding: 1px;
          box-sizing: border-box;
          padding: 6px;
          cursor: pointer;
          display: inline-block;
        }
        
        .custom-control-wrapper {
          margin-top: 8px;
        }
</style>
<div id="map"></div>
<script src="https://maps.googleapis.com/maps/api/js?key={{ Setting::get('map_key', 'AIzaSyA30-YQUNKSLkw69WCOzJBMHDDwH_X_QXY') }}&libraries=places" ></script>

<script>
var pathCoords = [{
  "lat": 8.896740000000001,
  "lng": 76.61312000000001
},  {
  "lat": 8.903450000000001,
  "lng": 76.62240000000001
}, {
  "lat": 8.903970000000001,
  "lng": 76.62272
}, {
  "lat": 8.90409,
  "lng": 76.62280000000001
}, {
  "lat": 8.904,
  "lng": 76.62288000000001
}, {
  "lat": 8.90342,
  "lng": 76.6233
}, {
  "lat": 8.902560000000001,
  "lng": 76.62386000000001
}, {
  "lat": 8.90033,
  "lng": 76.62522000000001
}, {
  "lat": 8.89601,
  "lng": 76.62777000000001
}, {
  "lat": 8.88676,
  "lng": 76.63327000000001
}, {
  "lat": 8.884450000000001,
  "lng": 76.63461000000001
}, {
  "lat": 8.882610000000001,
  "lng": 76.63582000000001
}, {
  "lat": 8.88089,
  "lng": 76.63711
}, {
  "lat": 8.87918,
  "lng": 76.63862
}, {
  "lat": 8.87785,
  "lng": 76.63936000000001
}, {
  "lat": 8.875760000000001,
  "lng": 76.63996
}, {
  "lat": 8.87273,
  "lng": 76.64141000000001
}, {
  "lat": 8.87067,
  "lng": 76.64251
}, {
  "lat": 8.869280000000002,
  "lng": 76.64336
}, {
  "lat": 8.86805,
  "lng": 76.6447
}, {
  "lat": 8.86782,
  "lng": 76.6451
}, {
  "lat": 8.86677,
  "lng": 76.64822000000001
}, {
  "lat": 8.86645,
  "lng": 76.64933
}, {
  "lat": 8.866200000000001,
  "lng": 76.65092
}, {
  "lat": 8.86546,
  "lng": 76.6533
}, {
  "lat": 8.86508,
  "lng": 76.65451
}, {
  "lat": 8.86495,
  "lng": 76.65667
}, {
  "lat": 8.864880000000001,
  "lng": 76.65962
}, {
  "lat": 8.86519,
  "lng": 76.66080000000001
}, {
  "lat": 8.866240000000001,
  "lng": 76.66343
}, {
  "lat": 8.86646,
  "lng": 76.66454
}, {
  "lat": 8.866200000000001,
  "lng": 76.66933
}, {
  "lat": 8.86569,
  "lng": 76.67323
}, {
  "lat": 8.86522,
  "lng": 76.67823
}, {
  "lat": 8.863840000000001,
  "lng": 76.68872
}, {
  "lat": 8.86359,
  "lng": 76.6907
}, {
  "lat": 8.86364,
  "lng": 76.69282000000001
}, {
  "lat": 8.86317,
  "lng": 76.69574
}, {
  "lat": 8.863420000000001,
  "lng": 76.69850000000001
}, {
  "lat": 8.8634,
  "lng": 76.69958000000001
}, {
  "lat": 8.863050000000001,
  "lng": 76.70048000000001
}, {
  "lat": 8.862350000000001,
  "lng": 76.70149
}, {
  "lat": 8.862020000000001,
  "lng": 76.70239000000001
}, {
  "lat": 8.86176,
  "lng": 76.70448
}, {
  "lat": 8.86218,
  "lng": 76.70703
}, {
  "lat": 8.863180000000002,
  "lng": 76.70957
}, {
  "lat": 8.8636,
  "lng": 76.71115
}, {
  "lat": 8.86382,
  "lng": 76.71257
}, {
  "lat": 8.86383,
  "lng": 76.71368000000001
}, {
  "lat": 8.86354,
  "lng": 76.71509
}, {
  "lat": 8.863240000000001,
  "lng": 76.71595
}, {
  "lat": 8.86259,
  "lng": 76.71731000000001
}, {
  "lat": 8.861540000000002,
  "lng": 76.71883000000001
}, {
  "lat": 8.85947,
  "lng": 76.72208
}, {
  "lat": 8.85875,
  "lng": 76.72339000000001
}, {
  "lat": 8.8573,
  "lng": 76.72554000000001
}, {
  "lat": 8.85478,
  "lng": 76.72876000000001
}, {
  "lat": 8.85397,
  "lng": 76.72962000000001
}, {
  "lat": 8.8518,
  "lng": 76.73123000000001
}, {
  "lat": 8.851220000000001,
  "lng": 76.73173000000001
}, {
  "lat": 8.850200000000001,
  "lng": 76.73293000000001
}, {
  "lat": 8.84966,
  "lng": 76.73342000000001
}, {
  "lat": 8.848920000000001,
  "lng": 76.73382000000001
}, {
  "lat": 8.84797,
  "lng": 76.73409000000001
}, {
  "lat": 8.84509,
  "lng": 76.73453
}, {
  "lat": 8.843850000000002,
  "lng": 76.73492
}, {
  "lat": 8.84294,
  "lng": 76.73541
}, {
  "lat": 8.841140000000001,
  "lng": 76.73677
}, {
  "lat": 8.84003,
  "lng": 76.73747
}, {
  "lat": 8.838830000000002,
  "lng": 76.73848000000001
}, {
  "lat": 8.836,
  "lng": 76.73998
}, {
  "lat": 8.835310000000002,
  "lng": 76.74063000000001
}, {
  "lat": 8.83496,
  "lng": 76.74137
}, {
  "lat": 8.834480000000001,
  "lng": 76.74313000000001
}, {
  "lat": 8.83385,
  "lng": 76.74406
}, {
  "lat": 8.832690000000001,
  "lng": 76.74528000000001
}, {
  "lat": 8.83188,
  "lng": 76.74599
}, {
  "lat": 8.830480000000001,
  "lng": 76.7467
}, {
  "lat": 8.82831,
  "lng": 76.74775000000001
}, {
  "lat": 8.82764,
  "lng": 76.74836
}, {
  "lat": 8.82665,
  "lng": 76.7493
}, {
  "lat": 8.82475,
  "lng": 76.7505
}, {
  "lat": 8.82245,
  "lng": 76.75188
}, {
  "lat": 8.82136,
  "lng": 76.75254000000001
}, {
  "lat": 8.819880000000001,
  "lng": 76.75371000000001
}, {
  "lat": 8.818140000000001,
  "lng": 76.75496000000001
}, {
  "lat": 8.815700000000001,
  "lng": 76.75652000000001
}, {
  "lat": 8.8125,
  "lng": 76.75867000000001
}, {
  "lat": 8.809470000000001,
  "lng": 76.76091000000001
}, {
  "lat": 8.806270000000001,
  "lng": 76.76203000000001
}, {
  "lat": 8.804110000000001,
  "lng": 76.763
}, {
  "lat": 8.803410000000001,
  "lng": 76.76368000000001
}, {
  "lat": 8.802990000000001,
  "lng": 76.76439
}, {
  "lat": 8.8024,
  "lng": 76.76629000000001
}, {
  "lat": 8.80207,
  "lng": 76.76687000000001
}, {
  "lat": 8.801540000000001,
  "lng": 76.76747
}, {
  "lat": 8.79983,
  "lng": 76.76870000000001
}, {
  "lat": 8.799180000000002,
  "lng": 76.76894
}, {
  "lat": 8.798390000000001,
  "lng": 76.76903
}, {
  "lat": 8.79742,
  "lng": 76.76905000000001
}, {
  "lat": 8.795710000000001,
  "lng": 76.76929000000001
}, {
  "lat": 8.79439,
  "lng": 76.76955000000001
}, {
  "lat": 8.79053,
  "lng": 76.77083
}, {
  "lat": 8.78954,
  "lng": 76.77144000000001
}, {
  "lat": 8.788820000000001,
  "lng": 76.77241000000001
}, {
  "lat": 8.78842,
  "lng": 76.77367000000001
}, {
  "lat": 8.78814,
  "lng": 76.77574000000001
}, {
  "lat": 8.78767,
  "lng": 76.77690000000001
}, {
  "lat": 8.78735,
  "lng": 76.77735000000001
}, {
  "lat": 8.78688,
  "lng": 76.77771000000001
}, {
  "lat": 8.78307,
  "lng": 76.78025000000001
}, {
  "lat": 8.779250000000001,
  "lng": 76.78346
}, {
  "lat": 8.77712,
  "lng": 76.78488
}, {
  "lat": 8.77275,
  "lng": 76.78745
}, {
  "lat": 8.770980000000002,
  "lng": 76.78802
}, {
  "lat": 8.76699,
  "lng": 76.78881000000001
}, {
  "lat": 8.765640000000001,
  "lng": 76.78967
}, {
  "lat": 8.76401,
  "lng": 76.79095000000001
}, {
  "lat": 8.763190000000002,
  "lng": 76.79128
}, {
  "lat": 8.76102,
  "lng": 76.79195
}, {
  "lat": 8.7558,
  "lng": 76.79388
}, {
  "lat": 8.75234,
  "lng": 76.79589
}, {
  "lat": 8.750900000000001,
  "lng": 76.79650000000001
}, {
  "lat": 8.75009,
  "lng": 76.79726000000001
}, {
  "lat": 8.748840000000001,
  "lng": 76.79895
}, {
  "lat": 8.747110000000001,
  "lng": 76.80017000000001
}, {
  "lat": 8.74582,
  "lng": 76.80142000000001
}, {
  "lat": 8.74407,
  "lng": 76.80305000000001
}, {
  "lat": 8.74125,
  "lng": 76.80436
}, {
  "lat": 8.73728,
  "lng": 76.80730000000001
}, {
  "lat": 8.73624,
  "lng": 76.80879
}, {
  "lat": 8.73544,
  "lng": 76.80953000000001
}, {
  "lat": 8.732800000000001,
  "lng": 76.8109
}, {
  "lat": 8.731950000000001,
  "lng": 76.8113
}, {
  "lat": 8.730500000000001,
  "lng": 76.81166
}, {
  "lat": 8.72644,
  "lng": 76.81249000000001
}, {
  "lat": 8.723980000000001,
  "lng": 76.81252
}, {
  "lat": 8.72197,
  "lng": 76.81271000000001
}, {
  "lat": 8.720460000000001,
  "lng": 76.81268
}, {
  "lat": 8.71958,
  "lng": 76.81273
}, {
  "lat": 8.71719,
  "lng": 76.81297
}, {
  "lat": 8.71636,
  "lng": 76.81293000000001
}, {
  "lat": 8.714080000000001,
  "lng": 76.81215
}, {
  "lat": 8.713080000000001,
  "lng": 76.81175
}, {
  "lat": 8.71254,
  "lng": 76.8117
}, {
  "lat": 8.71188,
  "lng": 76.81176
}, {
  "lat": 8.71118,
  "lng": 76.81200000000001
}, {
  "lat": 8.70936,
  "lng": 76.81330000000001
}, {
  "lat": 8.70716,
  "lng": 76.81486000000001
}, {
  "lat": 8.70616,
  "lng": 76.81506
}, {
  "lat": 8.70514,
  "lng": 76.81497
}, {
  "lat": 8.70363,
  "lng": 76.81461
}, {
  "lat": 8.70148,
  "lng": 76.81371
}, {
  "lat": 8.70063,
  "lng": 76.81357000000001
}, {
  "lat": 8.70003,
  "lng": 76.81360000000001
}, {
  "lat": 8.698400000000001,
  "lng": 76.81366000000001
}, {
  "lat": 8.698210000000001,
  "lng": 76.81378000000001
}, {
  "lat": 8.69705,
  "lng": 76.81499000000001
}, {
  "lat": 8.696520000000001,
  "lng": 76.81584000000001
}, {
  "lat": 8.69635,
  "lng": 76.81654
}, {
  "lat": 8.695580000000001,
  "lng": 76.8182
}, {
  "lat": 8.69529,
  "lng": 76.81865
}, {
  "lat": 8.694780000000002,
  "lng": 76.81908
}, {
  "lat": 8.69355,
  "lng": 76.81999
}, {
  "lat": 8.6928,
  "lng": 76.82099000000001
}, {
  "lat": 8.691880000000001,
  "lng": 76.82178
}, {
  "lat": 8.690430000000001,
  "lng": 76.82248000000001
}, {
  "lat": 8.68914,
  "lng": 76.82319000000001
}, {
  "lat": 8.68819,
  "lng": 76.82406
}, {
  "lat": 8.68773,
  "lng": 76.82423
}, {
  "lat": 8.68612,
  "lng": 76.82438
}, {
  "lat": 8.68462,
  "lng": 76.82435000000001
}, {
  "lat": 8.68229,
  "lng": 76.82445000000001
}, {
  "lat": 8.68044,
  "lng": 76.82475000000001
}, {
  "lat": 8.67947,
  "lng": 76.8251
}, {
  "lat": 8.678650000000001,
  "lng": 76.82560000000001
}, {
  "lat": 8.678180000000001,
  "lng": 76.82599
}, {
  "lat": 8.67652,
  "lng": 76.82787
}, {
  "lat": 8.67533,
  "lng": 76.82933000000001
}, {
  "lat": 8.67463,
  "lng": 76.83056
}, {
  "lat": 8.6738,
  "lng": 76.83302
}, {
  "lat": 8.67337,
  "lng": 76.83368
}, {
  "lat": 8.67276,
  "lng": 76.83424000000001
}, {
  "lat": 8.67102,
  "lng": 76.83500000000001
}, {
  "lat": 8.669350000000001,
  "lng": 76.83574
}, {
  "lat": 8.6684,
  "lng": 76.83630000000001
}, {
  "lat": 8.665890000000001,
  "lng": 76.83818000000001
}, {
  "lat": 8.66487,
  "lng": 76.83893
}, {
  "lat": 8.66254,
  "lng": 76.83997000000001
}, {
  "lat": 8.66042,
  "lng": 76.8408
}, {
  "lat": 8.65906,
  "lng": 76.84094
}, {
  "lat": 8.65737,
  "lng": 76.84088000000001
}, {
  "lat": 8.655100000000001,
  "lng": 76.84037000000001
}, {
  "lat": 8.654060000000001,
  "lng": 76.84041
}, {
  "lat": 8.65291,
  "lng": 76.84082000000001
}, {
  "lat": 8.65094,
  "lng": 76.84149000000001
}, {
  "lat": 8.647400000000001,
  "lng": 76.84214
}, {
  "lat": 8.64208,
  "lng": 76.84372
}, {
  "lat": 8.63718,
  "lng": 76.84567000000001
}, {
  "lat": 8.633890000000001,
  "lng": 76.84642000000001
}, {
  "lat": 8.63049,
  "lng": 76.84713
}, {
  "lat": 8.62597,
  "lng": 76.84825000000001
}, {
  "lat": 8.62233,
  "lng": 76.84888000000001
}, {
  "lat": 8.61796,
  "lng": 76.85015
}, {
  "lat": 8.61705,
  "lng": 76.85052
}, {
  "lat": 8.611970000000001,
  "lng": 76.85444000000001
}, {
  "lat": 8.609100000000002,
  "lng": 76.85662
}, {
  "lat": 8.60847,
  "lng": 76.85691000000001
}, {
  "lat": 8.60748,
  "lng": 76.85704000000001
}, {
  "lat": 8.604790000000001,
  "lng": 76.85632000000001
}, {
  "lat": 8.60331,
  "lng": 76.85583000000001
}, {
  "lat": 8.601980000000001,
  "lng": 76.85521
}, {
  "lat": 8.60084,
  "lng": 76.85462000000001
}, {
  "lat": 8.599530000000001,
  "lng": 76.85423
}, {
  "lat": 8.598180000000001,
  "lng": 76.85446
}, {
  "lat": 8.59272,
  "lng": 76.85616
}, {
  "lat": 8.58853,
  "lng": 76.85780000000001
}, {
  "lat": 8.587850000000001,
  "lng": 76.85819000000001
}, {
  "lat": 8.58651,
  "lng": 76.85957
}, {
  "lat": 8.58371,
  "lng": 76.86185
}, {
  "lat": 8.58211,
  "lng": 76.86330000000001
}, {
  "lat": 8.57942,
  "lng": 76.86524
}, {
  "lat": 8.57592,
  "lng": 76.86807
}, {
  "lat": 8.57488,
  "lng": 76.86879
}, {
  "lat": 8.573540000000001,
  "lng": 76.86953000000001
}, {
  "lat": 8.57169,
  "lng": 76.87060000000001
}, {
  "lat": 8.56986,
  "lng": 76.87212000000001
}, {
  "lat": 8.56779,
  "lng": 76.87364000000001
}, {
  "lat": 8.56587,
  "lng": 76.87492
}, {
  "lat": 8.56545,
  "lng": 76.8755
}, {
  "lat": 8.565230000000001,
  "lng": 76.87642000000001
}, {
  "lat": 8.56508,
  "lng": 76.87809
}, {
  "lat": 8.5647,
  "lng": 76.88276
}, {
  "lat": 8.56473,
  "lng": 76.88329
}, {
  "lat": 8.56521,
  "lng": 76.88474000000001
}, {
  "lat": 8.56591,
  "lng": 76.88702
}, {
  "lat": 8.566840000000001,
  "lng": 76.88966
}, {
  "lat": 8.566930000000001,
  "lng": 76.89045
}, {
  "lat": 8.56609,
  "lng": 76.89177000000001
}, {
  "lat": 8.564670000000001,
  "lng": 76.89337
}, {
  "lat": 8.56283,
  "lng": 76.89437000000001
}, {
  "lat": 8.56193,
  "lng": 76.89489
}, ];

var map = new google.maps.Map(document.getElementById("map"), {
  center: {
    lat: pathCoords[0].lat,
    lng: pathCoords[0].lng
  },
  zoom: 14,
  mapTypeId: google.maps.MapTypeId.ROADMAP
});
var customControlWrapper = $('<div class="custom-control-wrapper">');
var playBtn = $('<div class="map-control-button"><i class="fa fa-play-circle-o fa-lg"></i></div>');

var pauseBtn = $('<div id="pause" style="display:none" class="map-control-button"><i class="fa fa-pause-circle-o fa-lg"></i></div>');

var stopBtn = $('<div id="stop"  class="map-control-button"><i class="fa fa-stop-circle-o fa-lg"></i></div>');

customControlWrapper.append(playBtn, pauseBtn, stopBtn);
map.controls[google.maps.ControlPosition.TOP_CENTER].push(customControlWrapper[0])
var timer;
playBtn.on('click', function() {
  if (!timer) {
    line.getPath().clear()
    recursiveAnimate(0)
  } else {
    timer.resume()
  }
  marker.setMap(map)
  playBtn.hide()
  pauseBtn.show()
});
pauseBtn.on('click', function() {
  timer && timer.pause()
  playBtn.show()
  pauseBtn.hide()
});
stopBtn.on('click', function() {
  timer && timer.cancel();
  timer = null;
  line.setPath(pathCoords)
  marker.setMap(null)
  playBtn.show()
  pauseBtn.hide()
});
var line = new google.maps.Polyline({
  path: [],
  strokeColor: "#FF0000",
  strokeOpacity: 0.7,
  strokeWeight: 2,
  geodesic: true, //set to false if you want straight line instead of arc
  map: map,
});

var car = "M17.402,0H5.643C2.526,0,0,3.467,0,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759c3.116,0,5.644-2.527,5.644-5.644 V6.584C23.044,3.467,20.518,0,17.402,0z M22.057,14.188v11.665l-2.729,0.351v-4.806L22.057,14.188z M20.625,10.773 c-1.016,3.9-2.219,8.51-2.219,8.51H4.638l-2.222-8.51C2.417,10.773,11.3,7.755,20.625,10.773z M3.748,21.713v4.492l-2.73-0.349 V14.502L3.748,21.713z M1.018,37.938V27.579l2.73,0.343v8.196L1.018,37.938z M2.575,40.882l2.218-3.336h13.771l2.219,3.336H2.575z M19.328,35.805v-7.872l2.729-0.355v10.048L19.328,35.805z";
var icon = {
  path: car,
  scale: .7,
  strokeColor: 'white',
  strokeWeight: .10,
  fillOpacity: 1,
  fillColor: '#404040',
  offset: '5%',
  // rotation: parseInt(heading[i]),
  anchor: new google.maps.Point(10, 25) // orig 10,50 back of car, 10,0 front of car, 10,25 center of car
};
marker = new google.maps.Marker({
  map: map,
  icon: icon
});


function recursiveAnimate(index) {
  timer && timer.cancel()
  var coordsDeparture = pathCoords[index];
  var coordsArrival = pathCoords[index + 1];

  var departure = new google.maps.LatLng(coordsDeparture.lat, coordsDeparture.lng); //Set to whatever lat/lng you need for your departure location
  var arrival = new google.maps.LatLng(coordsArrival.lat, coordsArrival.lng); //Set to whatever lat/lng you need for your arrival location
  var step = 0;
  var numSteps = 20; //Change this to set animation resolution
  var timePerStep = 3; //Change this to alter animation speed
  timer = InvervalTimer(function(arg) {
    step += 1;
    if (step > numSteps) {
      //clearInterval(interval);
      step = 0
      timer.cancel()
      if (index < pathCoords.length - 2) {
        recursiveAnimate(index + 1)
      }
    } else {
      var are_we_there_yet = google.maps.geometry.spherical.interpolate(departure, arrival, step / numSteps);
      line.getPath().push(are_we_there_yet);
      moveMarker(map, marker, departure, are_we_there_yet)
    }
  }, timePerStep);

}

function moveMarker(map, marker, departure, currentMarkerPos) {
  marker.setPosition(currentMarkerPos);
  map.panTo(currentMarkerPos);
  var heading = google.maps.geometry.spherical.computeHeading(departure, currentMarkerPos);
  icon.rotation = heading;
  marker.setIcon(icon);
}

function InvervalTimer(callback, interval, arg) {
  console.log(timer)
  var timerId, startTime, remaining = 0;
  var state = 0; //  0 = idle, 1 = running, 2 = paused, 3= resumed
  var timeoutId
  this.pause = function() {
    if (state != 1) return;

    remaining = interval - (new Date() - startTime);
    window.clearInterval(timerId);
    state = 2;
  };

  this.resume = function() {
    if (state != 2) return;

    state = 3;
    console.log(remaining)
    timeoutId = window.setTimeout(this.timeoutCallback, remaining, arg);
  };

  this.timeoutCallback = function(timer) {
    if (state != 3) return;
    clearTimeout(timeoutId);
    startTime = new Date();
    timerId = window.setInterval(function() {
      callback(arg)
    }, interval);
    state = 1;
  };

  this.cancel = function() {
    clearInterval(timerId)
  }
  startTime = new Date();
  timerId = window.setInterval(function() {
    callback(arg)
  }, interval);
  state = 1;
  return {
    cancel: cancel,
    pause: pause,
    resume: resume,
    timeoutCallback: timeoutCallback
  };
}

</script>