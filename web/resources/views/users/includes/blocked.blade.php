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
            <div class="row justify-content-center mb-6">
                <div class="col-xl-12">
                    <h1 class="text-danger">BLOCKED!</h1>
                    <p class="pt-4 pb-5 error-subtitle">You reported a bogus report. Your account is blocked. To unblock, contact the City Police Office</p>
                </div>
            </div>
            <div class="row">
                <form action="{{ url('user/logout') }}" method="POST">
                @csrf
                    <button type="submit" class="ladda-button btn btn-primary" data-style="expand-right">Logout</button>
                </form>
            </div>
        </div>
    </div>

@include('pnpadmin.includes.script')

</body>
</html>
