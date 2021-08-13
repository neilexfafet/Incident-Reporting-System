@if(Auth::guard('admin')->check())

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <title>PNP | Incidents</title>
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
                                <h2>Incident Types</h2>
                                <a href="javascript:void(0);" class="btn btn-outline-primary text-uppercase" data-toggle="modal" data-target="#add-incident">
                                    <i class=" mdi mdi-gavel"></i>&nbsp;&nbsp;&nbsp;Add Category
                                </a>
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
            </div>


<!-- ADD INCIDENT MODAL -->
    <div class="modal fade" id="add-incident" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div id="add-incident-loader" class="col-sm-12" style="display: none;">@include('PNPadmin.includes.loader')</div>
            <form id="add-incident-form">
            @csrf 
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalFormTitle">Add Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row px-2">
                            <label class="text-dark font-size-17">Incident Type</label>
                            <input name="type" id="add-incident-type" type="text" class="form-control text-capitalize" placeholder="Incident Type">
                            <div class="invalid-feedback" id="add-incident-type-invalid">Please fill out this field.</div>
                        </div>
                        <div class="form-row px-2 pt-4">
                            <label class="text-dark font-size-17">Short Description</label>
                            <input name="description" type="text" class="form-control" placeholder="Description">
                            <div class="invalid-feedback">Please fill out this field.</div>
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
<!--END ADD INCIDENT MODAL -->

<!-- UPDATE INCIDENT MODAL -->
<div class="modal fade" id="update-incident" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div id="update-incident-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
        <form id="update-incident-form">
        @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalFormTitle">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="update-incident-id">
                    <div id="update-incident-alert-success" class="alert alert-success" style="display:none" role="alert"></div>
                    <div class="form-row px-2">
                        <label class="text-dark font-size-17">Incident Type</label>
                        <input name="type" id="update-incident-type" type="text" class="form-control text-capitalize" placeholder="Incident Type">
                        <div class="invalid-feedback" id="update-incident-type-invalid">Please fill out this field.</div>
                    </div>
                    <div class="form-row px-2 pt-4">
                        <label class="text-dark font-size-17">Short Description</label>
                        <input name="description" id="update-incident-description" type="text" class="form-control" placeholder="Description">
                        <div class="invalid-feedback">Please fill out this field.</div>
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
<!--END UPDATE INCIDENT MODAL -->

<!-- REMOVE INCIDENT MODAL -->
<div class="modal fade" id="remove-incident" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div id="remove-incident-loader" class="col-sm-12">@include('PNPadmin.includes.loader')</div>
        <form id="remove-incident-form">
        @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalFormTitle">Are you sure you want to remove this Incident Type?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="remove-incident-id">
                    <div class="form-row px-2 py-2">
                        <label class="text-dark font-size-16">Input Password to continue</label>
                        <input name="password" id="remove-password" type="password" class="form-control" placeholder="Password" required oninvalid="$(this).addClass('is-invalid')" oninput="$(this).removeClass('is-invalid')">
                        <span class="invalid-feedback" id="remove-password-inv">Please fill out this field</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning">Remove</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!--END REMOVE INCIDENT MODAL -->


        </div>
        
        @include('pnpadmin.includes.notifications')
            @include('pnpadmin.includes.footer')
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
          url: "{{ url('admin/incidents') }}",
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

