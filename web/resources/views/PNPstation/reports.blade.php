@if(Auth::guard('station')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>Station | Statistical Reports</title>
    @include('pnpstation.includes.link')
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body" onload=display_ct();>
  
<script>
    NProgress.start();
</script>

<button id="scroll-top-button" class="btn btn-primary" style="display: none;"><i class="mdi mdi-arrow-up-bold-outline"></i></button>

    <div class="wrapper">
        @include('pnpstation.includes.sidebar')
        <div class="page-wrapper">
            @include('pnpstation.includes.header')
            <div class="content-wrapper">
                <div class="content">			
                    <div class="breadcrumb-wrapper">
                        <h1>Reports</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0">
                                <li class="breadcrumb-item">
                                    <a href="javscript:void(0);">
                                        <span class="mdi mdi-home"></span>                
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a id="scroll-to-reports-activity" href="javscript:void(0);">Incident Reports Activity</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a id="scroll-to-map-search" href="javscript:void(0);">Map Search</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a id="scroll-to-reports-list" href="javscript:void(0);">Reports List</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a id="scroll-to-officers-list" href="javscript:void(0);">Officers List</a>
                                </li>
                            </ol>
                        </nav>
                    </div>			 
                    
                    <!--CONTENT SECTION-->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-default" id="reports-activity">
                                <ul class="nav nav-tabs ml-4 mt-4 nav-style-border pl-0 justify-content-between justify-content-xl-start" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#station" role="tab" aria-controls="station" aria-selected="true">Station Reports</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="false">All Reports</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent3">
                                    <div class="tab-pane pt-3 fade show active" id="station" role="tabpanel">
                                    @if($stationreports != 0)
                                        <div class="row no-gutters">
                                            <div class="col-xl-8">
                                                <div class="border-right">
                                                    <form id="reports-activity-search-station">
                                                    @csrf
                                                    <div class="card-header justify-content-between pt-5 pb-2">
                                                        <h2>Incident Reports Activity</h2>
                                                        <div class="form-group">
                                                            <input id="reports-activity-from-station" name="from" type="date" style="display: none;" required>
                                                            <input id="reports-activity-to-station" name="to" class="form-control" type="date" style="display: none;" required>
                                                            <div class="btn-group d-flex justify-content-end">
                                                                <button type="submit" class="btn btn-outline-primary btn-sm"><i class="mdi mdi-magnify"></i> Search Date</button>
                                                                <a id="reports-activity-display-station" href="javascript:void(0);" class="btn btn-primary btn-sm"><i class="mdi mdi-format-columns"></i> View All</a>
                                                            </div>
                                                            <div id="reports-activity-date-range-station" class="date-range-report"><span>Select Date</span></div>
                                                        </div>
                                                    </div>
                                                    </form>
                                                    <div id="reports-activity-loader-station">@include('PNPstation.includes.loader')</div>
                                                    <div id="reports-activity-form-display-station" style="display: none;">
                                                        <ul id="reports-activity-incident-type-station" class="nav nav-tabs nav-style-border justify-content-between justify-content-xl-start" role="tablist"></ul>
                                                        <div class="card-body">
                                                            <canvas id="reports-activity-chart-station" class="chartjs" style="min-height: 400px;"></canvas>
                                                        </div>
                                                        <div class="p-4">
                                                            <a href="{{url('station/print/reports-activity')}}" target="_blank" class="btn btn-sm btn-secondary"><i class="mdi mdi-printer"></i> Print or Save</a>
                                                        </div>
                                                    </div>
                                                    <div id="reports-activity-form-display-search-station" style="display: none;">
                                                        <ul id="reports-activity-incident-type-search-station" class="nav nav-tabs nav-style-border justify-content-between justify-content-xl-start border-bottom" role="tablist">
                                                        </ul>
                                                        <div class="card-body">
                                                            <canvas id="reports-activity-chart-search-station" class="chartjs" style="min-height: 400px;"></canvas>
                                                        </div>
                                                        <div class="p-4">
                                                            <form action="{{url('station/print/reports-activity/search')}}" method="POST" target="_blank">
                                                            @csrf
                                                            <input name="from" type="hidden" id="reports-activity-from-station-print">
                                                            <input name="to" type="hidden" id="reports-activity-to-station-print">
                                                                <button type="submit" class="btn btn-sm btn-secondary"><i class="mdi mdi-printer"></i> Print or Save</a>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div data-scroll-height="642">
                                                    <div class="card-header row pt-5 mx-0 px-0">
                                                        <div class="row col-lg-12 d-flex justify-content-center mb-3">
                                                            <p class="my-2">Average Incident Reports Per</p>
                                                        </div>  
                                                        <div class="row col-lg-12">
                                                            <span class="col-lg-3 text-center">Day <h4 id="reports-activity-ave-per-day-station"></h4></span>
                                                            <span class="col-lg-3 text-center">Week <h4 id="reports-activity-ave-per-week-station"></h4></span>
                                                            <span class="col-lg-3 text-center">Month <h4 id="reports-activity-ave-per-month-station"></h4></span>
                                                            <span class="col-lg-3 text-center">Year <h4 id="reports-activity-ave-per-year-station"></h4></span>
                                                        </div>
                                                    </div>
                                                    <div class="border-bottom"></div>
                                                    <div id="reports-activity-loader-barchart-station">@include('PNPstation.includes.loader')</div>
                                                    <div class="card-body">
                                                        <canvas id="reports-activity-barchart-station" class="chartjs" style="min-height: 500px;"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-lg-12 text-center py-6">
                                            <span>No Reports Received Yet</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="tab-pane pt-3 fade" id="all" role="tabpanel" >
                                        @if(count(App\Report::all()) != 0)
                                        <div class="row no-gutters">
                                            <div class="col-xl-8">
                                                <div class="border-right">
                                                    <form id="reports-activity-search">
                                                    @csrf
                                                    <div class="card-header justify-content-between pt-5 pb-2">
                                                        <h2>Incident Reports Activity</h2>
                                                        <div class="form-group">
                                                            <input id="reports-activity-from" name="from" type="date" style="display: none;" required>
                                                            <input id="reports-activity-to" name="to" class="form-control" type="date" style="display: none;" required>
                                                            <div class="btn-group d-flex justify-content-end">
                                                                <button type="submit" class="btn btn-outline-primary btn-sm"><i class="mdi mdi-magnify"></i> Search Date</button>
                                                                <a id="reports-activity-display" href="javascript:void(0);" class="btn btn-primary btn-sm"><i class="mdi mdi-format-columns"></i> View All</a>
                                                            </div>
                                                            <div id="reports-activity-date-range" class="date-range-report"><span>Select Date</span></div>
                                                        </div>
                                                    </div>
                                                    </form>
                                                    <div id="reports-activity-loader">@include('PNPstation.includes.loader')</div>
                                                    <div id="reports-activity-form-display" style="display: none;">
                                                        <ul id="reports-activity-incident-type" class="nav nav-tabs nav-style-border justify-content-between justify-content-xl-start" role="tablist"></ul>
                                                        <div class="card-body">
                                                            <canvas id="reports-activity-chart" class="chartjs" style="min-height: 400px;"></canvas>
                                                        </div>
                                                        <div class="p-4">
                                                            <a href="{{url('station/print/reports-activity/all')}}" target="_blank" class="btn btn-sm btn-secondary"><i class="mdi mdi-printer"></i> Print or Save</a>
                                                        </div>
                                                    </div>
                                                    <div id="reports-activity-form-display-search" style="display: none;">
                                                        <ul id="reports-activity-incident-type-search" class="nav nav-tabs nav-style-border justify-content-between justify-content-xl-start border-bottom" role="tablist">
                                                        </ul>
                                                        <div class="card-body">
                                                            <canvas id="reports-activity-chart-search" class="chartjs" style="min-height: 400px;"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div data-scroll-height="642">
                                                    <div class="card-header row pt-5 mx-0 px-0">
                                                        <div class="row col-lg-12 d-flex justify-content-center mb-3">
                                                            <p class="my-2">Average Incident Reports Per</p>
                                                        </div>  
                                                        <div class="row col-lg-12">
                                                            <span class="col-lg-3 text-center">Day <h4 id="reports-activity-ave-per-day"></h4></span>
                                                            <span class="col-lg-3 text-center">Week <h4 id="reports-activity-ave-per-week"></h4></span>
                                                            <span class="col-lg-3 text-center">Month <h4 id="reports-activity-ave-per-month"></h4></span>
                                                            <span class="col-lg-3 text-center">Year <h4 id="reports-activity-ave-per-year"></h4></span>
                                                        </div>
                                                    </div>
                                                    <div class="border-bottom"></div>
                                                    <div id="reports-activity-loader-barchart">@include('PNPstation.includes.loader')</div>
                                                    <div class="card-body">
                                                        <canvas id="reports-activity-barchart" class="chartjs" style="min-height: 500px;"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-lg-12 text-center py-6">
                                            <span>No Reports Yet</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card card-default" id="map-search">
                                <div class="card-header card-header-border-bottom">
                                    <h2>Search in Map to View Reports</h2>
                                </div>
                                <div class="card-body">
                                    <div id="map-add" class="map-container"></div>
                                    <form id="map-search-form">
                                    @csrf
                                        <div class="row mt-4 d-flex justify-content-end">
                                            <input id="map-search-location" class="form-control col-md-3" placeholder="Search Location" type="text">
                                            <input name="location_lat" id="map-search-location-lat" class="form-control col-md-3" placeholder="Lat" type="text">
                                            <input name="location_lng" id="map-search-location-lng" class="form-control col-md-3" placeholder="Lng" type="text">
                                            @if($reports != 0)
                                            <button type="submit" class="btn btn-outline-primary"><i class="mdi mdi-magnify"></i> Search Reports</button>
                                            @else
                                            <button class="btn btn-outline-primary" disabled>No Reports Recorded Yet</button>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card card-default" id="reports-list">
                                <ul class="nav nav-tabs ml-4 mt-4 nav-style-border pl-0 justify-content-between justify-content-xl-start" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#station2" role="tab" aria-controls="station2" aria-selected="true">Station Reports</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#all2" role="tab" aria-controls="all2" aria-selected="false">All Reports</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent3">
                                    <div class="tab-pane pt-3 fade show active" id="station2" role="tabpanel">
                                        <div class="card-header card-header-border-bottom justify-content-between">
                                            <h2>Incident Reports List</h2>
                                            <form id="reports-list-datepicker-search-form-station">
                                            @csrf
                                                <div class="row">
                                                    <input id="reports-list-search-from-station" name="from" type="date" style="display: none;" required>
                                                    <input id="reports-list-search-to-station" name="to" class="form-control" type="date" style="display: none;" required>
                                                    <div id="reports-list-search-date-range-station" class="date-range-report mr-2"><span>Select Date</span></div>
                                                    <div class="btn-group d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-outline-primary btn-sm"><i class="mdi mdi-magnify"></i> Search Date</button>
                                                        <a id="reports-list-display-station" href="javascript:void(0);" class="btn btn-primary btn-sm"><i class="mdi mdi-format-columns"></i> View All</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="card-body">
                                            <div id="reports-list-loader-station" style="display: none;">@include('PNPstation.includes.loader')</div>
                                            <div id="reports-list-table-station" class="basic-data-table" style="overflow: hidden;">
                                                <div class="btn-group pb-4">
                                                    <button class="btn btn-sm btn-secondary">
                                                        <i class="mdi mdi-content-save"></i> Save</button>
                                                    <button class="btn btn-sm btn-secondary">
                                                        <i class="mdi mdi-printer"></i> Print</button>
                                                </div>
                                                <table id="reports-list-data-table-station" class="table table-hover dt-responsive" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Report ID</th>
                                                            <th>Report</th>
                                                            <th>Outline</th>
                                                            <th>Location</th>
                                                            <th>Respondent</th>
                                                            <th>Date</th>
                                                            <th width="10%">Status</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div id="reports-list-search-table-station" class="basic-data-table" style="overflow: hidden;display: none;">
                                                <div class="d-flex justify-content-between">
                                                    <h4>Results: <span class="text-primary" id="reports-list-search-table-count-station">0</span> report/s</h4>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-secondary">
                                                            <i class="mdi mdi-content-save"></i> Save</button>
                                                        <button class="btn btn-sm btn-secondary">
                                                            <i class="mdi mdi-printer"></i> Print</button>
                                                    </div>
                                                </div>
                                                <table id="basic-data-table-station" class="table table-hover dt-responsive" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Report ID</th>
                                                            <th>Report</th>
                                                            <th>Outline</th>
                                                            <th>Location</th>
                                                            <th>Respondent</th>
                                                            <th>Date</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="reports-list-tbody-station"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane pt-3 fade" id="all2" role="tabpanel" >
                                        <div class="card-header card-header-border-bottom justify-content-between">
                                            <h2>Incident Reports List</h2>
                                            <form id="reports-list-datepicker-search-form">
                                            @csrf
                                                <div class="row">
                                                    <input id="reports-list-search-from" name="from" type="date" style="display: none;" required>
                                                    <input id="reports-list-search-to" name="to" class="form-control" type="date" style="display: none;" required>
                                                    <div id="reports-list-search-date-range" class="date-range-report mr-2"><span>Select Date</span></div>
                                                    <div class="btn-group d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-outline-primary btn-sm"><i class="mdi mdi-magnify"></i> Search Date</button>
                                                        <a id="reports-list-display" href="javascript:void(0);" class="btn btn-primary btn-sm"><i class="mdi mdi-format-columns"></i> View All</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="card-body">
                                            <div id="reports-list-loader" style="display: none;">@include('PNPstation.includes.loader')</div>
                                            <div id="reports-list-table" class="basic-data-table" style="overflow: hidden;">
                                                <div class="btn-group pb-4">
                                                    <button class="btn btn-sm btn-secondary">
                                                        <i class="mdi mdi-content-save"></i> Save</button>
                                                    <button class="btn btn-sm btn-secondary">
                                                        <i class="mdi mdi-printer"></i> Print</button>
                                                </div>
                                                <table id="reports-list-data-table" class="table table-hover dt-responsive" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Report ID</th>
                                                            <th>Report</th>
                                                            <th>Outline</th>
                                                            <th>Location</th>
                                                            <th>Respondent</th>
                                                            <th>Date</th>
                                                            <th width="10%">Status</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div id="reports-list-search-table" class="basic-data-table" style="overflow: hidden;display: none;">
                                                <div class="d-flex justify-content-between">
                                                    <h4>Results: <span class="text-primary" id="reports-list-search-table-count">0</span> report/s</h4>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-secondary">
                                                            <i class="mdi mdi-content-save"></i> Save</button>
                                                        <button class="btn btn-sm btn-secondary">
                                                            <i class="mdi mdi-printer"></i> Print</button>
                                                    </div>
                                                </div>
                                                <table id="basic-data-table" class="table table-hover dt-responsive" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Report ID</th>
                                                            <th>Report</th>
                                                            <th>Outline</th>
                                                            <th>Location</th>
                                                            <th>Respondent</th>
                                                            <th>Date</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="reports-list-tbody"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-default" id="officers-list">
                                <div class="card-header card-header-border-bottom justify-content-between">
                                    <h2>Police Officers List</h2>
                                </div>
                                <div class="card-body">
                                    <div id="officers-list-loader" style="display: none;">@include('PNPstation.includes.loader')</div>
                                    <div id="officers-list-table" class="basic-data-table" style="overflow: hidden;">
                                        <div class="btn-group pb-4">
                                            <button class="btn btn-sm btn-secondary">
                                                <i class="mdi mdi-content-save"></i> Save</button>
                                            <button class="btn btn-sm btn-secondary">
                                                <i class="mdi mdi-printer"></i> Print</button>
                                        </div>
                                        <table id="officers-list-data-table" class="table table-hover dt-responsive" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Rank</th>
                                                    <th>PNP ID #</th>
                                                    <th>Badge #</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


    <!-- SEARCH MAP MODAL -->
        <div class="modal fade" id="map-search-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormVIEW" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div id="map-search-loader" class="col-sm-12" style="display: none;">@include('PNPstation.includes.loader')</div>
                <div class="modal-content" id="map-search-form-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalFormEDIT">Reports List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Report #</th>
                                    <th>Incident</th>
                                    <th>Location</th>
                                <tr>
                            </thead>
                            <tbody id="map-search-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <!-- END SEARCH MAP MODAL -->

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
                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-danger" data-dismiss="modal">Close</a>
                        <a id="view-dispatch-print" href="javascript:void(0);" target="_blank" class="btn btn-secondary"><i class="mdi mdi-printer"></i> Print or Save</a>
                    </div>
                </div>
            </div>
        </div>
    <!-- END VIEW DISPATCH -->

    <!-- VIEW OFFICER MODAL -->
        <div class="modal fade" id="view-officer" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormVIEW" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div id="view-officer-loader" class="col-sm-12">@include('PNPstation.includes.loader')</div>
                <div class="modal-content" id="view-officer-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalFormEDIT">Police Officer Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row no-gutters">
                            <div class="col-md-6">
                                <div class="profile-content-left px-4">
                                    <div class="card text-center widget-profile px-0 py-4 border-0">
                                        <div class="card-img mx-auto d-block rounded" style="height: 150px; width: 150px;">
                                            <img id="view-officer-image" class="img-fluid" alt="user image">
                                        </div>
                                        <div class="card-body">
                                            <h4 class="py-2 text-dark"><span id="view-officer-name"></span></h4>
                                            <p><span id="view-officer-rank"></span></p>
                                        </div>
                                        <hr class="w-100">
                                        <div class="form-group row">
                                            <div class="col-sm-6 contact-info">
                                                <p class="text-dark font-weight-medium pt-4 mb-2">PNP ID #</p>
                                                <p><span id="view-officer-idno"></span></p>
                                            </div>
                                            <div class="col-sm-6 contact-info">
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Badge #</p>
                                                <p><span id="view-officer-badgeno"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="profile-content-right">
                                    <div class="col-md-12">
                                        <div class="card-body px-4">
                                            <h4 class="text-dark mb-1">Contact Details</h4>
                                                <hr class="w-100">
                                            <p class="text-dark font-weight-medium pt-4 mb-2">E-Mail</p>
                                            <p><span id="view-officer-email"></span></p>
                                            <p class="text-dark font-weight-medium pt-4 mb-2">Address</p>
                                            <p><span id="view-officer-address"></span></p>
                                            <p class="text-dark font-weight-medium pt-4 mb-2">Contact Number</p>
                                            <p><span id="view-officer-contactno"></span></p>
                                            <p class="text-dark font-weight-medium pt-4 mb-2">Gender</p>
                                            <p><span id="view-officer-gender"></span</p>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <p class="text-dark font-weight-medium pt-4 mb-2">Date of Birth</p>
                                                    <p><span id="view-officer-birthday"></span></p>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="text-dark font-weight-medium pt-4 mb-2">Age</p>
                                                    <p><span id="view-officer-age"></span> Years Old</p>
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
    <!-- END VIEW OFFICER MODAL -->


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

<!-- ===================================DATA TABLE SCRIPT==================================== -->
<script src={{ asset("assets/plugins/data-tables/jquery.datatables.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.bootstrap4.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.responsive.min.js") }}></script>

<script>
    $(document).ready(function() {
        $('#basic-data-table').DataTable({
            "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
            ordering: false,
            searching: false,
            paging: false,
            ordering: false,
            lengthChange: false,
            info: false,
        });
        $('#basic-data-table-station').DataTable({
            "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
            ordering: false,
            searching: false,
            paging: false,
            ordering: false,
            lengthChange: false,
            info: false,
        });
        $('#reports-list-data-table').DataTable({
            "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
            ordering: false,
            processing: true,
            serverSide: true,
            language: {
                emptyTable: 'No Reports Recorded Yet.',
                zeroRecords: 'No Reports Found. Try Searching another keyword.',
            },
            ajax: {
                url: "{{url('station/statistical-reports/reports-list')}}",
            },
            columns: [
                { data: 'dispatch.dispatch_id', render: function(data, type, row) {
                    return '<a href="javascript:void(0);" data-id='+row.dispatch["id"]+' data-toggle="modal" data-target="#view-dispatch">'+data+'</a>';
                } },
                { data: 'get_incident.type' },
                { data: 'description' },
                { data: 'location' },
                { data: 'get_station.station_name' },
                { data: 'created_at', render: function(data, type, full, meta) {
                    var created_at_date = moment(data).format('LL');
                    var created_at_time = moment(data).format('LT');
                    return created_at_date + ' at ' + created_at_time;
                } },
                { data: 'status', render: function(data, type, full, meta) {
                    if(data == "verified") {
                        return '<span class="text-center text-success"><i class="mdi mdi-check"></i> Verified</span>';
                    } else {
                        return '<span class="text-center text-danger"><i class="mdi mdi-close"></i> FRAUD</span>';
                    }
                } },
            ],
        });
        $('#reports-list-data-table-station').DataTable({
            "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
            ordering: false,
            processing: true,
            serverSide: true,
            language: {
                emptyTable: 'No Reports Recorded Yet.',
                zeroRecords: 'No Reports Found. Try Searching another keyword.',
            },
            ajax: {
                url: "{{url('station/statistical-reports/reports-list/station')}}",
            },
            columns: [
                { data: 'dispatch.dispatch_id', render: function(data, type, row) {
                    return '<a href="javascript:void(0);" data-id='+row.dispatch["id"]+' data-toggle="modal" data-target="#view-dispatch">'+data+'</a>';
                } },
                { data: 'get_incident.type' },
                { data: 'description' },
                { data: 'location' },
                { data: 'get_station.station_name' },
                { data: 'created_at', render: function(data, type, full, meta) {
                    var created_at_date = moment(data).format('LL');
                    var created_at_time = moment(data).format('LT');
                    return created_at_date + ' at ' + created_at_time;
                } },
                { data: 'status', render: function(data, type, full, meta) {
                    if(data == "verified") {
                        return '<span class="text-center text-success"><i class="mdi mdi-check"></i> Verified</span>';
                    } else {
                        return '<span class="text-center text-danger"><i class="mdi mdi-close"></i> Bogus</span>';
                    }
                } },
            ],
        });
        $('#officers-list-data-table').DataTable({
            "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
            ordering: true,
            processing: true,
            serverSide: true,
            language: {
                emptyTable: 'No Users.',
                zeroRecords: 'No Users Found. Try Searching another keyword.',
            },
            ajax: {
                url: "{{url('station/statistical-reports/officers-list')}}",
            },
            columns: [
                { data: 'last_name', render: function(data, type, row) {
                    return '<a href="javascript:void(0);" data-id='+row.id+' data-toggle="modal" data-target="#view-officer">' + data + ', ' + row["first_name"] + ' ' + row["middle_name"] + '</a>';
                } },
                { data: 'get_rank.name', render: function(data, type, row) {
                    return data+' ('+row.get_rank["abbreviation"]+')';
                } },
                { data: 'id_no' },
                { data: 'badge_no' },
                { data: 'status', render: function(data) {
                    if(data == "available") {
                        return '<span class="text-success">Available</span>';
                    } else {
                        return '<span class="text-danger">Dispatched</span>';
                    }
                } },
            ],
        });
    });
</script>
<!-- ========================================== END DATA TABLE ===================================== -->

<!-- ==================================== SCROLLS ============================= -->
<script>
$(document).ready(function() {
    /* SCROLL TO TOP */
    $(window).scroll(function() {
        if ($(window).scrollTop() > 500) {
            $('#scroll-top-button').show();
        } else {
            $('#scroll-top-button').hide();
        }
    });
    $('#scroll-top-button').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop:0}, 'slow');
    });
    /* SCROLL TO TOP */

    /* SCROLL BREADCRUMBS */
    $('#scroll-to-reports-activity').click(function() {
        $('html,body').animate({
            scrollTop: $("#reports-activity").offset().top - 100},
        'slow');
    });
    $('#scroll-to-report-logs').click(function() {
        $('html,body').animate({
            scrollTop: $("#report-logs").offset().top - 100},
        'slow');
    });
    $('#scroll-to-map-search').click(function() {
        $('html,body').animate({
            scrollTop: $("#map-search").offset().top - 100},
        'slow');
    });
    $('#scroll-to-reports-list').click(function() {
        $('html,body').animate({
            scrollTop: $("#reports-list").offset().top - 100},
        'slow');
    });
    $('#scroll-to-officers-list').click(function() {
        $('html,body').animate({
            scrollTop: $("#officers-list").offset().top - 100},
        'slow');
    });
    /* END SCROLL BREADCRUMBS */
})
</script>
<!-- ==================================== SCROLLS ============================= -->

