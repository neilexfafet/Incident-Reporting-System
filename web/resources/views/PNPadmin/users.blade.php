@if(Auth::guard('admin')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>PNP | Users</title>
    @include('pnpadmin.includes.link')
    <style>
        .modal { z-index: 1001 !important;} 
        .modal-backdrop {z-index: 1000 !important;}
        .pac-container {z-index: 1055 !important;}
    </style>
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body" onload=display_ct();>

<script>
    NProgress.start();
</script>

    @if (session()->has('error'))
        <div id="toaster-alert-error"></div>
    @endif
    @if (session()->has('success'))
        <div id="toaster-alert-success"></div>
    @endif

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
                                <h2>Admin Users</h2>
                                <a href="javascript:void(0);" class="btn btn-outline-primary text-uppercase" data-toggle="modal" data-target="#addadmin">
                                    <i class=" mdi mdi-account"></i>&nbsp;&nbsp;&nbsp;Add Admin Account
                                </a>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="account-tab" data-toggle="tab" href="#account" role="tab" aria-controls="account" aria-selected="true">Accounts</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="officer-tab" data-toggle="tab" href="#officer" role="tab" aria-controls="officer" aria-selected="false">Authorized Officers</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent1">
                                    <div class="tab-pane pt-3 fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                                        <div class="responsive-data-table">
                                            <table id="responsive-data-table" class="table table-hover dt-responsive" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Admin Name</th>
                                                        <th>Admin Contact #</th>
                                                        <th>Admin Location</th>
                                                        <th>Date Created</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($data as $row)
                                                    <tr>
                                                        <td>{{$row->username}}</td>
                                                        <td>{{$row->admin_name}}</td>
                                                        <td>{{$row->admin_contactno}}</td>
                                                        <td>{{$row->admin_location}}</td>
                                                        <td>{{$row->created_at->format('F j, Y')}} at {{$row->created_at->format('h:m A')}}</td>
                                                        <td>
                                                        @if($row->id != Auth::guard('admin')->user()->id)
                                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#remove_{{$row->id}}"><i class="mdi mdi-close"></i>&nbsp;Remove</button>
                                                        @else
                                                            <button class="btn btn-danger btn-sm" disabled><i class="mdi mdi-close"></i>&nbsp;Remove</button>
                                                        @endif
                                                        </td>
                                                    </tr>

                                                    <!-- REMOVE ADMIN MODAL -->
                                                        <div class="modal fade" id="remove_{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalFormTitle">Remove {{$row->admin_name}}</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="{{ url('admin/users/removeadmin') }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="id" value="{{$row->id}}">
                                                                        Are you sure you want to remove this account?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="ladda-button btn btn-warning" data-style="expand-right">
                                                                            <span class="ladda-label">Remove</span>
                                                                            <span class="ladda-spinner"></span>
                                                                        </button>
                                                                    </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <!--END REMOVE ADMIN MODAL -->
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane pt-3 fade" id="officer" role="tabpanel" aria-labelledby="officer-tab">
                                        <div class="responsive-data-table">
                                            <table id="responsive-data-table2" class="table table-hover dt-responsive" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th width="20%">Name</th>
                                                        <th width="20%">Rank</th>
                                                        <th width="20%">PNP ID</th>
                                                        <th width="20%">Badge</th>
                                                        <th width="20%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($officer as $row)
                                                    <tr>
                                                        <td>{{$row->last_name}}, {{$row->first_name}}</td>
                                                        <td>{{$row->getRank->name}} ({{$row->getRank->abbreviation}})</td>
                                                        <td>{{$row->id_no}}</td>
                                                        <td>{{$row->badge_no}}</td>
                                                        <td align="center">
                                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                                <button data-id="#" data-toggle="modal" data-target="#view-assign-{{$row->id}}" type="button" class="btn btn-primary btn-sm">
                                                                    <span class="mdi mdi-eye">&nbsp;View</span>
                                                                </button>
                                                                <div class="dropdown" title="Edit">
                                                                    <button class="btn btn-warning btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                                                        <span class="mdi mdi-square-edit-outline">&nbsp;Edit</span>
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                                            data-id="{{$row->id}}"
                                                                            data-toggle="modal" 
                                                                            data-target="#update-officer">Update Officer</a>
                                                                        @if(count($officer->where('status', 'admin')) != 1)
                                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                                            data-id="{{$row->id}}"
                                                                            data-toggle="modal" 
                                                                            data-target="#unauthorize-officer">Unauthorize Officer</a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- VIEW MODAL -->
                                                    <div class="modal fade" id="view-assign-{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormVIEW" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
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
                                                                                        @if($row->image == "TBD")
                                                                                        <img src="{{asset('uploads/user.jpg')}}" class="img-fluid" alt="user image">
                                                                                        @else
                                                                                        <img src="{{asset($row->image)}}" class="img-fluid" alt="user image">
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <h4 class="py-2 text-dark">{{$row->first_name}} {{$row->middle_name}} {{$row->last_name}}</h4>
                                                                                        <p>{{$row->getRank->name}} ({{$row->getRank->abbreviation}})</p>
                                                                                    </div>
                                                                                    <hr class="w-100">
                                                                                    <div class="form-group row">
                                                                                        <div class="col-sm-6 contact-info">
                                                                                            <p class="text-dark font-weight-medium pt-4 mb-2">PNP ID #</p>
                                                                                            <p>{{$row->id_no}}</p>
                                                                                        </div>
                                                                                        <div class="col-sm-6 contact-info">
                                                                                            <p class="text-dark font-weight-medium pt-4 mb-2">Badge #</p>
                                                                                            <p>{{$row->badge_no}}</p>
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
                                                                                        <p>{{$row->email}}</p>
                                                                                        <p class="text-dark font-weight-medium pt-4 mb-2">Address</p>
                                                                                        <p>{{$row->address}}</p>
                                                                                        <p class="text-dark font-weight-medium pt-4 mb-2">Contact Number</p>
                                                                                        <p>{{$row->contact_no}}</p>
                                                                                        <p class="text-dark font-weight-medium pt-4 mb-2">Gender</p>
                                                                                        <p>{{$row->gender}}</p>
                                                                                        <div class="form-group row">
                                                                                            <div class="col-sm-6">
                                                                                                <p class="text-dark font-weight-medium pt-4 mb-2">Date of Birth</p>
                                                                                                <p>{{Carbon\Carbon::parse($row->birthday)->format('F j, Y')}}</p>
                                                                                            </div>
                                                                                            <div class="col-sm-6">
                                                                                                <p class="text-dark font-weight-medium pt-4 mb-2">Age</p>
                                                                                                <p>{{Carbon\Carbon::parse($row->birthday)->age}} Years Old</p>
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
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


        <!-- ADD ADMIN MODAL -->
            <div class="modal fade" id="addadmin" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalFormTitle">Add Admin</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ url('admin/users/addadmin') }}" method="POST">
                            @csrf
                            <div class="form mb-3">
                                <label class="text-dark font-size-16">Username</label>
                                <input name="username" type="text" class="form-control" placeholder="Username"/> 
                                <div class="invalid-feedback">Please fill out this field</div>
                            </div>
                            <div class="form mb-3">
                                <label class="text-dark font-size-16">Admin Name</label>
                                <input name="admin_name" type="text" class="form-control" placeholder="Name"/> 
                                <div class="invalid-feedback">Please fill out this field</div>
                            </div>
                            <div class="form mb-3">
                                <label class="text-dark font-size-16">Admin Contact #</label>
                                <input name="admin_contactno" type="text" class="form-control" placeholder="(999) 999-9999" data-mask="(999) 999-9999"/> 
                                <div class="invalid-feedback">Please fill out this field</div>
                            </div>
                            <div class="form mb-3">
                                <label class="text-dark font-size-16">Admin Location</label>
                                <input name="admin_location" id="admin_location" type="text" class="form-control" placeholder="Location"/> 
                                <div class="invalid-feedback">Please fill out this field</div>
                            </div>
                            <div id="map" class="map-container"></div>
                            <div class="form-row mb-3">
                                <div class="col-md-6">
                                <label class="text-dark font-size-16">Lat</label>
                                <input name="location_lat" id="location_lat" type="text" class="form-control" disabled/> 
                                </div>
                                <div class="col-md-6">
                                <label class="text-dark font-size-16">Lng</label>
                                <input name="location_lng" id="location_lat" type="text" class="form-control" disabled/> 
                                </div>
                            </div>
                            <div class="form mb-3">
                                <label class="text-dark font-size-16">Password</label>
                                <input name="password" id="password" type="password" class="form-control" placeholder="Password"/> 
                                <div class="invalid-feedback">Please fill out this field</div>
                            </div>
                            <div class="form mb-3">
                                <label class="text-dark font-size-16">Confirm Password</label>
                                <input type="password" id="confirm_password" class="form-control" placeholder="Confirm Password"/> 
                                <div class="invalid-feedback">Please fill out this field</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="ladda-button btn btn-primary" data-style="expand-right">
                                <span class="ladda-label">Submit</span>
                                <span class="ladda-spinner"></span>
                            </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <!--END ADD ADMIN MODAL -->

        <!-- UNAUTHORIZE OFFICER MODAL -->
            <div class="modal fade" id="unauthorize-officer" tabindex="-1" role="dialog" aria-labelledby="exampleupdate-station" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div id="unauthorize-officer-loader" class="col-sm-12" style="display:none;">@include('PNPadmin.includes.loader')</div>
                    <form id="unauthorize-officer-form">
                    @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Unauthorize</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="unauthorize-officer-alert-danger" class="alert alert-danger" style="display: none;" role="alert"></div>
                                <h5>Are you sure you want to Unauthorize <span id="unauthorize-officer-name"></span>?</h5>
                                <input type="hidden" id="unauthorize-officer-id">
                                <div class="form-row px-2 py-2">
                                    <label class="text-dark font-size-16">Input Password to continue</label>
                                    <input name="password" id="unauthorize-password" type="password" class="form-control" placeholder="Password">
                                    <span class="invalid-feedback" id="unauthorize-password-inv">Please fill out this field</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Unauthorize</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <!--END UNAUTHORIZE OFFICER MODAL -->

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

@include('PNPadmin.includes.notifications')
@include('pnpadmin.includes.footer')

</div>

@include('pnpadmin.includes.script')

<!-- ===================================== GOOGLE MAP ============================== -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{config('googlemap')['map_apikey']}}&callback=initMap&libraries=places"
    type="text/javascript"></script>
