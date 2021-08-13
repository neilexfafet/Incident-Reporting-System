@if(Auth::guard('admin')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>PNP | Officers</title>
    @include('pnpadmin.includes.link')
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body" onload=display_ct();>

<script>
    NProgress.start();
</script>

    <div id="toaster-alert-success"></div>
    <div id="toaster-alert-error"></div>

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
                            <h2>Police Officers</h2>
                            <button href="javascript:void(0);" class="btn btn-outline-primary text-uppercase" data-toggle="modal" data-target="#add-officer">
                                <i class=" mdi mdi-account"></i>&nbsp;&nbsp;&nbsp;Add Officer
                            </button>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true">
                                    <i class="mdi mdi-security"></i>&nbsp;&nbsp;Assigned Officers</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="unassign-tab" data-toggle="tab" href="#unassign" role="tab" aria-controls="unassign" aria-selected="false">
                                    <i class="mdi mdi-format-list-bulleted"></i>&nbsp;&nbsp;Unassigned Officers&nbsp;&nbsp;
                                    <span id="unassign-count" class="badge badge-pill badge-warning"></span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="false">
                                    <i class="mdi mdi-loading mdi-spin"></i>&nbsp;&nbsp;Pending Officers&nbsp;&nbsp;
                                    <span id="pending-count" class="badge badge-pill badge-warning"></span></a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent1">
                                <div class="tab-pane pt-4 fade show active" id="list" role="tabpanel" aria-labelledby="list-tab">
                                    <div class="responsive-data-table">
                                        <table id="responsive-data-table" class="table table-hover dt-responsive" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th width="25%">Name</th>
                                                    <th width="25%">Rank</th>
                                                    <th width="25%">Station</th>
                                                    <th width="25%">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane pt-4 fade" id="unassign" role="tabpanel" aria-labelledby="unassign-tab">
                                    <div class="responsive-data-table">
                                        <table id="responsive-data-table-unassign" class="table table-hover dt-responsive" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th width="20%">Name</th>
                                                    <th width="20%">Rank</th>
                                                    <th width="20%">PNP ID #</th>
                                                    <th width="20%">Badge #</th>
                                                    <th width="20%">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane pt-4 fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                                    <div class="responsive-data-table">
                                        <table id="responsive-data-table-pending" class="table table-hover dt-responsive" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th width="20%">Name</th>
                                                    <th width="20%">Rank</th>
                                                    <th width="20%">PNP ID #</th>
                                                    <th width="20%">Badge #</th>
                                                    <th width="20%">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- ADD OFFICER MODAL -->
                <div class="modal fade" id="add-officer" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormEDIT" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div id="add-officer-loader" class="col-sm-12" style="display: none;">@include('PNPadmin.includes.loader')</div>
                        <form id="add-officer-form">
                        @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalFormEDIT">Add Police Officer</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="add-officer-alert-success" class="alert alert-success" style="display: none;" role="alert"></div>
                                    <div id="add-officer-alert-danger" class="alert alert-danger" style="display: none;" role="alert"></div>
                                    <div class="row no-gutters">
                                        <div class="col-lg-4 col-xl-4">
                                            <div class="profile-content-left pt-5 pb-3 px-3 px-xl-8">
                                                <div class="card widget-profile px-0 border-0">
                                                    <div class="card-img mx-auto rounded" style="height: 180px; width: 150px;">
                                                        <img src={{ asset('uploads/user.jpg') }} class="img-fluid" alt="user image" id="file-change">
                                                    </div>
                                                    <hr class="w-100">
                                                    <div class="form-group py-4">
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
                                        <div class="col-lg-8 col-xl-8">
                                            <div class="profile-content-right">
                                                <div class="header px-4 d-block">
                                                    <h2>Personal Information</h2>
                                                </div>
                                                <hr class="w-100">
                                                <div class="tab-content px-3 px-xl-4" id="myTabContent">
                                                    <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="settings-tab">
                                                        <div class="mt-4">
                                                            <div class="form-row py-2">
                                                                <div class="col-lg-4">
                                                                    <label class="text-dark font-size-17">First name</label>
                                                                    <input name="first_name" type="text" class="form-control">
                                                                    <span class="invalid-feedback">Please fill out this field</span>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <label class="text-dark font-size-17">Middle name</label>
                                                                    <input name="middle_name" type="text" class="form-control">
                                                                    <span class="invalid-feedback">Please fill out this field</span>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <label class="text-dark font-size-17">Last name</label>
                                                                    <input name="last_name" type="text" class="form-control">
                                                                    <span class="invalid-feedback">Please fill out this field</span>
                                                                </div>
                                                            </div>
                                                            <div class="form py-2">
                                                                <label class="text-dark font-size-17">Rank</label>
                                                                <select name="rank_id" class="form-control">
                                                                    <option selected disabled value="">--SELECT--</option>
                                                                    @foreach(App\Rank::all() as $row)
                                                                    <option value="{{$row->id}}">{{$row->name}} ({{$row->abbreviation}})</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="invalid-feedback">Please fill out this field</span>
                                                            </div>
                                                            <div class="form-row py-2">
                                                                <div class="col-lg-6">
                                                                    <label class="text-dark font-size-17">PNP ID #</label>
                                                                    <input name="id_no" id="id_no" type="text" class="form-control text-uppercase" onkeyup="this.value = this.value.toUpperCase();">
                                                                    <span class="invalid-feedback" id="id_no-inv">Please fill out this field</span>
                                                                    <span>PNP ID # cannot be updated once registered.</span>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <label class="text-dark font-size-17">Badge #</label>
                                                                    <input name="badge_no" id="badge_no" type="text" class="form-control text-uppercase" onkeyup="this.value = this.value.toUpperCase();">
                                                                    <span class="invalid-feedback" id="badge_no-inv">Please fill out this field</span>
                                                                    <span>Badge # cannot be updated once registered.</span>
                                                                </div>
                                                            </div>
                                                            <div class="form py-2">
                                                                <label class="text-dark font-size-17">Email</label>
                                                                <input name="email" type="email" class="form-control">
                                                                <span class="invalid-feedback">Please fill out this field</span>
                                                            </div>
                                                            <div class="form py-2">
                                                                <label class="text-dark font-size-17">Date of Birth</label>
                                                                <input type="text" id="birthday-datepicker" class="form-control" placeholder="Birthday" onchange="$(this).removeClass('is-invalid')">
                                                                <input name="birthday" id="birthday" type="date" class="form-control" style="display: none;" onchange="$(this).removeClass('is-invalid')">
                                                                <span class="invalid-feedback" id="birthday-inv">Please fill out this field</span>
                                                                <span>Must be 18+ of Age.</span>
                                                            </div>
                                                            <div class="form py-2">
                                                                <label class="text-dark font-size-17">Gender</label>
                                                                <select name="gender" class="form-control">
                                                                    <option selected disabled value="">--SELECT--</option>
                                                                    <option value="Male"> Male </option>
                                                                    <option value="Female"> Female </option>
                                                                </select>
                                                                <span class="invalid-feedback">Please fill out this field</span>
                                                            </div>
                                                            <div class="form py-2">
                                                                <label class="text-dark font-size-17">Contact No</label>
                                                                    <div class="input-group mb-2">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">
                                                                                <i class="mdi mdi-phone"></i>
                                                                            </span>
                                                                        </div>
                                                                    <input name="contact_no" type="text" class="form-control" data-mask="(999) 999-9999" placeholder="ex. (999) 999-9999">
                                                                    <span class="invalid-feedback">Please fill out this field</span>
                                                                </div>
                                                            </div>
                                                            <div class="form py-2">
                                                            <label class="text-dark font-size-17">Address</label>
                                                                <div class="input-group mb-2">
                                                                    <input name="address" type="text" class="form-control" placeholder="Street, City/Municapality, Zip Code">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">
                                                                                <i class="mdi mdi-map-marker"></i>
                                                                            </span>
                                                                        </div>
                                                                    <span class="invalid-feedback">Please fill out this field</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
            <!--END ADD OFFICER MODAL -->

            <!-- VIEW STATION MODAL -->
            <div class="modal fade" id="view-station" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormVIEW" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div id="view-station-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                    <div id="view-station-form" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalFormEDIT">Station Information</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="form d-flex justify-content-center pb-4">
                                        <img id="view-station-image" class="img-fluid">
                                    </div>
                                    <div class="form pb-4">
                                        <label class="text-dark font-size-17">Station Name</label>
                                        <p id="view-station-name"></p>
                                    </div>
                                    <div class="form pb-4">
                                        <label class="text-dark font-size-17">Station Contact #</label>
                                        <p id="view-station-contactno"></p>
                                    </div>
                                    <div class="form pb-4">
                                        <label class="text-dark font-size-17">Location</label>
                                        <p id="view-station-location-name"></p>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <label>Location Map</label>
                                    <div id="view-station-map" class="map-container"></div>
                                    <div class="form-group row" style="display: none;">
                                        <div class="col-md-6">
                                            <label>Latitude</label>
                                            <input id="view-station-location-lat" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Longitude</label>
                                            <input id="view-station-location-lng" type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END VIEW STATION MODAL -->

            <!-- VIEW MODAL -->
            <div class="modal fade" id="view-assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormVIEW" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div id="view-assign-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                    <div class="modal-content" id="view-assign-form">
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
                                                <img id="view-assign-image" class="img-fluid" alt="user image">
                                            </div>
                                            <div class="card-body">
                                                <h4 class="py-2 text-dark"><span id="view-assign-name"></span></h4>
                                                <p><span id="view-assign-rank"></span></p>
                                            </div>
                                            <hr class="w-100">
                                            <div class="form-group row">
                                                <div class="col-sm-6 contact-info">
                                                    <p class="text-dark font-weight-medium pt-4 mb-2">PNP ID #</p>
                                                    <p><span id="view-assign-idno"></span></p>
                                                </div>
                                                <div class="col-sm-6 contact-info">
                                                    <p class="text-dark font-weight-medium pt-4 mb-2">Badge #</p>
                                                    <p><span id="view-assign-badgeno"></span></p>
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
                                                <p><span id="view-assign-email"></span></p>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Address</p>
                                                <p><span id="view-assign-address"></span></p>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Contact Number</p>
                                                <p><span id="view-assign-contactno"></span></p>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Gender</p>
                                                <p><span id="view-assign-gender"></span></p>
                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <p class="text-dark font-weight-medium pt-4 mb-2">Date of Birth</p>
                                                        <p><span id="view-assign-birthday"></span></p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p class="text-dark font-weight-medium pt-4 mb-2">Age</p>
                                                        <p><span id="view-assign-age"></span> Years Old</p>
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
            <!-- END VIEW MODAL -->

            <!-- CHANGE STATION MODAL -->
                <div class="modal fade" id="change-station" tabindex="-1" role="dialog" aria-labelledby="exampleupdate-station" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div id="change-station-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                        <form id="change-station-form">
                        @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleupdate-station">Assign Station</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="change-station-alert-success" class="alert alert-success" style="display: none;" role="alert"></div>
                                    <div id="change-station-alert-danger" class="alert alert-danger" style="display: none;" role="alert"></div>
                                    <input type="hidden" id="change-station-id">
                                    <div class="card widget-profile px-0 py-4 border-0">
                                        <div class="card-img text-center mx-auto d-block rounded" style="height: 150px; width: 150px;">
                                            <img id="change-station-image" class="img-fluid" alt="user image">
                                        </div>
                                        <div class="card-body">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text font-weight-bolder">Name</span>
                                                </div>
                                                <input id="change-station-full-name" class="form-control" disabled>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text font-weight-bolder">Rank</span>
                                                </div>
                                                <input id="change-station-rank" class="form-control" disabled>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text font-weight-bolder">PNP ID #</span>
                                                </div>
                                                <input id="change-station-idno" class="form-control" disabled>
                                            </div>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text font-weight-bolder">BADGE #</span>
                                                </div>
                                                <input id="change-station-badgeno" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <hr class="w-100">
                                    </div>
                                    <div class="form-group">
                                    <label>Assign Station</label>
                                        <select name="station_id" class="form-control" required>
                                            <option selected disabled value="">--SELECT--</option>
                                            @foreach ($station as $assign)
                                            <option value="{{$assign->id}}">{{$assign->station_name}}</option>
                                            @endforeach
                                            <option value="unassign">Unassign Officer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Assign</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <!--END CHANGE STATION MODAL -->

            <!-- TRANSFER OFFICER MODAL -->
            <div class="modal fade" id="transfer-officer" tabindex="-1" role="dialog" aria-labelledby="exampleupdate-station" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div id="transfer-officer-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                        <form id="transfer-officer-form">
                        @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Authorize Officer</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="transfer-officer-alert-danger" class="alert alert-danger" style="display: none;" role="alert"></div>
                                    <h5>Are you sure you want to Authorize Officer <span id="transfer-officer-name"></span>?</h5>
                                    <input type="hidden" id="transfer-officer-id">
                                    <div class="form-row px-2 py-2">
                                        <label class="text-dark font-size-16">Input Password to continue</label>
                                        <input name="password" id="transfer-password" type="password" class="form-control" placeholder="Password" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                        <span class="invalid-feedback" id="transfer-password-inv">Please fill out this field</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Authorize</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <!--END TRANSFER OFFICER MODAL -->

            <!-- REMOVE OFFICER MODAL -->
                <div class="modal fade" id="remove-officer" tabindex="-1" role="dialog" aria-labelledby="exampleupdate-station" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div id="remove-officer-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                        <form id="remove-officer-form">
                        @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleupdate-station">Remove Officer</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="remove-officer-alert-danger" class="alert alert-danger" style="display: none;" role="alert"></div>
                                    <h5>Are you sure you want to remove <span id="remove-officer-name"></span>?</h5>
                                    <input type="hidden" id="remove-officer-id">
                                    <div class="form-row px-2 py-2">
                                        <label class="text-dark font-size-16">Input Password to continue</label>
                                        <input name="password" id="remove-password" type="password" class="form-control" placeholder="Password" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                        <span class="invalid-feedback" id="remove-password-inv">Please fill out this field</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Remove</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <!--END REMOVE OFFICER MODAL -->

            <!-- VIEW PENDING MODAL -->
            <div class="modal fade" id="view-pending" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormVIEW" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div id="view-pending-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                    <div class="modal-content" id="view-pending-form">
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
                                                <img id="view-pending-image" class="img-fluid" alt="user image">
                                            </div>
                                            <div class="card-body">
                                                <h4 class="py-2 text-dark"><span id="view-pending-name"></span></h4>
                                                <p><span id="view-pending-rank"></span></p>
                                            </div>
                                            <hr class="w-100">
                                            <div class="form-group row">
                                                <div class="col-sm-6 contact-info">
                                                    <p class="text-dark font-weight-medium pt-4 mb-2">PNP ID #</p>
                                                    <p><span id="view-pending-idno"></span></p>
                                                </div>
                                                <div class="col-sm-6 contact-info">
                                                    <p class="text-dark font-weight-medium pt-4 mb-2">Badge #</p>
                                                    <p><span id="view-pending-badgeno"></span></p>
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
                                                <p><span id="view-pending-email"></span></p>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Address</p>
                                                <p><span id="view-pending-address"></span></p>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Contact Number</p>
                                                <p><span id="view-pending-contactno"></span></p>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Gender</p>
                                                <p><span id="view-pending-gender"></span></p>
                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <p class="text-dark font-weight-medium pt-4 mb-2">Date of Birth</p>
                                                        <p><span id="view-pending-birthday"></span></p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p class="text-dark font-weight-medium pt-4 mb-2">Age</p>
                                                        <p><span id="view-pending-age"></span> Years Old</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <form id="view-pending-accept-form">
                            @csrf
                                <input type="hidden" id="view-pending-accept-id">
                                <button type="submit" class="btn btn-success"><i class="mdi mdi-check"></i>&nbsp;Accept Police Officer</button>
                            </form>
                            <form id="view-pending-deny-form">
                            @csrf
                                <input type="hidden" id="view-pending-deny-id">
                                <button type="submit" class="btn btn-danger"><i class="mdi mdi-close"></i>&nbsp;Deny Police Officer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END VIEW PENDING MODAL -->

            <!-- UPDATE OFFICER MODAL -->
            <div class="modal fade" id="update-officer" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormEDIT" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div id="update-officer-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                    <form id="update-officer-form">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalFormEDIT">Update Police Officer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <div id="update-officer-alert-success" class="alert alert-success" style="display: none;" role="alert"></div>
                            <div class="row no-gutters">
                                <div class="col-lg-4 col-xl-4">
                                    <div class="profile-content-left pt-5 pb-3 px-3 px-xl-8">
                                        <div class="card text-center widget-profile py-3 border-0">
                                            <div class="card-img mx-auto rounded" style="height: 150px; width: 150px;">
                                                <img class="img-fluid" alt="user image" id="update-officer-image">
                                            </div>
                                        </div>
                                        <hr class="w-100">
                                        <input type="hidden" id="update-officer-id">
                                        <div class="form-group py-4">
                                            <label>Update Image</label>
                                                <div class="custom-file mb-1">
                                                    <input name="image" type="file" class="custom-file-input" id="update-officer-img" accept="image/*" onchange="readURL(this);">
                                                    <label class="custom-file-label" for="img-up"><span id="update-officer-img-fn">Choose File . . .</span></label>
                                                </div>
                                            <span class="d-block mt-1" style="font-size: 85%">Only Image File Types are Accepted.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-xl-8">
                                    <div class="profile-content-right">
                                        <div class="header px-4 d-block">
                                            <h2>Personal Information</h2>
                                        </div>
                                        <hr class="w-100">
                                        <div class="tab-content px-3 px-xl-4" id="myTabContent">
                                            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="settings-tab">
                                                <div class="mt-4">
                                                    <div class="form-row py-2">
                                                        <div class="col-lg-4">
                                                            <label class="text-dark font-size-17">First name</label>
                                                            <input name="first_name" id="update-officer-first-name" type="text" class="form-control">
                                                            <span class="invalid-feedback">Please fill out this field.</span>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label class="text-dark font-size-17">Middle name</label>
                                                            <input name="middle_name" id="update-officer-middle-name" type="text" class="form-control">
                                                            <span class="invalid-feedback">Please fill out this field.</span>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label class="text-dark font-size-17">Last name</label>
                                                            <input name="last_name" id="update-officer-last-name" type="text" class="form-control">
                                                            <span class="invalid-feedback">Please fill out this field.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form py-2">
                                                        <label class="text-dark font-size-17">Rank</label>
                                                        <select name="rank_id" class="form-control">
                                                            <option id="update-officer-rank" selected></option>
                                                            @foreach(App\Rank::all() as $row)
                                                            <option value="{{$row->id}}">{{$row->name}} ({{$row->abbreviation}})</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="invalid-feedback">Please fill out this field.</span>
                                                    </div>
                                                    <div class="form-row py-2">
                                                        <div class="col-lg-6">
                                                            <label class="text-dark font-size-17">PNP ID #</label>
                                                            <input class="form-control" id="update-officer-idno" disabled>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="text-dark font-size-17">Badge #</label>
                                                            <input class="form-control" id="update-officer-badgeno" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="form py-2">
                                                        <label class="text-dark font-size-17">Email</label>
                                                        <input name="email" id="update-officer-email" type="email" class="form-control">
                                                        <span class="invalid-feedback">Please fill out this field.</span>
                                                    </div>
                                                    <div class="form py-2">
                                                        <label class="text-dark font-size-17">Date of Birth</label>
                                                        <input type="text" id="update-birthday-datepicker" class="form-control" placeholder="Birthday" onchange="$(this).removeClass('is-invalid')">
                                                        <input name="birthday" id="update-officer-birthday" type="date" class="form-control" style="display: none;" onchange="$(this).removeClass('is-invalid')">
                                                        <span class="invalid-feedback" id="update-officer-birthday-inv">Please fill out this field.</span>
                                                    </div>
                                                    <div class="form py-2">
                                                        <label class="text-dark font-size-17">Gender</label>
                                                        <select name="gender" id="update-officer-gender" class="form-control">
                                                            <option selected disabled value="">--SELECT--</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                        </select>
                                                        <span class="invalid-feedback">Please fill out this field.</span>
                                                    </div>
                                                    <div class="form py-2">
                                                        <label class="text-dark font-size-17">Contact No</label>
                                                            <div class="input-group mb-2">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="mdi mdi-phone"></i>
                                                                    </span>
                                                                </div>
                                                            <input name="contact_no" id="update-officer-contactno" type="text" class="form-control" data-mask="(999) 999-9999" placeholder="ex. (999) 999-9999">
                                                            <span class="invalid-feedback">Please fill out this field.</span>
                                                        </div>
                                                    </div>
                                                    <div class="form py-2">
                                                    <label class="text-dark font-size-17">Address</label>
                                                        <div class="input-group mb-2">
                                                            <input name="address" id="update-officer-address" type="text" class="form-control" placeholder="Street, City/Municapality, Zip Code">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="mdi mdi-map-marker"></i>
                                                                    </span>
                                                                </div>
                                                            <span class="invalid-feedback">Please fill out this field.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END UPDATE OFFICER MODAL -->

            

            </div>
        </div>
    </div>

    @include('pnpadmin.includes.notifications')

    @include('pnpadmin.includes.footer')

  </div>

@include('pnpadmin.includes.script')

<!-- ===================================== GOOGLE MAP ============================== -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{config('googlemap')['map_apikey']}}&callback=initMap&libraries=places"
    type="text/javascript"></script>
