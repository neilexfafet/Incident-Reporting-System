<!DOCTYPE html>
<html lang="en">
<head>
    <title> SETUP WEBSITE </title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
    <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />
    <!-- PLUGINS CSS STYLE -->
    <link href={{ asset("assets/plugins/nprogress/nprogress.css") }} rel="stylesheet" />
    <!-- SLEEK CSS -->
    <link id="sleek-css" rel="stylesheet" href={{ asset("assets/css/sleek.css") }} />
    <!-- FAVICON -->
    <link href={{ asset("assets/img/pnpseal.png") }} rel="shortcut icon" />
    <link href={{ asset("assets/plugins/ladda/ladda-themeless.min.css") }} rel="stylesheet" />
    <link href={{ asset("assets/plugins/daterangepicker/daterangepicker.css") }} rel="stylesheet" />
    <link href={{ asset("assets/plugins/toastr/toastr.min.css") }} rel="stylesheet" />
    <!--
        HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
    -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src={{ asset("assets/plugins/nprogress/nprogress.js") }}></script>
</head>
<body class="" id="body">

<script>
    NProgress.start();
</script>

<div class="container py-6">
    <div class="card card-default">
        <div class="card-header border-bottom d-flex justify-content-center">
            <h2>Welcome! It seems this Website is on a fresh start. Setup up accounts</h2>
        </div>
        <div class="card-body">
        @if(Session::has('successadmin'))
            <div class="alert alert-dismissible fade show alert-success" role="alert">
                Admin Account Successfully Created! Please Register Police Officer to Atuhorize to use the Admin Account.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if(count(App\Admin::all()) == 0)
            <form action="{{url('PNP/setup/admin')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="form mb-3">
                        <label>Upload Image (optional)</label>
                        <div class="custom-file mb-1">
                            <input name="image" type="file" class="custom-file-input" id="file-upload" accept="image/*" onchange="readURL(this);">
                            <label class="custom-file-label" for="file-upload"><span id="file-upload-filename">Choose File . . .</span></label>
                            <span class="d-block mt-1" style="font-size: 85%">Only Image File Types are Accepted.</span>
                        </div>
                    </div>
                    <div class="form mb-3">
                        <label class="text-dark font-size-16">Admin Name</label>
                        <input name="admin_name" type="text" class="form-control" placeholder="Name" required/> 
                        <div class="invalid-feedback">Please fill out this field</div>
                    </div>
                    <div class="form mb-4">
                        <label class="text-dark font-size-16">Admin Contact #</label>
                        <input name="admin_contactno" type="text" class="form-control" placeholder="(999) 999-9999" data-mask="(999) 999-9999" required/> 
                        <div class="invalid-feedback">Please fill out this field</div>
                    </div>
                    <hr class="separator">
                    <h4 class="pb-4">Login Information</h4>
                    <div class="form mb-3">
                        <label class="text-dark font-size-16">Username</label>
                        <input name="username" type="text" class="form-control" placeholder="Username" required/> 
                        <div class="invalid-feedback">Please fill out this field</div>
                    </div>
                    <div class="form mb-3">
                        <label class="text-dark font-size-16">Password</label>
                        <input name="password" id="password" type="password" class="form-control" placeholder="Password" required/> 
                        <div class="invalid-feedback">Please fill out this field</div>
                    </div>
                    <div class="form mb-3">
                        <label class="text-dark font-size-16">Confirm Password</label>
                        <input type="password" id="confirm_password" class="form-control" placeholder="Confirm Password" required/> 
                        <div class="invalid-feedback">Please fill out this field</div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form mb-3">
                        <label class="text-dark font-size-16">Admin Location</label>
                        <input name="admin_location" type="text" class="form-control" placeholder="Location" required/> 
                        <div class="invalid-feedback">Please fill out this field</div>
                    </div>
                    <div id="map" class="map-container"></div>
                    <div class="form-row mb-3">
                        <div class="col-md-6">
                        <label class="text-dark font-size-16">Lat</label>
                        <input name="location_lat" type="text" class="form-control" required/> 
                        </div>
                        <div class="col-md-6">
                        <label class="text-dark font-size-16">Lng</label>
                        <input name="location_lng" type="text" class="form-control" required/> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center py-2">
                <button type="submit" class="btn btn-lg btn-primary">Register Account</button>
            </div>
            </form>
        @else
            <form action="{{url('PNP/setup/officer')}}" method="POST">
            @csrf
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
                                <h2>Police Officer Information</h2>
                            </div>
                            <hr class="w-100">
                            <div class="tab-content px-3 px-xl-4" id="myTabContent">
                                <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="settings-tab">
                                    <div class="mt-4">
                                        <div class="form-row py-2">
                                            <div class="col-lg-4">
                                                <label class="text-dark font-size-17">First name</label>
                                                <input name="first_name" type="text" class="form-control" value="{{old('first_name')}}">
                                                <span class="invalid-feedback">Please fill out this field</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="text-dark font-size-17">Middle name</label>
                                                <input name="middle_name" type="text" class="form-control" value="{{old('middle_name')}}">
                                                <span class="invalid-feedback">Please fill out this field</span>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="text-dark font-size-17">Last name</label>
                                                <input name="last_name" type="text" class="form-control" value="{{old('last_name')}}">
                                                <span class="invalid-feedback">Please fill out this field</span>
                                            </div>
                                        </div>
                                        <div class="form py-2">
                                            <label class="text-dark font-size-17">Rank</label>
                                            <select name="rank_id" class="form-control" value="{{old('rank_id')}}">
                                                <option selected disabled value="">--SELECT RANK--</option>
                                                @foreach(App\Rank::all() as $row)
                                                <option value="{{$row->id}}">{{$row->name}} ({{$row->abbreviation}})</option>
                                                @endforeach
                                            </select>
                                            <span class="invalid-feedback">Please fill out this field</span>
                                        </div>
                                        <div class="form-row py-2">
                                            <div class="col-lg-6">
                                                <label class="text-dark font-size-17">PNP ID #</label>
                                                <input name="id_no" id="id_no" type="text" class="form-control text-uppercase" value="{{old('id_no')}}" onkeyup="this.value = this.value.toUpperCase();">
                                                <span class="invalid-feedback">Please fill out this field</span>
                                                <span>PNP ID # cannot be updated once registered.</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="text-dark font-size-17">Badge #</label>
                                                <input name="badge_no" id="badge_no" type="text" class="form-control text-uppercase" value="{{old('badge_no')}}" onkeyup="this.value = this.value.toUpperCase();">
                                                <span class="invalid-feedback">Please fill out this field</span>
                                                <span>Badge # cannot be updated once registered.</span>
                                            </div>
                                        </div>
                                        <div class="form py-2">
                                            <label class="text-dark font-size-17">Email</label>
                                            <input name="email" type="email" class="form-control" value="{{old('email')}}">
                                            <span class="invalid-feedback">Please fill out this field</span>
                                        </div>
                                        <div class="form py-2">
                                            <label class="text-dark font-size-17">Date of Birth</label>
                                            <input name="birthday" id="birthday" type="date" value="{{old('birthday')}}" class="form-control @if(Session::has('errorage')) is-invalid @endif">
                                            <span class="invalid-feedback">@if(Session::has('errorage')) {{Session::get('errorage')}} @else Please fill out this field @endif</span>
                                            @if(Session::has('errorage')) @else<span>Must be 18+ of Age.</span>@endif
                                        </div>
                                        <div class="form py-2">
                                            <label class="text-dark font-size-17">Gender</label>
                                            <select name="gender" class="form-control" value="{{old('gender')}}">
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
                                                <input name="contact_no" type="text" class="form-control" data-mask="(999) 999-9999" placeholder="ex. (999) 999-9999" value="{{old('contact_no')}}">
                                                <span class="invalid-feedback">Please fill out this field</span>
                                            </div>
                                        </div>
                                        <div class="form py-2">
                                        <label class="text-dark font-size-17">Address</label>
                                            <div class="input-group mb-2">
                                                <input name="address" type="text" class="form-control" placeholder="Street, City/Municapality, Zip Code" value="{{old('address')}}">
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
                <div class="d-flex justify-content-center py-2">
                    <button type="submit" class="btn btn-lg btn-primary">Register Officer</button>
                </div>
            </form>
        @endif
        </div>
    </div>
