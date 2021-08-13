@if(Auth::guard('station')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>Station | Announcements</title>
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
                            <div class="card-header card-header-border-bottom d-flex justify-content-between">
                                <h2>Announcements</h2>
                                <a href="javascript:void(0);" class="btn btn-outline-primary text-uppercase" data-toggle="modal" data-target="#add-announcement">
                                    <i class=" mdi mdi-account"></i>&nbsp;&nbsp;&nbsp;Post
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="responsive-data-table">
                                    <table id="responsive-data-table" class="table table-hover dt-responsive" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="20%">Subject </th>
                                                <th width="30%">Message</th>
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

            <!-- ADD ANNOUNCEMENT MODAL -->
            <div class="modal fade" id="add-announcement" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div id="add-announcement-loader" class="col-sm-12" style="display: none;">@include('PNPstation.includes.loader')</div>
                    <form id="add-announcement-form">
                    @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalFormTitle">ANNOUNCEMENT</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-success" style="display:none" role="alert"></div>
                                <div class="form px-2 pt-2">
                                    <label class="control control-checkbox">Post Announcement as Image
                                        <input id="image-checkbox" type="checkbox">
                                        <div class="control-indicator"></div>
                                    </label>
                                </div>
                                <div id="post-content">
                                    <div class="form-row px-2 py-2">
                                        <label class="text-dark font-size-16">Subject</label>
                                        <input name="subject" type="text" class="form-control" placeholder="Subject" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                        <span class="invalid-feedback">Please fill out this field.</span>
                                    </div>
                                    <div class="form-row px-2 py-2">
                                        <label class="text-dark font-size-16">Message</label>
                                        <textarea name="message" type="text" class="form-control" rows="5" placeholder="Message" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')"></textarea>
                                        <span class="invalid-feedback">Please fill out this field.</span>
                                    </div>
                                </div>
                                <div id="post-image" style="display: none;">
                                    <div class="custom-file">
                                        <input name="image" type="file" class="custom-file-input" id="file-upload" accept="image/*" onchange="readURL(this);">
                                        <label class="custom-file-label" for="file-upload"><span id="file-upload-filename">Choose File . . .</span></label>
                                        <span class="d-block mt-1" style="font-size: 85%">Only Image File Types are Accepted.</span>
                                    </div>
                                    <div class="text-center">
                                        <img id="image-change" class="img-fluid" style="display: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--END ADD ANNOUNCEMENT MODAL -->

            <!-- VIEW ANNOUNCEMENT MODAL -->
            <div class="modal fade" id="view-announcement" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div id="view-announcement-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                    <div class="modal-content" id="view-announcement-content">
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
                        <div class="modal-footer d-flex justify-content-between">
                            <div class="p-2">
                                <span>From:</span>&nbsp;<span class="text-dark font-weight-bold" id="view-announcement-from"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--END VIEW ANNOUNCEMENT MODAL -->

            <!-- UPDATE ANNOUNCEMENT MODAL -->
            <div class="modal fade" id="update-announcement" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div id="update-announcement-loader" class="col-sm-12" style="display: none;">@include('PNPstation.includes.loader')</div>
                    <form id="update-announcement-form">
                    @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalFormTitle">ANNOUNCEMENT</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="update-announcement-id">
                                <div id="update-announcement-success" class="alert alert-success" style="display:none" role="alert"></div>
                                <div id="update-post-content">
                                    <div class="form-row px-2 py-2">
                                        <label class="text-dark font-size-16">Subject</label>
                                        <input name="subject" id="update-announcement-subject" type="text" class="form-control" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                        <span class="invalid-feedback">Please fill out this field.</span>
                                    </div>
                                    <div class="form-row px-2 py-2">
                                        <label class="text-dark font-size-16">Message</label>
                                        <textarea name="message" id="update-announcement-message" type="text" class="form-control" rows="5" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')"></textarea>
                                        <span class="invalid-feedback">Please fill out this field.</span>
                                    </div>
                                </div>
                                <div id="update-post-image">
                                    <div class="text-center">
                                        <img id="update-announcement-image" class="img-fluid">
                                    </div>
                                    <div class="custom-file mt-4">
                                        <input name="image" type="file" class="custom-file-input" id="update-file-upload" accept="image/*" onchange="readURL2(this);" required>
                                        <label class="custom-file-label" for="file-upload"><span id="update-file-upload-filename">Choose File . . .</span></label>
                                        <span class="d-block mt-1" style="font-size: 85%">Only Image File Types are Accepted.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--END UPDATE ANNOUNCEMENT MODAL -->

            <!-- REMOVE ANNOUNCEMENT -->
            <div class="modal fade" id="remove-announcement" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div id="remove-announcement-loader" class="col-sm-12" style="display: none;">@include('PNPstation.includes.loader')</div>
                    <form id="remove-announcement-form">
                    @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalFormTitle">Remove Announcement</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="remove-announcement-id">
                                <h3 class="text-dark py-2">Are you sure you want to remove this announcement?</h3>
                                <table class="py-4">
                                    <tr class="py-4">
                                        <td width="30%"><h5>Subject:</h5></td>
                                        <td width="70%"><h5><span id="remove-announcement-subject"></span></h5></td>
                                    </tr>
                                    <tr class="py-4">
                                        <td width="30%"><h5>Message:<h5></td>
                                        <td width="70%"><h5><span id="remove-announcement-message"></span></h5></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Remove</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END REMOVE ANNOUNCEMENT -->

            @include('pnpstation.includes.notifications')
            @include('pnpstation.includes.footer')
        </div>
    </div>
</div>
@include('pnpstation.includes.script')

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
            url: "{{ url('station/announcements') }}",
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
            { data: 'created_at', render: function(data, type, full, meta) {
                var created_at_date = moment(data).format('LL');
                var created_at_time = moment(data).format('LT');
                return created_at_date+' at '+created_at_time;
            } },
            { data: 'action', orderable: false, className: "text-center" },
        ]
    });
    /* setInterval( function () {
        table.ajax.reload();
    }, 30000 ); */
  });
