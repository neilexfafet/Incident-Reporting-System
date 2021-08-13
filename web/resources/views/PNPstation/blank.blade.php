@if(Auth::guard('station')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>Station | </title>
    @include('pnpstation.includes.link')
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body" onload=display_ct();>
  
<script>
    NProgress.start();
</script>

    <div class="wrapper">
        @include('pnpstation.includes.sidebar')
        <div class="page-wrapper">
            @include('pnpstation.includes.header')
            <div class="content-wrapper">
                <div class="content">						 
                    
                    <!--CONTENT SECTION-->
                    <div class="row">
                        <div class="col-lg-12">
                                     
                        </div>
                    </div>

                </div>


            @include('pnpstation.includes.notifications')
            @include('pnpstation.includes.footer')
            </div>
        </div>
    </div>

@include('pnpstation.includes.script')


</body>

</html>

@else 
    @include('PNPstation.includes.419')
@endif
