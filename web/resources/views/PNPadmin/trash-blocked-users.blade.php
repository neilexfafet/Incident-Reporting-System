@if(Auth::guard('admin')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>PNP | Archive</title>
    @include('pnpadmin.includes.link')
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body" onload=display_ct();>

<script>
    NProgress.start();
</script>

<div class="wrapper">
    @include('pnpadmin.includes.sidebar')
    <div class="page-wrapper">
        @include('pnpadmin.includes.header')
        <div class="content-wrapper">
            <div class="content">	
                            
            <!--CONTENT SECTION-->
                <div class="row">
                    <div class="col-12">
                        <div class="card card-default">
                            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                                <h2>Blocked Users</h2>
                            </div>
                            <div class="card-body">
                                <div class="responsive-data-table">
                                    <table id="responsive-data-table" class="table table-hover dt-responsive" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>E-Mail</th>
                                                <th>Contact No</th>
                                                <th>Valid ID</th>
                                                <th>Age</th>
                                                <th>Report</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UNBLOCK USER MODAL -->
                <div class="modal fade" id="unblock-user" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div id="unblock-user-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                        <form id="unblock-user-form">
                        @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Are you sure you want to Unblock <span id="unblock-user-name"></span>?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="unblock-user-id">
                                    <div class="form-row px-2 py-2">
                                        <label class="text-dark font-size-16">Input Password to continue</label>
                                        <input name="password" id="restore-password" type="password" class="form-control" placeholder="Password" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                        <span class="invalid-feedback" id="restore-password-inv">Please fill out this field</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Unblock</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--END UNBLOCK USER MODAL -->

    <!-- VIEW REPORT -->
        <div class="modal fade" id="view-dispatch" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormEDIT" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div id="view-dispatch-loader" class="col-sm-12" style="display: none;">@include('PNPadmin.includes.loader')</div>
                <div id="view-dispatch-form" class="modal-content">
                    <div class="modal-header d-flex justify-content-center">
                        <img src="{{asset('assets/img/pnpseal.png')}}" class="img-fluid" style="max-height: 50px; max-width: 50px;">
                        <h1 class="text-dark px-4">INCIDENT REPORT</h1>
                        <img src="{{asset('assets/img/pnpseal.png')}}" class="img-fluid" style="max-height: 50px; max-width: 50px;">
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-between pt-4">
                            <h2 class="text-dark font-weight-medium" id="view-dispatch-dispatch_id"></h2>
                            <h2 class="font-weight-medium" id="view-dispatch-status"></h2>
                        </div>
                        <div class="row border-bottom pt-5">
                            <div class="col-xl-6 pb-4">
                                <h4 class="text-dark mb-2">Incident Details</h4>
                                    <p><i class="text-dark pr-4">Incident:&nbsp;</i><span id="view-dispatch-report" class="text-dark font-weight-medium"></span></p>
                                    <p class="pt-2"><i class="text-dark pr-2">Date and Time of the Report:</i>&nbsp;<span id="view-dispatch-datetime-report" class="pl-2"></span></p>
                                    <p class="pt-2"><i class="text-dark pr-2">Date and Time of the Incident:</i>&nbsp;<span id="view-dispatch-datetime-incident" class="pl-2"></span></p>
                                    <p class="pt-2"><i class="text-dark pr-2">Date and Time of the Station to Respond:</i>&nbsp;<span id="view-dispatch-datetime-respond" class="pl-2"></span></p>
                                    <p class="pt-2"><i class="text-dark pr-2">Date and Time of the Station to Verify:</i>&nbsp;<span id="view-dispatch-datetime-confirm" class="pl-2"></span></p>
                                    <p class="text-dark py-2"><i>Outline of the Incident according to the Reporter:</i>&nbsp;</p>
                                    <p id="view-dispatch-description" class="pl-2"></p>
                            </div>
                            <div class="col-xl-3 pb-4">
                                <h4 class="text-dark mb-2">Police Station</h4>
                                    <p class="pb-2"><i class="mdi mdi-security"></i>&nbsp;<span id="view-dispatch-station-name"></span></p>
                                    <p class="pb-2"><i class="mdi mdi-map-marker"></i>&nbsp;<span id="view-dispatch-station-location"></span></p>
                                    <p class="pb-2"><i class="mdi mdi-phone"></i>&nbsp;<span id="view-dispatch-station-contactno"></span></p>
                            </div>
                            <div class="col-xl-3 pb-4">
                                <h4 class="text-dark mb-2">Reporter</h4>
                                    <p class="pb-2"><i class="mdi mdi-account"></i>&nbsp;<span id="view-dispatch-reporter-name"></span></p>
                                    <p class="pb-2"><i class="mdi mdi-gender-male-female"></i>&nbsp;<span id="view-dispatch-reporter-gender"></span></p>
                                    <p class="pb-2"><i class="mdi mdi-calendar-clock"></i>&nbsp;<span id="view-dispatch-reporter-age"></span> years old</p>
                                    <p class="pb-2"><i class="mdi mdi-email"></i>&nbsp;<span id="view-dispatch-reporter-email"></span></p>
                                    <p class="pb-2"><i class="mdi mdi-phone"></i>&nbsp;<span id="view-dispatch-reporter-contactno"></span></p>
                            </div>
                        </div>
                        <div class="row border-bottom pt-4 pb-2 px-4">
                            <h3>Dispatched Police Officers</h3>
                            <table class="table mt-3 table-striped table-responsive table-responsive-large" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Police Officer</th>
                                        <th>Rank</th>
                                        <th>PNP ID</th>
                                        <th>Badge</th>
                                        <th>Contact #</th>
                                    </tr>
                                </thead>
                                <tbody id="view-dispatch-table"></tbody>
                            </table>
                        </div>
                        <div class="pt-4">
                            <div class="row">
                                <div class="col-lg-6 pr-2">
                                    <h3 class="pl-2 pb-2">Incident Location</h3>
                                    <div id="view-dispatch-location-map" class="map-container"></div>
                                </div>
                                <div class="col-lg-6">
                                    <h3 class="pl-2 pb-2">File Evidences</h3>
                                    <div id="view-dispatch-file-evidence" class="row"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- END VIEW REPORT -->

            </div>

            @include('PNPadmin.includes.notifications')
            @include('pnpadmin.includes.footer')
        </div>
    </div>
</div>
@include('pnpadmin.includes.script')

<!-- ===================================== GOOGLE MAP ============================== -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{config('googlemap')['map_apikey']}}&callback=initMap&libraries=places"
    type="text/javascript"></script>
