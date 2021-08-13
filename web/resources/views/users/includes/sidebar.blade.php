<!--
    ====================================
    ——— LEFT SIDEBAR 
    =====================================
        -->
    <aside class="left-sidebar bg-sidebar">
        <div id="sidebar" class="sidebar">
        <!-- Aplication Brand -->
        <div class="app-brand">
            <a title="Sleek Dashboard">
                <img class="brand-icon" src={{ asset('assets\img\pnpseal.png') }}  width="40" height="40">
                <span class="brand-name text-truncate">CDO | Report</span>
            </a>
        </div>
        <!-- begin sidebar scrollbar -->
        <div class="sidebar-scrollbar">
            <!-- sidebar menu -->
            <ul class="nav sidebar-inner" id="sidebar-menu">
                <li class="sub">
                    <a class="sidenav-item-link" href="javascript:void(0);" data-toggle="modal" data-target="#report-incident">
                        <i class="mdi mdi-alert-circle-outline text-warning"></i>
                        <span class="nav-text text-warning">Report Incident</span>
                    </a>
                </li>
                <li class="sub @if(Request::is('user/dashboard')) active @endif">
                    <a class="sidenav-item-link" href="{{ url('user/dashboard') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span class="nav-text">Newsfeed</span>
                    </a>
                </li>
                <li class="sub @if(Request::is('user/stations')) active @endif">
                    <a class="sidenav-item-link" href="{{ url('user/stations') }}">
                        <i class="mdi mdi-map-marker"></i>
                        <span class="nav-text">Police Stations</span>
                    </a>
                </li>
            </ul>
        </div>
            
        <!-- <div class="sidebar-footer">
            <hr class="separator mb-0" />
            <div class="sidebar-footer-content">
            <span>Philippine Standard Time</span><br>
            <span id="date"></span>
            </div>
        </div> -->
                        
        </div>
    </aside>

    <!-- REPORT MODAL -->
    <div class="modal fade" id="report-incident" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div id="report-incident-loader" class="col-sm-12" style="display: none;">@include('users.includes.loader')</div>
            <form id="report-incident-form">
            @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalFormTitle">File an Incident</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="report-incident-alert-danger" style="display: none;">
                            <div class="alert alert-warning" role="alert">
                                It seems like the incident is not in the area of responsibility of any Police
                                Stations located at Cagayan De Oro. Make sure to set the Location right to avoid conflicts.
                            </div>
                        </div>
                        <span class="text-dark font-size-17 font-italic">
                            <i class="mdi mdi-alert-outline text-warning"></i>
                            &nbsp;If you file a false report, there's a very good chance that you could be held 
                            liable for defamation, intentional infliction of emotional distress, or other 
                            damages directly resulting from your actions. Check below if you are aware of the terms.
                        </span>
                        <div class="py-4">
                            <input id="checkbox-accept-penalty" type="checkbox" required/>
                            <span class="text-dark font-size-17">I have read and aware of my consequences to the terms.</span>
                        </div>
                        <div id="display-file-incident-form" style="display: none;">
                            <div class="form">
                                <label class="text-dark font-size-17">Incident Type</label>
                                <select name="incident_id" class="form-control" id="display-incident-type-option" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                </select>
                                <div id="display-incident-type-warning">
                                    <span class="d-block mt-1" style="font-size: 85%">*Only these types of Incidents are applicable by system. Any incident that are not listed are advised to report it immediately or direct to the nearest police stations. <br> <a href="{{ url('user/stations') }}">Link Here for Police Station Contact Numbers and Locations.</a></span>
                                </div>
                                <div class="invalid-feedback">Please Select Incident Type.</div>
                            </div>
                            <div id="display-incident-type-loader" style="display: none;">
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <div class="sk-three-bounce">
                                        <div class="bounce1"></div>
                                        <div class="bounce2"></div>
                                        <div class="bounce3"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="display-incident-type-description-display" class="pt-2" style="display: none;">
                                <span class="text-dark font-size-17 font-italic font-weight-medium">Incident Description: </span>
                                <span class="text-dark font-size-17" id="display-incident-type-description"></span>
                            </div>
                            <div class="form pt-4">
                                <span class="text-dark font-size-17">When does this Incident happened?</span>
                                <input name="incident_date" id="incident_date" type="datetime-local" class="form-control" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                <div class="invalid-feedback" id="incident_date-inv">Please fill out this field.</div>
                            </div>
                            <div class="form pt-4">
                                <span class="text-dark font-size-17">Outline of the Incident</span>
                                <textarea name="description" class="form-control" rows="4" placeholder="Description" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')"></textarea>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                            <div class="form pt-4">
                                <span class="text-dark font-size-17">Attach Image or Video Evidence of the Incident</span>&nbsp;<span>(optional)</span>
                                <div class="custom-file mb-1">
                                    <input name="file_evidence[]" type="file" class="custom-file-input" id="report-incident-file-upload" accept="video/*,image/*" multiple onchange="readURL(this);">
                                    <label class="custom-file-label" for="file-upload"><span id="report-incident-file-upload-filename">Choose File . . .</span></label>
                                    <span class="d-block mt-1" style="font-size: 85%">*Only Image or Video File Types are Accepted.</span>
                                </div>
                            </div>
                            <div class="form py-4">
                                <label class="text-dark font-size-17">&nbsp;Where does this incident happened?</label>
                                <input name="location" type="text" class="form-control" id="display-location-input" placeholder="Street/District/Village/Town" style="background: transparent" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                            <div id="display-report-incident-map">
                                <div id="report-incident-map" class="map-container"></div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label>Latitude</label>
                                        <input name="location_lat" id="location_lat" type="text" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Longitude</label>
                                        <input name="location_lng" id="location_lng" type="text" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="display-file-incident-submit" class="modal-footer" style="display: none;">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- END REPORT MDOAL -->