<!-- ================================== END GOOGLE MAP ==================================== -->

<!-- ================================== DATA TABLE SCRIPT ========================================= -->
<script src={{ asset("assets/plugins/data-tables/jquery.datatables.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.bootstrap4.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.responsive.min.js") }}></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#responsive-data-table').DataTable({
        "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
        processing: true,
        serverSide: true,
        language: {
            emptyTable: "No Police Officers Listed.",
            zeroRecords: "No Police Officer found. Try Searching another keyword."
        },
        ajax: {
            url: "{{ url('admin/officers') }}",
        },
        columns: [
            { data: 'last_name', render: function(data, type, row, meta) {
                return data + ", " + row.first_name + " " + row.middle_name;
            } },
            { data: 'get_rank.name', render: function(data, type, row) {
                return data+' ('+row.get_rank["abbreviation"]+')';
            } },
            { render: function(data, type, row, meta, full) {
                return '<a href="javascript:void(0);" data-id='+row.get_station.id+' data-toggle="modal" data-target="#view-station">'+row.get_station.station_name+'</a>';
            } },
            { data: 'action', orderable: false, className: 'text-center' },
        ]
    });
    $('#responsive-data-table-unassign').DataTable({
        "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
        processing: true,
        serverSide: true,
        language: {
            emptyTable: "No Unassigned Police Officers",
            zeroRecords: "No Police Officer found. Try Searching another keyword."
        },
        ajax: {
            url: "{{ url('admin/officers/unassign') }}",
        },
        columns: [
            { data: 'last_name', render: function(data, type, row) {
                    return data + ", " + row["first_name"] + " " + row["middle_name"];
            } },
            { data: 'get_rank.name', render: function(data, type, row) {
                return data+' ('+row.get_rank['abbreviation']+')';
            } },
            { data: 'id_no' },
            { data: 'badge_no' },
            { data: 'action', orderable: false, className: 'text-center' },
        ]
    });
    $('#responsive-data-table-pending').DataTable({
        "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
        processing: true,
        serverSide: true,
        language: {
            emptyTable: "No Pending Police Officers",
            zeroRecords: "No Police Officer found. Try Searching another keyword."
        },
        ajax: {
            url: "{{ url('admin/officers/pending') }}",
        },
        columns: [
            { data: 'last_name', render: function(data, type, row) {
                    return data + ", " + row["first_name"] + " " + row["middle_name"];
            } },
            { data: 'get_rank.name', render: function(data, type, row) {
                return data+' ('+row.get_rank['abbreviation']+')';
            } },
            { data: 'id_no' },
            { data: 'badge_no' },
            { data: 'action', orderable: false, className: 'text-center' },
        ]
    });
});
</script>
<!-- =================================== END DATA TABLE SCRIPT ====================================== -->