<!-- ================================== END GOOGLE MAP ==================================== -->

<!-- ===================================DATA TABLE SCRIPT==================================== -->
<script src={{ asset("assets/plugins/data-tables/jquery.datatables.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.bootstrap4.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.responsive.min.js") }}></script>

<script>
    $(document).ready(function() {
        $('#responsive-data-table').DataTable({
            "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
            processing: true,
            serverSide: true,
            language: {
                emptyTable: 'No Blocked Users as of now.',
                zeroRecords: 'No Users Found. Try Searching another keyword.',
            },
            ajax: {
                url: "{{ url('admin/trash/blocked-users') }}",
            },
            columns: [
                { data: 'last_name', render: function(data, type, row) {
                    return data + ', ' + row["first_name"] + ' ' + row["middle_name"];
                } },
                { data: 'email' },
                { data: 'contact_no' },
                { data: 'valid_id_image', render: function(data) {
                    if(data == "TBD") {
                        return '<img class="img-fluid" src="{{asset("uploads/user.jpg")}}" style="max-height: 50px;max-width: 50px">';
                    } else {
                        return '<img class="img-fluid" src="{{asset('+data+')}}" style="max-height: 50px;max-width: 50px">';
                    }
                } },
                { data: 'birthday', render: function(data) {
                    var bday = moment().diff(data, "years");
                    return bday + ' Years Old';
                } },
                { data: 'report.dispatch.dispatch_id', render: function(data, type, row) {
                    return '<a href="javascript:void(0);" data-id='+row.report.dispatch["id"]+' data-toggle="modal" data-target="#view-dispatch">'+data+'</a>';
                } },
                { data: 'action', orderable: false, className: 'text-center' },
            ]
        });
    });
</script>
<!-- ========================================== END DATA TABLE ===================================== -->