<!-- ================================== END GOOGLE MAP ==================================== -->

<!-- ===================================== GOOGLE MAP ============================ -->
<script>
  function initMap() {
    var mapOptions, map, marker, searchBox,
        infoWindow = '',
        addressEl = $('#admin_location').get(0),
        latEl = $('#location_lat').get(0),
        longEl = $('#location_lng').get(0),
        element = $('#map').get(0);
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
        map.fitBounds( bounds );  // Fit to the bound
        var listener = google.maps.event.addListener(map, "idle", function() { 
            if(map.getZoom() > 15) {
                map.setZoom(15); // This function sets the zoom to 15, meaning zooms to level 15.
            };
            google.maps.event.removeListener(listener); 
        });
        // console.log( map.getZoom() );
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
  }
</script>
<!-- ================================= END GOOGLE MAP =============================== -->

<!-- ==================DATA TABLE SCRIPT==================== -->
<script src={{ asset("assets/plugins/data-tables/jquery.datatables.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.bootstrap4.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.responsive.min.js") }}></script>
<script>
$(document).ready(function() {
    $('#responsive-data-table').DataTable({
        "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
    });
    $('#responsive-data-table2').DataTable({
        "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">'
    });
});
</script>
<!-- =============== END DATA TABLE ======================= -->