</script>
<!-- ================================= END DATATABLE ======================================= -->

<!-- ==================================== AJAX ============================================ -->
<script>
/* POST IMAGE CHECKBOX */
$('#image-checkbox').change(function() {
    if($(this).is(':checked')) {
        $('#post-image').show();
        $('#post-content').hide();
        $('input[name=subject]').val("").prop('required', false);
        $('textarea[name=message]').val("").prop('required', false);
        $('input[name=image]').val("").prop('required', true);
    } else {
        $('#post-content').show();
        $('#post-image').hide();
        $('#image-change').hide();
        $('#file-upload-filename').html("Choose file . . .");
        $('input[name=subject]').val("").prop('required', true);
        $('textarea[name=message]').val("").prop('required', true);
        $('input[name=image]').val("").prop('required', false);
    }
});
/* END POST IMAGE CHECKBOX */

/* ADD ANNOUNCEMENT */
$('#add-announcement-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#add-announcement .modal-dialog').addClass('modal-dialog-centered');
    $('#add-announcement-loader').show();

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

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "{{ url('station/addannouncement') }}",
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            $('#add-announcement').modal('hide');
            $('#add-announcement .modal-dialog').removeClass('modal-dialog-centered');
            $('#add-announcement-loader').hide();
            $('#add-announcement-form').show();
            $('#file-upload-filename').html("Choose file . . .");
            $('#image-checkbox').prop('checked', false);
            $('#post-content').show();
            $('#post-image').hide();
            $('#image-change').hide();
            $('#responsive-data-table').DataTable().ajax.reload();
            document.getElementById('add-announcement-form').reset();
            toastr.success(result.success);
        },
        error: function(error) {
            console.log(error);
        },
    });
});
/* END ADD ANNOUNCEMENT */

/* VIEW ANNOUNCEMENT */
$('#view-announcement').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#view-announcement .modal-dialog').addClass('modal-dialog-centered');
    $('#view-announcement-content').hide();
    $('#view-post-content').hide();
    $('#view-post-image').hide();
    $('#view-announcement-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('/station/viewannouncement/') }}"+"/"+id,
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
                if(data.from_type == "App\\Station") {
                    $('#view-announcement-from').html(data.from.station_name);
                } else {
                    $('#view-announcement-from').html(data.from.admin_name);
                }
            }
        },
        complete: function() {
            $('#view-announcement .modal-dialog').removeClass('modal-dialog-centered');
            $('#view-announcement-loader').hide();
            $('#view-announcement-content').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
});
/* END VIEW ANNOUNCEMENT */