<!-- ====================================== AJAX ================================== -->
<script>
$(document).ready(function() {
    $('#unblock-user').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id= button.data('id');
        var modal = $(this);

        $('#unblock-user .modal-dialog').addClass('modal-dialog-centered');
        $('#unblock-user-loader').show();
        $('#unblock-user-form').hide();
        $('#restore-password').val("").removeClass('is-invalid');

        $.ajax({
            type: "GET",
            url: "{{ url('/admin/trash/blocked-users/view') }}"+"/"+id,
            dataType: "JSON",
            success: function(data) {
                $('#unblock-user-id').val(data.id);
                $('#unblock-user-name').html(data.first_name + " " + data.last_name);
            },
            complete: function() {
                $('#unblock-user .modal-dialog').removeClass('modal-dialog-centered');
                $('#unblock-user-loader').hide();
                $('#unblock-user-form').show();
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
    $('#unblock-user-form').on('submit', function(event) {
        event.preventDefault();

        $(this).hide();
        $('#unblock-user .modal-dialog').addClass('modal-dialog-centered');
        $('#unblock-user-loader').show();

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
        
        var restore_incident_id= $('#unblock-user-id').val();

        $.ajax({
            type: "POST",
            url: "/admin/trash/blocked-users/unblock/"+restore_incident_id,
            data: $('#unblock-user-form').serialize(),
            success: function(result) {
                if(result.success) {
                    $('#unblock-user').modal('hide');
                    $('#responsive-data-table').DataTable().ajax.reload();
                    $('#restore-password').val("");
                    toastr.success(result.success);
                }
                if(result.error) {
                    $('#unblock-user .modal-dialog').removeClass('modal-dialog-centered');
                    $('#unblock-user-loader').hide();
                    $('#unblock-user-form').show();
                    $('#restore-password').val("");
                    $('#restore-password').addClass('is-invalid');
                    $('#restore-password-inv').html(result.error);
                }
            },
            error: function(error) {
                console.log(error)
            },
        });
    });
    /* ============= VIEW DISPATCH DETAILS ============= */
    $('#view-dispatch').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);

        $('#view-dispatch .modal-dialog').addClass('modal-dialog-centered');
        $('#view-dispatch-form').hide();
        $('#view-dispatch-loader').show();

        $.ajax({
            type: "GET",
            url: "{{ url('/admin/incident-reports/view') }}"+"/"+id,
            dataType: "JSON",
            success: function(result) {
                var bday = moment(result.find.get_report.get_user.birthday).format('LL');
                var age = moment().diff(result.find.get_report.get_user.birthday, 'years');
                var datetime_incident = moment(result.find.get_report.incident_date).format('LL')+" at "+moment(result.find.get_report.incident_date).format('LT');
                var datetime_report = moment(result.find.get_report.created_at).format('LL')+" at "+moment(result.find.get_report.created_at).format('LT');
                var datetime_respond = moment(result.find.created_at).format('LL')+" at "+moment(result.find.created_at).format('LT');
                var datetime_confirm = moment(result.find.updated_at).format('LL')+" at "+moment(result.find.updated_at).format('LT');

                var mapview = new google.maps.Map(document.getElementById('view-dispatch-location-map'),{
                    center: {
                        lat: result.find.get_report.location_lat,
                        lng: result.find.get_report.location_lng
                    },
                    zoom:15
                });
                var markerview = new google.maps.Marker({
                    map:mapview,
                    position: {
                        lat: result.find.get_report.location_lat,
                        lng: result.find.get_report.location_lng
                    },
                });
                infoWindow = new google.maps.InfoWindow({
                    content: result.find.get_report.location
                });
                infoWindow.open(mapview, markerview);

                $('#view-dispatch-officers-tab').html(result.tab);
                $('#view-dispatch-officers-tab-content').html(result.tabcontent);
                $('#view-dispatch-location').html(result.find.get_report.location);
                $('#view-dispatch-dispatch_id').html(result.find.dispatch_id);
                $('#view-dispatch-report').html(result.find.get_report.get_incident.type);
                $('#view-dispatch-description').html(result.find.get_report.description);
                $('#view-dispatch-reporter-name').html(result.find.get_report.get_user.first_name+" "+result.find.get_report.get_user.last_name);
                $('#view-dispatch-reporter-gender').html(result.find.get_report.get_user.gender);
                $('#view-dispatch-reporter-email').html(result.find.get_report.get_user.email);
                $('#view-dispatch-reporter-contactno').html(result.find.get_report.get_user.contact_no);
                $('#view-dispatch-station-name').html(result.find.get_station.station_name);
                $('#view-dispatch-station-location').html(result.find.get_station.location_name);
                $('#view-dispatch-station-contactno').html(result.find.get_station.station_contactno);
                $('#view-dispatch-reporter-age').html(age);
                $('#view-dispatch-table').html(result.table);
                $('#view-dispatch-file-evidence').html(result.img);
                $('#view-dispatch-datetime-incident').html(datetime_incident);
                $('#view-dispatch-datetime-report').html(datetime_report);
                $('#view-dispatch-datetime-respond').html(datetime_respond);
                $('#view-dispatch-datetime-confirm').html(datetime_confirm);
                if(result.find.status == "verified") {
                    $('#view-dispatch-status').html("<span class='text-success'><i class='mdi mdi-check'></i> VERIFIED</span>");
                } else {
                    $('#view-dispatch-status').html("<span class='text-danger'><i class='mdi mdi-close'></i> FRAUD</span>");
                }
                if(result.find.get_report.file_evidence == "TBD") {
                    $('#view-dispatch-reporter-image').attr('src', '{{ asset("uploads/user.jpg") }}');
                } else {
                    $('#view-dispatch-reporter-image').attr('src', '{{ asset("/") }}'+result.find.get_report.file_evidence);
                }
            },
            complete: function() {
                $('#view-dispatch .modal-dialog').removeClass('modal-dialog-centered');
                $('#view-dispatch-loader').hide();
                $('#view-dispatch-form').show();
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
    /* ============== END VIEW DISPATCH DETAILS ============== */
})
</script>
<!-- ====================================== AJAX ================================== -->



</body>
</html>

            
@else
    @include('PNPadmin.includes.419')
@endif