<!-- ===================================== AJAX =================================== -->
<script>
/* COUNT UNASSIGN */
$(document).ready(function() {
    function unassignCount() {
        $.ajax({
            url: "{{ url('admin/officers/unassigncount') }}",
            success: function(result) {
                if(result == 0) {
                    $('#unassign-count').hide();
                } else {
                    $('#unassign-count').show().html(result);
                }
            },
        });
    }
    unassignCount();
    setInterval(unassignCount, 5000);
});
/* END COUNT UNASSIGN */

/* ADD OFFICER */
$('#add-officer-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#add-officer .modal-dialog').addClass('modal-dialog-centered');
    $('#add-officer-loader').show();

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "{{ url('admin/officers/addofficer') }}",
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if(result.error) {
                $('#add-officer-alert-danger').show().html(result.error);
                    setTimeout(function() {
                $('#add-officer-alert-danger').fadeOut('slow');
                }, 3000);
                $('#id_no').addClass('is-invalid');
                $('#badge_no').addClass('is-invalid');
                $('#id_no-inv').html('Officer Already Exists');
                $('#badge_no-inv').html('Officer Already Exists');
            } 
            if(result.errorbday) {
                $('#birthday').addClass('is-invalid');
                $('#birthday-datepicker').addClass('is-invalid');
                $('#birthday-inv').html(result.errorbday)
            } 
            if(result.success) {
                $('#add-officer-alert-success').show().html(result.success);
                $('#file-change').prop('src', '{{ asset('uploads/user.jpg') }}');
                $('#file-upload-filename').html('Choose File . . .');
                $('#birthday').removeClass('is-invalid');
                $('#responsive-data-table').DataTable().ajax.reload();
                $('#responsive-data-table-unassign').DataTable().ajax.reload();
                document.getElementById('add-officer-form').reset();
                setTimeout(function() {
                    $('#add-officer-alert-success').fadeOut('slow');
                }, 5000);
            }
        },
        complete: function() {
            $('#add-officer .modal-dialog').removeClass('modal-dialog-centered');
            $('#add-officer-loader').hide();
            $('#add-officer-form').show();
        },
        error: function(error) {
            console.log(error);
        }
    });
});
/* END ADD OFFICER */

