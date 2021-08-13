<!DOCTYPE html>
<html lang="en">
<head>
    <title> LOGIN </title>
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
    <style>
    /* responsively apply fixed position */
        #left {
            position: fixed;
            top: 0;
            bottom: 0;
        }
    </style>
</head>
<body class="" id="body">

<script>
    NProgress.start();
</script>

<div id="toaster-clock"></div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8 bg-dark text-white d-none d-md-flex px-0" id="left">
            <!-- <img src="" class="img-fluid" alt=""> -->
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                    <img src="{{ asset('assets/img/login/Albayalde-with-Gamboa.jpg') }}" class="d-block w-100" alt="..." style="height: 700px">
                    </div>
                    <div class="carousel-item">
                    <img src="{{ asset('assets/img/login/101216_pnp5_martinez.jpg') }}" class="d-block w-100" alt="..." style="height: 700px">
                    </div>
                    <div class="carousel-item">
                    <img src="{{ asset('assets/img/login/PNP.jpg') }}" class="d-block w-100" alt="..." style="height: 700px">
                    </div>
                    <div class="carousel-item">
                    <img src="{{ asset('assets/img/login/philippines-flag.jpg') }}" class="d-block w-100 img-fluid" alt="...">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <div class="col-sm-4 col-12 offset-0 offset-sm-8 py-2" style="background-color: #ffffff;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 py-4">
                        <h4 class="display-4 text-center"><img class="img-fluid my-auto" src="{{ asset('assets/img/pnpseal.png') }}" style="height: 150px;width: 150px;"></h4>
                        <div class="form-group py-5">
                            <h3 class="text-dark text-center mb-4">CDO | Report</h3>
                            <div id="login-loader" style="display: none;">
                                <div class="card-body d-flex align-items-center justify-content-center" style="height: 300px">
                                    <div class="sk-wonder-cube">
                                        <div class="cube1"></div>
                                        <div class="cube2"></div>
                                    </div>
                                </div>
                            </div>
                            <form id="login-form">
                            @csrf
                                <div class="form-row">
                                    <div class="form col-md-12">
                                        <label class="text-dark font-weight-medium" style="font-size: 90%;">E-Mail</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">@</span>
                                            </div>
                                            <input name="email" id="login-email" type="email" class="form-control" placeholder="E-Mail">
                                            <div class="invalid-feedback" id="login-email-inv"></div>
                                        </div>
                                    </div>
                                    <div class="form col-md-12 mb-4">
                                        <label class="text-dark font-weight-medium" style="font-size: 90%;">Password</label>
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="mdi mdi-lock"></i>
                                                </span>
                                            </div>
                                            <input name="password" id="login-password" type="password" class="form-control" placeholder="Password">
                                        </div>
                                        <p style="font-size: 90%">Password must consist of 6+ characters.</p>
                                    </div>
                                    <div class="col-md-12 py-2">
                                        <button type="submit" id="submit" class="btn btn-lg btn-primary btn-block" disabled><span class="mdi mdi-login">&nbsp;Sign-In</span></span></button>
                                        <p class="text-center py-4">Don't have an account yet ?
                                            <a class="text-blue" href="javascript:void(0);" data-toggle="modal" data-target="#register">Register Here</a>
                                        </p>
                                        <p class="text-center"><a class="text-blue" href="#">Forgot Your Password?</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="register" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="register-loader" style="display: none;">
                <div class="card-body d-flex align-items-center justify-content-center" style="height: 200px">
                    <div class="sk-wonder-cube">
                        <div class="cube1"></div>
                        <div class="cube2"></div>
                    </div>
                </div>
            </div>
            <form id="register-form">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalFormTitle">Registration</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" style="display: none;" role="alert"></div>
                <div class="alert alert-danger" style="display: none;" role="alert"></div>
                <div class="row no-gutters">
                    <div class="col-lg-4 col-xl-4">
                        <div class="profile-content-left pt-5 pb-3 px-3 px-xl-8">
                            <div class="card widget-profile px-0 border-0">
                                <div class="card-img mx-auto rounded" style="height: 180px; width: 150px;">
                                    <img src={{ asset('uploads/user.jpg') }} class="img-fluid" alt="user image" id="file-change">
                                </div>
                                <div class="form-group py-4">
                                    <label>Upload User Image</label>
                                    <div class="custom-file mb-1">
                                        <input name="image" type="file" class="custom-file-input" id="file-upload" accept="image/*" onchange="readURL(this);">
                                        <label class="custom-file-label" for="file-upload"><span id="file-upload-filename">Choose File . . .</span></label>
                                        <span class="d-block mt-1" style="font-size: 85%">Only Image File Types are Accepted.</span>
                                    </div>
                                </div>
                                <hr class="w-100">
                                <div class="card-img mx-auto rounded py-4" style="height: 180px; width: 150px;">
                                    <img src={{ asset('uploads/user.jpg') }} class="img-fluid" alt="user image" id="">
                                </div>
                                <div class="form-group py-4">
                                    <label>Upload Valid ID</label>
                                    <div class="custom-file mb-1">
                                        <input type="file" class="custom-file-input" id="" accept="image/*">
                                        <label class="custom-file-label" for="file-upload"><span id="">Choose File . . .</span></label>
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
                                            <label class="text-dark font-size-17">Date of Birth</label>
                                            <input name="birthday" id="birthday" type="date" class="form-control">
                                            <span class="invalid-feedback" id="birthday-inv">Please fill out this field</span>
                                            <span>Must be 16+ of Age.</span>
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
                                        <hr class="separator">
                                        <h4 class="text-center">Login Details</h4>
                                        <div class="form py-2">
                                            <label class="text-dark font-size-17">Email</label>
                                            <input name="email" type="email" class="form-control">
                                            <span class="invalid-feedback">Please fill out this field</span>
                                        </div>
                                        <div class="form py-2">
                                            <label class="text-dark font-size-17">Password</label>
                                            <input name="password" type="password" id="register-password" class="form-control">
                                            <span class="invalid-feedback" id="register-password-inv">Please fill out this field</span>
                                        </div>
                                        <div class="form py-2">
                                            <label class="text-dark font-size-17">Repeat Password</label>
                                            <input type="password" id="register-password-confirm" class="form-control">
                                            <span class="invalid-feedback" id="register-password-confirm-inv">Please fill out this field</span>
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
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </div>
    </div>
