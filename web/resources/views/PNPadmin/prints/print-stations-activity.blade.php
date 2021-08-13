@if(Auth::guard('admin')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>PRINT</title>
    @include('pnpadmin.includes.link')
    <style>
    @media print {
        @page {
            size: landscape;
        }
        i {
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
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
                <h1 class="text-dark">Stations Activity</h1>
                <h2 id="datetime"></h2>
            </div>
            <div class="card-body pb-6" style="height: 400px">
                <canvas id="stations-activity-chart"></canvas>
                <ul>
                    <li style="display: inline-block;margin-right: 1.56rem;margin-top: 1.88rem;"><i class="mdi mdi-checkbox-blank pr-2" style="color: rgb(41, 204, 151)"></i>Average Per Day</li>
                    <li style="display: inline-block;margin-right: 1.56rem;margin-top: 1.88rem;"><i class="mdi mdi-checkbox-blank pr-2" style="color: rgb(76, 132, 255)"></i>Average Per Week</li>
                    <li style="display: inline-block;margin-right: 1.56rem;margin-top: 1.88rem;"><i class="mdi mdi-checkbox-blank pr-2" style="color: rgb(255, 76, 66)"></i>Average Per Month</li>
                    <li style="display: inline-block;margin-right: 1.56rem;margin-top: 1.88rem;"><i class="mdi mdi-checkbox-blank pr-2" style="color: rgb(66, 255, 173)"></i>Average Per Year</li>
                    <li style="display: inline-block;margin-right: 1.56rem;margin-top: 1.88rem;"><i class="mdi mdi-checkbox-blank pr-2" style="color: rgb(254, 196, 0)"></i>Reports</li>
                    <li style="display: inline-block;margin-right: 1.56rem;margin-top: 1.88rem;"><i class="mdi mdi-checkbox-blank pr-2" style="color: rgb(76, 132, 255)"></i>Officers</li>
                </ul>
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
    var sachart = document.getElementById("stations-activity-chart");
    if (sachart !== null) {
    var acChart3 = new Chart(sachart, {
        // The type of chart we want to create
        type: "bar",

        // The data for our dataset
        data: {
        labels: [@foreach($stations as $row) "{{$row->station_name}}", @endforeach],
        datasets: [
            {
                label: "Average Per Day",
                backgroundColor: "rgb(41, 204, 151)",
                borderColor: "rgba(41, 204, 151,0)",
                data: {{$ave_day}},
                pointBackgroundColor: "rgba(41, 204, 151,0)",
                pointHoverBackgroundColor: "rgba(41, 204, 151,1)",
                pointHoverRadius: 3,
                pointHitRadius: 30
            },
            {
                label: "Average Per Week",
                backgroundColor: "rgb(76, 132, 255)",
                borderColor: "rgba(76, 132, 255,0)",
                data: {{$ave_week}},
                pointBackgroundColor: "rgba(76, 132, 255,0)",
                pointHoverBackgroundColor: "rgba(76, 132, 255,1)",
                pointHoverRadius: 3,
                pointHitRadius: 30
            },
            {
                label: "Average Per Month",
                backgroundColor: "rgb(255, 76, 66)",
                borderColor: "rgba(255, 76, 66,0)",
                data: {{$ave_month}},
                pointBackgroundColor: "rgba(255, 76, 66,0)",
                pointHoverBackgroundColor: "rgba(255, 76, 66,1)",
                pointHoverRadius: 3,
                pointHitRadius: 30
            },
            {
                label: "Average Per Year",
                backgroundColor: "rgb(66, 255, 173)",
                borderColor: "rgba(66, 255, 173,0)",
                data: {{$ave_year}},
                pointBackgroundColor: "rgba(66, 255, 173,0)",
                pointHoverBackgroundColor: "rgba(66, 255, 173,1)",
                pointHoverRadius: 3,
                pointHitRadius: 30
            },
            {
                label: "Reports",
                backgroundColor: "rgb(254, 196, 0)",
                borderColor: "rgba(254, 196, 0,0)",
                data: {{$station_report_count}},
                pointBackgroundColor: "rgba(254, 196, 0,0)",
                pointHoverBackgroundColor: "rgba(254, 196, 0,1)",
                pointHoverRadius: 3,
                pointHitRadius: 30
            },
            {
                label: "Officers",
                backgroundColor: "rgb(76, 132, 255)",
                borderColor: "rgba(76, 132, 255,0)",
                data: {{$station_officer_count}},
                pointBackgroundColor: "rgba(76, 132, 255,0)",
                pointHoverBackgroundColor: "rgba(76, 132, 255,1)",
                pointHoverRadius: 3,
                pointHitRadius: 30
            },
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
                    display: false
                },
                ticks: {
                    autoSkip: false,
                    maxRotation: 90,
                }
            }
            ],
            yAxes: [
            {
                gridLines: {
                display: true
                },
                ticks: {
                    beginAtZero: true,
                    callback: function(tick, index, array) {
                        return (index % 2) ? "" : tick;
                    },
                /* stepSize: 50, */
                    fontColor: "#8a909d",
                    fontFamily: "Roboto, sans-serif",
                    autoSkip: false,
                    maxRotation: 0,
                    minRotation: 0,
                }
            }
            ]
        },
        tooltips: {}
        }
    });
        document.getElementById("customLegend").innerHTML = acChart3.generateLegend();
    }
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
	setInterval(printpage, 2500)
    $('#datetime').html(moment().format('dddd, LL, LTS'));
})
</script>
<!-- END PRINT -->
</body>
</html>

            
@else
    @include('PNPadmin.includes.419')
@endif