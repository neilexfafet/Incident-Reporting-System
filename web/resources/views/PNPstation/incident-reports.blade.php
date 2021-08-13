@if(Auth::guard('station')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Station | Incident Reports</title>
    @include('pnpstation.includes.link')
    <style>
        .select2-container {
            width: 100% !important;
            padding: 0;
        }
    </style>
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
                            <div class="card card-default" id="recent-orders">
                                <div class="card-header card-header-border-bottom justify-content-between">
                                    <h2>Incident Reports</h2>
                                    <a href="javascript:void(0);" id="refresh-tables"><h2 class="mdi mdi-cached text-primary"> Reload Tables</h2></a>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="unresponded-tab" data-toggle="tab" href="#unresponded" role="tab" aria-controls="unresponded" aria-selected="true">
                                            Unresponded Incidents <span id="unresponded-count" class="badge badge-pill badge-warning" style="display: none;"></span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="responded-tab" data-toggle="tab" href="#responded" role="tab" aria-controls="responded" aria-selected="false">
                                            Responded Incidents <span id="responded-count" class="badge badge-pill badge-warning" style="display: none;"></span></a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="verified-tab" data-toggle="tab" href="#verified" role="tab" aria-controls="verified" aria-selected="false">
                                            Verified Reports</a>
                                        </li>
                                    </ul>
                                    
                                    <div class="tab-content" id="myTabContent1">
                                        <div class="tab-pane pt-3 fade show active" id="unresponded" role="tabpanel" aria-labelledby="unresponded-tab">
                                            <div class="responsive-data-table">
                                                <table id="unresponded-data-table" class="table table-hover dt-responsive" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="20%">Incident</th>
                                                            <th width="30%">Location</th>
                                                            <th width="20%">Reporter</th>
                                                            <th width="15%">Time Reported</th>
                                                            <th width="15%">Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane pt-3 fade" id="responded" role="tabpanel" aria-labelledby="responded-tab">
                                            <div class="responsive-data-table">
                                                <table id="responded-data-table" class="table table-hover dt-responsive" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="30%">Report #</th>
                                                            <th width="20%">Incident #</th>
                                                            <th width="20%">Time Reported</th>
                                                            <th width="15%">Officers</th>
                                                            <th width="15%">Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane pt-3 fade" id="verified" role="tabpanel" aria-labelledby="verified-tab">
                                            <div class="responsive-data-table">
                                                <table id="verified-data-table" class="table table-hover dt-responsive" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="25%">Report #</th>
                                                            <th width="15%">Date Reported</th>
                                                            <th width="15%">Time Responded</th>
                                                            <th width="15%">Time Verified</th>
                                                            <th width="15%">Status</th>
                                                            <th width="15%">Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    <!-- VIEW REPORTER MODAL -->
        <div class="modal fade" id="view-reporter" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormEDIT" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div id="view-reporter-loader" class="col-sm-12" style="display: none;">@include('PNPstation.includes.loader')</div>
                <div class="modal-content" id="view-reporter-form">
                    <div class="modal-header">
                        <h5 class="modal-title">Reporter Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <img id="view-reporter-image" class="img-fluid">
                            </div>
                            <div class="col-lg-6">
                                <p class="text-dark font-weight-medium mb-2">Name</p>
                                <p><span id="view-reporter-name"></span></p>
                                <p class="text-dark font-weight-medium pt-4 mb-2">Gender</p>
                                <p><span id="view-reporter-gender"></span</p>
                                <div class="form-group row pt-4">
                                    <div class="col-sm-6">
                                        <p class="text-dark font-weight-medium mb-2">Date of Birth</p>
                                        <p><span id="view-reporter-birthday"></span></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="text-dark font-weight-medium mb-2">Age</p>
                                        <p><span id="view-reporter-age"></span> Years Old</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- END VIEW REPORTER MODAL -->

    <!-- RESPOND REPORT MODAL -->
        <div class="modal fade" id="respond-report" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormEDIT" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div id="respond-report-loader" class="col-sm-12" style="display: none;">@include('PNPstation.includes.loader')</div>
                <form id="respond-report-form">
                @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Respond to Incident</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input name="report_id" type="hidden" id="respond-report-id">
                            <div class="row no-gutters">
                                <div class="col-lg-6">
                                    <h5 class="text-dark font-weight-medium">File Evidences</h5>
                                    <div id="carouselExampleControls" class="carousel slide pr-4 py-2" data-ride="carousel">
                                        <div class="carousel-inner">
                                            <div id="respond-report-image"></div>
                                        </div>
                                        <a class="carousel-control-prev pr-4" href="#carouselExampleControls" role="button" data-slide="prev">
                                            <span class="mdi mdi-chevron-left mdi-36px" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next pr-4" href="#carouselExampleControls" role="button" data-slide="next">
                                            <span class="mdi mdi-chevron-right mdi-36px" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="contact-info px-2 mb-2">
                                        <h5 class="text-dark mb-2">Location of the Incident: </h5>
                                        <p id="respond-report-location"></p>
                                    </div>
                                    <div id="respond-report-location-map" class="map-container"></div>
                                </div>
                            </div>
                            <div class="row">
                                <hr class="w-100">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h5 class="text-dark text-center mb-4">Incident outline according to the Reporter</h5>
                                            <textarea id="respond-report-description" rows="4" class="form-control" readonly style="background: transparent;"></textarea>
                                        </div>
                                        <div class="col-lg-6">
                                            <h5 class="text-dark text-center mb-4">Dispatch Officers</h5>
                                            <div class="form-group">
                                                <select name="officer_id[]" id="respond-report-officers-to-dispatch" class="js-example-basic-multiple js-states form-control" multiple="multiple" required>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" id="transfer-report">Transfer</button>
                            <button type="submit" class="btn btn-primary">Dispatch</button>
                        </div>
                    </div>
                </form>
                <form id="transfer-report-form" style="display: none;">
                @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Transfer Report</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="transfer-report-id">
                            <div class="form-row px-2 py-2">
                                <span class="text-dark font-size-17 mb-2">Please Select Station to Transfer Report</span>
                                <select name="station_id" id="transfer-report-option" class="form-control" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                    <option selected disabled value="">--- SELECT STATION ---</option>
                                    @foreach(App\Station::all()->where('is_active', '1')->where('id', '!=', Auth::guard('station')->user()->id) as $row)
                                    <option value="{{$row->id}}">{{$row->station_name}}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">Please fill out this field</span>
                            </div>
                            <div id="transfer-report-option-loader" style="display: none;">
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <div class="sk-three-bounce">
                                        <div class="bounce1"></div>
                                        <div class="bounce2"></div>
                                        <div class="bounce3"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="transfer-report-location-display" class="pt-2" style="display: none;">
                                <span class="text-dark font-size-17 font-italic font-weight-medium">Station Location: </span>
                                <span class="text-dark font-size-17" id="transfer-report-location"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" id="transfer-report-return">Return to Respond</button>
                            <button type="submit" class="btn btn-primary">Transfer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <!-- END RESPOND REPORT MODAL -->

    <!-- VIEW OFFICERS DISPATCHED REPORT MODAL -->
        <div class="modal fade" id="view-officers-dispatched" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormEDIT" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div id="view-officers-dispatched-loader" class="col-sm-12" style="display: none;">@include('PNPstation.includes.loader')</div>
                <div class="modal-content" id="view-officers-dispatched-form">
                    <div class="modal-header">
                        <h5 class="modal-title">Dispatched Officers</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-4 mb-4">
                                <ul class="nav nav-tabs nav-stacked flex-column" id="view-officers-dispatched-tab"></ul>
                            </div>
                            <div class="col-lg-8">
                                <div class="tab-content" id="view-officers-dispatched-tab-content"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- END VIEW OFFICERS DISPATCHED MODAL -->

    <!-- VERIFY REPORT MODAL -->
        <div class="modal fade" id="verify-report" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormEDIT" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div id="verify-report-loader" class="col-sm-12" style="display: none;">@include('PNPstation.includes.loader')</div>
                <form id="verify-report-form">
                @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="verify-report-id"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input name="dispatch_id" type="hidden" id="verify-report-name-id">
                            <div class="row no-gutters">
                                <div class="col-lg-6">
                                    <h5 class="text-dark font-weight-medium">File Evidence</h5>
                                    <div id="carouselExampleControls2" class="carousel slide pr-4 py-2" data-ride="carousel">
                                        <div class="carousel-inner" id="verify-report-image">
                                        </div>
                                        <a class="carousel-control-prev pr-4" href="#carouselExampleControls2" role="button" data-slide="prev">
                                            <span class="mdi mdi-chevron-left mdi-36px" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next pr-4" href="#carouselExampleControls2" role="button" data-slide="next">
                                            <span class="mdi mdi-chevron-right mdi-36px" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="contact-info px-2 mb-2">
                                        <h5 class="text-dark mb-2">Location of the Incident: </h5>
                                        <p id="verify-report-location"></p>
                                    </div>
                                    <div id="verify-report-location-map" class="map-container"></div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center pb-4">
                                <hr class="w-100">
                                <h4 class="text-center text-dark">Dispatched Officers</h4>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-4">
                                    <ul class="nav nav-tabs nav-stacked flex-column" id="verify-report-officers-dispatched-tab"></ul>
                                </div>
                                <div class="col-lg-8">
                                    <div class="tab-content" id="verify-report-officers-dispatched-tab-content"></div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <hr class="w-100">
                                <h4 class="text-center text-dark">Reporter Information</h4>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-3 text-center mb-4">
                                    <h4>Valid ID</h4>
                                    <img id="verify-report-reporter-image" class="img-fluid">
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <p class="text-dark font-weight-medium mb-2">Name</p>
                                            <p><span id="verify-report-reporter-name"></span></p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="text-dark font-weight-medium mb-2">Gender</p>
                                            <p><span id="verify-report-reporter-gender"></span></p>
                                        </div>
                                    </div>
                                    <div class="form-group row pt-2">
                                        <div class="col-sm-6">
                                            <p class="text-dark font-weight-medium mb-2">Date of Birth</p>
                                            <p><span id="verify-report-reporter-birthday"></span></p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="text-dark font-weight-medium mb-2">Age</p>
                                            <p><span id="verify-report-reporter-age"></span> Years Old</p>
                                        </div>
                                    </div>
                                    <div class="form-group row pt-2">
                                        <div class="col-sm-6">
                                            <p class="text-dark font-weight-medium mb-2">Email</p>
                                            <p><span id="verify-report-reporter-email"></span></p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="text-dark font-weight-medium mb-2">Contact Number</p>
                                            <p><span id="verify-report-reporter-contactno"></span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <span class="text-dark font-weight-medium">Outline of the Incident according to the reporter.</span>
                                    <textarea id="verify-report-description" rows="4" class="form-control mb-2" readonly style="background: transparent;"></textarea>
                                    <span class="text-dark font-weight-medium">Confirm this REPORT</span>
                                    <select name="status" class="form-control" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                        <option selected disabled value="">---SELECT CONFIRMATION---</option>
                                        <option value="verified">VALID REPORT</option>
                                        <option value="bogus">FRAUD REPORT</option>
                                    </select>
                                    <span class="invalid-feedback">Please Select and Option</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <!-- END VERIFY REPORT MODAL -->

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

<script>
$('#carouselExampleControls').carousel({
  interval: false,
});
$('#carouselExampleControls2').carousel({
  interval: false,
});
</script>

<!-- ===================================== GOOGLE MAP ============================== -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{config('googlemap')['map_apikey']}}&callback=initMap&libraries=places"
    type="text/javascript"></script>