<!-- ================================== AJAX =================================================== -->
<script>
$(document).ready(function() {
    /* ============ REPORTS ACTIVITY =============== */
    $('#reports-activity-display').click(function() {
        $('#reports-activity-form-display').show();
        $('#reports-activity-form-display-search').hide();
    });
    $('#reports-activity-display-station').click(function() {
        $('#reports-activity-form-display-station').show();
        $('#reports-activity-form-display-search-station').hide();
    });
    /* STATION */
    $.ajax({
        type: "GET",
        url: "{{url('station/statistical-reports/report-activity/station')}}",
        dataType: "JSON",
        success: function(result) {
            $('#reports-activity-incident-type-station').html(result.view);
            $('#reports-activity-ave-per-day-station').html(Math.round(result.reports_ave_day));
            $('#reports-activity-ave-per-week-station').html(Math.round(result.reports_ave_week));
            $('#reports-activity-ave-per-month-station').html(Math.round(result.reports_ave_month));
            $('#reports-activity-ave-per-year-station').html(Math.round(result.reports_ave_year));

            /* INCIDENT REPORTS ACTIVIY */
            var activity = document.getElementById("reports-activity-chart-station");
            if (activity !== null) {
                var activityData = [
                {
                    /* reports: result.all, */
                    verified: result.verified,
                    bogus: result.bogus,
                }
                ];

                var config = {
                    // The type of chart we want to create
                    type: "line",
                    // The data for our dataset
                    data: {
                        labels: result.view2,
                        datasets: [
                            /* {
                                label: "Reports",
                                backgroundColor: "transparent",
                                borderColor: "#fec400",
                                data: activityData[0].reports,
                                lineTension: 0,
                                pointRadius: 5,
                                pointBackgroundColor: "rgba(255,255,255,1)",
                                pointHoverBackgroundColor: "rgba(255,255,255,1)",
                                pointBorderWidth: 2,
                                pointHoverRadius: 7,
                                pointHoverBorderWidth: 1
                            }, */
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
                                label: "Bogus",
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
                                    maxRotation: 90,
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
                var ctx = document.getElementById("reports-activity-chart-station").getContext("2d");
                var myLine = new Chart(ctx, config);
            }
            /* INCIDENT REPORTS ACTIVIY */

            /* AVE INCIDENT REPORTS */
            var cUser = document.getElementById("reports-activity-barchart-station");
            if (cUser !== null) {
                var myUChart = new Chart(cUser, {
                    type: "bar",
                    data: {
                        labels: result.view2,
                            datasets: [
                            {
                                label: "Reports",
                                data: result.all,
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
                                    maxRotation: 90,
                                    minRotation: 90,
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
            /* AVE INCIDENT REPORTS */
        },
        complete: function() {
            $('#reports-activity-loader-station').hide();
            $('#reports-activity-loader-barchart-station').hide();
            $('#reports-activity-form-display-station').show();
        },
        error: function(error) {
            console.log(error);
        }
    })
    /* ALL */
    $.ajax({
        type: "GET",
        url: "{{url('station/statistical-reports/report-activity')}}",
        dataType: "JSON",
        success: function(result) {
            $('#reports-activity-incident-type').html(result.view);
            $('#reports-activity-ave-per-day').html(Math.round(result.reports_ave_day));
            $('#reports-activity-ave-per-week').html(Math.round(result.reports_ave_week));
            $('#reports-activity-ave-per-month').html(Math.round(result.reports_ave_month));
            $('#reports-activity-ave-per-year').html(Math.round(result.reports_ave_year));

            /* INCIDENT REPORTS ACTIVIY */
            var activity = document.getElementById("reports-activity-chart");
            if (activity !== null) {
                var activityData = [
                {
                    /* reports: result.all, */
                    verified: result.verified,
                    bogus: result.bogus,
                }
                ];

                var config = {
                    // The type of chart we want to create
                    type: "line",
                    // The data for our dataset
                    data: {
                        labels: result.view2,
                        datasets: [
                            /* {
                                label: "Reports",
                                backgroundColor: "transparent",
                                borderColor: "#fec400",
                                data: activityData[0].reports,
                                lineTension: 0,
                                pointRadius: 5,
                                pointBackgroundColor: "rgba(255,255,255,1)",
                                pointHoverBackgroundColor: "rgba(255,255,255,1)",
                                pointBorderWidth: 2,
                                pointHoverRadius: 7,
                                pointHoverBorderWidth: 1
                            }, */
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
                                label: "Bogus",
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
                                    maxRotation: 90,
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
            /* INCIDENT REPORTS ACTIVIY */

            /* AVE INCIDENT REPORTS */
            var cUser = document.getElementById("reports-activity-barchart");
            if (cUser !== null) {
                var myUChart = new Chart(cUser, {
                    type: "bar",
                    data: {
                        labels: result.view2,
                            datasets: [
                            {
                                label: "Reports",
                                data: result.all,
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
                                    maxRotation: 90,
                                    minRotation: 90,
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
            /* AVE INCIDENT REPORTS */
        },
        complete: function() {
            $('#reports-activity-loader').hide();
            $('#reports-activity-loader-barchart').hide();
            $('#reports-activity-form-display').show();
        },
        error: function() {
            console.log(error);
        }
    })
    /* INCIDENT REPORTS SEARCH STATION */
    $('#reports-activity-search-station').on('submit', function(event) {
        event.preventDefault();

        $('#reports-activity-form-display-station').hide();
        $('#reports-activity-form-display-search-station').hide();
        $('#reports-activity-loader-station').show();
     
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: "{{ url('station/statistical-reports/report-activity/station/search-by-date') }}",
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                $('#reports-activity-incident-type-search-station').html(result.view);
                var activity = document.getElementById("reports-activity-chart-search-station");
                if (activity !== null) {
                    var activityData = [
                    {
                        /* reports: result.all, */
                        verified: result.verified,
                        bogus: result.bogus,
                    }
                    ];

                    var config = {
                        // The type of chart we want to create
                        type: "line",
                        // The data for our dataset
                        data: {
                            labels: result.view2,
                            datasets: [
                                /* {
                                    label: "Reports",
                                    backgroundColor: "transparent",
                                    borderColor: "#fec400",
                                    data: activityData[0].reports,
                                    lineTension: 0,
                                    pointRadius: 5,
                                    pointBackgroundColor: "rgba(255,255,255,1)",
                                    pointHoverBackgroundColor: "rgba(255,255,255,1)",
                                    pointBorderWidth: 2,
                                    pointHoverRadius: 7,
                                    pointHoverBorderWidth: 1
                                }, */
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
                                    label: "Bogus",
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
                                        maxRotation: 90,
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
                                            callback: function(tick, index, array) {
                                                return (index % 2) ? "" : tick;
                                            },
                                            /* stepSize:  */
                                            fontColor: "#8a909d",
                                            fontFamily: "Roboto, sans-serif",
                                            beginAtZero: true,
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
                    var ctx = document.getElementById("reports-activity-chart-search-station").getContext("2d");
                    var myLine = new Chart(ctx, config);
                }
            },
            complete: function() {
                $('#reports-activity-loader-station').hide();
                $('#reports-activity-form-display-station').hide();
                $('#reports-activity-form-display-search-station').show();
            },
            error: function(error) {
                console.log(error);
            }
        })   
    })
    /* INCIDENT REPORTS SEARCH STATION */

    /* INCIDENT REPORTS SEARCH */
    $('#reports-activity-search').on('submit', function(event) {
        event.preventDefault();

        $('#reports-activity-form-display').hide();
        $('#reports-activity-form-display-search').hide();
        $('#reports-activity-loader').show();
     
        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: "{{ url('station/statistical-reports/report-activity/search-by-date') }}",
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                $('#reports-activity-incident-type-search').html(result.view);
                var activity = document.getElementById("reports-activity-chart-search");
                if (activity !== null) {
                    var activityData = [
                    {
                        /* reports: result.all, */
                        verified: result.verified,
                        bogus: result.bogus,
                    }
                    ];

                    var config = {
                        // The type of chart we want to create
                        type: "line",
                        // The data for our dataset
                        data: {
                            labels: result.view2,
                            datasets: [
                                /* {
                                    label: "Reports",
                                    backgroundColor: "transparent",
                                    borderColor: "#fec400",
                                    data: activityData[0].reports,
                                    lineTension: 0,
                                    pointRadius: 5,
                                    pointBackgroundColor: "rgba(255,255,255,1)",
                                    pointHoverBackgroundColor: "rgba(255,255,255,1)",
                                    pointBorderWidth: 2,
                                    pointHoverRadius: 7,
                                    pointHoverBorderWidth: 1
                                }, */
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
                                    label: "Bogus",
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
                                        maxRotation: 90,
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
                                            callback: function(tick, index, array) {
                                                return (index % 2) ? "" : tick;
                                            },
                                            /* stepSize:  */
                                            fontColor: "#8a909d",
                                            fontFamily: "Roboto, sans-serif",
                                            beginAtZero: true,
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
                    var ctx = document.getElementById("reports-activity-chart-search").getContext("2d");
                    var myLine = new Chart(ctx, config);
                }
            },
            complete: function() {
                $('#reports-activity-loader').hide();
                $('#reports-activity-form-display').hide();
                $('#reports-activity-form-display-search').show();
            },
            error: function(error) {
                console.log(error);
            }
        })   
    })
    /* INCIDENT REPORTS SEARCH */
    /* ========== REPORTS ACTIVITY ========== */

    /*========  REPORTS LIST ========*/
    $('#reports-list-display').click(function() {
        $('#reports-list-table').show();
        $('#reports-list-search-table').hide();
    })
    $('#reports-list-datepicker-search-form').on('submit', function(event) {
        event.preventDefault();

        $('#reports-list-table').hide();
        $('#reports-list-search-table').hide();
        $('#reports-list-loader').show();

        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: "{{ url('station/statistical-reports/reports-list/search-by-date') }}",
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                if(result.view2) {
                    $('#reports-list-search-table-count').html(result.count2);
                    $('#reports-list-tbody').html(result.view2);
                } else {
                    $('#reports-list-search-table-count').html(result.count);
                    $('#reports-list-tbody').html(result.view);
                }
            },
            complete: function() {
                $('#reports-list-loader').hide();
                $('#reports-list-search-table').show();
            },
            error: function(error) {
                console.log(error);
            }
        })
    })
    /*======== END REPORTS LIST ========*/

    /*========  REPORTS LIST STATION ========*/
    $('#reports-list-display-station').click(function() {
        $('#reports-list-table-station').show();
        $('#reports-list-search-table-station').hide();
    })
    $('#reports-list-datepicker-search-form-station').on('submit', function(event) {
        event.preventDefault();

        $('#reports-list-table-station').hide();
        $('#reports-list-search-table-station').hide();
        $('#reports-list-loader-station').show();

        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: "{{ url('station/statistical-reports/reports-list/station/search-by-date') }}",
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                if(result.view2) {
                    $('#reports-list-search-table-count-station').html(result.count2);
                    $('#reports-list-tbody-station').html(result.view2);
                } else {
                    $('#reports-list-search-table-count-station').html(result.count);
                    $('#reports-list-tbody-station').html(result.view);
                }
            },
            complete: function() {
                $('#reports-list-loader-station').hide();
                $('#reports-list-search-table-station').show();
            },
            error: function(error) {
                console.log(error);
            }
        })
    })
    /*======== END REPORTS LIST STATION ========*/

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
                $('#view-dispatch-print').attr('href', '/station/print/report/dg1234dfg214'+id+'sdfgsdfg3214sdaf');
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
    /* ============== END VIEW DISPATCH DETAILS ============== */

    /* ============== VIEW OFFICER ============== */
    $('#view-officer').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id =  button.data('id');
        var modal = $(this);

        $('#view-officer .modal-dialog').addClass('modal-dialog-centered');
        $('#view-officer-form').hide();
        $('#view-officer-loader').show();

        $.ajax({
            type: "GET",
            url: "{{ url('/station/officers/view/') }}"+"/"+id,
            dataType: "JSON",
            success: function(data) {
                var img = data.image;
                var bday = moment(data.birthday).format('LL');
                var age = moment().diff(data.birthday, 'years');

                $('#view-officer-name').html(data.last_name+", "+data.first_name+" "+data.middle_name);
                $('#view-officer-rank').html(data.get_rank.name+" ("+data.get_rank.abbreviation+")");
                $('#view-officer-idno').html(data.id_no);
                $('#view-officer-badgeno').html(data.badge_no);
                $('#view-officer-email').html(data.email);
                $('#view-officer-address').html(data.address);
                $('#view-officer-contactno').html(data.contact_no);
                $('#view-officer-gender').html(data.gender);
                $('#view-officer-birthday').html(bday);
                $('#view-officer-age').html(age);
                if(img == "TBD") {
                    $('#view-officer-image').attr('src', '{{asset("uploads/user.jpg")}}');
                } else {
                    $('#view-officer-image').attr('src', '{{asset("/")}}'+img);
                }
            },
            complete: function() {
                $('#view-officer .modal-dialog').removeClass('modal-dialog-centered');
                $('#view-officer-loader').hide();
                $('#view-officer-form').show();
            },
            error: function(error) {
                console.log(error);
            },
        });
    })
    /* ============== END VIEW OFFICER ============== */

    /* ================ SEARCH MAP ======================== */
    var mapOptions, map, marker, searchBox,
        infoWindow = '',
        addressEl = $('#map-search-location').get(0),
        latEl = $('#map-search-location-lat').get(0),
        longEl = $('#map-search-location-lng').get(0),
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
        map.fitBounds(bounds);  // Fit to the bound
        var listener = google.maps.event.addListener(map, "idle", function() { 
            if(map.getZoom() > 15) {
                map.setZoom(15); // This function sets the zoom to 15, meaning zooms to level 15.
            };
            google.maps.event.removeListener(listener); 
        });
        //console.log( map.getZoom() );
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
    $('#map-search-form').on('submit', function(event) {
        event.preventDefault();

        $('#map-search-form-content').hide();
        $('#map-search-modal .modal-dialog').addClass('modal-dialog-centered');
        $('#map-search-loader').show();
        $('#map-search-modal').modal('show');

        $.ajax({
            type: "POST",
            url: "{{url('station/statistical-reports/map-search')}}",
            data: $('#map-search-form').serialize(),
            success: function(result) {
                $('#map-search-tbody').html(result);
            },
            complete: function() {
                $('#map-search-modal .modal-dialog').removeClass('modal-dialog-centered');
                $('#map-search-loader').hide();
                $('#map-search-form-content').show();
            },
            error: function(error) {
                console.log(error);
            }
        })
    })
    /* ================ SEARCH MAP ======================== */
})
</script>

<!-- ================================== DATEPICKER ================================== -->
<script>
    $(document).ready(function() {
        /* DATE RANGE OF REPORTS ACTIVITY STATION */
        $("#reports-activity-date-range-station span").html(moment().format("ll") + " - " + moment().format("ll"));
        $('#reports-activity-from-station').val(moment().format('YYYY-MM-DD'));
        $('#reports-activity-to-station').val(moment().format('YYYY-MM-DD'));
        $('#reports-activity-from-station-print').val(moment().format('YYYY-MM-DD'));
        $('#reports-activity-to-station-print').val(moment().format('YYYY-MM-DD'));

        $("#reports-activity-date-range-station").daterangepicker({
            maxDate: moment(),
            startDate: moment(),
            endDate: moment(),
            format: 'YYYY-MM-DD',
            autoApply: true,
            opens: 'left',
            ranges: {
                "Today": [moment(), moment()],
                "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [moment().startOf("month"), moment().endOf("month")],
                "Last Month": [
                moment()
                    .subtract(1, "month")
                    .startOf("month"),
                moment()
                    .subtract(1, "month")
                    .endOf("month")
                ]
            }
        },function(start, end, label) {
            $('#reports-activity-from-station').val(start.format('YYYY-MM-DD'));
            $('#reports-activity-to-station').val(end.format('YYYY-MM-DD'));
            $('#reports-activity-from-station-print').val(start.format('YYYY-MM-DD'));
            $('#reports-activity-to-station-print').val(end.format('YYYY-MM-DD'));
            $("#reports-activity-date-range-station span").html(start.format("ll") + " - " + end.format("ll"));
            console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        });
        /* END DATE RANGE OF REPORTS ACTIVITY STATION */

        /* DATE RANGE OF REPORTS ACTIVITY */
        $("#reports-activity-date-range span").html(moment().format("ll") + " - " + moment().format("ll"));
        $('#reports-activity-from').val(moment().format('YYYY-MM-DD'));
        $('#reports-activity-to').val(moment().format('YYYY-MM-DD'));

        $("#reports-activity-date-range").daterangepicker({
            maxDate: moment(),
            startDate: moment(),
            endDate: moment(),
            format: 'YYYY-MM-DD',
            autoApply: true,
            opens: 'left',
            ranges: {
                "Today": [moment(), moment()],
                "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [moment().startOf("month"), moment().endOf("month")],
                "Last Month": [
                moment()
                    .subtract(1, "month")
                    .startOf("month"),
                moment()
                    .subtract(1, "month")
                    .endOf("month")
                ]
            }
        },function(start, end, label) {
            $('#reports-activity-from').val(start.format('YYYY-MM-DD'));
            $('#reports-activity-to').val(end.format('YYYY-MM-DD'));
            $("#reports-activity-date-range span").html(start.format("ll") + " - " + end.format("ll"));
            console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        });
        /* END DATE RANGE OF REPORTS ACTIVITY */

        /* DATE RANGE OF REPORTS LIST SEARCH */
        $("#reports-list-search-date-range span").html(moment().format("ll") + " - " + moment().format("ll"));
        $('#reports-list-search-from').val(moment().format('YYYY-MM-DD'));
        $('#reports-list-search-to').val(moment().format('YYYY-MM-DD'));

        $("#reports-list-search-date-range").daterangepicker({
            maxDate: moment(),
            startDate: moment(),
            endDate: moment(),
            format: 'YYYY-MM-DD',
            autoApply: true,
            opens: 'left',
            ranges: {
                "Today": [moment(), moment()],
                "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [moment().startOf("month"), moment().endOf("month")],
                "Last Month": [
                moment()
                    .subtract(1, "month")
                    .startOf("month"),
                moment()
                    .subtract(1, "month")
                    .endOf("month")
                ]
            }
        },function(start, end, label) {
            $('#reports-list-search-from').val(start.format('YYYY-MM-DD'));
            $('#reports-list-search-to').val(end.format('YYYY-MM-DD'));
            $("#reports-list-search-date-range span").html(start.format("ll") + " - " + end.format("ll"));
            console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        });
        /* END DATE RANGE OF REPORTS LIST SEARCH */

        /* DATE RANGE OF REPORTS LIST SEARCH STATION */
        $("#reports-list-search-date-range-station span").html(moment().format("ll") + " - " + moment().format("ll"));
        $('#reports-list-search-from-station').val(moment().format('YYYY-MM-DD'));
        $('#reports-list-search-to-station').val(moment().format('YYYY-MM-DD'));

        $("#reports-list-search-date-range-station").daterangepicker({
            maxDate: moment(),
            startDate: moment(),
            endDate: moment(),
            format: 'YYYY-MM-DD',
            autoApply: true,
            opens: 'left',
            ranges: {
                "Today": [moment(), moment()],
                "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [moment().startOf("month"), moment().endOf("month")],
                "Last Month": [
                moment()
                    .subtract(1, "month")
                    .startOf("month"),
                moment()
                    .subtract(1, "month")
                    .endOf("month")
                ]
            }
        },function(start, end, label) {
            $('#reports-list-search-from-station').val(start.format('YYYY-MM-DD'));
            $('#reports-list-search-to-station').val(end.format('YYYY-MM-DD'));
            $("#reports-list-search-date-range-station span").html(start.format("ll") + " - " + end.format("ll"));
            console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        });
        /* END DATE RANGE OF REPORTS LIST SEARCH STATION */
    })
</script>
<!-- ================================== DATEPICKER ================================== -->



</body>
</html>

@else 
    @include('PNPstation.includes.419')
@endif
