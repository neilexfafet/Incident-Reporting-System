@if(Auth::guard('admin')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>PNP | Officers</title>
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
                                <h2>News</h2>
                                <a href="javascript:void(0);" class="btn btn-outline-primary text-uppercase" data-toggle="modal" data-target="#add-news">
                                    <i class=" mdi mdi-account"></i>&nbsp;&nbsp;&nbsp;Post
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="responsive-data-table">
                                    <table id="responsive-data-table" class="table table-hover dt-responsive" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="35%">Title</th>
                                                <th width="15%">Author</th>
                                                <th width="25%">Published Date</th>
                                                <th width="25%">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

            <!-- ADD NEWS MODAL -->
            <div class="modal fade" id="add-news" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div id="add-news-loader" class="col-sm-12" style="display: none;">@include('PNPadmin.includes.loader')</div>
                    <form id="add-news-form">
                    @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalFormTitle">News Information</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="add-news-alert-success" class="alert alert-success" style="display:none" role="alert"></div>
                                <div class="form-row px-2 py-2">
                                    <label class="text-dark font-size-16">Title</label>
                                    <input name="title" type="text" class="form-control" placeholder="Title" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                    <span class="invalid-feedback">Please fill out this field.</span>
                                </div>
                                <div class="form-row px-2 py-2">
                                    <label class="text-dark font-size-16">Author</label>
                                    <input name="author" type="text" class="form-control" placeholder="Author" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                    <span class="invalid-feedback">Please fill out this field.</span>
                                </div>
                                <div class="form-group">
                                    <label class="text-dark font-size-16">Content</label>
                                    <textarea name="content" id="add-news-content" class="summernote" placeholder="Content Here . . ." required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')"></textarea>
                                    <span class="invalid-feedback">Please fill out this field.</span>
                                </div>
                                <div class="form-row px-2 py-2">
                                    <label class="text-dark font-size-16">Source URL</label>
                                    <input name="source_link" type="url" class="form-control" placeholder="URL" value="https://" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                    <span class="invalid-feedback">Please enter a URL.</span>
                                </div>
                                <div class="form-row px-2 py-2">
                                    <label class="text-dark font-size-16">Upload Image</label>
                                    <div class="custom-file mb-1">
                                        <input name="image" type="file" class="custom-file-input" id="file-upload" accept="image/*" onchange="readURL(this);" required>
                                        <label class="custom-file-label" for="file-upload"><span id="file-upload-filename">Choose File . . .</span></label>
                                    </div>
                                    <span class="d-block mt-1" style="font-size: 85%">Only Image File Types are Accepted.</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Post</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--END ADD NEWS MODAL -->

            <!-- VIEW NEWS MODAL -->
            <div class="modal fade" id="view-news" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div id="view-news-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                    <div id="view-news-section" class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title text-dark"><span id="view-news-title"></span></h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center">
                                <img id="view-news-image" class="img-fluid">
                            </div>
                            <div class="card-body">
                                <p class="card-text"><span id="view-news-content"></span></p>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <div class="p-2">
                                <span class="text-dark font-weight-bold">Author:</span>&nbsp;<span id="view-news-author"></span>
                                <span class="text-dark font-weight-bold">&nbsp;|&nbsp; Published Date:</span>&nbsp;<span id="view-news-publishdate"></span>
                            </div>
                            <div class="p-2">
                                <span class="text-dark font-weight-bold">Source:</span> <a href="" id="view-news-sourcelink"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--END VIEW NEWS MODAL -->

            <!-- UPDATE NEWS MODAL -->
            <div class="modal fade" id="update-news" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div id="update-news-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                    <form id="update-news-form">
                    @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalFormTitle">Update News Information</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="update-news-alert-success" class="alert alert-success" style="display:none" role="alert"></div>
                                <input type="hidden" id="update-news-id">
                                <div class="form-row px-2 py-2">
                                    <label class="text-dark font-size-16">Title</label>
                                    <input name="title" id="update-news-title" type="text" class="form-control" placeholder="Title" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                    <span class="invalid-feedback">Please fill out this field.</span>
                                </div>
                                <div class="form-row px-2 py-2">
                                    <label class="text-dark font-size-16">Author</label>
                                    <input name="author" id="update-news-author" type="text" class="form-control" placeholder="Author" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                    <span class="invalid-feedback">Please fill out this field.</span>
                                </div>
                                <div class="form-group">
                                    <label class="text-dark font-size-16">Content</label>
                                    <textarea name="content" id="update-news-content" class="summernote" placeholder="Content Here . . ." required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')"></textarea>
                                    <span class="invalid-feedback">Please fill out this field.</span>
                                </div>
                                <div class="form-row px-2 py-2">
                                    <label class="text-dark font-size-16">Source URL</label>
                                    <input name="source_link" id="update-news-sourcelink" type="url" class="form-control" placeholder="URL" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                    <span class="invalid-feedback">Please enter a URL.</span>
                                </div>
                                <div class="form-row px-2 py-2">
                                    <label class="text-dark font-size-16">Upload Image</label>
                                    <div class="custom-file mb-1">
                                        <input name="image" type="file" class="custom-file-input" id="update-file-upload" accept="image/*" onchange="readURL(this);">
                                        <label class="custom-file-label" for="file-upload"><span id="update-file-upload-filename">Choose File . . .</span></label>
                                    </div>
                                    <span class="d-block mt-1" style="font-size: 85%">Only Image File Types are Accepted.</span>
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
            <!--END UPDATE NEWS MODAL -->

            <!-- REMOVE NEWS MODAL -->
            <div class="modal fade" id="remove-news" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div id="remove-news-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                    <form id="remove-news-form">
                    @csrf 
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Are you sure you want to remove this data?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="remove-news-id">
                                <div class="form-row px-2 py-2">
                                    <label class="text-dark font-size-16">Input Password to continue</label>
                                    <input name="password" id="remove-password" type="password" class="form-control" placeholder="Password" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                                    <span class="invalid-feedback" id="remove-password-inv">Please fill out this field</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Remove </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--END REMOVE NEWS MODAL -->
            
        </div>
    </div>

    @include('pnpadmin.includes.notifications')
    @include('pnpadmin.includes.footer')

</div>

@include('pnpadmin.includes.script')

<!-- ===================================== DATATABLE ===================================== -->
<script src={{ asset("assets/plugins/data-tables/jquery.datatables.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.bootstrap4.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.responsive.min.js") }}></script>

<script>
  $(document).ready(function() {
    $('#responsive-data-table').DataTable({
        "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
        processing: true,
        serverSide: true,
        order: [[2, 'desc']],
        ajax: {
            url: "{{ url('admin/post/news') }}",
        },
        columns: [
            { data: 'title' },
            { data: 'author' },
            { data: 'created_at', render: function(data, type, full,meta) {
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

<!-- ================================ AJAX ============================== -->
<script>
/* ADD NEWS */
$('#add-news-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#add-news .modal-dialog').addClass('modal-dialog-centered');
    $("#add-news-loader").show();

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "{{ url('admin/post/addnews') }}",
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            $('#add-news .modal-dialog').removeClass('modal-dialog-centered');
            $("#add-news-loader").hide();
            $("#add-news-form").show();
            $('#add-news-alert-success').show().html(result.success);
            $('#file-upload-filename').html('Choose File . . .');
            $('#add-news-content').summernote('reset');
            $('#responsive-data-table').DataTable().ajax.reload();
            document.getElementById('add-news-form').reset();
            setTimeout(function() {
            $('#add-news-alert-success').fadeOut('slow');
            }, 2500);
        },
        error: function(error) {
            console.log(error);
        },
    });
});
/* END ADD NEWS */

/* VIEW NEWS */
$('#view-news').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#view-news .modal-dialog').addClass('modal-dialog-centered');
    $('#view-news-section').hide();
    $('#view-news-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/post/viewnews/') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            var date = moment(data.created_at).format('LL');
            var time = moment(data.created_at).format('LT');

            $('#view-news-title').html(data.title);
            $('#view-news-content').html(data.content);
            $('#view-news-author').html(data.author);
            $('#view-news-publishdate').html(date+" at "+time);
            $('#view-news-sourcelink').html(data.source_link).attr('href', data.source_link);
            if(data.image == "TBD") {
                $('#view-news-image').hide();
            } else {
                $('#view-news-image').show().attr('src', '{{asset("/")}}'+data.image);
            }
        },
        complete: function() {
            $('#view-news .modal-dialog').removeClass('modal-dialog-centered');
            $('#view-news-loader').hide();
            $('#view-news-section').show();
        },
        error: function(error) {
            console.log(error);
        }
    })
})
/* END VIEW NEWS */