</div>
<!-- END REGISTER MODAL -->


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

<!-- =========================== AJAX ============================ -->
<script>
/* LOGIN */
$('#login-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#login-loader').show();

    $.ajax({
        type: "POST",
        url: "{{ url('user/login') }}",
        data: $('#login-form').serialize(),
        success: function(result) {
            if(result.error) {
                $('#login-loader').hide();
                $('#login-form').show();
                $('#login-email').addClass('is-invalid');
                $('#login-email-inv').html(result.error);
                $('#login-password').addClass('is-invalid');
                $('#login-password').val("");
                $('#submit').prop("disabled", true);
            }
            else if(result.url) {
                window.location = result.url;
            }
        },
        error: function(error) {
            console.log(error);
        },
    });
});
/* ENDLOGIN */

/* REGISTER */
$('#register-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#register-loader').show();
    
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
        enctype: "multipart/form-data",
        url: "{{ url('user/register') }}",
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if(result.error) {
                $('.modal').animate({scrollTop:0},'slow');
                $('.modal-body .alert-danger').show().html(result.error);
                setTimeout(function() {
                $('.modal-body .alert-danger').fadeOut('slow');
                }, 2500);
            } 
            if(result.errorbday) {
                $('#birthday').addClass('is-invalid');
                $('#birthday-inv').html(result.errorbday);
            }
            if(result.success) {
                $('#register').modal('hide');
                $('#file-change').prop('src', '{{ asset('uploads/user.jpg') }}');
                $('#file-upload-filename').html('Choose File . . .');
                document.getElementById('register-form').reset();
                $('#birthday').removeClass('is-invalid');
                toastr.success("", result.success);
            }
        },
        complete: function() {
            $('#register-loader').hide();
            $('#register-form').show();
        },
        error: function(error) {
            console.log(error);
            if(error.status == 422) {
                $('#register-loader').hide();
                $('#register-form').show();
                $('#register-password').addClass('is-invalid');
                $('#register-password-inv').html("Password must contain 6+ characters");
            }
        }
    });
});
/* END REGISTER */
</script>
<!-- ============================== END AJAX ======================= -->

<!-- ==================== Disable Button if Empty Fields ====================== -->
<script type="text/javascript">
  $(document).ready(function() {
    $('#submit').prop('disabled', true);

    function validateNextButton() {
      var buttonDisabled = $('#login-email').val().trim() === '' || $('#login-password').val().length <= 6;
      $('#submit').prop('disabled', buttonDisabled);
    }

    $('#login-email').on('keyup', validateNextButton);
    $('#login-password').on('keyup', validateNextButton);
  });
</script>
<!-- ======================================================================== -->

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
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<!-- ======================================= END FILE/IMAGE PREVIEWS ==================================== -->

<!-- =================================== REPEAT PASSWORD =============================== -->
<script>
  var password = document.getElementById("register-password")
    , confirm_password = document.getElementById("register-password-confirm");

  function validatePassword(){
    if(password.value != confirm_password.value) {
      confirm_password.setCustomValidity("Passwords Don't Match");
      $('#register-password-confirm-inv').html("Passwords dont match");
    } else {
      confirm_password.setCustomValidity('');
    }
  }

  password.onchange = validatePassword;
  confirm_password.onkeyup = validatePassword;
</script>
<!-- ====================================== END REPEAT PASSWORD ============================== -->

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

    $('input[type="password"]').attr("required", true).on("invalid", function() {
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
        $('#birthday').daterangepicker({
            "maxDate": moment(),
            "singleDatePicker": true,
            "showDropdowns": true,
            "autoApply": true,
            "locale": {
                "format": "YYYY-MM-DD",
            }
        }, function(start, end, label) {
            console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        });
    });
</script>
<!-- ======================================= DATEPICKER ==================================== -->

</body>
</html>