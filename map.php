<?php
//google maps API key AIzaSyBgaHwmY7pUzLCCHhLST8gYpvtwwdol_Nk
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
          width: 500px;
        height: 500px;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgaHwmY7pUzLCCHhLST8gYpvtwwdol_Nk"
    defer>
    </script>
    
    <script>
      var map, marker;
     // var position = {lat: lat, lng: long};
      function initMap(lat, long) {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: lat, lng: long},
          zoom: 20
        });
        marker = new google.maps.Marker({
            position: {lat: lat, lng: long},
            map: map,
            title: 'Hello World!'
        });
      }
    </script>
    
    <script>
      var options = {
          enableHighAccuracy: true,
          timeout: 5000,
          maximumAge: 0
      };
          
          function success(pos) {
            var crd = pos.coords;
            initMap(crd.latitude, crd.longitude);
          
            console.log('Your current position is:');
            console.log('Latitude : ' + crd.latitude);
            console.log('Longitude: ' + crd.longitude);
            console.log('More or less ' + crd.accuracy + ' meters.');
          };
          
          function error(err) {
            console.warn('ERROR(' + err.code + '): ' + err.message);
          };
          
          window.onload = navigator.geolocation.getCurrentPosition(success, error, options);
    </script>
  </body>
</html>