/* UPDATE NEWS */
$('#update-news').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#update-news .modal-dialog').addClass('modal-dialog-centered');
    $('#update-news-form').hide();
    $('#update-news-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('admin/post/viewnews') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#update-news-id').val(data.id);
            $('#update-news-title').val(data.title);
            $('#update-news-author').val(data.author);
            $('#update-news-content').summernote('code', data.content);
            $('#update-news-sourcelink').val(data.source_link);
        },
        complete: function() {
            $('#update-news .modal-dialog').removeClass('modal-dialog-centered');
            $('#update-news-loader').hide();
            $('#update-news-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    })
});
$('#update-news-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#update-news .modal-dialog').addClass('modal-dialog-centered');
    $("#update-news-loader").show();

    var update_news_id = $('#update-news-id').val();

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "/admin/post/updatenews/"+update_news_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            $('#update-news .modal-dialog').removeClass('modal-dialog-centered');
            $("#update-news-loader").hide();
            $("#update-news-form").show();
            $('#update-news-alert-success').show().html(result.success);
            $('#responsive-data-table').DataTable().ajax.reload();
            setTimeout(function() {
            $('.modal-body .alert-success').fadeOut('slow');
            }, 5000);
        },
        error: function(error) {
            console.log(error);
        }
    });
});
/* END UPDATE NEWS */

