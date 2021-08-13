@if(Auth::guard('admin')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>PNP | Stations</title>
    @include('pnpadmin.includes.link')
    <style>
        .modal { z-index: 1001 !important;} 
        .modal-backdrop {z-index: 1000 !important;}
        .pac-container {z-index: 1055 !important;}
    </style>
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body" onload=display_ct();>

<script>
    NProgress.start();
</script>

    @if (session()->has('error'))
        <div id="toaster-alert-error"></div>
    @endif
    @if (session()->has('success'))
        <div id="toaster-alert-success"></div>
    @endif

    <div class="wrapper">
        @include('pnpadmin.includes.sidebar')
        <div class="page-wrapper">
            @include('pnpadmin.includes.header')
            <div class="content-wrapper">
                <div class="content">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-default">
                                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                                    <h2>Station Users</h2>
                                    <a href="javascript:void(0);" class="btn btn-outline-primary text-uppercase" data-toggle="modal" data-target="#add-station">
                                        <i class=" mdi mdi-account"></i>&nbsp;&nbsp;&nbsp;Add Station
                                    </a>
                                </div>
                            <div class="card-body">
                                <div class="responsive-data-table">
                                    <table id="responsive-data-table" class="table table-hover dt-responsive" style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th width="20%">Station Name</th>
                                            <th width="20%">Username</th>
                                            <th width="30%">Location</th>
                                            <th width="5%">Officers</th>
                                            <th width="25%">Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

    <!-- VIEW OFFICERS -->
    <div class="modal fade" id="view-officers" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div id="view-officers-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
            <div class="modal-content" id="view-officers-form">
                <div class="modal-header">
                    <h5 class="modal-title">Police Officers Assigned</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <ul class="nav nav-tabs nav-stacked flex-column" id="view-officers-tab"></ul>
                        </div>
                        <div class="col-lg-8">
                            <div class="tab-content" id="view-officers-tab-content"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END VIEW OFFICERS -->

    <!-- VIEW STATION MODAL -->
        <div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormVIEW" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div id="view-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                <div id="view-form" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Station Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="form d-flex justify-content-center pb-4">
                                    <img id="view-image" class="img-fluid">
                                </div>
                                <div class="form pb-4">
                                    <label class="text-dark font-size-17">Username</label>
                                    <p id="view-username"></p>
                                </div>
                                <div class="form pb-4">
                                    <label class="text-dark font-size-17">Station Name</label>
                                    <p id="view-station_name"></p>
                                </div>
                                <div class="form pb-4">
                                    <label class="text-dark font-size-17">Station Contact #</label>
                                    <p id="view-station_contactno"></p>
                                </div>
                                <div class="form pb-4">
                                    <label class="text-dark font-size-17">Location</label>
                                    <p id="view-location_name"></p>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <label>Location Map</label>
                                <div id="map-view" class="map-container"></div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Latitude</label>
                                        <input id="view-location_lat" type="text" class="form-control" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Longitude</label>
                                        <input id="view-location_lng" type="text" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- END VIEW STATION MODAL -->

    <!-- UPDATE DETAILS MODAL -->
        <div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormEDIT" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div id="update-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                <form id="update-form">
                @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Station</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="update-alert-success" class="alert alert-success" style="display:none" role="alert"></div>
                            <div id="update-alert-danger" class="alert alert-danger" style="display:none" role="alert"></div>
                            <input type="hidden" id="update-id">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form d-flex justify-content-center pb-4">
                                        <img id="update-image" class="img-fluid">
                                    </div>
                                    <div class="form pb-2">
                                        <label class="text-dark font-size-17">Update Image</label>
                                            <div class="custom-file mb-1">
                                                <input name="image" type="file" class="custom-file-input" id="update-img" accept="image/*" onchange="readURL(this);">
                                                <label class="custom-file-label" for="img-up"><span id="update-img-fn">Choose File . . .</span></label>
                                            </div>
                                        <span class="d-block mt-1" style="font-size: 85%">Only Image File Types are Accepted.</span>
                                    </div>
                                    <div class="form pb-2">
                                        <label class="text-dark font-size-17">Station Name</label>
                                        <input name="station_name" id="update-station_name" type="text" class="form-control" placeholder="Enter Station" required>
                                    </div>
                                    <div class="form pb-2">
                                        <label class="text-dark font-size-17">Station Contact #</label>
                                        <input name="station_contactno" id="update-station_contactno" data-mask="(999) 999-9999" type="text" class="form-control" placeholder="(999) 999-9999" required>
                                    </div>
                                    <div class="form pb-2">
                                        <label class="text-dark font-size-17">Location</label>
                                        <input name="location_name" id="update-location_name" type="text" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <label>Location Map</label>
                                    <div id="map-update" class="map-container"></div>
                                    <div class="row pb-2">
                                        <div class="col-md-6">
                                            <label>Latitude</label>
                                            <input name="location_lat" id="update-location_lat" type="text" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Longitude</label>
                                            <input name="location_lng" id="update-location_lng" type="text" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <!-- END UPDATE STATION MODAL -->

    <!-- CHANGE STATION USERNAME MODAL -->
        <div class="modal fade" id="update-username" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormEDIT" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div id="update-username-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                <form id="update-username-form">
                @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Change Username</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="update-username-alert-success" class="alert alert-success" style="display:none" role="alert"></div>
                            <div id="update-username-alert-danger" class="alert alert-danger" style="display:none" role="alert"></div>
                            <input type="hidden" id="update-username-id">
                            <div class="row px-3">
                                <label class="text-dark font-size-17">Username</label>
                                <input name="username" id="update-username-username" type="text" class="form-control" placeholder="Username" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <!-- CHANGE STATION USERNAME MODAL -->

    <!-- CHANGE STATION PASSWORD MODAL -->
        <div class="modal fade" id="change-password" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormEDIT" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div id="change-password-loader" class="col-sm-12" style="display: none;">@include('PNPadmin.includes.loader')</div>
                <div id="change-password-content" class="modal-content">
                    <form id="change-password-confirmation-password">
                    @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-row px-2 py-2">
                                <label class="text-dark font-size-16">Input Password to continue</label>
                                <input name="password" id="confirm-password" type="password" class="form-control" placeholder="Password" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                <span class="invalid-feedback" id="confirm-password-inv">Please fill out this field</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                    </form>
                    <form id="change-password-form" style="display: none;">
                    @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Reset Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="reset-password-id">
                            <div class="form-row px-2 py-2">
                                <label class="text-dark font-size-16">New Password</label>
                                <input name="password" id="check_password" type="password" class="form-control" placeholder="Password" required>
                                <div class="invalid-feedback">Password must be atleast 6+ of characters</div>
                            </div>
                            <div class="form-row px-2 py-2">
                                <label class="text-dark font-size-16">Repeat Password</label>
                                <input id="confirm_password" type="password" class="form-control" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- CHANGE STATION PASSWORD MODAL -->

    <!-- ADD STATION MODAL -->
        <div class="modal fade" id="add-station" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div id="add-station-loader" class="col-sm-12" style="display: none;">@include('PNPadmin.includes.loader')</div>
                <form id="add-station-form">
                    @csrf
                      <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalFormTitle">Add Station</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="add-station-alert-success" class="alert alert-success" style="display:none" role="alert"></div>
                            <div id="add-station-alert-danger" class="alert alert-danger" style="display:none" role="alert"></div>
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form pb-2">
                                        <label class="text-dark font-size-17">Station Name</label>
                                        <input name="station_name" type="text" class="form-control" placeholder="Enter Station" required>
                                        <div class="invalid-feedback">Please fill out this field</div>
                                    </div>
                                    <div class="form- pb-2">
                                        <label class="text-dark font-size-17">Station Contact #</label>
                                        <input name="station_contactno" type="text" class="form-control" data-mask="(999) 999-9999" placeholder="ex. (999) 999-9999" required>
                                        <div class="invalid-feedback">Please fill out this field</div>
                                    </div>
                                    <div class="form pb-2">
                                        <label class="text-dark font-size-17">Location</label>
                                        <input name="location_name" id="location_name" type="text" class="form-control" placeholder="Location" required>
                                        <div class="invalid-feedback">Please fill out this field</div>
                                    </div>
                                    <hr>
                                    <label class="font-size-17">Login Details</label>
                                    <div class="form py-2">
                                        <label class="text-dark font-size-17">Username</label>
                                        <input name="username" id="add-station-username" type="text" class="form-control" placeholder="Enter Username" required>
                                        <div class="invalid-feedback">Please fill out this field</div>
                                    </div>
                                    <div class="form pb-2">
                                        <label class="text-dark font-size-17">Password</label>
                                        <input name="password" type="password" class="form-control" id="check_password" placeholder="Password" required>
                                        <div class="invalid-feedback">Please fill out this field</div>
                                    </div><div class="form">
                                        <label class="text-dark font-size-17">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_password" placeholder="Confirm Password" required>
                                        <div class="invalid-feedback">Please fill out this field</div>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <label>Location Map</label>
                                    <div id="map-add" class="map-container"></div>
                                    <div class="row pb-2" style="display: none;">
                                        <div class="col-md-6">
                                            <label>Latitude</label>
                                            <input name="location_lat" id="location_lat" type="text" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Longitude</label>
                                            <input name="location_lng" id="location_lng" type="text" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form py-2">
                                        <label>Upload Image</label>
                                        <div class="custom-file mb-1">
                                            <input name="image" type="file" class="custom-file-input" id="file-upload" accept="image/*" onchange="readURL(this);">
                                            <label class="custom-file-label" for="file-upload"><span id="file-upload-filename">Choose File . . .</span></label>
                                            <span class="d-block mt-1" style="font-size: 85%">Only Image File Types are Accepted.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <!--END ADD STATION MODAL -->

    <!-- REMOVE STATION MODAL -->
    <div class="modal fade" id="remove-station" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div id="remove-station-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
            <form id="remove-station-form">
            @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalFormTitle">Are you sure you want to remove this Station?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="remove-station-id">
                        <div class="form-row px-2 py-2">
                            <label class="text-dark font-size-16">Input Password to continue</label>
                            <input name="password" id="remove-password" type="password" class="form-control" placeholder="Password" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                            <span class="invalid-feedback" id="remove-password-inv">Please fill out this field</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Remove</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<!--END REMOVE STATION MODAL -->

</div>
</div>
</div>
@include('pnpadmin.includes.notifications')
@include('pnpadmin.includes.footer')

@include('PNPadmin.includes.script')

<!-- ===================================== GOOGLE MAP ============================== -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{config('googlemap')['map_apikey']}}&callback=initMap&libraries=places"
    type="text/javascript"></script>
