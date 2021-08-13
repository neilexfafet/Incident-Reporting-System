@if(Auth::guard('user')->check())

@if(Auth::guard('user')->user()->status == "blocked")
    @include('users.includes.blocked')
@else

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>CDO | Report</title>
    @include('users.includes.link')
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body" onload=display_ct();>

<script>
    NProgress.start();
</script>

<div class="wrapper">
    @include('users.includes.sidebar')
    <div class="page-wrapper">
        @include('users.includes.header')
        <div class="content-wrapper">
            <div class="content">	
                            
            <!--CONTENT SECTION-->
                <div class="row">
                    <div class="col-12">
                        <div class="card card-default">
                            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                                <h2>Account Settings</h2>
                            </div>
                            <div class="bg-white">
                                <div class="row no-gutters">
                                    <div class="col-lg-4 col-xl-3">
                                        <div class="profile-content-left pt-5 pb-3 px-4 px-xl-4">
                                            <div class="card text-center widget-profile px-0 border-0">
                                                <div class="card-img mx-auto rounded-circle">
                                                @if(Auth::guard('user')->user()->image == "TBD")
                                                    <img id="image-change" src="{{asset("uploads/user.jpg")}}" alt="user image" class="img-fluid">
                                                @else
                                                    <img id="image-change" src="{{asset(Auth::guard('user')->user()->image)}}" alt="user image" class="img-fluid">
                                                @endif
                                                </div>
                                                <hr class="w-100">
                                                <h5 class="text-dark">Valid ID</h5>
                                                <div class="card-img mx-auto">
                                                @if(Auth::guard('user')->user()->valid_id_image == "TBD")
                                                    <img id="image-change-valid-id" src="{{asset("uploads/user.jpg")}}" alt="user image" class="img-fluid">
                                                @else
                                                    <img id="image-change-valid-id" src="{{asset(Auth::guard('user')->user()->valid_id_image)}}" alt="user image" class="img-fluid">
                                                @endif
                                                </div>
                                                <div class="card-body mt-4">
                                                    <h4 class="text-dark">{{Auth::guard('user')->user()->first_name}} {{Auth::guard('user')->user()->middle_name}} {{Auth::guard('user')->user()->last_name}}</h4>
                                                </div>
                                            </div>
                                            <hr class="w-100">
                                            <div class="contact-info pt-4">
                                                <h5 class="text-dark">Contact Information</h5>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Phone Number</p>
                                                <p>{{Auth::guard('user')->user()->contact_no}}</p>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Email</p>
                                                <p>{{Auth::guard('user')->user()->email}}</p>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Address</p>
                                                <p>{{Auth::guard('user')->user()->address}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-xl-9">
                                        <div class="profile-content-right py-5">
                                            <ul class="nav nav-tabs px-4" id="myTab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab"aria-selected="true">
                                                        <i class="mdi mdi-account mr-2"></i>Information
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="email-tab" data-toggle="tab" href="#email" role="tab" aria-selected="false">
                                                        <i class="mdi mdi-email mr-2"></i>Change Email
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="change-password-tab" data-toggle="tab" href="#change-password" role="tab" aria-selected="false">
                                                        <i class="mdi mdi-lock mr-2"></i>Change Password
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content px-3 px-xl-5" id="myTabContent">
                                                <div class="tab-pane fade show active" id="details" role="tabpanel">
                                                    <div class="mt-5">
                                                        <div class="form-row">
                                                            <div class="col-md-12">
                                                                <form id="update-profile-form">
                                                                @csrf
                                                                <input type="hidden" id="update-profile-id" value="{{ Auth::guard('user')->user()->id }}">
                                                                <div id="update-profile-success" class="alert alert-success" style="display:none" role="alert"></div>
                                                                <div class="form mb-3">
                                                                    <label>Upload Image</label>
                                                                    <div class="custom-file mb-1">
                                                                        <input name="image" type="file" class="custom-file-input" id="file-upload" accept="image/*" onchange="readURL(this);">
                                                                        <label class="custom-file-label" for="file-upload"><span id="file-upload-filename">Choose File . . .</span></label>
                                                                        <span class="d-block mt-1" style="font-size: 85%">Only Image File Types are Accepted.</span>
                                                                    </div>
                                                                </div>
                                                                <div class="form mb-3">
                                                                    <label>Upload Valid Image</label>
                                                                    <div class="custom-file mb-1">
                                                                        <input name="valid_id_image" type="file" class="custom-file-input" id="file-upload-valid-id" accept="image/*" onchange="readURL2(this);">
                                                                        <label class="custom-file-label" for="file-upload-valid-id"><span id="file-upload-filename-valid-id">Choose File . . .</span></label>
                                                                        <span class="d-block mt-1" style="font-size: 85%">Only Image File Types are Accepted.</span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-row mb-3">
                                                                    <div class="col-lg-4">
                                                                        <label class="text-dark font-size-17">First name</label>
                                                                        <input name="first_name" type="text" class="form-control" value="{{ Auth::guard('user')->user()->first_name }}">
                                                                        <span class="invalid-feedback">Please fill out this field</span>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <label class="text-dark font-size-17">Middle name</label>
                                                                        <input name="middle_name" type="text" class="form-control" value="{{ Auth::guard('user')->user()->middle_name }}">
                                                                        <span class="invalid-feedback">Please fill out this field</span>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <label class="text-dark font-size-17">Last name</label>
                                                                        <input name="last_name" type="text" class="form-control" value="{{ Auth::guard('user')->user()->last_name }}">
                                                                        <span class="invalid-feedback">Please fill out this field</span>
                                                                    </div>
                                                                </div>
                                                                <div class="form mb-3">
                                                                    <label class="text-dark font-size-16">Contact #</label>
                                                                    <input name="contact_no" type="text" class="form-control" data-mask="(999) 999-9999" value="{{ Auth::guard('user')->user()->contact_no }}">
                                                                    <div class="invalid-feedback">Please fill out this field</div>
                                                                </div>
                                                                <div class="form mb-3">
                                                                    <label class="text-dark font-size-16">Date of Birth</label>
                                                                    <input name="birthday" id="update-profile-birthday" type="date" class="form-control" value="{{ Auth::guard('user')->user()->birthday }}">
                                                                    <div class="invalid-feedback" id="update-profile-birthday-inv">Please fill out this field</div>
                                                                </div>
                                                                <div class="form mb-3">
                                                                    <label class="text-dark font-size-17">Gender</label>
                                                                    <select name="gender" class="form-control">
                                                                        <option selected value="{{ Auth::guard('user')->user()->gender }}">{{ Auth::guard('user')->user()->gender }}</option>
                                                                        <option value="Male"> Male </option>
                                                                        <option value="Female"> Female </option>
                                                                    </select>
                                                                    <span class="invalid-feedback">Please fill out this field</span>
                                                                </div>
                                                                <div class="form mb-3">
                                                                    <label class="text-dark font-size-17">Address</label>
                                                                    <div class="input-group mb-2">
                                                                        <input name="address" type="text" class="form-control" placeholder="Street, City/Municapality, Zip Code" value="{{ Auth::guard('user')->user()->address }}">
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text">
                                                                                    <i class="mdi mdi-map-marker"></i>
                                                                                </span>
                                                                            </div>
                                                                        <span class="invalid-feedback">Please fill out this field</span>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-end py-4">
                                                                    <button class="ladda-button btn btn-primary" data-style="expand-right">
                                                                        <span class="ladda-label">Update</span>
                                                                        <span class="ladda-spinner"></span>
                                                                    </button>
                                                                </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="email" role="tabpanel">
                                                    <div class="mt-5">
                                                        <div class="form-row">
                                                            <div class="col-md-12">
                                                                <form id="update-email-form">
                                                                @csrf
                                                                <input type="hidden" id="profile-id-email" value="{{ Auth::guard('user')->user()->id }}">
                                                                    <div id="update-email-success" class="alert alert-success" style="display:none" role="alert"></div>
                                                                    <div class="form mb-3">
                                                                        <label class="text-dark font-size-16">Email</label>
                                                                        <input name="email" id="profile-email" type="text" class="form-control" value="{{ Auth::guard('user')->user()->email }}" required oninput="$(this).removeClass('is-invalid')">
                                                                        <div class="invalid-feedback"><span id="profile-email-feedback"></span></div>
                                                                    </div>
                                                                    <div class="form mb-3">
                                                                        <label class="text-dark font-size-16">Confirm Password</label>
                                                                        <input name="confirmpassword" id="profile-confirm-password" type="password" class="form-control" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                                                        <div class="invalid-feedback"><span id="profile-confirm-password-feedback">Please Input Current Password.</span></div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-end py-4">
                                                                    <button class="ladda-button btn btn-primary" data-style="expand-right">
                                                                        <span class="ladda-label">Update</span>
                                                                        <span class="ladda-spinner"></span>
                                                                    </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="change-password" role="tabpanel">
                                                    <div class="mt-5">
                                                        <div class="form-row">
                                                            <div class="col-md-12">
                                                                <form id="update-password-form">
                                                                @csrf
                                                                <input type="hidden" id="profile-id-password" value="{{ Auth::guard('user')->user()->id }}">
                                                                <div id="update-password-success" class="alert alert-success" style="display:none" role="alert"></div>
                                                                <div id="update-password-danger" class="alert alert-danger" style="display:none" role="alert"></div>
                                                                <div class="form-row">
                                                                    <div class="col-md-12 mb-3">
                                                                    <label class="text-dark font-size-16">Current Password</label>
                                                                        <input name="current_password" id="current-password" type="password" class="form-control" required oninput="$(this).removeClass('is-invalid')">
                                                                        <div class="invalid-feedback"><span id="profile-password-feedback"></span></div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-3">
                                                                        <label class="text-dark font-size-16">New Password</label>
                                                                        <input name="new_password" id="profile-password" type="password" class="form-control" required>
                                                                        <div class="invalid-feedback">Password must consist 6+ characters</div>
                                                                    </div>
                                                                    <div class="col-md-12 mb-3">
                                                                        <label class="text-dark font-size-16">Repeat Password</label>
                                                                        <input id="confirm-profile-password" type="password" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group d-flex justify-content-end">
                                                                    <button class="ladda-button btn btn-primary" data-style="expand-right">
                                                                        <span class="ladda-label">Update</span>
                                                                        <span class="ladda-spinner"></span>
                                                                    </button>
                                                                </div>
                                                                </form>
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
                </div>

            </div>
            @include('users.includes.notifications')
            @include('users.includes.footer')
        </div>
    </div>
</div>
@include('users.includes.script')

<!-- ============================= AJAX =============================== -->
<script>
$('#update-email-form').on('submit', function(event) {
  event.preventDefault();
  
  var email_id = $('#profile-id-email').val();

  $.ajax({
    type: "POST",
    url: "/user/profile/updateemail/"+email_id,
    data: $('#update-email-form').serialize(),
    success: function(result) {
      if(result.error) {
        $('#profile-email').addClass('is-invalid');
        $('#profile-email-feedback').html(result.error);
        $('#profile-confirm-password').val('');
      } 
      if(result.errorpw) {
        $('#profile-confirm-password').addClass('is-invalid');
        $('#profile-confirm-password-feedback').html(result.errorpw);
        $('#profile-confirm-password').val('');
      }
      if(result.success) {
        $('#update-email-success').show().html(result.success);
        $('#profile-email').removeClass('is-invalid');
        $('#profile-confirm-password').val('');
        setTimeout(function() {
            $('#update-email-success').fadeOut('slow');
        }, 2500);
      }
    },
    error: function(error) {
      console.log(error);
    },
  });
});

$('#update-profile-form').on('submit', function(event) {
    event.preventDefault();
    
    var profile_id = $('#update-profile-id').val();

    $.ajax({
        type: "POST",
        url: "/user/profile/updateprofile/"+profile_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if(result.errorbday) {
                $('#update-profile-birthday').addClass('is-invalid');
                $('#update-profile-birthday-inv').html(result.errorbday);
            }
            if(result.success) {
                $('html, body').animate({scrollTop:0},'slow');
                $('#update-profile-success').show().html(result.success);
                setTimeout(function() {
                    $('#update-profile-success').fadeOut('slow');
                }, 2500);
            }
        },
        error: function(error) {
            console.log(error);
        },
    });
});

