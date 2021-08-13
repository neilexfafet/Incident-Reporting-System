@if(Auth::guard('admin')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>PRINT</title>
    @include('pnpadmin.includes.link')
    <style>
    @media print {
        body {
            min-height: 100%;
            max-width: 100%;
            height: auto!important;
            width: auto!important;
        }
    }
    </style>
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">

<script>
    NProgress.start();
</script>

<div class="row">
    <div class="col-12">
        <div class="card card-default">
            <div class="card-header justify-content-between py-6">
                <h1 class="text-dark">Incident Reports Activity</h1>
                <h2 id="datetime"></h2>
            </div>
            <div class="row col-lg-12 d-flex justify-content-between pb-4" id="ave">
                <div class="text-center pl-4">Average Incident Reports Per Day <h4>{{round($reports_ave_day, 2)}}</h4></div>
                <div class="text-center">Average Incident Reports Per Week <h4>{{round($reports_ave_week, 2)}}</h4></div>
                <div class="text-center">Average Incident Reports Per Month <h4>{{round($reports_ave_month, 2)}}</h4></div>
                <div class="text-center">Average Incident Reports Per Year <h4>{{round($reports_ave_year, 2)}}</h4></div>
            </div>
            <ul class="nav nav-tabs nav-style-border justify-content-between justify-content-xl-start" role="tablist">
                @foreach($incidents as $row)
                    @if(count($reports) != 0)
                    <li class="nav-item">
                        <a class="nav-link pb-md-0" data-toggle="tab" data-id="{{$row->id}}" style="pointer-events: none;" href="javascript:void(0);" role="tab" aria-controls="" aria-selected="false">
                            <span class="type-name">{{$row->type}}</span>
                            <h4 class="d-inline-block mr-2 mb-3">{{count($reports->where('incident_id', $row->id))}}</h4>
                            <i class="mdi mdi-chart-bar-stacked"></i> <span class="text-info">{{round((count($reports->where('incident_id', $row->id)) / count($reports))*100)}}%</span>
                        </a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link pb-md-0" data-toggle="tab" data-id="{{$row->id}}" style="pointer-events: none;" href="javascript:void(0);" role="tab" aria-controls="" aria-selected="false">
                            <span class="type-name">{{$row->type}}</span>
                            <h4 class="d-inline-block mr-2 mb-3">{{count($reports->where('incident_id', $row->id))}}</h4>
                            <i class="mdi mdi-chart-bar-stacked"></i> <span class="text-info">0%</span>
                        </a>
                    </li>
                    @endif
                @endforeach
            </ul>
            <div class="card-body">
                <canvas id="reports-activity-chart" class="chartjs" style="min-height: 400px;"></canvas>
            </div>
            <div class="card-body">
                <canvas id="reports-activity-barchart" class="chartjs" style="min-height: 500px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- MODAL LOADER -->
<div id="print-loader" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true" data-backdrop="static" style="overflow: hidden">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="card-body d-flex align-items-center justify-content-center" style="height: 300px">
            <div class="sk-fading-circle" style="height: 100px;width: 100px">
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
</div>
<!-- END MODAL LOADER -->

@include('pnpadmin.includes.script')

<script>
$('#print-loader').modal('show');
</script>

<!-- CHART -->
<script>
$(document).ready(function() {
    /* LINE CHART */
    var activity = document.getElementById("reports-activity-chart");
    if (activity !== null) {
        var activityData = [
        {
            /* reports: result.all, */
            verified: [@foreach($incidents as $row) {{count($reports->where('incident_id', $row->id)->where('status','verified'))}}, @endforeach],
            bogus: [@foreach($incidents as $row) {{count($reports->where('incident_id', $row->id)->where('status','bogus'))}}, @endforeach],
        }
        ];

        var config = {
            // The type of chart we want to create
            type: "line",
            // The data for our dataset
            data: {
                labels: [@foreach($incidents as $row) "{{$row->type}}", @endforeach],
                datasets: [
                    {
                        label: "Verified",
                        backgroundColor: "transparent",
                        borderColor: "#29cc97",
                        data: activityData[0].verified,
                        lineTension: 0,
                        pointRadius: 5,
                        pointBackgroundColor: "rgba(255,255,255,1)",
                        pointHoverBackgroundColor: "rgba(255,255,255,1)",
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBorderWidth: 1
                    },
                    {
                        label: "Fraud",
                        backgroundColor: "transparent",
                        borderColor: "#fe5461",
                        data: activityData[0].bogus,
                        lineTension: 0,
                        borderDash: [10, 5],
                        borderWidth: 1,
                        pointRadius: 5,
                        pointBackgroundColor: "rgba(255,255,255,1)",
                        pointHoverBackgroundColor: "rgba(255,255,255,1)",
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBorderWidth: 1
                    }
                ]
            },
        // Configuration options go here
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
                            display: false,
                        },
                        ticks: {
                            fontColor: "#8a909d", // this here
                            autoSkip: false,
                            maxRotation: 0,
                            minRotation: 0,
                            beginAtZero: true,
                            
                        },
                        }
                    ],
                    yAxes: [
                        {
                            gridLines: {
                                fontColor: "#8a909d",
                                fontFamily: "Roboto, sans-serif",
                                display: true,
                                color: "#eee",
                                zeroLineColor: "#eee"
                            },
                            ticks: {
                                beginAtZero: true,
                                callback: function(tick, index, array) {
                                    return (index % 2) ? "" : tick;
                                },
                                /* stepSize:  */
                                fontColor: "#8a909d",
                                fontFamily: "Roboto, sans-serif",
                            }
                        }
                    ]
                },
                tooltips: {
                    mode: "index",
                    intersect: false,
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
        };
        var ctx = document.getElementById("reports-activity-chart").getContext("2d");
        var myLine = new Chart(ctx, config);
    }
    /* END LINE CHART */

    /* BAR CHART */
    var cUser = document.getElementById("reports-activity-barchart");
        if (cUser !== null) {
            var myUChart = new Chart(cUser, {
                type: "bar",
                data: {
                    labels: [@foreach($incidents as $row) "{{$row->type}}", @endforeach],
                        datasets: [
                        {
                            label: "Reports",
                            data: [@foreach($incidents as $row) {{count($reports->where('incident_id', $row->id))}}, @endforeach],
                            backgroundColor: "#4c84ff"
                        }
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
                                drawBorder: true,
                                display: false,
                            },
                            ticks: {
                                fontColor: "#8a909d",
                                fontFamily: "Roboto, sans-serif",
                                display: true, // hide main x-axis line
                                beginAtZero: true,
                                autoSkip: false,
                                maxRotation: 0,
                                minRotation: 0,
                            },
                            barPercentage: 1.8,
                            categoryPercentage: 0.2,
                        }
                    ],
                    yAxes: [
                        {
                            gridLines: {
                                drawBorder: true,
                                display: true,
                                color: "#eee",
                                zeroLineColor: "#eee"
                            },
                            ticks: {
                                fontColor: "#8a909d",
                                fontFamily: "Roboto, sans-serif",
                                display: true,
                                beginAtZero: true,
                            }
                        }
                    ]
                },
                tooltips: {
                    mode: "index",
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
    /* END BAR CHART */
})
</script>
<!-- END CHART -->


<!-- PRINT -->
<script>
$(document).ready(function() {
	function printpage() {
        $('#print-loader').modal('hide');
		window.print();
		window.close();
	}
	setInterval(printpage, 2500);
    $('#datetime').html(moment().format('dddd, LL, LTS'));
})
</script>
<!-- END PRINT -->
</body>
</html>

            
@else
    @include('PNPadmin.includes.419')
@endif