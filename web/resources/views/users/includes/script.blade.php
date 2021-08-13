
<!-- ======================================= TIME AND DATE ========================== -->
<script type="text/javascript"> 
function display_c(){
  var refresh=1000; // Refresh rate in milli seconds
  mytime=setTimeout('display_ct()',refresh)
}
function display_ct() {
  var date = moment().format('dddd, LL, LTS');
  document.getElementById('date').innerHTML = date;
  display_c();
}
</script>
<!-- ======================================== END TIME AND DATE ===================== -->

<script src={{ asset("assets/plugins/jquery/jquery.min.js") }}></script>
<script src={{ asset("assets/plugins/slimscrollbar/jquery.slimscroll.min.js") }}></script>
<script src={{ asset("assets/plugins/jekyll-search.min.js") }}></script>
<script src={{ asset("assets/plugins/charts/Chart.min.js") }}></script>
<script src={{ asset("assets/plugins/ladda/spin.min.js") }}></script>
<script src={{ asset("assets/plugins/ladda/ladda.min.js") }}></script>
<script src={{ asset("assets/plugins/daterangepicker/moment.min.js") }}></script>
<script src={{ asset("assets/plugins/daterangepicker/daterangepicker.js") }}></script>
<script src={{ asset("assets/plugins/jquery-mask-input/jquery.mask.min.js") }}></script>
<script src={{ asset("assets/plugins/toastr/toastr.min.js") }}></script>
<script src={{ asset("assets/js/sleek.bundle.js") }}></script>


<!-- ===================================== GOOGLE MAP ============================== -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{config('googlemap')['map_apikey']}}&callback=initMap&libraries=places"
    type="text/javascript"></script>
<!-- ================================== END GOOGLE MAP ==================================== -->



<!-- ============================= AJAX ==================================== -->
<script>
$(document).ready(function() {
    $('#checkbox-accept-penalty').change(function() {
        if($(this).is(':checked')) {
            $('#display-file-incident-form').show();
            $('#display-file-incident-submit').show();
        } else {
            $('#display-file-incident-form').hide();
            $('#display-file-incident-submit').hide();
        }
    });

    $.ajax({
        type: "GET",
        url: "{{ url('user/incidenttype') }}",
        dataType: "JSON",
        success: function(result) {
            $('#display-incident-type-option').html('<option selected disabled value="">--SELECT INCIDENT TYPE--</option>' + result);
        },
        error: function(error) {
            console.log(error);
        },
    });

    $('#report-incident-form').on('submit', function(event) {
        event.preventDefault();

        $(this).hide();
        $('#report-incident .modal-dialog').addClass('modal-dialog-centered');
        $('#report-incident-loader').show();

        toastr.options = {
            closeButton: true,
            debug: false,
            newestOnTop: false,
            progressBar: true,
            positionClass: "toast-top-right",
            preventDuplicates: false,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "5000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };

        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: "{{ url('user/report-incident') }}",
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                if(result.error) {
                    $('#report-incident .modal-dialog').removeClass('modal-dialog-centered');
                    $('#report-incident-loader').hide();
                    $('#report-incident-form').show();
                    $('#report-incident-alert-danger').show();
                }
                else if(result.errordate) {
                    $('#report-incident .modal-dialog').removeClass('modal-dialog-centered');
                    $('#report-incident-loader').hide();
                    $('#report-incident-form').show();
                    $('#incident_date').addClass('is-invalid');
                    $('#incident_date-inv').html('Future dates cannot be accepted.');
                } else {
                    $('#report-incident').modal('hide');
                    $('#report-incident-loader').hide();
                    $('#report-incident-form').show();
                    $('#checkbox-accept-penalty').prop('checked', false);
                    document.getElementById('report-incident-form').reset();
                    $('#report-incident-file-upload-filename').html('Choose File . . .');
                    toastr.success(result.success);
                }
            },
            error: function(error) {
                console.log(error);
            },
        });
    });

    $('#display-incident-type-option').on('change', function() {
        var id = this.value;

        $('#display-incident-type-warning').hide();
        $('#display-incident-type-description-display').hide();
        $('#display-incident-type-loader').show();
        
        $.ajax({
            type: "GET",
            url: "{{url('user/description')}}"+"/"+id,
            dataType: "JSON",
            success: function(result) {
                $('#display-incident-type-description').html(result.description);
            },
            complete: function() {
                $('#display-incident-type-loader').hide();
                $('#display-incident-type-description-display').show();
            },
            error: function(error) {
                console.log(error);
            }
        })
    });
});
</script>
<!-- ============================ END AJAX ================================ -->

<!-- ===================================== GOOGLE MAP FOR REPORT INCIDENT ============================ -->
<script>
  function initMap() {
    var mapOptions, map, marker, searchBox,
        infoWindow = '',
        addressEl = $('#display-location-input').get(0),
        latEl = $('#location_lat').get(0),
        longEl = $('#location_lng').get(0),
        element = $('#report-incident-map').get(0);
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
    map = new google.maps.Map(element, mapOptions);
    marker = new google.maps.Marker({
        position: mapOptions.center,
        map: map,
        draggable: true
    });
    searchBox = new google.maps.places.SearchBox(addressEl);
    google.maps.event.addListener(searchBox, 'places_changed', function() {
        var places = searchBox.getPlaces(),
            bounds = new google.maps.LatLngBounds(),
            i, place, lat, long, resultArray,
            addresss = places[0].formatted_address;
        for( i = 0; place = places[i]; i++ ) {
            bounds.extend( place.geometry.location );
            marker.setPosition( place.geometry.location ); // Set marker position new.
        }
        map.fitBounds( bounds );  // Fit to the bound
        var listener = google.maps.event.addListener(map, "idle", function() { 
            if(map.getZoom() > 15) {
                map.setZoom(15); // This function sets the zoom to 15, meaning zooms to level 15.
            };
            google.maps.event.removeListener(listener); 
        });
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
        infoWindow = new google.maps.InfoWindow({
            content: addresss
        });
        infoWindow.open( map, marker );
    });
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
            infoWindow = new google.maps.InfoWindow({
                content: address
            });
            infoWindow.open( map, marker );
        } );
    });
  }