/* REMOVE NEWS */
$('#remove-news').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#remove-news .modal-dialog').addClass('modal-dialog-centered');
    $('#remove-news-form').hide();
    $('#remove-news-loader').show();
    $('#remove-password').val("");

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/post/viewnews/') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#remove-news-id').val(data.id);
        },
        complete: function() {
            $('#remove-news .modal-dialog').removeClass('modal-dialog-centered');
            $('#remove-news-loader').hide();
            $('#remove-news-form').show();
        },
        error: function(error) {
            console.log(error);
        }
    });
});
$('#remove-news-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#remove-news .modal-dialog').addClass('modal-dialog-centered');
    $('#remove-news-loader').show();

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

    var remove_news_id = $('.modal-body #remove-news-id').val();

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "/admin/post/removenews/"+remove_news_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if(result.error) {
                $('#remove-news .modal-dialog').removeClass('modal-dialog-centered');
                $('#remove-news-loader').hide();
                $('#remove-news-form').show();
                $('#remove-password').addClass('is-invalid');
                $('#remove-password-inv').html(result.error);
            }
            if(result.success) {
                $('#remove-news').modal('hide');
                $('#remove-password').val("");
                $('#responsive-data-table').DataTable().ajax.reload();
                toastr.error("Data transfered to TRASH", result.success);
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
});
/* END REMOVE NEWS */
</script>
<!-- ============================== END AJAX ============================== -->

<!-- =============================== SUMMERNOTE =========================== -->
<script src={{ asset("assets/plugins/summernote/summernote.min.js") }}></script>
<script>
$(document).ready(function(){
    $('.summernote').summernote({
        height: 240,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: false,                 // set focus to editable area after initializing summernote
        popover: false,
    });
});
</script>
<!-- ========================== END SUMMERNOTE ================================== -->

<!-- ======================== FILE/IMAGE PREVIEW =============================== -->
<script type="text/javascript">
    var input = document.getElementById( 'file-upload' );
    var infoArea = document.getElementById( 'file-upload-filename' );
    input.addEventListener( 'change', showFileName );
    function showFileName( event ) {
    // the change event gives us the input it occurred in 
    var input = event.srcElement;
    // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
    var fileName = input.files[0].name;
    // use fileName however fits your app best, i.e. add it into a div
    infoArea.textContent = fileName;
    }
</script>
<script type="text/javascript">
    var input2 = document.getElementById( 'update-file-upload' );
    var infoArea2 = document.getElementById( 'update-file-upload-filename' );
    input2.addEventListener( 'change', showFileName );
    function showFileName( event ) {
    // the change event gives us the input it occurred in 
    var input2 = event.srcElement;
    // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
    var fileName2 = input2.files[0].name;
    // use fileName however fits your app best, i.e. add it into a div
    infoArea2.textContent = fileName2;
    }
</script>
<!-- ============================= END FILE/IMAGE PREVIEW ======================== -->



</body>
</html>

            
@else 
    @include('PNPadmin.includes.419')
@endif