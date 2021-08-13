<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{config('googlemap')['map_apikey']}}&callback=initMap&libraries=places"
    type="text/javascript"></script>
</head>
<body>
    <div id="map" style="height: 500px;width:500px;"></div>
    <input id="searchinput" type="text" placeholder="Enter a location" size="50">
    <input type="text" id="lat" placeholder="LAT">
    <input type="text" id="lng" placeholder="LONG">
    <span id="asd"></span>


<script>
function initMap() {
    var mapOptions, map, marker, searchBox,
        infoWindow = '',
        addressEl = $('#searchinput').get(0),
        latEl = $('#lat').get(0),
        longEl = $('#lng').get(0),
        element = $('#map').get(0);
    mapOptions = {
        zoom: 15,
        center: {
            lat: 8.454236,
            lng: 124.631897
        },
        disableDefaultUI: false,
        scrollWheel: true,
        draggable: true,
    };
    map = new google.maps.Map( element, mapOptions );
    marker = new google.maps.Marker({
        position: mapOptions.center,
        map: map,
        draggable: true
    });
    searchBox = new google.maps.places.SearchBox( addressEl );
    google.maps.event.addListener( searchBox, 'places_changed', function () {
        var places = searchBox.getPlaces(),
            bounds = new google.maps.LatLngBounds(),
            i, place, lat, long, resultArray,
            addresss = places[0].formatted_address;
        for( i = 0; place = places[i]; i++ ) {
            bounds.extend( place.geometry.location );
            marker.setPosition( place.geometry.location );  // Set marker position new.
        }
        map.fitBounds( bounds );  // Fit to the bound
        map.setZoom( 15 ); // This function sets the zoom to 15, meaning zooms to level 15.
        // console.log( map.getZoom() );
        lat = marker.getPosition().lat();
        long = marker.getPosition().lng();
        latEl.value = lat;
        longEl.value = long;
        resultArray =  places[0].address_components;
        // Closes the previous info window if it already exists
        if ( infoWindow ) {
            infoWindow.close();
        }
        /**
         * Creates the info Window at the top of the marker
         */
        infoWindow = new google.maps.InfoWindow({
            content: addresss
        });
        infoWindow.open( map, marker );
    } );
    /**
     * Finds the new position of the marker when the marker is dragged.
     */
    google.maps.event.addListener(marker,"dragend", function ( event ) {
        var lat, long, address, resultArray, citi;
        /* console.log( 'i am dragged' ); */
        lat = marker.getPosition().lat();
        long = marker.getPosition().lng();
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode( { latLng: marker.getPosition() }, function ( result, status ) {
            if ( 'OK' === status ) {  // This line can also be written like if ( status == google.maps.GeocoderStatus.OK ) {
                address = result[0].formatted_address;
                resultArray =  result[0].address_components;
                addressEl.value = address;
                latEl.value = lat;
                longEl.value = long;
            } else {
                console.log( 'Geocode was not successful for the following reason: ' + status );
            }
            // Closes the previous info window if it already exists
            if ( infoWindow ) {
                infoWindow.close();
            }
            /**
             * Creates the info Window at the top of the marker
             */
            infoWindow = new google.maps.InfoWindow({
                content: address
            });
            infoWindow.open( map, marker );
        } );
    });
}
</script> -->

<!-- <script>
function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: 8.454236, 
            lng: 124.631897
        },
        zoom: 13,
    });
    var searchBox = new google.maps.places.SearchBox(document.getElementById('searchinput'));
        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
    });
    let markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener("places_changed", () => {
        var places = searchBox.getPlaces();
        if (places.length == 0) {
            return;
        }
        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach((place) => {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            // Create a marker for each place.
            var marker = new google.maps.Marker({
                map: map,
                title: place.name,
                position: place.geometry.location,
                draggable: true,
            });
            if (place.geometry.viewport) {
            // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
            $('#lat').val(place.geometry.location.lat());
            $('#lng').val(place.geometry.location.lng());
            $('#asd').html(place.name);
            google.maps.event.addListener(marker, 'dragend', function (event) {
                $('#lat').val(this.getPosition().lat());
                $('#lng').val(this.getPosition().lng());
                $('#asd').html(this.getTitle());
            });
        });
        map.fitBounds(bounds);
    });
}
</script> -->
<!-- </body>
</html> -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TESTING</title>
</head>
<body>
    {{$data}}
</body>
</html>