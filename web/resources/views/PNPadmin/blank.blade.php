@if(Auth::guard('admin')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>P</title>
    @include('pnpadmin.includes.link')
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body" onload=display_ct();>

<script>
    NProgress.start();
</script>

<div class="wrapper">
    @include('pnpadmin.includes.sidebar')
    <div class="page-wrapper">
        @include('pnpadmin.includes.header')
        <div class="content-wrapper">
            <div class="content">	
                            
            <!--CONTENT SECTION-->
                <div class="row">
                    <div class="col-12">
                        yujhgjyhgjh
                    </div>
                </div>

            </div>

            @include('PNPadmin.includes.notifications')
            @include('pnpadmin.includes.footer')
        </div>
    </div>
</div>
@include('pnpadmin.includes.script')
</body>
</html>

            
@else
    @include('PNPadmin.includes.419')
@endif