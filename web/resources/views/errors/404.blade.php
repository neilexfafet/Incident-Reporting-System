<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>Session Expired</title>
    @include('pnpadmin.includes.link')
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">

<script>
    NProgress.start();
</script>

    <div class="content">						
        <div class="error-wrapper rounded border bg-white px-5">
            <div class="row justify-content-center">
                <div class="col-xl-4">
                    <h1 class="text-primary bold error-title">404</h1>
                    <p class="pt-4 pb-5 error-subtitle">Page Not Found!</p>
                </div>
                <div class="col-xl-6 pt-5 pt-xl-0 text-center">
                    <img src="{{ asset('assets/img/lightenning.png') }}" class="img-responsive" alt="Error Page Image">
                </div>
            </div>
        </div>
    </div>

@include('pnpadmin.includes.script')


<script>
    setTimeout(function(){
        window.location.href = "{{ url('/') }}";
    }, 5000);
</script>
</body>
</html>
