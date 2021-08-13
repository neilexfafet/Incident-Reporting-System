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
                                <h2>Newsfeed</h2>
                            </div>
                            <div class="card-body">
                                @if($news != 0)
                                @foreach($data as $row)
                                    <div class="py-2">
                                        <div class="card">
                                            @if($row->image != "TBD")
                                            <img class="card-img-top" src="{{ asset($row->image) }}" alt="Card image cap">
                                            @endif
                                            <div class="card-body">
                                                <h5 class="card-title text-primary">{{$row->title}}</h5>
                                                <p class="card-text pb-3">{!! $row->content !!}</p>
                                            </div>
                                            <div class="card-footer py-4">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <span class="text-dark font-weight-bold">Author:</span>&nbsp;{{$row->author}}
                                                        </div>
                                                    <div class="col-lg-4">
                                                        <span class="text-dark font-weight-bold">Published Date:</span>&nbsp;{{$row->created_at->format('F j, Y')}} at {{$row->created_at->format('h:i A')}}
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <span class="text-dark font-weight-bold">Source:</span> <a href="{{$row->source_link}}">{{$row->source_link}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-end py-4">
                                    {{$data->links()}}
                                </div>
                                @else
                                <div class="col-lg-12 text-center">
                                    <span>No News Posted Yet</span>
                                </div>
                                @endif
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