/* VIEW STATION */
$('#view-station').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#view-station .modal-dialog').addClass('modal-dialog-centered');
    $('#view-station-form').hide();
    $('#view-station-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/station/view/') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            var mapview = new google.maps.Map(document.getElementById('view-station-map'),{
                center: {
                    lat: data.location_lat,
                    lng: data.location_lng
                },
                zoom:15
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

            $('#view-station .modal-dialog').removeClass('modal-dialog-centered');
            $('#view-station-name').html(data.station_name);
            $('#view-station-location-name').html(data.location_name);
            $('#view-station-contactno').html(data.station_contactno);
            if(data.image == "TBD") {
                $('#view-station-image').attr('src', '{{asset("assets/img/pnpseal.png")}}');
            } else {
                $('#view-station-image').attr('src', '{{asset("/")}}'+data.image);
            }
        },
        complete: function() {
            $('#view-station-loader').hide();
            $('#view-station-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
})
/* END VIEW STATION */

/* VIEW OFFICER */
$('#view-assign').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#view-assign .modal-dialog').addClass('modal-dialog-centered');
    $('#view-assign-form').hide();
    $('#view-assign-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/officers/view/') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#view-assign .modal-dialog').removeClass('modal-dialog-centered');
            var img = data.image;
            var bday = moment(data.birthday).format('LL');
            var age = moment().diff(data.birthday, 'years');

            $('#view-assign-name').html(data.last_name+", "+data.first_name+" "+data.middle_name);
            $('#view-assign-rank').html(data.get_rank.name+' ('+data.get_rank.abbreviation+')');
            $('#view-assign-idno').html(data.id_no);
            $('#view-assign-badgeno').html(data.badge_no);
            $('#view-assign-email').html(data.email);
            $('#view-assign-address').html(data.address);
            $('#view-assign-contactno').html(data.contact_no);
            $('#view-assign-gender').html(data.gender);
            $('#view-assign-birthday').html(bday);
            $('#view-assign-age').html(age);
            if(img == "TBD") {
                $('#view-assign-image').attr('src', '{{asset("uploads/user.jpg")}}');
            } else {
                $('#view-assign-image').attr('src', '{{asset("/")}}'+img);
            }
        },
        complete: function() {
            $('#view-assign-loader').hide();
            $('#view-assign-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
})
/* END VIEW OFFICER */

