@if(Auth::guard('admin')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>PNP | Dashboard</title>
    @include('pnpadmin.includes.link')
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body" onload=display_ct();>

<script>
    NProgress.start();
</script>

@if(session()->has('loginsuccess'))
    <div id="toaster-alert-info"></div>
@endif

<div class="wrapper">
    @include('pnpadmin.includes.sidebar')
    <div class="page-wrapper">
        @include('pnpadmin.includes.header')
        <div class="content-wrapper">
            <div class="content">						 
                
                <!--CONTENT SECTION-->
                <div class="row">
                    <div class="col-lg-3">
                        <div class="media widget-media p-4 bg-white border">
                            <div class="icon rounded-circle mr-4 bg-info">
                                <i class="mdi mdi-account-check text-white "></i>
                            </div>
                            <div class="media-body align-self-center">
                                <h4 class="text-primary mb-2">{{$userscount}}</h4>
                                <p>Registered Users</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <a href="{{ url('admin/officers') }}" style="color: #8a909d">
                            <div class="media widget-media p-4 bg-white border">
                                <div class="icon rounded-circle mr-4 bg-primary">
                                    <i class="mdi mdi-account-group text-white "></i>
                                </div>
                                <div class="media-body align-self-center">
                                    <h4 class="text-primary mb-2">{{$officers}}</h4>
                                    <p>Police Officers</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3">
                        <a href="{{ url('admin/station') }}" style="color: #8a909d">
                            <div class="media widget-media p-4 bg-white border">
                                <div class="icon rounded-circle mr-4 bg-success">
                                    <i class="mdi mdi-security text-white "></i>
                                </div>
                                <div class="media-body align-self-center">
                                    <h4 class="text-primary mb-2">{{$stationcount}}</h4>
                                    <p>Police Stations</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3">
                        <a href="{{ url('admin/incident-reports') }}" style="color: #8a909d">
                            <div class="media widget-media p-4 bg-white border">
                                <div class="icon rounded-circle mr-4 bg-warning">
                                    <i class="mdi mdi-alert-outline text-white "></i>
                                </div>
                                <div class="media-body align-self-center">
                                    <h4 class="text-primary mb-2">{{$reports}}</h4>
                                    <p>Received Reports</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="card widget-block p-4 rounded bg-white">
                            <div class="card-block">
                                <p class="py-2">Incident Reports Activity</p>
                            </div>
                            @if($reports != 0)
                                <div id="incident-reports-activity-loader">
                                    <div class="card-body d-flex align-items-center justify-content-center" style="height: 110px;">
                                        <div class="sk-fading-circle">
                                            <div class="sk-circle1 sk-circle"></div>
                                            <div class="sk-circle2 sk-circle"></div>
                                            <div class="sk-circle3 sk-circle"></div>
                                            <div class="sk-circle4 sk-circle"></div>
                                            <div class="sk-circle5 sk-circle"></div>
                                            <div class="sk-circle6 sk-circle"></div>
                                            <div class="sk-circle7 sk-circle"></div>
                                            <div class="sk-circle8 sk-circle"></div>
                                            <div class="sk-circle9 sk-circle"></div>
                                            <div class="sk-circle10 sk-circle"></div>
                                            <div class="sk-circle11 sk-circle"></div>
                                            <div class="sk-circle12 sk-circle"></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="incident-reports-activity-chart" class="chartjs-wrapper" style="height: 110px; display: none;">
                                    <canvas id="incident-reports-activity"></canvas>
                                </div>
                            @else
                                <span class="text-center font-weight-medium py-5">No Reports Yet</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card widget-block p-4 rounded bg-white ">
                            <div class="card-block">
                                <p class="py-2">Stations Activity</p>
                            </div>
                            @if($stationcount != 0)
                                <div id="stations-activity-loader">
                                    <div class="card-body d-flex align-items-center justify-content-center" style="height: 110px;">
                                        <div class="sk-fading-circle">
                                            <div class="sk-circle1 sk-circle"></div>
                                            <div class="sk-circle2 sk-circle"></div>
                                            <div class="sk-circle3 sk-circle"></div>
                                            <div class="sk-circle4 sk-circle"></div>
                                            <div class="sk-circle5 sk-circle"></div>
                                            <div class="sk-circle6 sk-circle"></div>
                                            <div class="sk-circle7 sk-circle"></div>
                                            <div class="sk-circle8 sk-circle"></div>
                                            <div class="sk-circle9 sk-circle"></div>
                                            <div class="sk-circle10 sk-circle"></div>
                                            <div class="sk-circle11 sk-circle"></div>
                                            <div class="sk-circle12 sk-circle"></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="stations-activity-chart" class="chartjs-wrapper" style="height: 110px; display: none;">
                                    <canvas id="stations-activity"></canvas>
                                </div>
                            @else
                                <span class="text-center font-weight-medium py-5">No Registered Stations Yet</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card widget-block p-4 rounded bg-white ">
                            <div class="card-block">
                                <p class="py-2">Users Activity</p>
                            </div>
                            @if($userscount != 0)
                                <div id="users-activity-loader">
                                    <div class="card-body d-flex align-items-center justify-content-center" style="height: 110px;">
                                        <div class="sk-fading-circle">
                                            <div class="sk-circle1 sk-circle"></div>
                                            <div class="sk-circle2 sk-circle"></div>
                                            <div class="sk-circle3 sk-circle"></div>
                                            <div class="sk-circle4 sk-circle"></div>
                                            <div class="sk-circle5 sk-circle"></div>
                                            <div class="sk-circle6 sk-circle"></div>
                                            <div class="sk-circle7 sk-circle"></div>
                                            <div class="sk-circle8 sk-circle"></div>
                                            <div class="sk-circle9 sk-circle"></div>
                                            <div class="sk-circle10 sk-circle"></div>
                                            <div class="sk-circle11 sk-circle"></div>
                                            <div class="sk-circle12 sk-circle"></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="users-activity-chart" class="chartjs-wrapper" style="height: 110px; display: none;">
                                    <canvas id="users-activity"></canvas>
                                </div>
                            @else 
                                <span class="text-center font-weight-medium py-5">No Registered Users Yet</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-5">
                        <div class="card card-default" data-scroll-height="500">
                            <div class="card-header d-flex justify-content-between">
                                <h2>New Registered Users</h2>
                                <h2 class="text-primary">{{$newusers}}</h2>		
                            </div>
                            <div class="card-body slim-scroll">
                                @if(count($users) != 0)
                                    @foreach($users as $user)
                                        <div class="media py-3 align-items-center justify-content-between">
                                            <div class="media-image mr-3 rounded-circle">
                                            @if($user->image == "TBD")
                                                <img class="rounded-circle w-45" src="{{ asset("uploads/user.jpg") }}" alt="user image" onerror="this.src='{{ asset("uploads/user.jpg") }}'">
                                            @else
                                                <img class="rounded-circle w-45" src="{{ asset($user->image) }}" alt="user image" onerror="this.src='{{ asset("uploads/user.jpg") }}'">
                                            @endif
                                            </div>
                                            <div class="media-body pr-3 ">
                                                <p class="text-dark">{{$user->first_name}} {{$user->middle_name}} {{$user->last_name}}</p>
                                                <p>{{$user->first_name}} has joined us {{$user->created_at->diffForHumans()}}</p>
                                            </div>
                                        </div>	
                                    @endforeach
                                @else
                                <div class="media py-3 align-items-center justify-content-between">
                                    <div class="media-body text-center pr-3 ">
                                        <p>No Users Yet</p>
                                    </div>
                                </div>
                                @endif
                            </div>		
                        </div>		
                    </div>
                    <div class="col-lg-7">
                        <div class="card card-default" data-scroll-height="500">
                            <div class="card-header d-flex justify-content-between">
                                <h2>Incident Report Logs</h2>
                                <div>
                                    <button id="incident-reports-refresh" class="text-black-50 mr-2 font-size-20"><i class="mdi mdi-cached"></i></button>
                                    <div class="dropdown show d-inline-block widget-dropdown">
                                        <a class="dropdown-toggle icon-burger-mini" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static"></a>
                                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-notification">
                                            <a id="incident-reports-select1" href="javascript:void(0);"><li class="dropdown-item">Details</li></a>
                                            <a id="incident-reports-select2" href="javascript:void(0);"><li class="dropdown-item">Chart</li></a>
                                        </ul>
                                    </div>
                                </div>			
                            </div>
                            <div class="card-body slim-scroll">
                                <div id="incident-reports-loader">@include('PNPadmin.includes.loader')</div>
                                <form id="incident-reports-tab1" style="display: none;"></form>
                                <form id="incident-reports-tab2" style="display: none;">
                                    <div class="pb-2">
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
                                                    <li class="mb-2"><i class="mdi mdi-check text-success mr-2"></i>Valid Reports</li>
                                                    <li id="incident-reports-verified-count"></li>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <li><i class="mdi mdi-close text-danger mr-2"></i>Fraud Reports</li>
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

<!-- ============================= DATATABLE ============================ -->
<script src={{ asset("assets/plugins/data-tables/jquery.datatables.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.bootstrap4.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.responsive.min.js") }}></script>
<!-- =============================== END DATATABLE ================================ -->

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
        $('#incident-reports-tab2').hide();
        incidentReports();
    });
    $('#incident-reports-select1').on('click', function() {
        $('#incident-reports-loader').show();
        $('#incident-reports-tab1').hide();
        $('#incident-reports-tab2').hide();
        incidentReports();
    });
    $('#incident-reports-select2').on('click', function() {
        $('#incident-reports-loader').show();
        $('#incident-reports-tab1').hide();
        $('#incident-reports-tab2').show();
        incidentReportsDonut();
    });
    function incidentReports() {
        $('#incident-reports-tab1').hide();
        $.ajax({
            type: "GET",
            url: "{{ url('admin/dashboard/reports') }}",
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
    function incidentReportsDonut() {
        $('#incident-reports-tab2').hide();
        $.ajax({
            type: "GET",
            url: "{{ url('admin/dashboard/reports') }}",
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
                        labels: ["Responded", "Valid", "Fraud"],
                        datasets: [{
                            label: ["Responded", "Valid", "Fraud"],
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
                $('#incident-reports-loader').hide();
                $('#incident-reports-tab2').show();
            },
            error: function(error) {
                console.log(error);
            },
        })
    }
    /* END INCIDENT REPORTS */

    /* INCIDENT REPORTS ACTIVITY */
    $.ajax({
        type: "GET",
        url: "{{url('admin/statistical-reports/report-activity')}}",
        dataType: "JSON",
        success: function(result) {
            var line = document.getElementById("incident-reports-activity");
            if (line !== null) {
                line = line.getContext("2d");
                var gradientFill = line.createLinearGradient(0, 120, 0, 0);
                gradientFill.addColorStop(0, "rgba(41,204,151,0.10196)");
                gradientFill.addColorStop(1, "rgba(41,204,151,0.30196)");

                var lChart = new Chart(line, {
                type: "line",
                data: {
                    labels: result.view2,
                    datasets: [
                    {
                        label: "Verifed",
                        lineTension: 0,
                        pointRadius: 4,
                        pointBackgroundColor: "rgba(255,255,255,1)",
                        pointBorderWidth: 2,
                        fill: true,
                        backgroundColor: gradientFill,
                        borderColor: "#29cc97",
                        borderWidth: 2,
                        data: result.verified,
                    },
                    {
                        label: "Fraud",
                        lineTension: 0,
                        pointRadius: 4,
                        pointBackgroundColor: "rgba(255,255,255,1)",
                        pointBorderWidth: 2,
                        fill: true,
                        backgroundColor: gradientFill,
                        borderColor: "#fe5461",
                        borderWidth: 2,
                        data: result.bogus
                    }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                    display: false
                    },
                    layout: {
                    padding: {
                        right: 10
                    }
                    },
                    scales: {
                    xAxes: [
                        {
                        gridLines: {
                            drawBorder: false,
                            display: false
                        },
                        ticks: {
                            display: false, // hide main x-axis line
                            beginAtZero: true
                        },
                        barPercentage: 1.8,
                        categoryPercentage: 0.2
                        }
                    ],
                    yAxes: [
                        {
                        gridLines: {
                            drawBorder: false, // hide main y-axis line
                            display: false
                        },
                        ticks: {
                            display: false,
                            beginAtZero: true
                        }
                        }
                    ]
                    },
                    tooltips: {
                    titleFontColor: "#888",
                    bodyFontColor: "#555",
                    titleFontSize: 12,
                    bodyFontSize: 14,
                    backgroundColor: "rgba(256,256,256,0.95)",
                    displayColors: true,
                    borderColor: "rgba(220, 220, 220, 0.9)",
                    borderWidth: 2
                    }
                }
                });
            }
        },
        complete: function() {
            $('#incident-reports-activity-loader').hide();
            $('#incident-reports-activity-chart').show();
        },
        error: function(error) {
            console.log(error);
        },
    })
    /* END INCIDENT REPORTS ACTIVITY */

    /* STATIONS ACTIVITY */
    $.ajax({
        type: "GET",
        url: "{{url('admin/statistical-reports/stations-activity')}}",
        dataType: "JSON",
        success: function(result) {
            var barX = document.getElementById("stations-activity");
            if (barX !== null) {
            var myChart = new Chart(barX, {
                type: "bar",
                data: {
                labels: result.station_label,
                datasets: [
                    {
                        label: "Officers",
                        data: result.station_officer_count,
                        backgroundColor: "rgb(76, 132, 255)"
                    },
                    {
                        label: "Reports",
                        data: result.station_report_count,
                        backgroundColor: "rgb(254, 196, 0)"
                    },
                ]
                },
                options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [
                    {
                        gridLines: {
                            drawBorder: false,
                            display: false
                        },
                        ticks: {
                            display: false, // hide main x-axis line
                            beginAtZero: true
                        },
                    }
                    ],
                    yAxes: [
                    {
                        gridLines: {
                            drawBorder: false, // hide main y-axis line
                            display: false
                        },
                        ticks: {
                            display: false,
                            beginAtZero: true
                        }
                    }
                    ]
                },
                tooltips: {
                    titleFontColor: "#888",
                    bodyFontColor: "#555",
                    titleFontSize: 12,
                    bodyFontSize: 15,
                    backgroundColor: "rgba(256,256,256,0.95)",
                    displayColors: false,
                    borderColor: "rgba(220, 220, 220, 0.9)",
                    borderWidth: 2
                }
                }
            });
            }
        },
        complete: function() {
            $('#stations-activity-loader').hide();
            $('#stations-activity-chart').show();
        },
        error: function(error) {
            console.log(error);
        }
    })
    /* END STATIONS ACTIVITY */

    /* USERS ACTIVTY */
    $.ajax({
        type: "GET",
        url: "{{url('admin/statistical-reports/users-activity')}}",
        dataType: "JSON",
        success: function(result) {
            var dual = document.getElementById("users-activity");
            if (dual !== null) {
                var urChart = new Chart(dual, {
                type: "line",
                data: {
                    labels: ["Average Per Day","Average Per Week","Average Per Month","Average Per Year",],
                    datasets: [
                    {
                        label: "Registrations",
                        fill: false,
                        pointRadius: 4,
                        pointBackgroundColor: "rgba(255,255,255,1)",
                        pointBorderWidth: 2,
                        backgroundColor: "transparent",
                        borderWidth: 2,
                        borderColor: "#4c84ff",
                        data: [ Math.round(result.ave_day * 100) / 100, 
                            Math.round(result.ave_week * 100) / 100, 
                            Math.round(result.ave_month * 100) / 100, 
                            Math.round(result.ave_year * 100) / 100 ]
                    },
                    {
                        label: "Reported an Incident",
                        pointRadius: 4,
                        pointBackgroundColor: "rgba(255,255,255,1)",
                        pointBorderWidth: 2,
                        fill: false,
                        backgroundColor: "transparent",
                        borderWidth: 2,
                        borderColor: "#fdc506",
                        data: [ Math.round(result.ave_day_report * 100) / 100, 
                            Math.round(result.ave_week_report * 100) / 100, 
                            Math.round(result.ave_month_report * 100) / 100, 
                            Math.round(result.ave_year_report * 100) / 100 ]
                    },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                    padding: {
                        right: 10
                    }
                    },

                    legend: {
                    display: false
                    },
                    scales: {
                    xAxes: [
                        {
                        gridLines: {
                            drawBorder: false,
                            display: false
                        },
                        ticks: {
                            display: false, // hide main x-axis line
                            beginAtZero: true
                        },
                        barPercentage: 1.8,
                        categoryPercentage: 0.2
                        }
                    ],
                    yAxes: [
                        {
                        gridLines: {
                            drawBorder: false, // hide main y-axis line
                            display: false
                        },
                        ticks: {
                            display: false,
                            beginAtZero: true
                        }
                        }
                    ]
                    },
                    tooltips: {
                        titleFontColor: "#888",
                        bodyFontColor: "#555",
                        titleFontSize: 12,
                        bodyFontSize: 14,
                        backgroundColor: "rgba(256,256,256,0.95)",
                        displayColors: true,
                        borderColor: "rgba(220, 220, 220, 0.9)",
                        borderWidth: 2
                    }
                }
                });
            }
        },
        complete: function() {
            $('#users-activity-loader').hide();
            $('#users-activity-chart').show();
        },
        error: function(error) {
            console.log(error);
        }
    })
    /* END USERS ACTIVITY */

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
            url: "{{ url('/admin/statistical-reports/reports-list/dispatch/view') }}"+"/"+id,
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
                } else if(result.find.status == "responded") {
                    $('#view-dispatch-status').html("<span class='text-primary'><i class='mdi mdi-chat-alert'></i> Not Yet Verified</span>");
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
});
</script>
<!-- ============================================ END AJAX ======================================= -->

<!-- ==================== TOASTER SCRIPT =================== -->
@if(session()->has('loginsuccess'))
<script type="text/javascript">
  var toaster = $('#toaster-alert-info');
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
      toastr.info("{{ session()->get('loginsuccess') }} {{ Auth::guard('admin')->user()->username }}!", "Logged In Succesfully!");
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
<!-- ===================== END TOASTER SCRIPT ========================== -->

<!-- TEST -->
<script>
$(document).ready(function() {
    
})
</script>



</body>
</html>

@else 
    @include('PNPadmin.includes.419')
@endif