</div>

<script src={{ asset("assets/plugins/jquery/jquery.min.js") }}></script>
<script src={{ asset("assets/plugins/slimscrollbar/jquery.slimscroll.min.js") }}></script>
<script src={{ asset("assets/plugins/jekyll-search.min.js") }}></script>
<script src={{ asset("assets/plugins/charts/Chart.min.js") }}></script>
<script src={{ asset("assets/plugins/ladda/spin.min.js") }}></script>
<script src={{ asset("assets/plugins/ladda/ladda.min.js") }}></script>
<script src={{ asset("assets/plugins/daterangepicker/moment.min.js") }}></script>
<script src={{ asset("assets/plugins/daterangepicker/daterangepicker.js") }}></script>
<script src={{ asset("assets/plugins/jquery-mask-input/jquery.mask.min.js") }}></script>
<script src={{ asset("assets/plugins/toastr/toastr.min.js") }}></script>
<script src={{ asset("assets/js/sleek.bundle.js") }}></script>

<!-- ===================================== GOOGLE MAP ============================ -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{config('googlemap')['map_apikey']}}&callback=initMap"
  type="text/javascript"></script>
<script>
  function initMap() {
    var map = new google.maps.Map(document.getElementById('map'),{
        center: {
            lat: 8.454236,
            lng: 124.631897
        },
        zoom:12
    });
  }
</script>
<!-- ================================= END GOOGLE MAP =============================== -->

<!-- ========= REPEAT PASSWORD ======== -->
<script>
  var password = document.getElementById("password")
    , confirm_password = document.getElementById("confirm_password");

  function validatePassword(){
    if(password.value != confirm_password.value) {
      confirm_password.setCustomValidity("Passwords Don't Match");
      password.setCustomValidity("Passwords Don't Match");
    } else {
      confirm_password.setCustomValidity('');
      password.setCustomValidity('');
    }
  }

  password.onchange = validatePassword;
  confirm_password.onkeyup = validatePassword;
</script>
<!-- ====================== END REPEAT PASSWORD ================== -->

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




</body>
</html>