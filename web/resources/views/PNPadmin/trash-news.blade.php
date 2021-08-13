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
                                <h2>Deleted News</h2>
                            </div>
                            <div class="card-body">
                                <div class="responsive-data-table">
                                    <table id="responsive-data-table" class="table table-hover dt-responsive" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="25%">Title</th>
                                                <th width="25%">Author</th>
                                                <th width="25%">Published Date</th>
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

            <!-- VIEW NEWS MODAL -->
            <div class="modal fade" id="view-news" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div id="view-news-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                    <div id="view-news-form" class="modal-content">
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
                                <span class="text-dark font-weight-bold">Source:</span> <a id="view-news-sourcelink"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--END VIEW NEWS MODAL -->

            <!-- RESTORE NEWS MODAL -->
            <div class="modal fade" id="restore-news" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div id="restore-news-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                    <form id="restore-news-form" style="display: none;">
                    @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Are you sure you want to restore this Post?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="restore-news-id">
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
            <!--END RESTORE NEWS MODAL -->



            @include('pnpadmin.includes.notifications')
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
            url: "{{ url('admin/trash/news') }}",
        },
        columns: [
            { data: 'title' },
            { data: 'author' },
            { data: 'created_at', render: function(data, full, type, meta) {
                var created_at_date = moment(data).format('LL');
                var created_at_time = moment(data).format('LT');
                return created_at_date+' at '+created_at_time;
            } },
            { data: 'action', orderable: false, className: 'text-center' },
        ]
    });
});
</script>
<!-- =================================== END DATA TABLE SCRIPT ====================================== -->

<!-- ========================================= AJAX ============================================== -->
<script>
/* VIEW NEWS */
$('#view-news').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#view-news .modal-dialog').addClass('modal-dialog-centered');
    $('#view-news-form').hide();
    $('#view-news-loader').show();

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/trash/news/view/') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            var date = moment(data.created_at).format('LL');
            var time = moment(data.created_at).format('LT');

            modal.find('.modal-title #view-news-title').html(data.title);
            modal.find('.modal-body #view-news-content').html(data.content);
            modal.find('.modal-footer #view-news-author').html(data.author);
            modal.find('.modal-footer #view-news-publishdate').html(date+" at "+time);
            modal.find('.modal-footer #view-news-sourcelink').html(data.source_link).attr('href', data.source_link);
            if(data.image == "TBD") {
                modal.find('.modal-body #view-news-image').hide();
            } else {
                modal.find('.modal-body #view-news-image').show().attr('src', '{{asset("/")}}'+data.image);
            }
        },
        complete: function() {
            $('#view-news .modal-dialog').removeClass('modal-dialog-centered');
            $('#view-news-loader').hide();
            $('#view-news-form').show();
        },
        error: function(error) {
            console.log(error);
        }
    });
});
/* END VIEW NEWS */

/* RESTORE NEWS */
$('#restore-news').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var modal = $(this);

    $('#restore-news .modal-dialog').addClass('modal-dialog-centered');
    $('#restore-news-form').hide();
    $('#restore-news-loader').show();
    $('#restore-password').val("").removeClass('is-invalid');

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/trash/news/view/') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#restore-news-id').val(data.id);
        },
        complete: function() {
            $('#restore-news .modal-dialog').removeClass('modal-dialog-centered');
            $('#restore-news-loader').hide();
            $('#restore-news-form').show();
        },
        error: function(error) {
            console.log(error);
        }
    });
});
$('#restore-news-form').on('submit', function (event) {
    event.preventDefault();
    
    $('#restore-news .modal-dialog').addClass('modal-dialog-centered');
    $('#restore-news-form').hide();
    $('#restore-news-loader').show();

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

    var restore_news_id = $('#restore-news-id').val();

    $.ajax({
        type: "POST",
        enctype: "multipart/form-data",
        url: "/admin/trash/news/restore/"+restore_news_id,
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        success: function(result) {
            if(result.success) {
                $('#restore-news').modal('hide');
                $('#responsive-data-table').DataTable().ajax.reload();
                $('#restore-password').val("");
                toastr.success(result.success);
            }
            if(result.error) {
                $('#restore-news .modal-dialog').removeClass('modal-dialog-centered');
                $('#restore-news-loader').hide();
                $('#restore-news-form').show();
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
/* END RESTORE NEWS */
</script>
<!-- ======================================== END AJAX =========================================== -->


</body>
</html>

            
@else 
    @include('PNPadmin.includes.419')
@endif