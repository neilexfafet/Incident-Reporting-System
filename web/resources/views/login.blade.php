<!DOCTYPE html>
<html lang="en">
<head>
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
        /* body {
            background-image: url('{{ asset('assets/img/login/background.jpg') }}');
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: 100% 100%;
        } */
        body {
            background-color: #cccccc;
            background-image: linear-gradient(315deg, #cccccc 0%, #5899e2 74%);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="" id="body" onload=display_ct();>

<script>
    NProgress.start();
</script>

    <div class="container d-flex flex-column justify-content-between vh-100">
        <div class="row justify-content-center my-4">
            <div class="col-xl-5 col-lg-6 col-md-10">
                <div class="card">
                    <div class="card-header bg-primary">
                        <div class="app-brand">
                            <a href="javascript:void(0);" style="width: 350px;">
                            <img src="assets/img/pnpseal.png" height="75" width="75">
                            <span class="brand-name" style="width: 300px;">PNP | Cagayan de Oro City</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-5">
                        <div class="d-flex justify-content-center py-4">
                            <h5 class="text-dark" id="date"></h5>
                        </div>
                        <form id="login-form">
                        @csrf
                        <div class="row py-2">
                            <div class="form col-md-12">
                                <label class="text-dark font-weight-medium" style="font-size: 90%;">Station Registered Username</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="mdi mdi-account"></i></span>
                                        </div>
                                        <input name="username" id="login-username" type="text" class="form-control" placeholder="Username" autocomplete="off" value="{{ old('username') }}">
                                        <div id="login-username-feedback" class="invalid-feedback"></div>
                                    </div>
                                <label class="text-dark font-weight-medium" style="font-size: 90%;">PNP ID #</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="mdi mdi-account-card-details"></i></span>
                                        </div>
                                        <input name="id_no" id="login-id_no" type="text" class="form-control text-uppercase" autocomplete="off" onkeyup="this.value=this.value.toUpperCase();" aria-describedby="inputGroupPrepend3" placeholder="PNP ID" value="{{ old('id_no') }}">
                                        <div id="login-idno-feedback" class="invalid-feedback"></div>
                                    </div>
                                <label class="text-dark font-weight-medium" style="font-size: 90%;">Password</label>
                                    <div class="input-group mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="mdi mdi-lock"></i></span>
                                        </div>
                                        <input name="password" id="login-password" type="password" class="form-control" placeholder="Password">
                                    </div>
                                <p style="font-size: 90%">Password must consist of 6+ characters.</p>
                                <div class="form-group pt-4">
                                    <button type="submit" id="submit" class="btn btn-lg btn-primary btn-block" disabled><span class="mdi mdi-login">&nbsp;Login</button>
                                </div>
                                <p class="text-center">Police Officer?
                                    <a class="text-blue" href="javascript:void(0);" data-toggle="modal" data-target="#register-officer">Register Here</a>
                                </p>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- MODAL LOADER -->
<div id="login-loader" class="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true" data-backdrop="static" style="overflow: hidden">
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

<!-- ADD OFFICER MODAL -->
<div class="modal fade" id="register-officer" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormEDIT" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="register-officer-loader" class="col-sm-12" style="display: none;">
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
        <form id="register-officer-form">
        @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalFormEDIT">Register</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="register-officer-alert-success" class="alert alert-success" style="display: none;" role="alert"></div>
                    <div id="register-officer-alert-danger" class="alert alert-danger" style="display: none;" role="alert"></div>
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

<script type="text/javascript"> 
function display_c(){
var refresh=1000; // Refresh rate in milli seconds
mytime=setTimeout('display_ct()',refresh)
}
function display_ct() {
var date = moment().format('dddd, LL, LTS');
document.getElementById('date').innerHTML = date;
display_c();
}
</script>


<script src={{ asset("assets/plugins/jquery/jquery.min.js") }}></script>
<script src={{ asset("assets/plugins/slimscrollbar/jquery.slimscroll.min.js") }}></script>
<script src={{ asset("assets/plugins/jekyll-search.min.js") }}></script>
<script src={{ asset("assets/plugins/ladda/spin.min.js") }}></script>
<script src={{ asset("assets/plugins/ladda/ladda.min.js") }}></script>
<script src={{ asset("assets/plugins/jquery-mask-input/jquery.mask.min.js") }}></script>
<script src={{ asset("assets/js/sleek.bundle.js") }}></script>
<script src={{ asset("assets/plugins/daterangepicker/moment.min.js") }}></script>
<script src={{ asset("assets/plugins/daterangepicker/daterangepicker.js") }}></script>
<script src={{ asset("assets/plugins/toastr/toastr.min.js") }}></script>

<!-- ============================== AJAX ================================== -->
<script type="text/javascript">
$(document).ready(function() {
    $('#login-form').on('submit', function (event) {
        event.preventDefault();

        $('#login-loader').modal('show');

        $.ajax({
            type: "POST",
            url: "/PNP/login",
            data: $('#login-form').serialize(),
            success: function(result) {
                if(result.errorform) {
                    $('#login-loader').modal('hide');
                    $('#login-username').addClass('is-invalid');
                    $('#login-username-feedback').html(result.errorform);
                    $('#login-id_no').removeClass('is-invalid');
                    $('#login-password').val("");
                    $('#submit').prop("disabled", true);
                }
                else if(result.adminurl) {
                    localStorage.setItem("success", result.officername);
                    window.location = result.adminurl;
                }
                else if(result.stationurl) {
                    localStorage.setItem("success", result.officername);
                    window.location = result.stationurl;
                }
                else if(result.erroridno) {
                    $('#login-loader').modal('hide');
                    $('#login-id_no').addClass('is-invalid');
                    $('#login-username').removeClass('is-invalid');
                    $('#login-idno-feedback').html(result.erroridno);
                    $('#login-password').val("");
                    $('#submit').prop("disabled", true);
                }
                else if(result.errorstatus) {
                    $('#login-loader').modal('hide');
                    $('#login-id_no').addClass('is-invalid');
                    $('#login-username').removeClass('is-invalid');
                    $('#login-idno-feedback').html(result.errorstatus);
                    $('#login-password').val("");
                    $('#submit').prop("disabled", true);
                }
            },
            error: function(error) {
                console.log(error);
                if(error.status == 422) {
                    $('#login-loader').modal('hide');
                    $('#login-id_no').addClass('is-invalid');
                    $('#login-username').removeClass('is-invalid');
                    $('#login-idno-feedback').html("Officer not Registered.");
                    $('#login-password').val("");
                    $('#submit').prop("disabled", true);
                }
            },
        });
    });
});
/* ADD OFFICER */
$('#register-officer-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#register-officer .modal-dialog').addClass('modal-dialog-centered');
    $('#register-officer-loader').show();

    toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: false,
        progressBar: true,
        positionClass: "toast-top-center",
        preventDuplicates: false,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "5000",
        extendedTimeOut: "5000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    };

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "{{ url('PNP/register-officer') }}",
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if(result.error) {
                $('#register-officer-alert-danger').show().html(result.error);
                setTimeout(function() {
                    $('#register-officer-alert-danger').fadeOut('slow');
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
                $('#register-officer-alert-danger').show().html(result.errorbday);
                setTimeout(function() {
                    $('#register-officer-alert-danger').fadeOut('slow');
                }, 3000);
            } 
            if(result.success) {
                $('#register-officer').modal('hide');
                $('#file-change').prop('src', '{{ asset('uploads/user.jpg') }}');
                $('#file-upload-filename').html('Choose File . . .');
                $('#birthday').removeClass('is-invalid');
                document.getElementById('register-officer-form').reset();
                toastr.success(result.success, "");
            }
        },
        complete: function() {
            $('#register-officer .modal-dialog').removeClass('modal-dialog-centered');
            $('#register-officer-loader').hide();
            $('#register-officer-form').show();
        },
        error: function(error) {
            console.log(error);
        }
    });
});
/* END ADD OFFICER */
</script>
<!-- ============================== AJAX ================================== -->

<!-- =================================== First Letter Uppercase SCRIPT ================================= -->
<script>
$('#register-officer-form input[type="text"]').keyup(function(evt){
    var txt = $(this).val();
    $(this).val(txt.replace(/^(.)|\s(.)/g, function($1){ return $1.toUpperCase( ); }));
});
</script>
<!-- =================================== END First Letter Uppercase SCRIPT ================================= -->

<!-- ================== Submit button disabled if all textfields are empty ========================== -->
<script type="text/javascript">
$(document).ready(function() {
    $('#submit').prop('disabled', true);

    function validateNextButton() {
        var buttonDisabled = $('#login-username').val() === '' || $('#login-password').val().length < 5 || $('#login-id_no').val() === '';
        $('#submit').prop('disabled', buttonDisabled);
    }

    $('#login-username').on('keyup', validateNextButton);
    $('#login-id_no').on('keyup', validateNextButton);
    $('#login-password').on('keyup', validateNextButton);
});
</script>
<!-- ================== Submit button disabled if all textfields are empty ========================== -->

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