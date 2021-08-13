@if(Auth::guard('station')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>Station | Police Officers</title>
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
                            <div class="card card-default" id="recent-orders">
                                <div class="card-header card-header-border-bottom justify-content-between">
                                    <h2>Police Officers</h2>
                                </div>
                                <div class="card-body">
                                    <div class="responsive-data-table">
                                        <table id="responsive-data-table" class="table table-hover dt-responsive" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="25%">Name</th>
                                                    <th width="25%">Rank</th>
                                                    <th width="20%">PNP ID</th>
                                                    <th width="20%">Badge No.</th>
                                                    <th width="10%">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

            <!-- VIEW MODAL -->
            <div class="modal fade" id="view-assign" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormVIEW" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div id="view-assign-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                    <div class="modal-content" id="view-assign-form">
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
                                                <img id="view-assign-image" class="img-fluid" alt="user image">
                                            </div>
                                            <div class="card-body">
                                                <h4 class="py-2 text-dark"><span id="view-assign-name"></span></h4>
                                                <p><span id="view-assign-rank"></span></p>
                                            </div>
                                            <hr class="w-100">
                                            <div class="form-group row">
                                                <div class="col-sm-6 contact-info">
                                                    <p class="text-dark font-weight-medium pt-4 mb-2">PNP ID #</p>
                                                    <p><span id="view-assign-idno"></span></p>
                                                </div>
                                                <div class="col-sm-6 contact-info">
                                                    <p class="text-dark font-weight-medium pt-4 mb-2">Badge #</p>
                                                    <p><span id="view-assign-badgeno"></span></p>
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
                                                <p><span id="view-assign-email"></span></p>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Address</p>
                                                <p><span id="view-assign-address"></span></p>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Contact Number</p>
                                                <p><span id="view-assign-contactno"></span></p>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Gender</p>
                                                <p><span id="view-assign-gender"></span</p>
                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <p class="text-dark font-weight-medium pt-4 mb-2">Date of Birth</p>
                                                        <p><span id="view-assign-birthday"></span></p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p class="text-dark font-weight-medium pt-4 mb-2">Age</p>
                                                        <p><span id="view-assign-age"></span> Years Old</p>
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


            @include('pnpstation.includes.notifications')
            @include('pnpstation.includes.footer')
            </div>
        </div>
    </div>

@include('pnpstation.includes.script')


<!-- =================================== DATA TABLE ============================================= -->
<script src={{ asset("assets/plugins/data-tables/jquery.datatables.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.bootstrap4.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.responsive.min.js") }}></script>

<script>
    $(document).ready(function() {
        $('#responsive-data-table').DataTable({
            "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
            processing: true,
            serverSide: true,
            language: {
                emptyTable: 'No Police Officers Assigned',
                zeroRecords: 'No Police Officer found. Try searching another keyword',
            },
            ajax: {
                url: "{{ url('station/officers') }}",
            },
            columns: [
                { data: 'last_name', render: function(data, type, row) {
                    return data + ', ' + row["first_name"];
                } },
                { data: 'get_rank.name', render: function(data, type, row)  {
                    return data+' ('+row.get_rank["abbreviation"]+')';
                } },
                { data: 'id_no' },
                { data: 'badge_no' },
                { data: 'action', orderable: false, className: 'text-center', },
            ]
        });
    });
</script>
<!-- =================================================== END DATA TABLE ========================================== -->

<!-- ========================================== AJAX =========================================== -->
<script>
$(document).ready(function() {
    /* VIEW OFFICER */
    $('#view-assign').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);

        $('#view-assign .modal-dialog').addClass('modal-dialog-centered');
        $('#view-assign-form').hide();
        $('#view-assign-loader').show();

        $.ajax({
            type: "GET",
            url: "{{ url('/station/officers/view/') }}"+"/"+id,
            dataType: "JSON",
            success: function(data) {
                var img = data.image;
                var bday = moment(data.birthday).format('LL');
                var age = moment().diff(data.birthday, 'years');

                $('#view-assign-name').html(data.last_name+", "+data.first_name+" "+data.middle_name);
                $('#view-assign-rank').html(data.get_rank.name+" ("+data.get_rank.abbreviation+")");
                $('#view-assign-idno').html(data.id_no);
                $('#view-assign-badgeno').html(data.badge_no);
                $('#view-assign-email').html(data.email);
                $('#view-assign-address').html(data.address);
                $('#view-assign-contactno').html(data.contact_no);
                $('#view-assign-gender').html(data.gender);
                $('#view-assign-birthday').html(bday);
                $('#view-assign-age').html(age);
                if(img == "TBD") {
                    $('#view-assign-image').attr('src', '{{asset("uploads/user.jpg")}}');
                } else {
                    $('#view-assign-image').attr('src', '{{asset("/")}}'+img);
                }
            },
            complete: function() {
                $('#view-assign .modal-dialog').removeClass('modal-dialog-centered');
                $('#view-assign-loader').hide();
                $('#view-assign-form').show();
            },
            error: function(error) {
                console.log(error);
            },
        });
    })
    /* END VIEW OFFICER */
})
</script>
<!-- ========================================== AJAX =========================================== -->

</body>
</html>


@else 
    @include('PNPstation.includes.419')
@endif