<!-- ================================== END GOOGLE MAP ==================================== -->

<!-- ===================================== GOOGLE MAP ============================ -->
<script>
  function initMap() {
    var mapOptions, map, marker, searchBox,
        infoWindow = '',
        addressEl = $('#location_name').get(0),
        latEl = $('#location_lat').get(0),
        longEl = $('#location_lng').get(0),
        element = $('#map-add').get(0);
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
<!-- ================================= END GOOGLE MAP =============================== -->

<!-- ===================================DATA TABLE SCRIPT==================================== -->
<script src={{ asset("assets/plugins/data-tables/jquery.datatables.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.bootstrap4.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.responsive.min.js") }}></script>

<script>
  $(document).ready(function() {
    var table = $('#responsive-data-table').DataTable({
      "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{ url('admin/station') }}",
      },
      columns: [
        { data: 'station_name' },
        { data: 'username' },
        { data: 'location_name' },
        { data: 'officers', className: 'text-center', render: function(data, type, row) {
            if(data == 0) {
                return data;
            } else {
                return '<a href="javascript:void(0);" data-id='+row["id"]+' data-toggle="modal" data-target="#view-officers">'+data+'</a>';
            }
        } },
        { data: 'action', orderable: false, className: 'text-center' },
      ]
    });
  });
