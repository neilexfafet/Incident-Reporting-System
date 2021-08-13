@if(Auth::guard('station')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>Station | Dashboard</title>
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
                        <div class="col-lg-3">
                            <a href="{{ url('station/officers') }}" style="color: #8a909d">
                                <div class="media widget-media p-4 bg-white border">
                                    <div class="icon rounded-circle mr-4 bg-primary">
                                        <i class="mdi mdi-account-group text-white "></i>
                                    </div>
                                    <div class="media-body align-self-center">
                                        <h4 class="text-primary mb-2">{{$officers}}</h4>
                                        <p>Assigned Officers</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3">
                            <a href="{{ url('station/incident-reports') }}" style="color: #8a909d">
                                <div class="media widget-media p-4 bg-white border">
                                    <div class="icon rounded-circle mr-4 bg-warning">
                                        <i class="mdi mdi-alert text-white "></i>
                                    </div>
                                    <div class="media-body align-self-center">
                                        <h4 class="text-primary mb-2">{{$reports}}</h4>
                                        <p>Reports Received</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3">
                            <a href="{{ url('station/incident-reports') }}" style="color: #8a909d">
                                <div class="media widget-media p-4 bg-white border">
                                    <div class="icon rounded-circle mr-4 bg-success">
                                        <i class="mdi mdi-check-circle-outline text-white "></i>
                                    </div>
                                    <div class="media-body align-self-center">
                                        <h4 class="text-primary mb-2">{{$verifiedreports}}</h4>
                                        <p>Verified Reports</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3">
                            <a href="{{ url('station/incident-reports') }}" style="color: #8a909d">
                                <div class="media widget-media p-4 bg-white border">
                                    <div class="icon rounded-circle mr-4 bg-danger">
                                        <i class="mdi mdi-close-outline text-white "></i>
                                    </div>
                                    <div class="media-body align-self-center">
                                        <h4 class="text-primary mb-2">{{$bogusreports}}</h4>
                                        <p>Fraud Reports</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="card card-default" data-scroll-height="550">
                                <div class="card-header justify-content-between ">
                                    <h2>Incident Reports Log</h2>
                                    <div>
                                        <button id="incident-reports-refresh" class="text-black-50 mr-2 font-size-20"><i class="mdi mdi-cached"></i></button>
                                    </div>
                                </div>
                                <div class="card-body slim-scroll">
                                    <div id="incident-reports-loader">@include('PNPstation.includes.loader')</div>
                                    <form id="incident-reports-tab1" style="display: none;"></form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="card card-default" data-scroll-height="550">
                                <div class="card-body slim-scroll">
                                    <div id="donut-incident-reports-loader">@include('PNPstation.includes.loader')</div>
                                    <form id="incident-reports-tab2" style="display: none;">
                                        <div class="py-6">
                                            <canvas id="incident-report-DonutChart" style="min-height: 200px; min-height: 230px;"></canvas>
                                        </div>
                                        <div class="card-footer d-flex flex-wrap bg-white p-0">
                                            <div class="col-6">
                                                <div class="py-4 ">
                                                <ul>
                                                    <div class="d-flex justify-content-between">
                                                        <li class="mb-2"><i class="mdi mdi-alert text-warning mr-2"></i>Total Incident Reports</li>
                                                        <li id="incident-reports-count"></li>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <li><i class="mdi mdi-map-search text-primary mr-2"></i>Responded Reports</li>
                                                        <li id="incident-reports-responded-count"></li>
                                                    </div>
                                                </ul>
                                                </div>
                                            </div>
                                            <div class="col-6 border-left">
                                                <div class="py-4 ">
                                                <ul>
                                                    <div class="d-flex justify-content-between">
                                                        <li class="mb-2"><i class="mdi mdi-check text-success mr-2"></i>Verified Reports</li>
                                                        <li id="incident-reports-verified-count"></li>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <li><i class="mdi mdi-close text-danger mr-2"></i>Bogus Reports</li>
                                                        <li id="incident-reports-bogus-count"></li>
                                                    </div>
                                                </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    <!-- VIEW DISPATCH -->
        <div class="modal fade" id="view-dispatch" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormEDIT" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div id="view-dispatch-loader" class="col-sm-12" style="display: none;">@include('PNPstation.includes.loader')</div>
                <div class="modal-content" id="view-dispatch-form">
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
                                    <p class="pt-2"><i class="text-dark pr-2">Location of the Incident:</i>&nbsp;<span id="view-dispatch-location" class="pl-2"></span></p>
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
                                    <h3 class="pl-2 pb-2">Incident Location Map</h3>
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
    <!-- END VIEW DISPATCH -->


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

<!-- ============================================ AJAX ======================================= -->
<script>
$(document).ready(function() {
    /* LOGIN TOASTR */
    if(localStorage.getItem("success")) {
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
        
        toastr.info(localStorage.getItem("success"), "Welcome Back!");
        localStorage.clear();
    }
    /* END LOGIN TOASTR */

    /* INCIDENT REPORTS */
    incidentReports();

    $('#incident-reports-refresh').on('click', function() {
        $('#incident-reports-loader').show();
        $('#incident-reports-tab1').hide();
        incidentReports();
    });
    function incidentReports() {
        $('#incident-reports-tab1').hide();
        $.ajax({
            type: "GET",
            url: "{{ url('station/dashboard/reports') }}",
            dataType: "JSON",
            success: function(result) {
                $('#incident-reports-tab1').html(result.view);
            },
            complete: function() {
                $('#incident-reports-loader').hide();
                $('#incident-reports-tab1').show();
            },
            error: function(error) {
                console.log(error);
            },
        })
    };
    /* END INCIDENT REPORTS */

    /* DONUT CHART */
    $.ajax({
        type: "GET",
        url: "{{ url('station/dashboard/reports') }}",
        dataType: "JSON",
        success: function(result) {
            var deviceChart = $('#incident-report-DonutChart');
            var responded = Math.round((result.responded / result.all) * 100);
            var verified = Math.round((result.verified / result.all) * 100);
            var bogus = Math.round((result.bogus / result.all) * 100);

            $('#incident-reports-count').html(result.all);
            $('#incident-reports-responded-count').html(result.responded);
            $('#incident-reports-verified-count').html(result.verified);
            $('#incident-reports-bogus-count').html(result.bogus);
            if (deviceChart !== null) {
                var mydeviceChart = new Chart(deviceChart, {
                    type: "doughnut",
                    data: {
                    labels: ["Responded", "Verified", "Bogus"],
                    datasets: [{
                        label: ["Responded", "Verified", "Bogus"],
                        data: [responded, verified, bogus],
                        backgroundColor: [
                        "#4c84ff",
                        "#29cc97",
                        "#fe5461",
                        ],
                        borderWidth: 1
                    }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        cutoutPercentage: 75,
                        tooltips: {
                            callbacks: {
                                title: function (tooltipItem, data) {
                                    return data["labels"][tooltipItem[0]["index"]];
                                },
                                label: function (tooltipItem, data) {
                                    return (
                                        data["datasets"][0]["data"][tooltipItem["index"]] + "% Report/s"
                                    );
                                }
                            },
                            titleFontColor: "#888",
                            bodyFontColor: "#555",
                            titleFontSize: 12,
                            bodyFontSize: 15,
                            backgroundColor: "rgba(256,256,256,0.95)",
                            displayColors: true,
                            xPadding: 10,
                            yPadding: 7,
                            borderColor: "rgba(220, 220, 220, 0.9)",
                            borderWidth: 2,
                            caretSize: 6,
                            caretPadding: 5
                        }   
                    }
                });
            }
        },
        complete: function() {
            $('#donut-incident-reports-loader').hide();
            $('#incident-reports-tab2').show();
        },
        error: function(error) {
            console.log(error);
        },
    })
    /* DONUT CHART */

    /* VIEW DISPATCH DETAILS */
    $('#view-dispatch').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);

        $('#view-dispatch .modal-dialog').addClass('modal-dialog-centered');
        $('#view-dispatch-form').hide();
        $('#view-dispatch-loader').show();

        $.ajax({
            type: "GET",
            url: "{{ url('/station/incident-reports/dispatch/view') }}"+"/"+id,
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
                    $('#view-dispatch-status').html("<span class='text-success'><i class='mdi mdi-check'></i> VALID</span>");
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
    /* END VIEW DISPATCH DETAILS */
})
</script>
<!-- ============================================ END AJAX ======================================= -->


</body>
</html>

@else 
    @include('PNPstation.includes.419')
@endif