/* UPDATE OFFICER */
$('#update-officer').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#update-officer .modal-dialog').addClass('modal-dialog-centered');
    $('#update-officer-form').hide();
    $('#update-officer-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('admin/officers/view') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            var update_officer_image = data.image;
            var bdaydatepicker = moment(data.birthday).format('MMMM DD, Y');

            $('#update-birthday-datepicker').daterangepicker({
                maxDate: moment(),
                startDate: moment(data.birthday),
                singleDatePicker: true,
                showDropdowns: true,
                autoApply: true,
                locale: {
                    format: "MMMM DD, Y",
                }
            }, function(start, end, label) {
                $('#update-officer-birthday').val(start.format('YYYY-MM-DD'));
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            });

            $('#update-officer-id').val(data.id);
            $('#update-officer-first-name').val(data.first_name);
            $('#update-officer-middle-name').val(data.middle_name);
            $('#update-officer-last-name').val(data.last_name);
            $('#update-officer-rank').attr('value', data.rank_id).html(data.get_rank.name);
            $('#update-officer-idno').val(data.id_no);
            $('#update-officer-badgeno').val(data.badge_no);
            $('#update-officer-email').val(data.email);
            $('#update-birthday-datepicker').val(bdaydatepicker);
            $('#update-officer-birthday').val(data.birthday);
            $('#update-officer-gender').val(data.gender);
            $('#update-officer-address').val(data.address);
            $('#update-officer-contactno').val(data.contact_no);
            if(update_officer_image == "TBD") {
                $('#update-officer-image').attr('src', '{{asset("uploads/user.jpg")}}');
            } else {
                $('#update-officer-image').attr('src', '{{asset("/")}}'+update_officer_image);
            }
        },
        complete: function() {
            $('#update-officer .modal-dialog').removeClass('modal-dialog-centered');
            $('#update-officer-loader').hide();
            $('#update-officer-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
})
$('#update-officer-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#update-officer .modal-dialog').addClass('modal-dialog-centered');
    $('#update-officer-loader').show();

    var update_officer_id = $('#update-officer-id').val();

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "/admin/officers/updateofficer/"+update_officer_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if(result.errorbday) {
                $('#update-officer-birthday').addClass('is-invalid');
                $('#update-birthday-datepicker').addClass('is-invalid');
                $('#update-officer-birthday-inv').html(result.errorbday)
            } else {
                $('#update-officer-alert-success').show().html(result.success);
                $('#update-officer-img-fn').html('Choose File . . .');
                $('#update-officer-birthday').removeClass('is-invalid');
                $('#responsive-data-table').DataTable().ajax.reload();
                $('#responsive-data-table-unassign').DataTable().ajax.reload();
                setTimeout(function() {
                    $('#update-officer-alert-success').fadeOut('slow');
                }, 5000);
            }
        },
        complete: function() {
            $('#update-officer .modal-dialog').removeClass('modal-dialog-centered');
            $('#update-officer-loader').hide();
            $('#update-officer-form').show();
        },
        error: function(error) {
            console.log(error);
        }
    });
});
/* END UPDATE OFFICER */