<!-- =============================================== AJAX =================================== -->
<script>
/* ADD CATEGORY */
$('#add-incident-form').on('submit', function (event) {
    event.preventDefault();

    $(this).hide();
    $('#add-incident .modal-dialog').addClass('modal-dialog-centered');
    $("#add-incident-loader").show();

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
      url: "/admin/incidents/add",
      data: $('#add-incident-form').serialize(),
      success: function(result) {
        if(result.error) {
            $('#add-incident .modal-dialog').removeClass('modal-dialog-centered');
            $("#add-incident-loader").hide();
            $("#add-incident-form").show();
            $('#add-incident-type').addClass('is-invalid');
            $('#add-incident-type-invalid').html(result.error);
        } else {
            $('#add-incident').modal('hide');
            $('#add-incident .modal-dialog').removeClass('modal-dialog-centered');
            $("#add-incident-loader").hide();
            $("#add-incident-form").show();
            $('#responsive-data-table').DataTable().ajax.reload();
            document.getElementById('add-incident-form').reset();
            toastr.success(result.success);
        }
      },
      error: function(error) {
        console.log(error);
      },
    });
});
/* EDIT CATEGORY */
$('#update-incident').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id= button.data('id')
    var modal = $(this)

    $('#update-incident .modal-dialog').addClass('modal-dialog-centered');
    $('#update-incident-loader').show();
    $('#update-incident-form').hide();

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/incidents/view') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#update-incident-id').val(data.id);
            $('#update-incident-type').val(data.type);
            $('#update-incident-description').val(data.description);
        },
        complete: function() {
            $('#update-incident .modal-dialog').removeClass('modal-dialog-centered');
            $('#update-incident-loader').hide();
            $('#update-incident-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
});
$('#update-incident-form').on('submit', function(event) {
    event.preventDefault();

    $(this).hide();
    $('#update-incident .modal-dialog').addClass('modal-dialog-centered');
    $('#update-incident-loader').show();
    
    var update_incident_id= $('.modal-body #update-incident-id').val();

    $.ajax({
        type: "POST",
        url: "/admin/incidents/update/"+update_incident_id,
        data: $('#update-incident-form').serialize(),
        success: function(result) {
            if(result.error) {
                $('#update-incident .modal-dialog').removeClass('modal-dialog-centered');
                $('#update-incident-loader').hide();
                $('#update-incident-form').show();
                $('#update-incident-type').addClass('is-invalid');
                $('#update-incident-type-invalid').html(result.error);
            } else {
                $('#update-incident .modal-dialog').removeClass('modal-dialog-centered');
                $('#update-incident-loader').hide();
                $('#update-incident-form').show();
                $('#update-incident-alert-success').show().html(result.success);
                $('#responsive-data-table').DataTable().ajax.reload();
                setTimeout(function() {
                $('#update-incident-alert-success').fadeOut('slow');
                }, 2500);
            }
        },
        error: function(error) {
            alert('ERROR! CONTACT DEVELOPER!')
        },
    });
});
/* REMOVE INCIDENT */
$('#remove-incident').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id= button.data('id')
    var modal = $(this)

    $('#remove-incident .modal-dialog').addClass('modal-dialog-centered');
    $('#remove-incident-loader').show();
    $('#remove-incident-form').hide();
    $('#remove-password').val("");

    $.ajax({
        type: "GET",
        url: "{{ url('/admin/incidents/view') }}"+"/"+id,
        dataType: "JSON",
        success: function(data) {
            $('#remove-incident-id').val(data.id);
        },
        complete: function() {
            $('#remove-incident .modal-dialog').removeClass('modal-dialog-centered');
            $('#remove-incident-loader').hide();
            $('#remove-incident-form').show();
        },
        error: function(error) {
            console.log(error);
        },
    });
});
$('#remove-incident-form').on('submit', function(event) {
    event.preventDefault();

    $(this).hide();
    $('#remove-incident .modal-dialog').addClass('modal-dialog-centered');
    $('#remove-incident-loader').show();

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
    
    var remove_incident_id= $('#remove-incident-id').val();

    $.ajax({
        type: "POST",
        url: "/admin/incidents/remove/"+remove_incident_id,
        data: $('#remove-incident-form').serialize(),
        success: function(result) {
            if(result.success) {
                $('#remove-incident').modal('hide');
                $('#responsive-data-table').DataTable().ajax.reload();
                $('#remove-password').val("");
                toastr.error(result.success);
            }
            if(result.error) {
                $('#remove-incident .modal-dialog').removeClass('modal-dialog-centered');
                $('#remove-incident-loader').hide();
                $('#remove-incident-form').show();
                $('#remove-password').addClass('is-invalid');
                $('#remove-password-inv').html(result.error);
            }
        },
        error: function(error) {
            console.log(error)
        },
    });
});
</script>
<!-- ============================================= END AJAX ==================================== -->

<!-- =================================== First Letter Uppercase SCRIPT ================================= -->
<script>
$('#add-incident-type, #update-incident-type').keyup(function(evt){
    var txt = $(this).val();
    $(this).val(txt.replace(/^(.)|\s(.)/g, function($1){ return $1.toUpperCase( ); }));
});
</script>
<!-- =================================== END First Letter Uppercase SCRIPT ================================= -->

<!-- ================================ TEXTFIELDS ARE REQUIRED ============================ -->
<script>
$(document).ready(function() {
    $('input[type="text"]').attr("required", true).on("invalid", function() {
        $(this).addClass('is-invalid');
    }).on("input", function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
<!-- =============================== END TEXTFIELDS ARE REQUIRED ========================= -->

</body>
</html>

            
@else
    @include('PNPadmin.includes.419')
@endif