<!-- ================================== END GOOGLE MAP ==================================== -->

<!-- ======================== MULTIPLE SELECT ================= -->
<script>
$(document).ready(function() {
    var multipleSelect = $(".js-example-basic-multiple");
    if(multipleSelect.length != 0){
        multipleSelect.select2({
            language: {
                noResults: function() {
                    return "No Available Officers as of now...";
                }
            },
            placeholder: "Select or Search Police Officers to Dispatch",
        });
    }
    
})
</script>
<!-- =========================END MULTIPLE SELECT =================== -->

<!-- =================================== DATA TABLE ============================================= -->
<script src={{ asset("assets/plugins/data-tables/jquery.datatables.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.bootstrap4.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.responsive.min.js") }}></script>

<script>
    $(document).ready(function() {
        $('#unresponded-data-table').DataTable({
            "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
            ordering: false,
            processing: true,
            serverSide: true,
            language: {
                emptyTable: "No Incident Reports Received Yet...",
                zeroRecords: "No Incident Report Found"
            },
            ajax: {
                url: "{{ url('station/incident-reports') }}",
            },
            columns: [
                { data: 'get_incident.type' },
                { data: 'location' },
                { data: 'get_user.last_name', render: function(data, type, row) {
                    return '<a href="javascript:void(0);" data-id='+ row.get_user["id"] +' data-toggle="modal" data-target="#view-reporter">'+row.get_user["first_name"]+' '+data+'</a>';
                } },
                { data: 'created_at', render: function(data, type, full, meta) {
                    var present = moment(data).fromNow();
                    return present + '&nbsp;<i class="mdi mdi-clock-outline"></i>';
                } },
                { data: 'action', className: 'text-center' },
            ],
            /* createdRow: function(row, data, index) {
                if(data.status == "pending") {
                    $(row).addClass('table-warning');
                }
            }, */
        });
        /* $('#responsive-data-table thead th').removeClass('text-danger text-uppercase font-weight-bold'); */
    });
    $(document).ready(function() {
        $('#responded-data-table').DataTable({
            "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
            ordering: false,
            processing: true,
            serverSide: true,
            language: {
                emptyTable: "No Pending Incident Reports Yet...",
                zeroRecords: "No Incident Report Found"
            },
            ajax: {
                url: "{{ url('station/incident-reports/responded') }}",
            },
            columns: [
                { data: 'dispatch_id' },
                { data: 'get_report.get_incident.type' },
                { data: 'get_report.created_at', render: function(data, type, full, meta) {
                    var present = moment(data).fromNow();
                    return present + '&nbsp;<i class="mdi mdi-clock-outline"></i>';
                } },
                { data: 'id', className: 'text-center', render: function(data, type, full, meta) {
                    return '<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-id=' + data + ' data-target="#view-officers-dispatched"><span class="mdi mdi-eye">&nbsp;View</span></button>';
                } },
                { data: 'action', className: 'text-center' },
            ]
        });
    });
    $(document).ready(function() {
        $('#verified-data-table').DataTable({
            "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
            ordering: false,
            processing: true,
            serverSide: true,
            language: {
                emptyTable: "No Incident Reports Yet...",
                zeroRecords: "No Incident Report Found"
            },
            ajax: {
                url: "{{ url('station/incident-reports/verified') }}",
            },
            columns: [
                { data: 'dispatch_id' },
                { data: 'get_report.created_at', render: function(data, type, full,meta) {
                    var created_at_date = moment(data).format('LL');
                    var created_at_time = moment(data).format('LT');
                    return created_at_date + ' at ' + created_at_time;
                } },
                { data: 'created_at', render: function(data, type, full,meta) {
                    var created_at_date = moment(data).format('LL');
                    var created_at_time = moment(data).format('LT');
                    return created_at_date + ' at ' + created_at_time;
                } },
                { data: 'updated_at', render: function(data, type, full,meta) {
                    var created_at_date = moment(data).format('LL');
                    var created_at_time = moment(data).format('LT');
                    return created_at_date + ' at ' + created_at_time;
                } },
                { data: 'status', className: 'text-center', render: function(data) {
                    if(data == "verified") {
                        return '<span class="text-success"><i class="mdi mdi-check"></i> Verified</span>';
                    } else {
                        return '<span class="text-danger"><i class="mdi mdi-close"></i> Fraud</span>';
                    }
                } },
                { data: 'action', className: 'text-center' },
            ]
        });
    });
