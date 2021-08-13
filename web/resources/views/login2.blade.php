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
<body class="" id="body" onload=display_ct();>

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
                        <div class="col-lg-12 justify-content-end py-4">
                            <span class="text-dark float-right">Philippine Standard Time</span><br>
                            <span class="text-dark float-right" id="date"></span>
                        </div>
                        <div class="col-lg-10 mx-auto">
                            <h4 class="display-4 text-center"><img class="img-fluid my-auto" src="{{ asset('assets/img/pnpseal.png') }}" style="height: 150px;width: 150px;"></h4>
                            <div class="form-group py-5">
                                <form method="POST" action="{{ url('/PNP/login') }}">
                                @csrf
                                    <label class="text-dark font-weight-medium" style="font-size: 100%;">Station Registered Username</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-account"></i>
                                            </span>
                                        </div>
                                        <input name="username" id="username" type="text" class="form-control @if(session()->has('error')) is-invalid @endif" aria-describedby="inputGroupPrepend3" placeholder="Username" value="{{ old('username') }}" required>
                                        @if (session()->has('error'))
                                            <div class="invalid-feedback">
                                                {{ session()->get('error') }}
                                            </div>
                                        @endif
                                    </div>
                                    <label class="text-dark font-weight-medium" style="font-size: 100%;">PNP ID #</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                            <i class="mdi mdi-account-card-details"></i>
                                            </span>
                                        </div>
                                        <input name="id_no" id="id_no" type="text" class="form-control text-uppercase @error('id_no') is-invalid @enderror" onkeyup="this.value = this.value.toUpperCase();" aria-describedby="inputGroupPrepend3" placeholder="PNP ID" value="{{ old('id_no') }}" required>
                                        @error('id_no')
                                            <div class="invalid-feedback">
                                                Officer Not Registered.
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="mdi mdi-lock"></i>
                                            </span>
                                            </div>
                                        <input name="password" id="password" type="password" class="form-control @if(session()->has('error')) is-invalid @endif @error('password') is-invalid @enderror" placeholder="Password" required>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                Password must be minimum of 6 characters.
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="input-group">
                                        <button type="submit" id="submit" class="ladda-button btn btn-lg btn-primary btn-block" data-style="zoom-out">
                                            <span class="ladda-label"><span class="mdi mdi-login">&nbsp;Login</span></span>
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
<script src={{ asset("assets/js/sleek.bundle.js") }}></script>
<script src={{ asset("assets/plugins/daterangepicker/moment.min.js") }}></script>

<!-- ==================== Disable Button if Empty Fields ====================== -->
<script type="text/javascript">
  $(document).ready(function() {
    $('#submit').prop('disabled', true);

    function validateNextButton() {
      var buttonDisabled = $('#username').val().trim() === '' || $('#password').val().trim() === '' || $('#id_no').val().trim() === '';
      $('#submit').prop('disabled', buttonDisabled);
    }

    $('#username').on('keyup', validateNextButton);
    $('#id_no').on('keyup', validateNextButton);
    $('#password').on('keyup', validateNextButton);
  });
</script>
<!-- ======================================================================== -->

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