</script>
<!-- ================================= END GOOGLE MAP REPORT INCIDENT =============================== -->

<!-- ============================ FILE NAME CHANGE ========================= -->
<script type="text/javascript">
    var input = document.getElementById('report-incident-file-upload');
    var infoArea = document.getElementById('report-incident-file-upload-filename');
    input.addEventListener( 'change', showFileName );
    function showFileName( event ) {
    // the change event gives us the input it occurred in 
    var input = event.srcElement;
    // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
    var fileName = input.files[0].name;
    // use fileName however fits your app best, i.e. add it into a div
    infoArea.textContent = fileName;
    }
</script>
<!-- ================================ END FILE NAME CHANGE ========================= -->

<!-- ================================== NOTIFICATIONS ============================== -->
<script>
$(document).ready(function() {
    function notifHeader() {
        $.ajax({
            url: "{{ url('user/notifications/header') }}",
            type: "GET",
            dataType: "JSON",
            success: function(result) {
                $('#notifications-view').html(result);
            },
            error: function(error) {
                console.log(error);
            }
        }); 
    };
    notifHeader();
    setInterval(notifHeader, 5000);

    function notifCount() {
        $.ajax({
            url: "{{ url('user/notifications/count') }}",
            success: function(result) {
                if(result == 0) {
                    $('#notification-count-badge').hide();
                    $('#notification-count-sidebar').hide();
                    $('#notification-count-dropdown').html('No');
                    $('#notification-mdi').removeClass('mdi-spin');
                    $('#notification-count-sidebar-mdi').removeClass('mdi-spin');
                } else {
                    $('#notification-count-badge').show().html(result);
                    $('#notification-count-sidebar').show().html(result);
                    $('#notification-count-dropdown').html(result);
                    $('#notification-mdi').addClass('mdi-spin');
                    $('#notification-count-sidebar-mdi').addClass('mdi-spin');
                }
            },
        });
    };
    notifCount();  
    setInterval(notifCount, 5000);

    function notifSidebar() {
        $.ajax({
            type: "GET",
            url: "{{url('user/notifications/sidebar')}}",
            dataType: "JSON",
            success: function(result) {
                if(result.view2) {
                    $('#notifications-sidebar').html(result.view2);
                } else {
                    $('#notifications-sidebar').html(result.view);
                }
            },
            error: function(error) {
                console.log(error);
            }
        })
    }
    notifSidebar();
    setInterval(notifSidebar, 5000);
    
    $('#notifications-dropdown').click(function() {
        $('#notification-count-badge').hide();
        notifHeader();
        $.ajax({
            url: "{{url('user/notifications/status/read')}}",
        })
    })

    /* VIEW ANNOUNCEMENT */
    $('#notification-announcement-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        
        $('#notification-announcement-form').hide();
        $('#notification-announcement-modal .modal-dialog').addClass('modal-dialog-centered');
        $('#notification-post-content').hide();
        $('#notification-post-image').hide();
        $('#notification-announcement-loader').show();

        $.ajax({
            type: "GET",
            url: "{{ url('/user/notifications/view/announcement/') }}"+"/"+id,
            dataType: "JSON",
            success: function(data) {
                if(data.image == "TBD") {
                    $('#notification-post-content').show();
                    $('#notification-announcement-subject').val(data.subject);
                    $('#notification-announcement-message').val(data.message);
                    if(data.from_type == "App\\Admin") {
                        $('#notification-announcement-from').html(data.from.admin_name);
                    } else {
                        $('#notification-announcement-from').html(data.from.station_name);
                    }
                } else {
                    $('#notification-post-image').show();
                    $('#notification-announcement-image').attr('src', '{{asset("/")}}'+data.image);
                    if(data.from_type == "App\\Station") {
                        $('#notification-announcement-from').html(data.from.station_name);
                    } else {
                        $('#notification-announcement-from').html(data.from.admin_name);
                    }
                }
            },
            complete: function() {
                $('#notification-announcement-modal .modal-dialog').removeClass('modal-dialog-centered');
                $('#notification-announcement-loader').hide();
                $('#notification-announcement-form').show();
            },
            error: function(error) {
                console.log(error);
            }
        })
    })
    /* END VIEW ANNOUNCEMENT */
});
</script>
<!-- ================================ END NOTIFICATIONS ============================== -->

<!-- ================================ DATETIME-LOCAL ================================== -->
<script>
$(document).ready(function() {
    var datetime = moment().format('YYYY-MM-DDTHH:mm');
    $('input[type="datetime-local"]').attr('max', datetime).attr('value', datetime);
})
</script>
<!-- ============================== END DATETIME-LOCAL ================================== -->






<!--
HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
-->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->