</script>
<!-- =================================================== END DATA TABLE ========================================== -->

<!-- ================================== AJAX ================================= -->
<script>
$(document).ready(function() {
    /* REFRESH TABLES */
    $('#refresh-tables').on('click', function() {
        $('#unresponded-data-table').DataTable().ajax.reload();
        $('#responded-data-table').DataTable().ajax.reload();
        $('#verified-data-table').DataTable().ajax.reload();
    })
    /* END REFRESH TABLES */
    
    /* UNRESPONDED COUNT */
    function unrespondedCount() {
        $.ajax({
            type: "GET",
            url: "{{ url('station/incident-reports/unresponded/count') }}",
            dataType: "JSON",
            success: function(result) {
                if(result == 0) {
                    $('#unresponded-count').hide();
                } else {
                    $('#unresponded-count').show().html(result);
                }
            },
            error: function(error) {
                console.log(error);
            },
        });
    };
    unrespondedCount();
    /* END UNRESPONDED COUNT */

    /* RESPONDED COUNT */
    function respondedCount() {
        $.ajax({
            type: "GET",
            url: "{{ url('station/incident-reports/responded/count') }}",
            dataType: "JSON",
            success: function(result) {
                if(result == 0) {
                    $('#responded-count').hide();
                } else {
                    $('#responded-count').show().html(result);
                }
            },
            error: function(error) {
                console.log(error);
            },
        });
    };
    respondedCount();
    /* END RESPONDED COUNT */

    /* VIEW REPORTER */
    $('#view-reporter').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);

        $('#view-reporter-form').hide();
        $('#view-reporter .modal-dialog').addClass('modal-dialog-centered');
        $('#view-reporter-loader').show();

        $.ajax({
            type: "GET",
            url: "{{ url('/station/incident-reports/reporter/view') }}"+"/"+id,
            dataType: "JSON",
            success: function(data) {
                var user_image = data.image;
                var user_bday = moment(data.birthday).format('LL');
                var user_age = moment().diff(data.birthday, 'years');

                $('#view-reporter-name').html(data.first_name + " " + data.last_name);
                $('#view-reporter-gender').html(data.gender);
                $('#view-reporter-birthday').html(user_bday);
                $('#view-reporter-age').html(user_age);
                if(user_image == "TBD") {
                    $('#view-reporter-image').attr('src', '{{ asset("uploads/user.jpg") }}');
                } else {
                    $('#view-reporter-image').attr('src', '{{ asset("/") }}'+user_image);
                }
            },
            complete: function() {
                $('#view-reporter .modal-dialog').removeClass('modal-dialog-centered');
                $('#view-reporter-loader').hide();
                $('#view-reporter-form').show();
            },
            error: function(error) {
                console.log(error);
            },
        });
    })
    /* END VIEW REPORTER */

    /* GET OFFICERS TO DISPATCH */
    function officersToDispatch() {
        $.ajax({
            type: "GET",
            url: "{{ url('station/incident-reports/officers-to-dispatch') }}",
            dataType: "JSON",
            success: function(result) {
                $('#respond-report-officers-to-dispatch').html(result);
            },
            error: function(error) {
                console.log(error);
            },
        });
    }
    officersToDispatch();
    /* END GET OFFICERS TO DISPATCH */

    /* RESPOND REPORT */
    $('#respond-report').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);

        officersToDispatch();
        $('#respond-report-form').hide();
        $('#transfer-report-form').hide();
        $('#respond-report .modal-dialog').addClass('modal-dialog-centered');
        $('#respond-report-loader').show();

        $.ajax({
            type: "GET",
            url: "{{ url('/station/incident-reports/report/') }}"+"/"+id,
            dataType: "JSON",
            success: function(data) {
                var mapview = new google.maps.Map(document.getElementById('respond-report-location-map'),{
                    center: {
                        lat: data.view.location_lat,
                        lng: data.view.location_lng
                    },
                    zoom:15
                });
                var markerview = new google.maps.Marker({
                    map:mapview,
                    position: {
                        lat: data.view.location_lat,
                        lng: data.view.location_lng
                    },
                });
                infoWindow = new google.maps.InfoWindow({
                    content: data.view.location
                });
                infoWindow.open(mapview, markerview);

                $('#respond-report-id').val(data.view.id);
                $('#transfer-report-id').val(data.view.id);
                $('#respond-report-location').html(data.view.location);
                $('#respond-report-description').html(data.view.description);
                $('#respond-report-image-list').html(data.list);
                $('#respond-report-image').html(data.img);
                
            },
            complete: function() {
                $('#respond-report .modal-dialog').removeClass('modal-dialog-centered').addClass('modal-xl');
                $('#respond-report-loader').hide();
                $('#respond-report-form').show();
            },
            error: function(error) {
                console.log(error);
            },
        });
    })
    $('#respond-report-form').on('submit', function(event) {
        event.preventDefault();

        $(this).hide();
        $('#respond-report .modal-dialog').addClass('modal-dialog-centered');
        $('#respond-report-loader').show();

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
            url: "{{ url('station/incident-reports/dispatch-officers') }}",
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                officersToDispatch();
                $('#respond-report').modal('hide');
                $('#respond-report-loader').hide();
                $('#respond-report-form').show();
                respondedCount();
                unrespondedCount();
                $('#unresponded-data-table').DataTable().ajax.reload();
                $('#responded-data-table').DataTable().ajax.reload();
                $('#verified-data-table').DataTable().ajax.reload();
                toastr.success("", result.success);
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
    /* END RESPOND REPORT */

    /* TRANSFER REPORT */
    $('#transfer-report').on('click', function() {
        $('#respond-report .modal-dialog').removeClass('modal-xl');
        $('#respond-report-form').hide();
        $('#transfer-report-form').show();
    });
    $('#transfer-report-return').on('click', function() {
        $('#respond-report .modal-dialog').addClass('modal-xl');
        $('#transfer-report-form').hide();
        $('#respond-report-form').show();
    });
    $('#transfer-report-option').on('change', function() {
        var id = this.value;

        $('#transfer-report-location-display').hide();
        $('#transfer-report-option-loader').show();
        
        $.ajax({
            type: "GET",
            url: "{{url('station/incident-reports/station-details')}}"+"/"+id,
            dataType: "JSON",
            success: function(result) {
                $('#transfer-report-location').html(result.location_name);
            },
            complete: function() {
                $('#transfer-report-option-loader').hide();
                $('#transfer-report-location-display').show();
            },
            error: function(error) {
                console.log(error);
            }
        })
    });
    $('#transfer-report-form').on('submit', function(event) {
        event.preventDefault();

        $(this).hide();
        $('#respond-report .modal-dialog').addClass('modal-dialog-centered');
        $('#respond-report-loader').show();

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

        var transfer_report_id = $('#transfer-report-id').val();

        $.ajax({
            type: "POST",
            url: "{{ url('station/incident-reports/transfer') }}"+"/"+transfer_report_id,
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                if(result.success) {
                    $('#respond-report').modal('hide');
                    $('#respond-report-form').hide();
                    $('#transfer-report-form').hide();
                    $('#respond-report .modal-dialog').addClass('modal-dialog-centered');
                    $('#respond-report-loader').show();
                    $('#unresponded-data-table').DataTable().ajax.reload();
                    $('#responded-data-table').DataTable().ajax.reload();
                    $('#verified-data-table').DataTable().ajax.reload();
                    toastr.success("", result.success);
                }
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
    /* END TRANSFER REPORT */

    /* VIEW DISPATCHED OFFICERS */
    $('#view-officers-dispatched').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);

        $('#view-officers-dispatched .modal-dialog').addClass('modal-dialog-centered');
        $('#view-officers-dispatched-form').hide();
        $('#view-officers-dispatched-loader').show();

        $.ajax({
            type: "GET",
            url: "{{ url('/station/incident-reports/view-dispatched-officers') }}"+"/"+id,
            dataType: "JSON",
            success: function(result) {
                $('#view-officers-dispatched-tab').html(result.tab);
                $('#view-officers-dispatched-tab-content').html(result.tabcontent);
            },
            complete: function() {
                $('#view-officers-dispatched .modal-dialog').removeClass('modal-dialog-centered');
                $('#view-officers-dispatched-loader').hide();
                $('#view-officers-dispatched-form').show();
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
    /* END VIEW DISPATCHED OFFICERS */

    /* VERIFY INCIDENT REPORT */
    $('#verify-report').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);

        $('#verify-report .modal-dialog').addClass('modal-dialog-centered');
        $('#verify-report-form').hide();
        $('#verify-report-loader').show();

        $.ajax({
            type: "GET",
            url: "{{ url('/station/incident-reports/responded/view') }}"+"/"+id,
            dataType: "JSON",
            success: function(data) {
                var bday = moment(data.view.get_report.get_user.birthday).format('LL');
                var age = moment().diff(data.view.get_report.get_user.birthday, 'years');

                var mapview = new google.maps.Map(document.getElementById('verify-report-location-map'),{
                    center: {
                        lat: data.view.get_report.location_lat,
                        lng: data.view.get_report.location_lng
                    },
                    zoom:15
                });
                var markerview = new google.maps.Marker({
                    map:mapview,
                    position: {
                        lat: data.view.get_report.location_lat,
                        lng: data.view.get_report.location_lng
                    },
                });
                infoWindow = new google.maps.InfoWindow({
                    content: data.view.get_report.location
                });
                infoWindow.open(mapview, markerview);

                $('#verify-report-id').html(data.view.dispatch_id);
                $('#verify-report-name-id').val(data.view.dispatch_id);
                $('#verify-report-location').html(data.view.get_report.location);
                $('#verify-report-description').val(data.view.get_report.description);
                $('#verify-report-reporter-name').html(data.view.get_report.get_user.last_name+", "+data.view.get_report.get_user.first_name);
                $('#verify-report-reporter-gender').html(data.view.get_report.get_user.gender);
                $('#verify-report-reporter-birthday').html(bday);
                $('#verify-report-reporter-age').html(age);
                $('#verify-report-reporter-email').html(data.view.get_report.get_user.email);
                $('#verify-report-reporter-contactno').html(data.view.get_report.get_user.contact_no);
                $('#verify-report-image').html(data.img);
                $('#verify-report-officers-dispatched-tab').html(data.tab);
                $('#verify-report-officers-dispatched-tab-content').html(data.content);
                if(data.view.get_report.get_user.valid_id_image == "TBD") {
                    $('#verify-report-reporter-image').attr('src', '{{ asset("uploads/user.jpg") }}');
                } else {
                    $('#verify-report-reporter-image').attr('src', '{{ asset("/") }}'+data.view.get_report.get_user.valid_id_image);
                }
            },
            complete: function() {
                $('#verify-report .modal-dialog').removeClass('modal-dialog-centered');
                $('#verify-report-loader').hide();
                $('#verify-report-form').show();
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
    $('#verify-report-form').on('submit', function(event) {
        event.preventDefault();

        $(this).hide();
        $('#verify-report .modal-dialog').addClass('modal-dialog-centered');
        $('#verify-report-loader').show();

        $.ajax({
            type: "POST",
            url: "{{ url('station/incident-reports/verify') }}",
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                $('#verify-report').modal('hide');
                $('#verify-report-loader').hide();
                $('#verify-report-form').show();
                respondedCount();
                unrespondedCount();
                $('#unresponded-data-table').DataTable().ajax.reload();
                $('#responded-data-table').DataTable().ajax.reload();
                $('#verified-data-table').DataTable().ajax.reload();
                document.getElementById('verify-report-form').reset();
                toastr.success("", result.success);
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
    /* END VERIFY INCIDENT REPORT */

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
<!-- ================================== AJAX ================================= -->

</body>
</html>


@else 
    @include('PNPstation.includes.419')
@endif