</script>
<!-- ========================================== END DATA TABLE ===================================== -->

<!-- ============================================= AJAX ======================================== -->
<script>
/* ADD STATION */
$('#add-station-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#add-station .modal-dialog').addClass('modal-dialog-centered');
    $('#add-station-loader').show();

    $.ajax({
        type: "POST",
        url: "/admin/station/addstation",
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if(result.error) {
                $('#add-station .modal-dialog').removeClass('modal-dialog-centered');
                $('#add-station-loader').hide();
                $('#add-station-form').show();
                $('#add-station-alert-danger').show().html(result.error);
                $('#add-station-username').addClass('is-invalid');
                setTimeout(function() {
                    $('#add-station-alert-danger').fadeOut('slow');
                }, 2500);
            } else {
                $('#add-station .modal-dialog').removeClass('modal-dialog-centered');
                $('#add-station-loader').hide();
                $('#add-station-form').show();
                $('#add-station-alert-success').show().html(result.success);
                $('#add-station-username').removeClass('is-invalid');
                $('#file-upload-filename').html("Choose file . . .");
                $('#responsive-data-table').DataTable().ajax.reload();
                document.getElementById('add-station-form').reset();
                setTimeout(function() {
                    $('#add-station-alert-success').fadeOut('slow');
                }, 2500);
            }
        },
        error: function(error) {
            console.log(error);
        },
    });
});
/* END ADD STATION */

