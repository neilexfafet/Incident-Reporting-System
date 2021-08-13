@if(Auth::guard('station')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>Station | News</title>
    @include('pnpstation.includes.link')
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body" onload=display_ct();>

  <div class="wrapper">

        @include('pnpstation.includes.sidebar')

    <div class="page-wrapper">
                
        @include('pnpstation.includes.header')
      <div class="content-wrapper">
        <div class="content">						 
                  
                  <!--CONTENT SECTION-->
                  <div class="row">
                    <div class="col-12">
                        <div class="card card-default">
                            <div class="card-header card-header-border-bottom">
                                <h2>News</h2>
                            </div>
                            <div class="card-body">
                                @if($news != 0)
                                @foreach($data as $row)
                                    <div class="py-2">
                                        <div class="card">
                                            <img class="card-img-top" src="{{ asset($row->image) }}" alt="Card image cap">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary">{{$row->title}}</h5>
                                                <p class="card-text pb-3">{!! $row->content !!}</p>
                                            </div>
                                            <div class="card-footer d-flex justify-content-between py-4">
                                                <div>
                                                    <span class="text-dark font-weight-bold">Author:</span>&nbsp;{{$row->author}}
                                                    <span class="text-dark font-weight-bold">&nbsp;|&nbsp; Published Date:</span>&nbsp;{{$row->created_at->format('F j, Y')}} at {{$row->created_at->format('h:i A')}}
                                                </div>
                                                <div>
                                                    <span class="text-dark font-weight-bold">Source:</span> <a href="{{$row->source_link}}">{{$row->source_link}}</a>
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