$('#update-password-form').on('submit', function(event) {
  event.preventDefault();
  
  var profile_id_pw = $('#profile-id-password').val();

  $.ajax({
    type: "POST",
    url: "/user/profile/updatepassword/"+profile_id_pw,
    data: $('#update-password-form').serialize(),
    success: function(result) {
      if(result.error) {
            $('#current-password').addClass('is-invalid');
            $('#profile-password-feedback').html(result.error);
      } else {
            $('#update-password-success').show().html(result.success);
            $('#current-password').removeClass('is-invalid');
            document.getElementById('update-password-form').reset();
            setTimeout(function() {
                $('#update-password-success').fadeOut('slow');
            }, 2500);
        }
    },
    error: function(error) {
        console.log(error);
        if(error.status == 422) {
            $('#profile-password').addClass('is-invalid');
        }
    },
  });
});
</script>
<!-- ============================= END AJAX ================================= -->

<!-- ================================== FILE/IMAGE PREVIEWS ===================================== -->
<script type="text/javascript">
    var x = document.getElementById('file-upload');
    var y = document.getElementById('file-upload-filename');
    x.addEventListener( 'change', showFileName );
    function showFileName( event ) {
    var x = event.srcElement;
    var z = x.files[0].name;
    y.textContent = z;
    }
</script>
<script type="text/javascript">
    var input = document.getElementById('file-upload-valid-id');
    var infoArea = document.getElementById('file-upload-filename-valid-id');
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
                $('#image-change').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#image-change-valid-id').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<!-- ======================================= END FILE/IMAGE PREVIEWS ==================================== -->

<!-- ================================ REPEAT PASSWORD ============================ -->
<script>
  var password = document.getElementById("profile-password")
    , confirm_password = document.getElementById("confirm-profile-password");

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
<!-- ================================= END REPEAT PASSWORD =============================== -->

<!-- ======================================= DATEPICKER ==================================== -->
<script>
    $(document).ready(function() {
        $('#update-profile-birthday').daterangepicker({
            "startDate": "{{ Auth::guard('user')->user()->birthday }}",
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

</body>
</html>


@endif


@else 
    @include('users.includes.419')
@endif