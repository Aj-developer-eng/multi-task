
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.mapboxgl-ctrl-logo{
    display: none !important;
}
.mapboxgl-popup {
    width: 100%;

}
.mapboxgl-popup-content {
    background-color: lightblue;
     border: solid;
    border-width: 2px;
    border-color: #1e828de8;
    width: 100%;


}

body { margin: 0; padding: 0; }
#map { position: absolute; top: 12%; bottom: 0; width: 77%; }

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
marker.setLngLat([{{$location->longitude}},{{$location->latitude}}])
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


<?php
foreach ($all_lead as $key => $value) {
    if ($value->status == 1) { ?>
        lead_marker({{$value->longitude}},{{$value->latitude}},'{{$value->email}}',{{$value->status}},'<?php if(!empty($value->hotelmakName)){echo $value->hotelmakName;} ?>','<?php if(!empty($value->hmakCheckdatein)){echo $value->hmakCheckdatein;} ?>','<?php if(!empty($value->hmakCheckdateout)){echo $value->hmakCheckdateout;} ?>','<?php if(!empty($value->makcheckTimein)){echo $value->makcheckTimein;} ?>','<?php if(!empty($value->makcheckTimeout)){echo $value->makcheckTimeout;} ?>','<?php if (!empty($value->hotelmadiName)) {echo $value->hotelmadiName; } ?>','<?php if (!empty($value->hmadiCheckdatein)) {echo $value->hmadiCheckdatein; } ?>','<?php if (!empty($value->hmadiCheckdateout)) {echo $value->hmadiCheckdateout; } ?>','<?php if (!empty($value->madicheckTimein)) {echo $value->madicheckTimein; } ?>','<?php if (!empty($value->madicheckTimeout)) {echo $value->madicheckTimeout; } ?>');
   <?php }
   if ($value->status == 0){ ?>
lead_marker({{$value->longitude}},{{$value->latitude}},'{{$value->email}}',{{$value->status}},'<?php if(!empty($value->hotelmakName)){echo $value->hotelmakName;} ?>','<?php if(!empty($value->hmakCheckdatein)){echo $value->hmakCheckdatein;} ?>','<?php if(!empty($value->hmakCheckdateout)){echo $value->hmakCheckdateout;} ?>','<?php if(!empty($value->makcheckTimein)){echo $value->makcheckTimein;} ?>','<?php if(!empty($value->makcheckTimeout)){echo $value->makcheckTimeout;} ?>','<?php if (!empty($value->hotelmadiName)) {echo $value->hotelmadiName; } ?>','<?php if (!empty($value->hmadiCheckdatein)) {echo $value->hmadiCheckdatein; } ?>','<?php if (!empty($value->hmadiCheckdateout)) {echo $value->hmadiCheckdateout; } ?>','<?php if (!empty($value->madicheckTimein)) {echo $value->madicheckTimein; } ?>','<?php if (!empty($value->madicheckTimeout)) {echo $value->madicheckTimeout; } ?>');
   <?php }
}
       ?>

function lead_marker(Lng,Lat,email,status,hmakname,hmakin,hmakout,hmaktin,hmaktout,hmadiname,hmadidatein,hmadidateout,hmaditimein,hmaditimeout){
    console.log(hmadiname);
    //if (hmadiname !== null) {alert("in");}
    console.log(hmadidatein);
    console.log(hmadidateout);
    console.log(hmaditimein);
    console.log(hmaditimeout);
     const marker = new mapboxgl.Marker({ "color": "#b40219" })
 const minPopup = new mapboxgl.Popup({closeButton: false, closeOnClick: false})
if (status == 1) {
 status = "active";
}else{
status = "inactive";
}

if(!!hmadiname){
var madina = "<b>STAY DETAIL :</b><br><i class='fa fa-h-square' style='margin-right: 1%;'></i>"+hmadiname+"<br><b>CHECK IN & OUT DETAILS: </b><br><p style='display: flex'><i class='fa fa-calendar-check-o' style='margin-right: 1%;'></i>"+hmadidatein+'<i class="fa fa-long-arrow-right" style="font-size:25px; margin-right: 1%; margin-left: 1%;"></i>'+hmadidateout+"</p><p style='display: flex'><i class='fa fa-calendar-check-o' style='margin-right: 1%;'></i>"+hmaditimein+'<i class="fa fa-long-arrow-right" style="font-size:25px; margin-left: 1%; margin-right: 1%; padding-top: -10%;"></i>'+hmaditimeout+"</p>";
}else{
    var madina = '';
}


 minPopup.setHTML("<strong><b>LEAD DETAIL :</b><br><i class='fa fa-envelope' style='margin-right: 1%;'></i>"+email+"<br><i class='fa fa-user' style='margin-right: 1%;'></i> "+status+"<b><br>STAY DETAIL :</b><br><i class='fa fa-h-square' style='margin-right: 1%;'></i>"+hmakname+"<br><b>CHECK IN & OUT DETAIL : </b><br><p style='display: flex'><i class='fa fa-calendar-check-o' style='margin-right: 1%;'></i>"+hmakin+'<i class="fa fa-long-arrow-right" style="font-size:25px; margin-right: 1%; margin-left: 1%;"></i>'+hmakout+"</p><p style='display: flex'><i class='fa fa-calendar-check-o' style='margin-right: 1%;'></i>"+hmaktin+'<i class="fa fa-long-arrow-right" style="font-size:25px; margin-left: 1%; margin-right: 1%; padding-top: -10%;"></i>'+hmaktout+"</p>" + madina + "</strong>")
 marker.setPopup(minPopup)
 marker.setLngLat([Lng,Lat])
 // marker.setRotation(45);
 marker.addTo(map)
 

}
</script>

</body>




@stop