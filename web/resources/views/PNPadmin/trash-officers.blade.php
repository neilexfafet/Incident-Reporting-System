@if(Auth::guard('admin')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>PNP | Archive</title>
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
                                <h2>Removed Officers</h2>
                            </div>
                            <div class="card-body">
                                <div class="responsive-data-table">
                                    <table id="responsive-data-table" class="table table-hover dt-responsive" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="20%">Name</th>
                                                <th width="20%">Rank</th>
                                                <th width="20%">PNP ID #</th>
                                                <th width="20%">Badge #</th>
                                                <th width="20%">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIEW MODAL -->
            <div class="modal fade" id="view-officer" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormVIEW" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div id="view-officer-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                    <div id="view-officer-form" class="modal-content">
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
                                                
                                                <img id="view-officer-image" class="img-fluid" alt="user image">
                                                
                                            </div>
                                            <div class="card-body">
                                                <h4 class="py-2 text-dark"><span id="view-officer-name"></span></h4>
                                                <p><span id="view-officer-rank"></span></p>
                                            </div>
                                            <hr class="w-100">
                                            <div class="form-group row">
                                                <div class="col-sm-6 contact-info">
                                                    <p class="text-dark font-weight-medium pt-4 mb-2">PNP ID #</p>
                                                    <p><span id="view-officer-idno"></span></p>
                                                </div>
                                                <div class="col-sm-6 contact-info">
                                                    <p class="text-dark font-weight-medium pt-4 mb-2">Badge #</p>
                                                    <p><span id="view-officer-badgeno"></span></p>
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
                                                <p><span id="view-officer-email"></span></p>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Address</p>
                                                <p><span id="view-officer-address"></span></p>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Contact Number</p>
                                                <p><span id="view-officer-contactno"></span></p>
                                                <p class="text-dark font-weight-medium pt-4 mb-2">Gender</p>
                                                <p><span id="view-officer-gender"></span</p>
                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <p class="text-dark font-weight-medium pt-4 mb-2">Date of Birth</p>
                                                        <p><span id="view-officer-birthday"></span></p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p class="text-dark font-weight-medium pt-4 mb-2">Age</p>
                                                        <p><span id="view-officer-age"></span> Years Old</p>
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

            <!-- RESTORE OFFICER MODAL -->
                <div class="modal fade" id="restore-officer" tabindex="-1" role="dialog" aria-labelledby="exampleupdate-station" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div id="restore-officer-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                        <form id="restore-officer-form">
                        @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleupdate-station">Are you sure you want to restore Officer?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                <div class="alert alert-success" style="display: none;" role="alert"></div>
                                    <input type="hidden" id="restore-officer-id">
                                    <div class="form-row px-2 py-2">
                                        <label class="text-dark font-size-16">Input Password to continue</label>
                                        <input name="password" id="restore-password" type="password" class="form-control" placeholder="Password" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                        <span class="invalid-feedback" id="restore-password-inv">Please fill out this field</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Restore</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <!--END RESTORE OFFICER MODAL -->

            @include('PNPadmin.includes.notifications')
            @include('pnpadmin.includes.footer')
        </div>
    </div>
</div>
@include('pnpadmin.includes.script')

<!-- ================================== DATA TABLE SCRIPT ========================================= -->
<script src={{ asset("assets/plugins/data-tables/jquery.datatables.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.bootstrap4.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.responsive.min.js") }}></script>
<script type="text/javascript">
$(document).ready(function() {
    var table = $('#responsive-data-table').DataTable({
        "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('admin/trash/officers') }}",
        },
        columns: [
            { data: 'last_name', render: function(data, type, row) {
                return data + ", " + row["first_name"] + " " + row["middle_name"];
            } },
            { data: 'get_rank.name', render: function(data, type, row) {
                return data+' ('+row.get_rank["abbreviation"]+')';
            } },
            { data: 'id_no' },
            { data: 'badge_no' },
            { data: 'action', orderable: false, className: 'text-center' },
        ]
    });
});
</script>
<!-- =================================== END DATA TABLE SCRIPT ====================================== -->

<!-- ==================================== AJAX ============================================== -->
<script>
/* VIEW OFFICER */
$('#view-officer').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var modal = $(this)
    
    $('#view-officer .modal-dialog').addClass('modal-dialog-centered');
    $('#view-officer-form').hide();
    $('#view-officer-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/trash/officers/view') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            var img = data.image;
            var bday = moment(data.birthday).format('LL');
            var age = moment().diff(data.birthday, 'years');

            $('#view-officer-name').html(data.last_name+", "+data.first_name+" "+data.middle_name);
            $('#view-officer-rank').html(data.rank);
            $('#view-officer-idno').html(data.id_no);
            $('#view-officer-badgeno').html(data.badge_no);
            $('#view-officer-email').html(data.email);
            $('#view-officer-address').html(data.address);
            $('#view-officer-contactno').html(data.contact_no);
            $('#view-officer-gender').html(data.gender);
            $('#view-officer-birthday').html(bday);
            $('#view-officer-age').html(age);
            if(img == "TBD") {
                $('#view-officer-image').attr('src', '{{asset("uploads/user.jpg")}}');
            } else {
                $('#view-officer-image').attr('src', '{{asset("/")}}'+img);
            }
        },
        complete: function() {
            $('#view-officer .modal-dialog').removeClass('modal-dialog-centered');
            $('#view-officer-loader').hide();
            $('#view-officer-form').show();
        },
        error: function(error) {
            console.log(error);
        }
    });
});
/* END VIEW OFFICER */

/* RESTORE OFFICER */
$('#restore-officer').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var modal = $(this)
    
    $('#restore-officer .modal-dialog').addClass('modal-dialog-centered');
    $('#restore-officer-form').hide();
    $('#restore-officer-loader').show();
    $('#restore-password').val("").removeClass('is-invalid');

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/trash/officers/view') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#restore-officer-id').val(data.id);
        },
        complete: function() {
            $('#restore-officer .modal-dialog').removeClass('modal-dialog-centered');
            $('#restore-officer-loader').hide();
            $('#restore-officer-form').show();
        },
        error: function(error) {
            console.log(error);
        }
    });
});
$('#restore-officer-form').on('submit', function(event) {
    event.preventDefault();

    $('#restore-officer .modal-dialog').addClass('modal-dialog-centered');
    $('#restore-officer-form').hide();
    $('#restore-officer-loader').show();

    toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: false,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: false,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "5000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    };
    
    var restore_officer_id = $('#restore-officer-id').val();

    $.ajax({
        type: "POST",
        url: "/admin/trash/officers/restore/"+restore_officer_id,
        data: $('#restore-officer-form').serialize(),
        success: function(result) {
            if(result.success) {
                $('#restore-officer').modal('hide');
                $('#responsive-data-table').DataTable().ajax.reload();
                $('#restore-password').val("");
                toastr.success(result.success);
            }
            if(result.error) {
                $('#restore-officer .modal-dialog').removeClass('modal-dialog-centered');
                $('#restore-officer-loader').hide();
                $('#restore-officer-form').show();
                $('#restore-password').val("");
                $('#restore-password').addClass('is-invalid');
                $('#restore-password-inv').html(result.error);
            }
        },
        error: function(error) {
            console.log(error);
        },
    });
});
/* END RESTORE OFFICER */
</script>
<!-- ================================== END AJAX ============================================== -->


</body>
</html>

            
@else 
    @include('PNPadmin.includes.419')
@endif