/* CHANGE STATION */
$('#change-station').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#change-station .modal-dialog').addClass('modal-dialog-centered');
    $('#change-station-form').hide();
    $('#change-station-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('admin/officers/view') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            var change_station_image = data.image;

            $('#change-station-id').val(data.id);
            $('#change-station-full-name').val(data.last_name+", "+data.first_name+" "+data.middle_name);
            $('#change-station-rank').val(data.get_rank.name+" ("+data.get_rank.abbreviation+")");
            $('#change-station-idno').val(data.id_no);
            $('#change-station-badgeno').val(data.badge_no);
            if(change_station_image == "TBD") {
                $('#change-station-image').attr('src', '{{asset("uploads/user.jpg")}}');
            } else {
                $('#change-station-image').attr('src', '{{asset("/")}}'+change_station_image);
            }
        },
        complete: function() {
            $('#change-station .modal-dialog').removeClass('modal-dialog-centered');
            $('#change-station-loader').hide();
            $('#change-station-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
})
$('#change-station-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#change-station .modal-dialog').addClass('modal-dialog-centered');
    $('#change-station-loader').show();

    var change_station_id = $('#change-station-id').val();

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "/admin/officers/assignstation/"+change_station_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if(result.error) {
                $('#change-station .modal-dialog').removeClass('modal-dialog-centered');
                $('#change-station-loader').hide();
                $('#change-station-form').show();
                $('#change-station-alert-danger').show().html(result.error);
                setTimeout(function() {
                    $('#change-station-alert-danger').fadeOut('slow');
                }, 3000);
            } else {
                $('#change-station .modal-dialog').removeClass('modal-dialog-centered');
                $('#change-station-loader').hide();
                $('#change-station-form').show();
                $('#change-station-alert-success').show().html(result.success);
                $('#responsive-data-table').DataTable().ajax.reload();
                $('#responsive-data-table-unassign').DataTable().ajax.reload();
                setTimeout(function() {
                    $('#change-station-alert-success').fadeOut('slow');
                }, 3000);
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
});
/* END CHANGE STATION */