/* VIEW OFFICERS */
$('#view-officers').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);

        $('#view-officers .modal-dialog').addClass('modal-dialog-centered');
        $('#view-officers-form').hide();
        $('#view-officers-loader').show();

        $.ajax({
            type: "GET",
            url: "{{ url('/admin/station/officers/view') }}"+"/"+id,
            dataType: "JSON",
            success: function(result) {
                $('#view-officers-tab').html(result.tab);
                $('#view-officers-tab-content').html(result.tabcontent);
            },
            complete: function() {
                $('#view-officers .modal-dialog').removeClass('modal-dialog-centered');
                $('#view-officers-loader').hide();
                $('#view-officers-form').show();
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
    /* END VIEW OFFICERS */

/* VIEW STATION */
$('#view').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#view .modal-dialog').addClass('modal-dialog-centered');
    $('#view-form').hide();
    $('#view-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/station/view') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            var mapview = new google.maps.Map(document.getElementById('map-view'),{
                center: {
                    lat: data.location_lat,
                    lng: data.location_lng
                },
                zoom:17
            });
            var markerview = new google.maps.Marker({
                map:mapview,
                position: {
                    lat: data.location_lat,
                    lng: data.location_lng
                },
            });
            infoWindow = new google.maps.InfoWindow({
                content: data.location_name
            });
            infoWindow.open(mapview, markerview);

            $('#view-username').html(data.username);
            $('#view-station_name').html(data.station_name);
            $('#view-station_contactno').html(data.station_contactno);
            $('#view-location_name').html(data.location_name);
            $('#view-location_lat').val(data.location_lat);
            $('#view-location_lng').val(data.location_lng);
            if(data.image == "TBD") {
                $('#view-image').attr('src', '{{asset("assets/img/pnpseal.png")}}');
            } else {
                $('#view-image').attr('src', '{{asset("/")}}'+data.image);
            }
        },
        complete: function() {
            $('#view .modal-dialog').removeClass('modal-dialog-centered');
            $('#view-loader').hide();
            $('#view-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
});
/* END VIEW STATION */

/* UPDATE DETAILS */
  $('#update').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id= button.data('id');
    var modal = $(this);

    $('#update .modal-dialog').addClass('modal-dialog-centered');
    $('#update-form').hide();
    $('#update-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/station/view') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            var mapOptions, map, marker, searchBox,
                infoWindow = '',
                addressEl = $('#update-location_name').get(0),
                latEl = $('#update-location_lat').get(0),
                longEl = $('#update-location_lng').get(0),
                element = $('#map-update').get(0);
            mapOptions = {
                zoom: 15,
                center: {
                    lat: data.location_lat,
                    lng: data.location_lng
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

            $('#update-id').val(data.id);
            $('#update-station_name').val(data.station_name);
            $('#update-station_contactno').val(data.station_contactno);
            $('#update-location_name').val(data.location_name);
            $('#update-location_lat').val(data.location_lat);
            $('#update-location_lng').val(data.location_lng);
            if(data.image == "TBD") {
                $('#update-image').attr('src', '{{asset("assets/img/pnpseal.png")}}');
            } else {
                $('#update-image').attr('src', '{{asset("/")}}'+data.image);
            }
        },
        complete: function() {
            $('#update .modal-dialog').removeClass('modal-dialog-centered');
            $('#update-loader').hide();
            $('#update-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
});
$('#update-form').on('submit', function(event) {
    event.preventDefault();

    $(this).hide();
    $('#update .modal-dialog').addClass('modal-dialog-centered');
    $('#update-loader').show();
    
    var update_form_id= $('#update-id').val();

    $.ajax({
        type: "POST",
        url: "/admin/station/updatestation/"+update_form_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            $('#update .modal-dialog').removeClass('modal-dialog-centered');
            $('#update-loader').hide();
            $('#update-form').show();
            $('#update-alert-success').show().html(result.success);
            $('#responsive-data-table').DataTable().ajax.reload();
            $('#update-img-fn').html("Choose file . . .");
            setTimeout(function() {
                $('#update-alert-success').fadeOut('slow');
            }, 2500);
        },
        error: function(error) {
            alert('ERROR! CONTACT DEVELOPER!')
        },
    });
});
/* END UPDATE DETAILS */

/* UPDATE USERNAME */
$('#update-username').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#update-username .modal-dialog').addClass('modal-dialog-centered');
    $('#update-username-form').hide();
    $('#update-username-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/station/view') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#update-username-id').val(data.id);
            $('#update-username-username').val(data.username);
        },
        complete: function() {
            $('#update-username .modal-dialog').removeClass('modal-dialog-centered');
            $('#update-username-loader').hide();
            $('#update-username-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
});
$('#update-username-form').on('submit', function(event) {
    event.preventDefault();

    $(this).hide();
    $('#update-username .modal-dialog').addClass('modal-dialog-centered');
    $('#update-username-loader').show();
    
    var iducf = $('#update-username-id').val();

    $.ajax({
        type: "POST",
        url: "/admin/station/updatestation/"+iducf,
        data: $('#update-username-form').serialize(),
        success: function(result) {
            if(result.error) {
                $('#update-username .modal-dialog').removeClass('modal-dialog-centered');
                $('#update-username-loader').hide();
                $('#update-username-form').show();
                $('#update-username-alert-danger').show().html(result.error);
                $('#update-username-username').addClass('is-invalid');
                setTimeout(function() {
                    $('#update-username-alert-danger').fadeOut('slow');
                }, 2500);
            } else {
                $('#update-username .modal-dialog').removeClass('modal-dialog-centered');
                $('#update-username-loader').hide();
                $('#update-username-form').show();
                $('#update-username-alert-success').show().html(result.success);
                $('#update-username-username').removeClass('is-invalid');
                $('#responsive-data-table').DataTable().ajax.reload();
                setTimeout(function() {
                    $('#update-username-alert-success').fadeOut('slow');
                }, 2500);
            }
        },
        error: function(error) {
            console.log(error);
        },
    });
  });
/* END UPDATE USERNAME */

/* REMOVE STATION */
$('#remove-station').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id= button.data('id')
    var modal = $(this)

    $('#remove-station .modal-dialog').addClass('modal-dialog-centered');
    $('#remove-station-form').hide();
    $('#remove-station-loader').show();
    $('#remove-password').val("");

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/station/view') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#remove-station-id').val(data.id);
        },
        complete: function() {
            $('#remove-station .modal-dialog').removeClass('modal-dialog-centered');
            $('#remove-station-loader').hide();
            $('#remove-station-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
});
$('#remove-station-form').on('submit', function(event) {
    event.preventDefault();

    $(this).hide();
    $('#remove-station .modal-dialog').addClass('modal-dialog-centered');
    $('#remove-station-loader').show();

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
    
    var remove_station_id= $('#remove-station-id').val();

    $.ajax({
        type: "POST",
        url: "/admin/station/remove/"+remove_station_id,
        data: $('#remove-station-form').serialize(),
        success: function(result) {
            if(result.success) {
                $('#remove-station').modal('hide');
                $('#responsive-data-table').DataTable().ajax.reload();
                $('#remove-password').val("");
                toastr.error(result.success);
            }
            if(result.error) {
                $('#remove-station .modal-dialog').removeClass('modal-dialog-centered');
                $('#remove-station-loader').hide();
                $('#remove-station-form').show();
                $('#remove-password').addClass('is-invalid');
                $('#remove-password-inv').html(result.error);
                $('#remove-password').val("");
            }
        },
        error: function(error) {
            console.log(error)
        },
    });
});
/* END REMOVE STATION */
  
