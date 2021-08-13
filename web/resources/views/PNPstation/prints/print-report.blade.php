@if(Auth::guard('station')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>PRINT</title>
    @include('pnpstation.includes.link')
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">

<script>
    NProgress.start();
</script>

<div class="row">
    <div class="col-12">
        <div class="card card-default">
            <div class="card-header d-flex justify-content-center">
                <img src="{{asset('assets/img/pnpseal.png')}}" class="img-fluid" style="max-height: 50px; max-width: 50px;">
                <h1 class="text-dark px-4">INCIDENT REPORT</h1>
                <img src="{{asset('assets/img/pnpseal.png')}}" class="img-fluid" style="max-height: 50px; max-width: 50px;">
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h2 class="text-dark font-weight-medium">{{$find->dispatch_id}}</h2>
                    <h2 class="font-weight-medium">
                        @if($find->status == "verified")
                            <span class='text-success'><i class='mdi mdi-check'></i> VALID</span>
                        @else
                            <span class='text-danger'><i class='mdi mdi-close'></i> FRAUD</span>
                        @endif
                    </h2>
                </div>
                <div class="row border-bottom pt-5">
                    <div class="col-sm-6 pb-4">
                        <h4 class="text-dark mb-2">Incident Details</h4>
                            <p><i class="text-dark pr-4">Incident:&nbsp;</i><span class="text-dark font-weight-medium">{{$find->getReport->getIncident->type}}</span></p>
                            <p class="pt-2"><i class="text-dark pr-2">Date and Time of the Report:</i>&nbsp;<span class="pl-2">{{$find->getReport->created_at->format('F j, Y')}} at {{$find->getReport->created_at->format('h:i A')}}</span></p>
                            <p class="pt-2"><i class="text-dark pr-2">Date and Time of the Incident:</i>&nbsp;<span class="pl-2">{{Carbon\Carbon::parse($find->getReport->incident_date)->format('F j, Y')}} at {{Carbon\Carbon::parse($find->getReport->incident_date)->format('h:i A')}}</span></p>
                            <p class="pt-2"><i class="text-dark pr-2">Date and Time of the Station to Respond:</i>&nbsp;<span class="pl-2">{{$find->created_at->format('F j, Y')}} at {{$find->created_at->format('h:i A')}}</span></p>
                            <p class="pt-2"><i class="text-dark pr-2">Date and Time of the Station to Verify:</i>&nbsp;<span class="pl-2">{{$find->updated_at->format('F j, Y')}} at {{$find->updated_at->format('h:i A')}}</span></p>
                            <p class="pt-2"><i class="text-dark pr-2">Location of the Incident:</i>&nbsp;<span class="pl-2">{{$find->getReport->location}}</span></p>
                            <p class="text-dark py-2"><i>Outline of the Incident according to the Reporter:</i>&nbsp;</p>
                            <p class="pl-2">{{$find->getReport->description}}</p>
                    </div>
                    <div class="col-sm-3 pb-4">
                        <h4 class="text-dark mb-2">Police Station</h4>
                            <p class="pb-2"><i class="mdi mdi-security"></i>&nbsp;{{$find->getStation->station_name}}</p>
                            <p class="pb-2"><i class="mdi mdi-map-marker"></i>&nbsp;{{$find->getStation->location_name}}</p>
                            <p class="pb-2"><i class="mdi mdi-phone"></i>&nbsp;{{$find->getStation->station_contactno}}</p>
                    </div>
                    <div class="col-sm-3 pb-4">
                        <h4 class="text-dark mb-2">Reporter</h4>
                            <p class="pb-2"><i class="mdi mdi-account"></i>&nbsp;{{$find->getReport->getUser->first_name}} {{$find->getReport->getUser->last_name}}</p>
                            <p class="pb-2"><i class="mdi mdi-gender-male-female"></i>&nbsp;{{$find->getReport->getUser->gender}}</p>
                            <p class="pb-2"><i class="mdi mdi-calendar-clock"></i>&nbsp;{{Carbon\Carbon::parse($find->getReport->getUser->birthday)->age}} years old</p>
                            <p class="pb-2"><i class="mdi mdi-email"></i>&nbsp;{{$find->getReport->getUser->email}}</p>
                            <p class="pb-2"><i class="mdi mdi-phone"></i>&nbsp;{{$find->getReport->getUser->contact_no}}</p>
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
                        <tbody>
                            @foreach($data as $row)
                            <tr>
                                <td>{{$row->getOfficer->last_name}}, {{$row->getOfficer->first_name}}</td>
                                <td>{{$row->getOfficer->getRank->name}} ({{$row->getOfficer->getRank->abbreviation}})</td>
                                <td>{{$row->getOfficer->id_no}}</td>
                                <td>{{$row->getOfficer->badge_no}}</td>
                                <td>{{$row->getOfficer->contact_no}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="py-6">
                    <div class="row border-bottom">
                        <div class="col-lg-12 pr-2 pb-4">
                            <h3 class="pl-2 pb-2">Incident Map Location</h3>
                            <div id="location-map" class="map-container" style="page-break-inside:avoid"></div>
                        </div>
                    </div>
                </div>
                <div class="pt-6">
                    <div class="row pt-6">
                        <div class="col-lg-12">
                            <h3 class="pl-2 pb-2">File Evidences</h3>
                            <div class="row pt-6">
                                @if(count($evidence) == 0)
                                <div class="col-sm-12 text-center">
                                    <h3 class="py-6">No File Available</h3>
                                </div>
                                @else
                                    @foreach($evidence as $row)
                                        @if(count($evidence) == 1)
                                            @if($row->filetype == "video")
                                            <div class="col-sm-12 text-center">
                                                <h4>Video available on this link below.</h4>
                                                <a href="{{asset($row->filename)}}" target="_blank">{{asset($row->filename)}}</a>
                                            </div>
                                            @else
                                            <div class="col-sm-12">
                                                <img src="{{asset($row->filename)}}" class="img-fluid" style="page-break-inside:avoid">
                                            </div>
                                            @endif
                                        @elseif(count($evidence) == 2)
                                            @if($row->filetype == "video")
                                            <div class="col-sm-6 text-center">
                                                <h4>Video available on this link below.</h4>
                                                <a href="{{asset($row->filename)}}" target="_blank">{{asset($row->filename)}}</a>
                                            </div>
                                            @else
                                            <div class="col-sm-6">
                                                <img src="{{asset($row->filename)}}" class="img-fluid" style="page-break-inside:avoid">
                                            </div>
                                            @endif
                                        @else
                                            @if($row->filetype == "video")
                                            <div class="col-sm-4 text-center">
                                                <h4>Video available on this link below.</h4>
                                                <a href="{{asset($row->filename)}}" target="_blank">{{asset($row->filename)}}</a>
                                            </div>
                                            @else
                                            <div class="col-sm-4">
                                                <img src="{{asset($row->filename)}}" class="img-fluid" style="page-break-inside:avoid">
                                            </div>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
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

@include('pnpstation.includes.script')

<script>
$('#print-loader').modal('show');
</script>

<!-- ===================================== GOOGLE MAP ============================== -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{config('googlemap')['map_apikey']}}&callback=initMap&libraries=places"
    type="text/javascript"></script>
<!-- ================================== END GOOGLE MAP ==================================== -->

<!-- MAP -->
<script>
function initMap() {
    var mapview = new google.maps.Map(document.getElementById('location-map'),{
        center: {
            lat: {{$find->getReport->location_lat}},
            lng: {{$find->getReport->location_lng}}
        },
        zoom:15
    });
    var markerview = new google.maps.Marker({
        map:mapview,
        position: {
            lat: {{$find->getReport->location_lat}},
            lng: {{$find->getReport->location_lng}}
        },
    });
    infoWindow = new google.maps.InfoWindow({
        content: "{{$find->getReport->location}}"
    });
    infoWindow.open(mapview, markerview);
}
</script>
<!-- END MAP -->


<!-- PRINT -->
<script>
$(document).ready(function() {
    function printpage() {
        $('#print-loader').modal('hide');
        window.print();
        window.close();
    }
    setInterval(printpage, 2500);
})
</script>
<!-- END PRINT -->

</body>
</html>

            
@else
    @include('PNPstation.includes.419')
@endif