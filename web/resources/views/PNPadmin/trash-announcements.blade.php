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
                                    <h2>Removed Announcements</h2>
                                </div>
                                <div class="card-body">
                                    <div class="responsive-data-table">
                                        <table id="responsive-data-table" class="table table-hover dt-responsive" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th width="25%">Subject </th>
                                                    <th width="25%">Message</th>
                                                    <th width="25%">Date Announced</th>
                                                    <th width="25%">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- VIEW ANNOUNCEMENT MODAL -->
                <div class="modal fade" id="view-announcement" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div id="view-announcement-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                        <div id="view-announcement-form" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalFormTitle">ANNOUNCEMENT</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="view-post-content">
                                    <div class="form-row px-2 py-2">
                                        <label class="text-dark font-size-16">Subject</label>
                                        <input id="view-announcement-subject" type="text" class="form-control" disabled>
                                    </div>
                                    <div class="form-row px-2 py-2">
                                        <label class="text-dark font-size-16">Message</label>
                                        <textarea id="view-announcement-message" type="text" class="form-control" rows="5" disabled></textarea>
                                    </div>
                                </div>
                                <div id="view-post-image">
                                    <div class="text-center">
                                        <img id="view-announcement-image" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-start">
                                <span>From:</span>&nbsp;<span class="text-dark font-weight-bold" id="view-announcement-from"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--END VIEW ANNOUNCEMENT MODAL -->

                <!-- RESTORE ANNOUNCEMENT -->
                <div class="modal fade" id="restore-announcement" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div id="restore-announcement-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                        <form id="restore-announcement-form">
                        @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalFormTitle">Are you sure you want to Restore this announcement?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="restore-announcement-id">
                                    <div class="form-row px-2 py-2">
                                        <label class="text-dark font-size-16">Input Password to continue</label>
                                        <input name="password" id="restore-password" type="password" class="form-control" placeholder="Password" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                        <span class="invalid-feedback" id="restore-password-inv">Please fill out this field</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" data-style="expand-right">Restore</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END RESTORE ANNOUNCEMENT -->


                @include('pnpadmin.includes.notifications')
            @include('pnpadmin.includes.footer')
        </div>
    </div>
</div>
@include('pnpadmin.includes.script')

<!-- ===================================== DATATABLE ===================================== -->
<script src={{ asset("assets/plugins/data-tables/jquery.datatables.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.bootstrap4.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.responsive.min.js") }}></script>

<script>
  $(document).ready(function() {
    var table = $('#responsive-data-table').DataTable({
        "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
        order: [[2, "desc"]],
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('admin/trash/announcements') }}",
        },
        columns: [
            { data: 'subject', render: function(data) {
                if(data == null) {
                    return '<span class="font-weight-bolder">IMAGE POSTED</span>';
                } else {
                    return data;
                }
            } },
            { data: 'message', render: function(data) {
                if(data == null) {
                    return '<span class="font-weight-bolder">IMAGE POSTED</span>';
                } else {
                    return data;
                }
            } },
            /* { data: 'from_type', render: function(data, type, row) {
                var confirm = data;
                if(data == confirm) {
                    return row.from["admin_name"];
                } else {
                    return row.from["station_name"];
                }
            } }, */
            { data: 'created_at', render: function(data, type, full, meta) {
                var created_at_date = moment(data).format('LL');
                var created_at_time = moment(data).format('LT');
                return created_at_date+' at '+created_at_time;
            } },
            { data: 'action', orderable: false, className: 'text-center' },
        ]
    });
  });
</script>
<!-- ================================= END DATATABLE ======================================= -->

<!-- =========================================== AJAX ============================================= -->
<script>
/* VIEW ANNOUNCEMENT */
$('#view-announcement').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);
    
    $('#view-announcement .modal-dialog').addClass('modal-dialog-centered');
    $('#view-announcement-form').hide();
    $('#view-post-content').hide();
    $('#view-post-image').hide();

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/post/viewannouncement/') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            if(data.image == "TBD") {
                $('#view-post-content').show();
                $('#view-announcement-subject').val(data.subject);
                $('#view-announcement-message').val(data.message);
                if(data.from_type == "App\\Admin") {
                    $('#view-announcement-from').html(data.from.admin_name);
                } else {
                    $('#view-announcement-from').html(data.from.station_name);
                }
            } else {
                $('#view-post-image').show();
                $('#view-announcement-image').attr('src', '{{asset("/")}}'+data.image);
                if(data.from_type == "App\\Admin") {
                    $('#view-announcement-from').html(data.from.admin_name);
                } else {
                    $('#view-announcement-from').html(data.from.station_name);
                }
            }
        }, 
        complete: function() {
            $('#view-announcement .modal-dialog').removeClass('modal-dialog-centered');
            $('#view-announcement-loader').hide();
            $('#view-announcement-form').show();
        },
        error: function(error) {
            console.log(error);
        }
    });
});
/* END VIEW ANNOUNCEMENT */

/* RESTORE ANNOUNCEMENT */
$('#restore-announcement').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var modal = $(this)

    $('#restore-announcement .modal-dialog').addClass('modal-dialog-centered');
    $('#restore-announcement-form').hide();
    $('#restore-announcement-loader').show();
    $('#restore-password').val("").removeClass('is-invalid');

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/post/viewannouncement/') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#restore-announcement-id').val(data.id);
        },
        complete: function() {
            $('#restore-announcement .modal-dialog').removeClass('modal-dialog-centered');
            $('#restore-announcement-loader').hide();
            $('#restore-announcement-form').show();
        },
        error: function(error) {
            console.log(error);
        }
    });
});

$('#restore-announcement-form').on('submit', function (event) {
    event.preventDefault();
    
    $(this).hide();
    $('#restore-announcement .modal-dialog').addClass('modal-dialog-centered');
    $('#restore-announcement-loader').show();

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

    var restore_announcement_id = $('.modal-body #restore-announcement-id').val();

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "/admin/trash/announcements/restore/"+restore_announcement_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if(result.success) {
                $('#restore-announcement').modal('hide');
                $('#responsive-data-table').DataTable().ajax.reload();
                $('#restore-password').val("");
                toastr.success("",result.success);
            }
            if(result.error) {
                $('#restore-announcement .modal-dialog').removeClass('modal-dialog-centered');
                $('#restore-announcement-loader').hide();
                $('#restore-announcement-form').show();
                $('#restore-password').val("");
                $('#restore-password').addClass('is-invalid');
                $('#restore-password-inv').html(result.error);
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
});
/* END RESTORE ANNOUNCEMENT */
</script>
<!-- ========================================= END AJAX ============================================ -->


</body>
</html>

            
@else 
    @include('PNPadmin.includes.419')
@endif