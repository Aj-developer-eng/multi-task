
@extends('template/frontend/userdashboard/employeepanel/layout/default')
@section('content')
<head>
<meta charset="utf-8">
<title>Locate the user</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src='https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.js'></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.js"></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css' rel='stylesheet' />

<link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.css" type="text/css">
<style>
.mapboxgl-ctrl-logo{
    display: none !important;
}
body { margin: 0; padding: 0; }
#map { position: absolute; top: 12%; bottom: 0; width: 79%; }

</style>
</head>
<body>



<div id='map' style="border: solid;border-color: #1e828de8;border-radius: 1%;"></div>

<script>
mapboxgl.accessToken = 'pk.eyJ1IjoiYXdhYWIiLCJhIjoiY2t1aWRod3ZkMGxwNjJ2b3piY2JidGRkMiJ9.xGrCYaQiqxu8QICQCqKAVA';
const map = new mapboxgl.Map({
attributionControl: false,
container: 'map',
style: 'mapbox://styles/mapbox/streets-v11',
center: [39.857910, 21.389082],
zoom: 5
});
map.addControl(new mapboxgl.NavigationControl());
map.addControl(
new mapboxgl.GeolocateControl({
positionOption:{
enableHighAccuracy:true
},
trackUserLocation:true
}));
map.addControl(
new MapboxDirections({
accessToken: mapboxgl.accessToken
}),
'top-left'
);
const addMarker = () => {
const marker = new mapboxgl.Marker()
const minPopup = new mapboxgl.Popup({closeButton: false, closeOnClick: false})
minPopup.setHTML("<h1>Default Location</h1><strong>Country:{{$location->countryName}},City:{{$location->cityName}}<p>Longitude:{{$location->longitude}},Latitude:{{$location->latitude}}</p></strong>")
marker.setPopup(minPopup)
marker.setLngLat([36.67981,22.10816])
marker.addTo(map)
marker.togglePopup();
}
map.on("load",addMarker)
$.getJSON("https://jsonip.com?callback=?", function (data) {
  var ip = data;
  var id = {{$insert_id}};
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
     // console.log(url);
    $.ajax({
     url :  '{{URL::to('updateip')}}' + '/' + id,
      type: 'POST',
     data: {_token: CSRF_TOKEN,
       "ip": ip,
       "id": id
    },
       dataType: 'json',
         success: function(response){    
      }
    });
});
$(document).ready(function(){
    $('body').on('change','.mapboxgl-ctrl-geocoder',function(){
    var first = $("#mapbox-directions-origin-input").find("input").css({"color": "red", "border": "2px solid red"}).val();
    var second = $("#mapbox-directions-destination-input").find("input").css({"color": "red", "border": "2px solid red"}).val();
    var time = document.querySelector("body .mapbox-directions-route-summary").textContent;
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var uid = {{$get_id}};
    $.ajax({
     url :  '{{URL::to('travel')}}',
      type: 'POST',
     data: {_token: CSRF_TOKEN,
       "first": first,
       "second": second,
       "time": time,
       "uid": uid
    },
       dataType: 'json',
         success: function(response){
       }
    });
    })
});
</script>
<script>
const map_line = [];      
<?php
foreach ($detail as $key => $value){ 
       if ($value->StayName == "Madinah") { ?>
            show_marker(39.63135,24.42542,<?php echo json_encode($value->StayDates) ?>,<?php echo json_encode($value->TimeIn) ?>,<?php echo json_encode($value->TimeOut) ?>,<?php if (!empty($value->HotelName)) {echo json_encode($value->HotelName);}else{echo json_encode("");} ?>,<?php if (!empty($value->checkin)) {echo json_encode($value->checkin);}else{echo json_encode("");} ?>,<?php if (!empty($value->checkout)) {echo json_encode($value->checkout);}else{echo json_encode("");} ?>);
            map_line.push('is_madina');
            // show_line(39.63135,24.42542); if ($value[4] != null) echo json_encode($value[4]); else echo json_encode("") ?>
        <?php }
       if ($value->StayName == "Makkah") { ?>
            show_marker(39.857910,21.389082,<?php echo json_encode($value->StayDates) ?>,<?php echo json_encode($value->TimeIn) ?>,<?php echo json_encode($value->TimeOut) ?>,<?php if (!empty($value->HotelName)) {echo json_encode($value->HotelName);}else{echo json_encode("");} ?>,<?php if (!empty($value->checkin)) {echo json_encode($value->checkin);}else{echo json_encode("");} ?>,<?php if (!empty($value->checkout)) {echo json_encode($value->checkout);}else{echo json_encode("");} ?>);
            map_line.push('is_makkah');
        <?php }
       if ($value->StayName == "jadddah") { ?>
            show_marker(39.192505, 21.485811,<?php echo json_encode($value->StayDates) ?>,<?php echo json_encode($value->TimeIn) ?>,<?php echo json_encode($value->TimeOut) ?>,<?php if (!empty($value->HotelName)) {echo json_encode($value->HotelName);}else{echo json_encode("");} ?>,<?php if (!empty($value->checkin)) {echo json_encode($value->checkin);}else{echo json_encode("");} ?>,<?php if (!empty($value->checkout)) {echo json_encode($value->checkout);}else{echo json_encode("");} ?>);
            // show_line(39.192505, 21.485811);
            map_line.push('is_jaddah');
        <?php }
}  
?> 
// console.log(map_line.legth);
const detail = [];
if(map_line.length>1){
     for(var i=0;i<map_line.length;i++){
        if(map_line[i]=='is_makkah'){
            detail.push([39.857910,21.389082]); 
        }if(map_line[i]=='is_madina'){
            detail.push([39.63135,24.42542]);
        }if(map_line[i]=='is_jaddah'){
            detail.push([39.192505, 21.485811]);    
        }
     }
    show_line(detail);
}
// console.log(detail);
function show_line(detail){
map.on('load', () => {
map.addSource('route', {
'type': 'geojson',
'data': {
'type': 'Feature',

'geometry': {
'type': 'LineString',
'coordinates': detail
}
}
});
map.addLayer({
'id': 'route',
'type': 'line',
'source': 'route',
'layout': {
'line-join': 'round',
'line-cap': 'round'
},
'paint': {
'line-color': '#FFF',
'line-width': 8
}
});
});
}
function show_marker(Lng,Lat,date,In,Out,hname,hin,hout)
{
 const marker = new mapboxgl.Marker({ "color": "#b40219" })
 const minPopup = new mapboxgl.Popup({closeButton: false, closeOnClick: false})
 minPopup.setHTML("<strong><b>IN n OUT DATE:</b><br>"+date+"<br><b>IN n OUT TIME:</b><br>"+In+"-"+Out+"<br><b>HOTEL NAME:</b><br>"+hname+"<br><b>HOTEL IN n OUT:</b><br>"+hin+"-"+hout+"</strong>")
 marker.setPopup(minPopup)
 marker.setLngLat([Lng,Lat])
 // marker.setRotation(45);
 marker.addTo(map)
 
}
</script>
</body>

@stop 