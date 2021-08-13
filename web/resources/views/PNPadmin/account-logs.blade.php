@if(Auth::guard('admin')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>PNP | Logs</title>
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
                    <div class="card card-default">
                        <div class="card-header card-header-border-bottom d-flex justify-content-between">
                            <h2>Account Logs</h2>
                        </div>
                        <div class="card-body">
                            <div class="responsive-data-table">
                                <table id="responsive-data-table" class="table table-hover dt-responsive" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Timestamp</th>
                                            <th>Activity</th>
                                            <th>Account</th>
                                            <th>Officer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($data as $row)
                                        <tr>
                                            <td class="text-secondary">{{$row->created_at->format('F j, Y')}} at {{$row->created_at->format('h:i A')}}</td>
                                            <td>{{$row->activity}}</td>
                                            <td>
                                                @if($row->account_type == "App\\Admin") 
                                                    {{$row->account->admin_name}}
                                                @else
                                                    {{$row->account->station_name}} 
                                                @endif
                                            </td>
                                            <td>
                                                @if($row->officer_id != null)
                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#view_{{$row->id}}">{{$row->getOfficers->last_name}}, {{$row->getOfficers->first_name}} {{$row->getOfficers->middle_name}}</a>
                                                @else
                                                LOGOUT
                                                @endif
                                            </td>
                                        </tr>

                                        
                                        @if($row->officer_id != null)
                                        <!-- VIEW MODAL -->
                                        <div class="modal fade" id="view_{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormVIEW" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalFormEDIT">Police Officer Information</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row no-gutters">
                                                        <div class="col-md-6">
                                                            <div class="profile-content-left px-4">
                                                                <div class="card text-center widget-profile px-0 py-4 border-0">
                                                                    <div class="card-img mx-auto d-block rounded" style="height: 150px; width: 150px;">
                                                                        @if ($row->getOfficers->image == "TBD")
                                                                        <img src="{{ asset("uploads/user.jpg") }}" class="img-fluid" alt="user image">
                                                                        @else
                                                                        <img src="{{ asset($row->getOfficers->image) }}" class="img-fluid" alt="user image">
                                                                        @endif
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <h4 class="py-2 text-dark">{{$row->getOfficers->first_name}} {{$row->getOfficers->middle_name}} {{$row->getOfficers->last_name}}</h4>
                                                                        <p>{{$row->getOfficers->getRank->name}} ({{$row->getOfficers->getRank->abbreviation}})</p>
                                                                    </div>
                                                                    <hr class="w-100">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-6 contact-info">
                                                                            <p class="text-dark font-weight-medium pt-4 mb-2">PNP ID #</p>
                                                                            <p>{{$row->getOfficers->id_no}}</p>
                                                                        </div>
                                                                        <div class="col-sm-6 contact-info">
                                                                            <p class="text-dark font-weight-medium pt-4 mb-2">Badge #</p>
                                                                            <p>{{$row->getOfficers->badge_no}}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="profile-content-right">
                                                                <div class="col-md-12">
                                                                    <div class="card-body px-4">
                                                                        <h4 class="text-dark mb-1">Contact Details</h4>
                                                                            <hr class="w-100">
                                                                        <p class="text-dark font-weight-medium pt-4 mb-2">E-Mail</p>
                                                                        <p>{{$row->getOfficers->email}}</p>
                                                                        <p class="text-dark font-weight-medium pt-4 mb-2">Address</p>
                                                                        <p>{{$row->getOfficers->address}}</p>
                                                                        <p class="text-dark font-weight-medium pt-4 mb-2">Contact Number</p>
                                                                        <p>{{$row->getOfficers->contact_no}}</p>
                                                                        <p class="text-dark font-weight-medium pt-4 mb-2">Gender</p>
                                                                        <p>{{$row->getOfficers->gender}}</p>
                                                                        <div class="form-group row">
                                                                                <div class="col-sm-6">
                                                                                    <p class="text-dark font-weight-medium pt-4 mb-2">Date of Birth</p>
                                                                                    <p>{{ date('F j, Y', strtotime($row->getOfficers->birthday)) }}</p>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <p class="text-dark font-weight-medium pt-4 mb-2">Age</p>
                                                                                    <p>{{Carbon\Carbon::parse($row->getOfficers->birthday)->age}} years old</p>
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
                                        <!-- END VIEW MODAL -->
                                        @endif
                                    @endforeach
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    @include('PNPadmin.includes.notifications')

    @include('pnpadmin.includes.footer')

    </div>

</div>
@include('pnpadmin.includes.script')


<script src={{ asset("assets/plugins/data-tables/jquery.datatables.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.bootstrap4.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.responsive.min.js") }}></script>

<!--        DATA TABLE SCRIPT               -->
<script type="text/javascript">
  $(document).ready(function() {
    $('#responsive-data-table').DataTable({ 
        "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
        ordering: false,
     });
  });
</script>

</body>

</html>

@else 
    @include('PNPadmin.includes.419')
@endif
