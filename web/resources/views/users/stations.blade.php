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
                            <div class="card-header card-header-border-bottom">
                                <h2>Police Stations</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                @foreach($data as $row)
                                    <div class="col-6">
                                        <div class="card card-default p-4">
                                            <a href="javascript:void(0);" class="media text-secondary" style="pointer-events: none;">
                                                <img @if($row->image == "TBD") src="{{ asset('assets\img\pnpseal.png') }}" @else src="{{ asset($row->image) }}" @endif class="mr-3 img-fluid rounded" width="100px" height="100px" alt="Avatar Image">
                                                <div class="media-body">
                                                    <h4 class="mt-0 mb-2 text-dark">{{$row->station_name}}</h4>
                                                    <ul class="list-unstyled">
                                                        <li class="d-flex mb-1">
                                                        <i class="mdi mdi-map-marker mr-1"></i>
                                                        <span>{{$row->location_name}}</span>
                                                        </li>
                                                        <li class="d-flex mb-1">
                                                        <i class="mdi mdi-phone mr-1"></i>
                                                        <span>{{$row->station_contactno}}</span>
                                                        </li>
                                                        <li class="d-flex mb-1">
                                                        <i class="mdi mdi-clock-outline mr-1"></i>
                                                        <span>Updated {{$row->updated_at->diffForHumans()}}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
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

</body>
</html>

@endif


@else 
    @include('users.includes.419')
@endif