<!-- =========================== AJAX =============================== -->
<script>
$(document).ready(function() {
    /* SESSION */
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
        toastr.info(localStorage.getItem("success"), "");
        localStorage.clear();
    }
    /* SESSION */

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

    /* UNAUTHORIZE OFFICER */
    $('#unauthorize-officer').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);

        $('#unauthorize-officer .modal-dialog').addClass('modal-dialog-centered');
        $('#unauthorize-officer-form').hide();
        $('#unauthorize-officer-loader').show();
        $('#unauthorize-password').val("");

        $.ajax({
            type: "GET",
            url: "{{ url('admin/officers/view') }}"+"/"+id,
            dataType: "JSON",
            success: function(data) {
                $('#unauthorize-officer-id').val(data.id);
                $('#unauthorize-officer-name').html(data.get_rank.abbreviation+" "+data.first_name+" "+data.last_name);
            },
            complete: function() {
                $('#unauthorize-officer .modal-dialog').removeClass('modal-dialog-centered');
                $('#unauthorize-officer-loader').hide();
                $('#unauthorize-officer-form').show();
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
    $('#unauthorize-officer-form').on('submit', function (event) {
        event.preventDefault();

        $(this).hide();
        $('#unauthorize-officer .modal-dialog').addClass('modal-dialog-centered');
        $('#unauthorize-officer-loader').show();

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
        
        var unauthorize_officer_id = $('#unauthorize-officer-id').val();

        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: "/admin/users/unauthorizeofficer/"+unauthorize_officer_id,
            data: new FormData(this),
            dataType: "JSON",
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                if(result.error) {
                    $('#unauthorize-officer .modal-dialog').removeClass('modal-dialog-centered');
                    $('#unauthorize-officer-loader').hide();
                    $('#unauthorize-officer-form').show();
                    $('#unauthorize-password').addClass('is-invalid');
                    $('#unauthorize-password-inv').html(result.error);
                }
                if(result.success) {
                    localStorage.setItem("success", result.success);
                    window.location = "{{url('admin/users')}}";
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
    /* END UNAUTHORIZE OFFICER */
});
</script>
<!-- =========================== AJAX =============================== -->

<!-- ========= REPEAT PASSWORD ======== -->
<script>
    var password = document.getElementById("password")
    , confirm_password = document.getElementById("confirm_password");

    function validatePassword(){
        if(password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Passwords Don't Match");
        } else {
            confirm_password.setCustomValidity('');
        }
    }
    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
</script>
<!-- ====================== END REPEAT PASSWORD ================== -->

<!-- =================== TOASTER ====================================== -->
@if (session()->has('success'))
<script type="text/javascript">
  var toaster = $('#toaster-alert-success');
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
        toastr.success("{{ session()->get('success') }}", "Success!");
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
@if (session()->has('error'))
<script type="text/javascript">
  var toaster = $('#toaster-alert-error');
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
        toastr.error("{{ session()->get('error') }}", "Error!");
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
<!-- ======================= END TOASTER ========================== -->

<!-- ================================ TEXTFIELDS ARE REQUIRED ============================ -->
<script>
$(document).ready(function() {
    $('input[type="text"]').attr("required", true).on("invalid", function() {
        $(this).addClass('is-invalid');
    }).on("input", function() {
        $(this).removeClass('is-invalid');
    });
    
    $('input[type="password"]').attr("required", true).on("invalid", function() {
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


</body>
</html>


@else 
    @include('PNPadmin.includes.419')
@endif