/* UPDATE ANNOUNCEMENT */
$('#update-announcement').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#update-announcement .modal-dialog').addClass('modal-dialog-centered');
    $('#update-announcement-form').hide();
    $('#update-post-content').hide();
    $('#update-post-image').hide();
    $('#update-announcement-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('/station/viewannouncement/') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#update-announcement-id').val(data.id);
            if(data.image == "TBD") {
                $('#update-post-content').show();
                $('#update-announcement-subject').val(data.subject);
                $('#update-announcement-message').val(data.message);
                $('#update-file-upload').prop('required', false);
            } else {
                $('#update-post-image').show();
                $('#update-announcement-subject').prop('required', false);
                $('#update-announcement-message').prop('required', false);
                $('#update-announcement-image').attr('src', '{{asset("/")}}'+data.image);
            }
        },
        complete: function() {
            $('#update-announcement .modal-dialog').removeClass('modal-dialog-centered');
            $('#update-announcement-loader').hide();
            $('#update-announcement-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
});

$('#update-announcement-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#update-announcement .modal-dialog').addClass('modal-dialog-centered');
    $('#update-announcement-loader').show();

    var update_announcement_id = $('#update-announcement-id').val();

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "/station/updateannouncement/"+update_announcement_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            $('#update-announcement .modal-dialog').removeClass('modal-dialog-centered');
            $('#update-announcement-loader').hide();
            $('#update-announcement-form').show();
            $('#update-announcement-success').show().html(result.success);
            $('#update-file-upload-filename').html("Choose file . . .");
            $('#responsive-data-table').DataTable().ajax.reload();
            setTimeout(function() {
                $('#update-announcement-success').fadeOut('slow');
            }, 3000);
        },
        error: function(error) {
            console.log(error);
        },
    });
});
/* END UPDATE ANNOUNCEMENT */

/* REMOVE ANNOUNCEMENT */
$('#remove-announcement').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var modal = $(this)

    $('#remove-announcement-form').hide();
    $('#remove-announcement .modal-dialog').addClass('modal-dialog-centered');
    $('#remove-announcement-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('/station/viewannouncement/') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            var station_id = data.station_id;

            $('#remove-announcement-id').val(data.id);
            $('#remove-announcement-subject').html(data.subject);
            $('#remove-announcement-message').html(data.message);
        },
        complete: function() {
            $('#remove-announcement .modal-dialog').removeClass('modal-dialog-centered');
            $('#remove-announcement-loader').hide();
            $('#remove-announcement-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
});

$('#remove-announcement-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#remove-announcement .modal-dialog').addClass('modal-dialog-centered');
    $('#remove-announcement-loader').show();

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

    var remove_announcement_id = $('#remove-announcement-id').val();

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "/station/removeannouncement/"+remove_announcement_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            $('#remove-announcement').modal('hide');
            $('#remove-announcement .modal-dialog').removeClass('modal-dialog-centered');
            $('#responsive-data-table').DataTable().ajax.reload();
            toastr.error("Data transfered to TRASH", result.success);
        },
        error: function(error) {
            console.log(error);
        }
    });
});
/* END REMOVE ANNOUNCEMENT */
</script>
<!-- =================================== END AJAX =========================================== -->

<!-- ================================== FILE/IMAGE PREVIEWS ===================================== -->
<script type="text/javascript">
    var input = document.getElementById('file-upload');
    var infoArea = document.getElementById('file-upload-filename');
    input.addEventListener( 'change', showFileName );
    function showFileName( event ) {
    // the change event gives us the input it occurred in 
    var input = event.srcElement;
    // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
    var fileName = input.files[0].name;
    // use fileName however fits your app best, i.e. add it into a div
    infoArea.textContent = fileName;
    }

    var u_input = document.getElementById('update-file-upload');
    var u_infoArea = document.getElementById('update-file-upload-filename');
    u_input.addEventListener( 'change', showFileName2 );
    function showFileName2( event ) {
    // the change event gives us the input it occurred in 
    var u_input = event.srcElement;
    // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
    var u_fileName = u_input.files[0].name;
    // use fileName however fits your app best, i.e. add it into a div
    u_infoArea.textContent = u_fileName;
    }
</script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image-change').show().attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#update-announcement-image').show().attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<!-- ======================================= END FILE/IMAGE PREVIEWS ==================================== --> 

</body>

</html>


@else 
    @include('PNPstation.includes.419')
@endif