/* RESET PASSWORD */
$('#change-password').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#change-password .modal-dialog').addClass('modal-dialog-centered');
    $('#change-password-content').hide();
    $('#change-password-confirmation-password').hide();
    $('#change-password-form').hide();
    $('#change-password-loader').show();
    $('#confirm-password').val("");
    $('#check_password').val("");
    $('#confirm_password').val("");

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/station/view') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#reset-password-id').val(data.id);
        },
        complete: function() {
            $('#change-password .modal-dialog').removeClass('modal-dialog-centered');
            $('#change-password-content').show();
            $('#change-password-loader').hide();
            $('#change-password-confirmation-password').show();
            $('#change-password-form').hide();
        },
        error: function(error) {
            console.log(error);
        },
    });
});
$('#change-password-confirmation-password').on('submit', function(event) {
    event.preventDefault();

    $('#change-password .modal-dialog').addClass('modal-dialog-centered');
    $('#change-password-content').hide();
    $('#change-password-confirmation-password').hide();
    $('#change-password-form').hide();
    $('#change-password-loader').show();

    $.ajax({
        type: "POST",
        url: "{{url('admin/station/resetpassword/confirm')}}",
        data: $('#change-password-confirmation-password').serialize(),
        success: function(result) {
            if(result.error) {
                $('#change-password .modal-dialog').removeClass('modal-dialog-centered');
                $('#change-password-content').show();
                $('#change-password-loader').hide();
                $('#change-password-confirmation-password').show();
                $('#change-password-form').hide();
                $('#confirm-password').val("");
                $('#confirm-password').addClass('is-invalid');
                $('#confirm-password-inv').html(result.error);
            }
            if(result.success) {
                $('#change-password .modal-dialog').removeClass('modal-dialog-centered');
                $('#change-password-content').show();
                $('#change-password-loader').hide();
                $('#change-password-confirmation-password').hide();
                $('#change-password-form').show();
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
})
$('#change-password-form').on('submit', function(event) {
    event.preventDefault();

    $('#change-password .modal-dialog').addClass('modal-dialog-centered');
    $('#change-password-content').hide();
    $('#change-password-confirmation-password').hide();
    $('#change-password-form').hide();
    $('#change-password-loader').show();

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

    var reset_pw_id = $('#reset-password-id').val();

    $.ajax({
        type: "POST",
        url: "{{url('admin/station/resetpassword/reset')}}"+"/"+reset_pw_id,
        data: $('#change-password-form').serialize(),
        success: function(result) {
            if(result.success) {
                $('#change-password').modal('hide');
                document.getElementById('change-password-form').reset();
                document.getElementById('change-password-confirmation-password').reset();
                $('#responsive-data-table').DataTable().ajax.reload();
                toastr.success("", result.success);
            }
        },
        error: function(error) {
            if(error.status == 422) {
                $('#change-password .modal-dialog').removeClass('modal-dialog-centered');
                $('#change-password-content').show();
                $('#change-password-confirmation-password').hide();
                $('#change-password-form').show();
                $('#change-password-loader').hide();

                $('#check_password').val("").addClass('is-invalid');
                $('#confirm_password').val("");
            }
        }
    })
})
/* END RESET PASSWORD */
</script>
<!-- ========================================== END AJAX ===================================== -->

<!-- =================================== REPEAT PASSWORD =============================== -->
<script>
  var password = document.getElementById("check_password")
    , confirm_password = document.getElementById("confirm_password");

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
<!-- ====================================== END REPEAT PASSWORD ============================== -->



<!-- ======================================= TOASTER ================================================ -->
@if (session()->has('success'))
<script type="text/javascript">
  var toaster = $('#toaster-alert-success');
    function callToaster(positionClass) {
      toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: false,
        progressBar: true,
        positionClass: positionClass,
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
      toastr.success("{{ session()->get('success') }}", "Succes!");
    }

    if(toaster.length != 0){
      if (document.dir != "rtl") {
        callToaster("toast-top-right");
      } else {
        callToaster("toast-top-left");
      }

    }
</script>
@endif
@if (session()->has('error'))
<script type="text/javascript">
  var toaster = $('#toaster-alert-error');
    function callToaster(positionClass) {
      toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: false,
        progressBar: true,
        positionClass: positionClass,
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
      toastr.error("{{ session()->get('error') }}", "Error!");
    }

    if(toaster.length != 0){
      if (document.dir != "rtl") {
        callToaster("toast-top-right");
      } else {
        callToaster("toast-top-left");
      }

    }
</script>
@endif
<!-- =========================================== END TOASTER ======================================== -->

<!-- ================================ TEXTFIELDS ARE REQUIRED ============================ -->
<script>
$(document).ready(function() {
    $('input[type="text"]').attr("required", true).on("invalid", function() {
        $(this).addClass('is-invalid');
    }).on("input", function() {
        $(this).removeClass('is-invalid');
    });

    $('input[type="password"]').attr("required", true).on("invalid", function() {
        $(this).addClass('is-invalid');
    }).on("input", function() {
        $(this).removeClass('is-invalid');
    });
    
    $('input[type="date"]').attr("required", true).on("invalid", function() {
        $(this).addClass('is-invalid');
    }).on("input", function() {
        $(this).removeClass('is-invalid');
    });

    $('input[type="email"]').attr("required", true).on("invalid", function() {
        $(this).addClass('is-invalid');
    }).on("input", function() {
        $(this).removeClass('is-invalid');
    });

    $('select').attr("required", true).on("invalid", function() {
        $(this).addClass('is-invalid');
    }).on("input", function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
<!-- =============================== END TEXTFIELDS ARE REQUIRED ========================= -->

<!-- ================================== FILE/IMAGE PREVIEWS ===================================== -->
<script type="text/javascript">
    var x = document.getElementById('update-img');
    var y = document.getElementById('update-img-fn');
    x.addEventListener( 'change', showFileName );
    function showFileName( event ) {
    var x = event.srcElement;
    var z = x.files[0].name;
    y.textContent = z;
    }
</script>
<script type="text/javascript">
    var input = document.getElementById( 'file-upload' );
    var infoArea = document.getElementById( 'file-upload-filename' );
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
                $('#update-image').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<!-- ======================================= END FILE/IMAGE PREVIEWS ==================================== -->


</body>
</html>


@else 
    @include('PNPadmin.includes.419')
@endif