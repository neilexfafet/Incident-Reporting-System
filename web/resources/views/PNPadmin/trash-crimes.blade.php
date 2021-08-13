@if(Auth::guard('admin')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>PNP | Trash</title>
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
                                <h2>Removed Incident / Crimes</h2>
                            </div>
                            <div class="card-body">
                                <div class="responsive-data-table">
                                    <table id="responsive-data-table" class="table table-hover dt-responsive" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="20%">Type</th>
                                                <th width="60%">Short Description</th>
                                                <th width="20%">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- RESTORE INCIDENT MODAL -->
            <div class="modal fade" id="restore-incident" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div id="restore-incident-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
                    <form id="restore-incident-form">
                    @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalFormTitle">Are you sure you want to restore this Incident Type?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="restore-incident-id">
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
            <!--END RESTORE INCIDENT MODAL -->





            </div>

            @include('PNPadmin.includes.notifications')
            @include('pnpadmin.includes.footer')
        </div>
    </div>
</div>
@include('pnpadmin.includes.script')

<!-- ===================================DATA TABLE SCRIPT==================================== -->
<script src={{ asset("assets/plugins/data-tables/jquery.datatables.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.bootstrap4.min.js") }}></script>
<script src={{ asset("assets/plugins/data-tables/datatables.responsive.min.js") }}></script>

<script>
  $(document).ready(function() {
    var table = $('#responsive-data-table').DataTable({
      "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
      processing: true,
      serverSide: true,
      ajax: {
          url: "{{ url('admin/trash/crimes') }}",
      },
      columns: [
          { data: 'type' },
          { data: 'description' },
          { data: 'action', orderable: false, className: 'text-center' },
      ]
    });
  });
</script>
<!-- ========================================== END DATA TABLE ===================================== -->

<!-- ====================================== AJAX ================================== -->
<script>
$(document).ready(function() {
    /* restore INCIDENT */
    $('#restore-incident').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var id= button.data('id')
        var modal = $(this)

        $('#restore-incident .modal-dialog').addClass('modal-dialog-centered');
        $('#restore-incident-loader').show();
        $('#restore-incident-form').hide();
        $('#restore-password').val("").removeClass('is-invalid');

        $.ajax({
            type: "GET",
            url: "{{ url('/admin/trash/crimes/view') }}"+"/"+id,
            dataType: "JSON",
            success: function(data) {
                $('#restore-incident-id').val(data.id);
                $('#restore-incident-type').html(data.type);
            },
            complete: function() {
                $('#restore-incident .modal-dialog').removeClass('modal-dialog-centered');
                $('#restore-incident-loader').hide();
                $('#restore-incident-form').show();
            },
            error: function(error) {
                console.log(error);
            },
        });
    });
    $('#restore-incident-form').on('submit', function(event) {
        event.preventDefault();

        $(this).hide();
        $('#restore-incident .modal-dialog').addClass('modal-dialog-centered');
        $('#restore-incident-loader').show();

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
        
        var restore_incident_id= $('#restore-incident-id').val();

        $.ajax({
            type: "POST",
            url: "/admin/trash/crimes/restore/"+restore_incident_id,
            data: $('#restore-incident-form').serialize(),
            success: function(result) {
                if(result.success) {
                    $('#restore-incident').modal('hide');
                    $('#responsive-data-table').DataTable().ajax.reload();
                    $('#restore-password').val("");
                    toastr.success(result.success);
                }
                if(result.error) {
                    $('#restore-incident .modal-dialog').removeClass('modal-dialog-centered');
                    $('#restore-incident-loader').hide();
                    $('#restore-incident-form').show();
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
})
</script>
<!-- ====================================== AJAX ================================== -->

</body>
</html>

            
@else
    @include('PNPadmin.includes.419')
@endif