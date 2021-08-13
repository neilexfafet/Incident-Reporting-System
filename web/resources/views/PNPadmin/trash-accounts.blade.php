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
                                <h2>Removed Accounts</h2>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-style-border mb-2 px-0" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#admin" role="tab" aria-selected="true">Admin</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#station" role="tab" aria-selected="false">Station</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent1">
                                    <div class="tab-pane pt-3 fade show active" id="admin" role="tabpanel">
                                        <div class="responsive-data-table">
                                            <table id="admin-data-table" class="table table-hover dt-responsive" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Admin Name</th>
                                                        <th>Location</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane pt-3 fade" id="station" role="tabpanel">
                                        <div class="responsive-data-table">
                                            <table id="station-data-table" class="table table-hover dt-responsive" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Station Name</th>
                                                        <th>Location</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RESTORE ADMIN MODAL -->
                <div class="modal fade" id="restore-admin" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div id="restore-admin-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                        <form id="restore-admin-form">
                        @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalFormTitle">Are you sure you want to restore this Account?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="restore-admin-id">
                                    <div class="form-row px-2 py-2">
                                        <label class="text-dark font-size-16">Input Password to continue</label>
                                        <input name="password" id="restore-password" type="password" class="form-control" placeholder="Password" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                        <span class="invalid-feedback" id="restore-password-inv">Please fill out this field</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Restore</button>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
                <!--END RESTORE ADMIN MODAL -->

                <!-- RESTORE STATION MODAL -->
                <div class="modal fade" id="restore-station" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div id="restore-station-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                        <form id="restore-station-form">
                        @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalFormTitle">Are you sure you want to restore this Account?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="restore-station-id">
                                    <div class="form-row px-2 py-2">
                                        <label class="text-dark font-size-16">Input Password to continue</label>
                                        <input name="password" id="restore-password2" type="password" class="form-control" placeholder="Password" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                        <span class="invalid-feedback" id="restore-password-inv2">Please fill out this field</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Restore</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--END RESTORE STATION MODAL -->

            </div>

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
    $('#admin-data-table').DataTable({
        "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('admin/trash/accounts/admin') }}",
        },
        columns: [
            { data: 'username' },
            { data: 'admin_name' },
            { data: 'admin_location' },
            { data: 'action', orderable: false, className: 'text-center' },
        ]
    });
    $('#station-data-table').DataTable({
        "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('admin/trash/accounts/station') }}",
        },
        columns: [
            { data: 'username' },
            { data: 'station_name' },
            { data: 'location_name' },
            { data: 'action', orderable: false, className: 'text-center' },
        ]
    });
});
</script>
<!-- =================================== END DATA TABLE SCRIPT ====================================== -->

<!-- ====================================== AJAX ================================== -->
<script>
$(document).ready(function() {
    /* RESTORE ADMIN */
    $('#restore-admin').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id= button.data('id')
        var modal = $(this)
        
        $('#restore-admin .modal-dialog').addClass('modal-dialog-centered');
        $('#restore-admin-loader').show();
        $('#restore-admin-form').hide();
        $('#restore-password').val("").removeClass('is-invalid');

        $.ajax({
            type: "GET",
            url: "{{ url('/admin/trash/accounts/admin/view') }}"+"/"+id,
            dataType: "JSON",
            success: function(data) {
                $('#restore-admin-id').val(data.id);
                $('#restore-admin-type').html(data.type);
            },
            complete: function() {
                $('#restore-admin .modal-dialog').removeClass('modal-dialog-centered');
                $('#restore-admin-loader').hide();
                $('#restore-admin-form').show();
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
    $('#restore-admin-form').on('submit', function(event) {
        event.preventDefault();

        $(this).hide();
        $('#restore-admin .modal-dialog').addClass('modal-dialog-centered');
        $('#restore-admin-loader').show();

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
        
        var restore_admin_id= $('#restore-admin-id').val();

        $.ajax({
            type: "POST",
            url: "/admin/trash/accounts/admin/restore/"+restore_admin_id,
            data: $('#restore-admin-form').serialize(),
            success: function(result) {
                if(result.success) {
                    $('#restore-admin').modal('hide');
                    $('#admin-data-table').DataTable().ajax.reload();
                    $('#restore-password').val("");
                    toastr.success(result.success);
                }
                if(result.error) {
                    $('#restore-admin .modal-dialog').removeClass('modal-dialog-centered');
                    $('#restore-admin-loader').hide();
                    $('#restore-admin-form').show();
                    $('#restore-password').val("");
                    $('#restore-password').addClass('is-invalid');
                    $('#restore-password-inv').html(result.error);
                }
            },
            error: function(error) {
                console.log(error)
            },
        });
    });
    /* END RESTORE ADMIN */

    /* RESTORE STATION */
    $('#restore-station').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id= button.data('id')
        var modal = $(this)

        $('#restore-station .modal-dialog').addClass('modal-dialog-centered');
        $('#restore-station-loader').show();
        $('#restore-station-form').hide();
        $('#restore-password2').val("").removeClass('is-invalid');

        $.ajax({
            type: "GET",
            url: "{{ url('/admin/trash/accounts/station/view') }}"+"/"+id,
            dataType: "JSON",
            success: function(data) {
                $('#restore-station-id').val(data.id);
                $('#restore-station-type').html(data.type);
            },
            complete: function() {
                $('#restore-station .modal-dialog').removeClass('modal-dialog-centered');
                $('#restore-station-loader').hide();
                $('#restore-station-form').show();
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
    $('#restore-station-form').on('submit', function(event) {
        event.preventDefault();

        $(this).hide();
        $('#restore-station .modal-dialog').addClass('modal-dialog-centered');
        $('#restore-station-loader').show();

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
        
        var restore_station_id= $('#restore-station-id').val();

        $.ajax({
            type: "POST",
            url: "/admin/trash/accounts/station/restore/"+restore_station_id,
            data: $('#restore-station-form').serialize(),
            success: function(result) {
                if(result.success) {
                    $('#restore-station').modal('hide');
                    $('#station-data-table').DataTable().ajax.reload();
                    $('#restore-password2').val("");
                    toastr.success(result.success);
                }
                if(result.error) {
                    $('#restore-station .modal-dialog').removeClass('modal-dialog-centered');
                    $('#restore-station-loader').hide();
                    $('#restore-station-form').show();
                    $('#restore-password2').val("");
                    $('#restore-password2').addClass('is-invalid');
                    $('#restore-password-inv2').html(result.error);
                }
            },
            error: function(error) {
                console.log(error)
            },
        });
    });
    /* END RESTORE STATION */
})
</script>
<!-- ====================================== AJAX ================================== -->


</body>
</html>

            
@else
    @include('PNPadmin.includes.419')
@endif