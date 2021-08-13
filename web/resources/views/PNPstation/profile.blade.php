@if(Auth::guard('station')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>Station | Account Settings</title>
    @include('pnpstation.includes.link')
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body" onload=display_ct();>
  
<script>
    NProgress.start();
</script>

    <div class="wrapper">
        @include('pnpstation.includes.sidebar')
        <div class="page-wrapper">
            @include('pnpstation.includes.header')
            <div class="content-wrapper">
                <div class="content">						 
                    
                    <!--CONTENT SECTION-->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-default">
                                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                                    <h2>Account Settings</h2>
                                </div>
                                <div class="bg-white">
                                    <div class="row no-gutters">
                                        <div class="col-lg-4 col-xl-3">
                                            <div class="profile-content-left pt-5 pb-3 px-4 px-xl-4">
                                                <div class="card text-center widget-profile px-0 border-0">
                                                    <div class="card-img mx-auto rounded-circle">
                                                    @if(Auth::guard('station')->user()->image == "TBD")
                                                        <img id="image-change" src="{{asset("uploads/user.jpg")}}" alt="user image" class="img-fluid">
                                                    @else
                                                        <img id="image-change" src="{{asset(Auth::guard('station')->user()->image)}}" alt="user image" class="img-fluid">
                                                    @endif
                                                    </div>
                                                    <div class="card-body">
                                                        <h4 class="py-2 text-dark">{{Auth::guard('station')->user()->station_name}}</h4>
                                                    </div>
                                                </div>
                                                <hr class="w-100">
                                                <div class="contact-info pt-4">
                                                    <h5 class="text-dark">Contact Information</h5>
                                                    <p class="text-dark font-weight-medium pt-4 mb-2">Phone Number</p>
                                                    <p>{{Auth::guard('station')->user()->station_contactno}}</p>
                                                    <p class="text-dark font-weight-medium pt-4 mb-2">Address</p>
                                                    <p>{{Auth::guard('station')->user()->location_name}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-xl-9">
                                            <div class="profile-content-right py-5">
                                                <ul class="nav nav-tabs px-4" id="myTab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab"aria-selected="true">
                                                            <i class="mdi mdi-account mr-2"></i>Station Details
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="username-tab" data-toggle="tab" href="#username" role="tab" aria-selected="false">
                                                            Change Username
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="change-password-tab" data-toggle="tab" href="#change-password" role="tab" aria-selected="false">
                                                            Change Password
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content px-3 px-xl-5" id="myTabContent">
                                                    <div class="tab-pane fade show active" id="details" role="tabpanel">
                                                        <div class="mt-5">
                                                            <div class="form-row">
                                                                <div class="col-md-12">
                                                                    <form id="update-profile-form">
                                                                    @csrf
                                                                    <input type="hidden" id="update-profile-id" value="{{ Auth::guard('station')->user()->id }}">
                                                                    <div id="update-profile-success" class="alert alert-success" style="display:none" role="alert"></div>
                                                                    <div class="form mb-3">
                                                                        <label>Upload Image</label>
                                                                        <div class="custom-file mb-1">
                                                                            <input name="image" type="file" class="custom-file-input" id="file-upload" accept="image/*" onchange="readURL(this);">
                                                                            <label class="custom-file-label" for="file-upload"><span id="file-upload-filename">Choose File . . .</span></label>
                                                                            <span class="d-block mt-1" style="font-size: 85%">Only Image File Types are Accepted.</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form mb-3">
                                                                        <label class="text-dark font-size-16">Station Name</label>
                                                                        <input name="station_name" id="update-profile-name" type="text" class="form-control" value="{{ Auth::guard('station')->user()->station_name }}" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                                                        <div class="invalid-feedback">Please input this field</div>
                                                                    </div>
                                                                    <div class="form mb-3">
                                                                        <label class="text-dark font-size-16">Station Contact #</label>
                                                                        <input name="station_contactno" id="update-profile-contactno" type="text" class="form-control" data-mask="(999) 999-9999" value="{{ Auth::guard('station')->user()->station_contactno }}">
                                                                        <div class="invalid-feedback"><span id="update-profile-contactno-feedback"></span></div>
                                                                    </div>
                                                                    <div class="form mb-3">
                                                                        <label class="text-dark font-size-16">Station Location</label>
                                                                        <input name="location_name" id="update-profile-location" type="text" class="form-control" value="{{ Auth::guard('station')->user()->location_name }}">
                                                                        <div class="invalid-feedback"><span id="update-profile-location-feedback"></span></div>
                                                                    </div>
                                                                    <div id="map" class="map-container"></div>
                                                                    <div class="form-row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label class="text-dark font-size-16">Lat</label>
                                                                        <input name="location_lat" id="update-profile-lat" type="text" class="form-control" readonly value="{{ Auth::guard('station')->user()->location_lat}}">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="text-dark font-size-16">Lng</label>
                                                                        <input name="location_lng" id="update-profile-lng" type="text" class="form-control" readonly value="{{ Auth::guard('station')->user()->location_lng }}">
                                                                    </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-end py-4">
                                                                    <button class="ladda-button btn btn-primary" data-style="expand-right">
                                                                        <span class="ladda-label">Update</span>
                                                                        <span class="ladda-spinner"></span>
                                                                    </button>
                                                                    </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="username" role="tabpanel">
                                                        <div class="mt-5">
                                                            <div class="form-row">
                                                                <div class="col-md-12">
                                                                    <form id="update-username-form">
                                                                    @csrf
                                                                    <input type="hidden" id="profile-id-username" value="{{ Auth::guard('station')->user()->id }}">
                                                                        <div id="update-username-success" class="alert alert-success" style="display:none" role="alert"></div>
                                                                        <div class="form mb-3">
                                                                            <label class="text-dark font-size-16">Username</label>
                                                                            <input name="username" id="profile-username" type="text" class="form-control" value="{{ Auth::guard('station')->user()->username }}" required oninput="$(this).removeClass('is-invalid')">
                                                                            <div class="invalid-feedback"><span id="profile-username-feedback"></span></div>
                                                                        </div>
                                                                        <div class="form mb-3">
                                                                            <label class="text-dark font-size-16">Confirm Password</label>
                                                                            <input name="confirmpassword" id="profile-confirm-password" type="password" class="form-control" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                                                            <div class="invalid-feedback"><span id="profile-confirm-password-feedback">Please Input Current Password.</span></div>
                                                                        </div>
                                                                        <div class="d-flex justify-content-end py-4">
                                                                        <button class="ladda-button btn btn-primary" data-style="expand-right">
                                                                            <span class="ladda-label">Update</span>
                                                                            <span class="ladda-spinner"></span>
                                                                        </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="change-password" role="tabpanel">
                                                        <div class="mt-5">
                                                            <div class="form-row">
                                                                <div class="col-md-12">
                                                                    <form id="update-password-form">
                                                                    @csrf
                                                                    <input type="hidden" id="profile-id-password" value="{{ Auth::guard('station')->user()->id }}">
                                                                    <div id="update-password-success" class="alert alert-success" style="display:none" role="alert"></div>
                                                                    <div id="update-password-danger" class="alert alert-danger" style="display:none" role="alert"></div>
                                                                    
                                                                    <div class="form-row">
                                                                        <div class="col-md-12 mb-3">
                                                                        <label class="text-dark font-size-16">Current Password</label>
                                                                        <input name="current_password" id="current-password" type="password" class="form-control" required oninput="$(this).removeClass('is-invalid')">
                                                                        <div class="invalid-feedback"><span id="profile-password-feedback"></span></div>
                                                                        </div>
                                                                        <div class="col-md-12 mb-3">
                                                                        <label class="text-dark font-size-16">New Password</label>
                                                                        <input name="new_password" id="profile-password" type="password" class="form-control" required>
                                                                        </div>
                                                                        <div class="col-md-12 mb-3">
                                                                        <label class="text-dark font-size-16">Repeat Password</label>
                                                                        <input id="confirm-profile-password" type="password" class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group d-flex justify-content-end">
                                                                        <button class="ladda-button btn btn-primary" data-style="expand-right">
                                                                        <span class="ladda-label">Update</span>
                                                                        <span class="ladda-spinner"></span>
                                                                        </button>
                                                                    </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            @include('pnpstation.includes.notifications')
            @include('pnpstation.includes.footer')
            </div>
        </div>
    </div>

@include('pnpstation.includes.script')

<!-- ===================================== GOOGLE MAP ============================== -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{config('googlemap')['map_apikey']}}&callback=initMap&libraries=places"
    type="text/javascript"></script>
<!-- ================================== END GOOGLE MAP ==================================== -->

<!-- ===================================== GOOGLE MAP ============================ -->
<script>
    function initMap() {
        var mapOptions, map, marker, searchBox,
            infoWindow = '',
            addressEl = $('#update-profile-location').get(0),
            latEl = $('#update-profile-lat').get(0),
            longEl = $('#update-profile-lng').get(0),
            element = $('#map').get(0);
        mapOptions = {
            zoom: 15,
            center: {
                lat: {{Auth::guard('station')->user()->location_lat}},
                lng: {{Auth::guard('station')->user()->location_lng}}
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
<!-- ================================= END GOOGLE MAP =============================== -->

<!-- ============================= AJAX =============================== -->
<script>
$('#update-username-form').on('submit', function(event) {
  event.preventDefault();
  
  var username_id = $('#profile-id-username').val();

  $.ajax({
    type: "POST",
    url: "/station/profile/updateusername/"+username_id,
    data: $('#update-username-form').serialize(),
    success: function(result) {
        if(result.error) {
            $('#profile-username').addClass('is-invalid');
            $('#profile-username-feedback').html(result.error);
            $('#profile-confirm-password').val('');
        } 
        if(result.errorpw) {
            $('#profile-confirm-password').addClass('is-invalid');
            $('#profile-confirm-password-feedback').html(result.errorpw);
            $('#profile-confirm-password').val('');
        }
        if(result.success) {
            $('#update-username-success').show().html(result.success);
            $('#profile-username').removeClass('is-invalid');
            $('#profile-confirm-password').val('');
            setTimeout(function() {
            $('#update-username-success').fadeOut('slow');
            }, 2500);
        }
    },
    error: function(error) {
      console.log(error);
    },
  });
});

$('#update-profile-form').on('submit', function(event) {
    event.preventDefault();
    
    var profile_id = $('#update-profile-id').val();

    $.ajax({
        type: "POST",
        url: "/station/profile/updateprofile/"+profile_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            $('html, body').animate({scrollTop:0},'slow');
            $('#update-profile-success').show().html(result.success);
            setTimeout(function() {
                $('#update-profile-success').fadeOut('slow');
            }, 2500);
        },
        error: function(error) {
            console.log(error);
        },
    });
});

$('#update-password-form').on('submit', function(event) {
  event.preventDefault();
  
  var profile_id_pw = $('#profile-id-password').val();

  $.ajax({
    type: "POST",
    url: "/station/profile/updatepassword/"+profile_id_pw,
    data: $('#update-password-form').serialize(),
    success: function(result) {
      if(result.error) {
        $('#current-password').addClass('is-invalid');
        $('#profile-password-feedback').html(result.error);
      } else {
        $('#update-password-success').show().html(result.success);
        $('#current-password').removeClass('is-invalid');
        document.getElementById('update-password-form').reset();
        setTimeout(function() {
          $('#update-password-success').fadeOut('slow');
        }, 2500);
      }
    },
    error: function(error) {
      console.log(error);
    },
  });
});
</script>
<!-- ============================= END AJAX ================================= -->

<!-- ================================ REPEAT PASSWORD ============================ -->
<script>
  var password = document.getElementById("profile-password")
    , confirm_password = document.getElementById("confirm-profile-password");

  function validatePassword(){
    if(password.value != confirm_password.value) {
      confirm_password.setCustomValidity("Passwords Don't Match");
    } else {
      confirm_password.setCustomValidity('');
    }
  }

  password.onchange = validatePassword;
  confirm_password.onkeyup = validatePassword;
</script>
<!-- ================================= END REPEAT PASSWORD =============================== -->

<!-- ================================== FILE/IMAGE PREVIEWS ===================================== -->
<script type="text/javascript">
    var input = document.getElementById('file-upload');
    var infoArea = document.getElementById('file-upload-filename');
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
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image-change').attr('src', e.target.result);
                /* $('#update-officer-image').attr('src', e.target.result); */
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<!-- ======================================= END FILE/IMAGE PREVIEWS ==================================== -->



</body>

</html>

@else 
    @include('PNPstation.includes.419')
@endif