/* VIEW OFFICER */
$('#view-pending').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#view-pending .modal-dialog').addClass('modal-dialog-centered');
    $('#view-pending-form').hide();
    $('#view-pending-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/officers/view/') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#view-pending .modal-dialog').removeClass('modal-dialog-centered');
            var img = data.image;
            var bday = moment(data.birthday).format('LL');
            var age = moment().diff(data.birthday, 'years');

            $('#view-pending-accept-id').val(data.id);
            $('#view-pending-deny-id').val(data.id);
            $('#view-pending-name').html(data.last_name+", "+data.first_name+" "+data.middle_name);
            $('#view-pending-rank').html(data.get_rank.name+' ('+data.get_rank.abbreviation+')');
            $('#view-pending-idno').html(data.id_no);
            $('#view-pending-badgeno').html(data.badge_no);
            $('#view-pending-email').html(data.email);
            $('#view-pending-address').html(data.address);
            $('#view-pending-contactno').html(data.contact_no);
            $('#view-pending-gender').html(data.gender);
            $('#view-pending-birthday').html(bday);
            $('#view-pending-age').html(age);
            if(img == "TBD") {
                $('#view-pending-image').attr('src', '{{asset("uploads/user.jpg")}}');
            } else {
                $('#view-pending-image').attr('src', '{{asset("/")}}'+img);
            }
        },
        complete: function() {
            $('#view-pending-loader').hide();
            $('#view-pending-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
});
$('#view-pending-accept-form').on('submit', function (event) {
    event.preventDefault();

    $('#view-pending .modal-dialog').addClass('modal-dialog-centered');
    $('#view-pending-form').hide();
    $('#view-pending-loader').show();

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

    var accept_id = $('#view-pending-accept-id').val();

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "/admin/officers/accept-officer/"+accept_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if(result.success) {
                $('#view-pending').modal('hide');
                $('#responsive-data-table').DataTable().ajax.reload();
                $('#responsive-data-table-unassign').DataTable().ajax.reload();
                $('#responsive-data-table-pending').DataTable().ajax.reload();
                toastr.success("", result.success);
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
});
$('#view-pending-deny-form').on('submit', function (event) {
    event.preventDefault();

    $('#view-pending .modal-dialog').addClass('modal-dialog-centered');
    $('#view-pending-form').hide();
    $('#view-pending-loader').show();

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

    var deny_id = $('#view-pending-deny-id').val();

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "/admin/officers/deny-officer/"+deny_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if(result.success) {
                $('#view-pending').modal('hide');
                $('#responsive-data-table').DataTable().ajax.reload();
                $('#responsive-data-table-unassign').DataTable().ajax.reload();
                $('#responsive-data-table-pending').DataTable().ajax.reload();
                toastr.error("", result.success);
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
});
/* END VIEW OFFICER */

/* REMOVE OFFICER */
$('#remove-officer').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#remove-officer .modal-dialog').addClass('modal-dialog-centered');
    $('#remove-officer-form').hide();
    $('#remove-officer-loader').show();
    $('#remove-password').val("").removeClass('is-invalid');

    $.ajax({
        type: "GET",
        url: "{{ url('admin/officers/view') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#remove-officer-id').val(data.id);
            $('#remove-officer-name').html(data.get_rank.abbreviation+" "+data.first_name+" "+data.last_name);
        },
        complete: function() {
            $('#remove-officer .modal-dialog').removeClass('modal-dialog-centered');
            $('#remove-officer-loader').hide();
            $('#remove-officer-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
});
$('#remove-officer-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#remove-officer .modal-dialog').addClass('modal-dialog-centered');
    $('#remove-officer-loader').show();

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
    
    var remove_officer_id = $('#remove-officer-id').val();

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "/admin/officers/removeofficer/"+remove_officer_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if(result.errorpw) {
                $('#remove-officer .modal-dialog').removeClass('modal-dialog-centered');
                $('#remove-officer-loader').hide();
                $('#remove-officer-form').show();
                $('#remove-password').addClass('is-invalid').val("");
                $('#remove-password-inv').html(result.errorpw);
            }
            if(result.error) {
                $('#remove-officer .modal-dialog').removeClass('modal-dialog-centered');
                $('#remove-officer-loader').hide();
                $('#remove-officer-form').show();
                $('#remove-officer-alert-danger').show().html(result.error);
                $('#remove-password').val("");
                setTimeout(function() {
                    $('#remove-officer-alert-danger').fadeOut('slow');
                }, 3000);
            }
            if(result.success) {
                $('#remove-officer').modal('hide');
                $('#responsive-data-table').DataTable().ajax.reload();
                $('#responsive-data-table-unassign').DataTable().ajax.reload();
                $('#remove-password').val("");
                toastr.error("Data transfered to Archive", result.success);
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
});
/* END REMOVE OFFICER */

/* TRANSFER OFFICER */
$('#transfer-officer').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#transfer-officer .modal-dialog').addClass('modal-dialog-centered');
    $('#transfer-officer-form').hide();
    $('#transfer-officer-loader').show();
    $('#transfer-password').val("").removeClass('is-invalid');

    $.ajax({
        type: "GET",
        url: "{{ url('admin/officers/view') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#transfer-officer-id').val(data.id);
            $('#transfer-officer-name').html(data.get_rank.abbreviation+" "+data.first_name+" "+data.last_name);
        },
        complete: function() {
            $('#transfer-officer .modal-dialog').removeClass('modal-dialog-centered');
            $('#transfer-officer-loader').hide();
            $('#transfer-officer-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
});
$('#transfer-officer-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#transfer-officer .modal-dialog').addClass('modal-dialog-centered');
    $('#transfer-officer-loader').show();

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
    
    var transfer_officer_id = $('#transfer-officer-id').val();

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "/admin/officers/transferofficer/"+transfer_officer_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if(result.error) {
                $('#transfer-officer .modal-dialog').removeClass('modal-dialog-centered');
                $('#transfer-officer-loader').hide();
                $('#transfer-officer-form').show();
                $('#transfer-password').addClass('is-invalid').val("");
                $('#transfer-password-inv').html(result.error);
            }
            if(result.success) {
                $('#transfer-officer').modal('hide');
                $('#responsive-data-table').DataTable().ajax.reload();
                $('#responsive-data-table-unassign').DataTable().ajax.reload();
                $('#transfer-password').val("");
                toastr.success("Police Officer Authorized", result.success);
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
});
/* END TRANSFER OFFICER */
</script>
<!-- ======================================== END AJAX =========================================== -->

<!-- =================================== First Letter Uppercase SCRIPT ================================= -->
<script>
$('input[type="text"]').keyup(function(evt){
    var txt = $(this).val();
    $(this).val(txt.replace(/^(.)|\s(.)/g, function($1){ return $1.toUpperCase( ); }));
});
</script>
<!-- =================================== END First Letter Uppercase SCRIPT ================================= -->

<!-- ================================== FILE/IMAGE PREVIEWS ===================================== -->
<script type="text/javascript">
    var x = document.getElementById('update-officer-img');
    var y = document.getElementById('update-officer-img-fn');
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
                $('#file-change').attr('src', e.target.result);
                $('#update-officer-image').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<!-- ======================================= END FILE/IMAGE PREVIEWS ==================================== -->

<!-- ================================ TEXTFIELDS ARE REQUIRED ============================ -->
<script>
$(document).ready(function() {
    $('input[type="text"]').attr("required", true).on("invalid", function() {
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

<!-- ======================================= DATEPICKER ==================================== -->
<script>
    $(document).ready(function() {
        $('#birthday').val(moment().format('YYYY-MM-DD'));
        $('#birthday-datepicker').daterangepicker({
            maxDate: moment(),
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            locale: {
                format: "MMMM DD, Y",
            }
        }, function(start, end, label) {
            $('#birthday').val(start.format('YYYY-MM-DD'));
            console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        });
    });
</script>
<!-- ======================================= DATEPICKER ==================================== -->

</body>
</html>

            
@else 
    @include('PNPadmin.includes.419')
@endif