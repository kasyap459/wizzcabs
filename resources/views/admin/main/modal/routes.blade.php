<div id="map"></div>
    
<script type="text/javascript">
initMap();   
function initMap() {
    var locations = {!! $data !!}
    if(locations.length > 0){
        var lat = locations[0].latitude;
        var lng = locations[0].longitude;
    }else{
        var lat = parseFloat("{{ Setting::get('address_lat', '') }}");
        var lng = parseFloat("{{ Setting::get('address_long', '') }}");
    }
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 18,
        center: new google.maps.LatLng(lat, lng),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var marker, i;

    for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i].latitude, locations[i].longitude),
            map: map,
            icon: '/asset/img/marker-end.png',
        });
    }
}
</script>