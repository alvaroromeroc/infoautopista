<!DOCTYPE html >
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Using MySQL and PHP with Google Maps</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 80%;
        width: 800px;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>

  <body>
    <div id="map"></div>
    <div id="controles">
      <button onclick="initMap(1);">Vespucio Norte</button>
      <button onclick="initMap(2);">Vespucio Sur</button>
      <button onclick="initMap(3);">Autopista Central</button>
      <button onclick="initMap(4);">Costanera Norte</button>
    </div>

    <script>
      var customLabel = {
        OP: {
          label: 'OP'
        },
        PO: {
          label: 'PO'
        },
        NS: {
          label: 'NS'
        },
        SN: {
          label: 'SN'
        }
      };

        function initMap(autopista) {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(-33.448919,-70.659588),
          zoom: 11
        });
        var infoWindow = new google.maps.InfoWindow;

          // Change this depending on the name of your PHP or XML file
          downloadUrl('porticos-xml.php?value='+autopista, function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var name = markerElem.getAttribute('nombre');
              var autopista = markerElem.getAttribute('autopista');
              var sentido = markerElem.getAttribute('sentido');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = name
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('text');
              text.textContent = autopista
              infowincontent.appendChild(text);
              var icon = customLabel[sentido] || {};
              //var image = 'http://maps.google.com/mapfiles/ms/icons/green.png';
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label,
                //icon: image

              });
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
            });
          });
        }



      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}

      /*function prueba(value){
        alert("hola"+value);
      }*/
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCw0gF3W9rWAzxlYwOuAZxt5zlC1HfUE6w&callback=initMap">
    </script>
  </body>
</html>