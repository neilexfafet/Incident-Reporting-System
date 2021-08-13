
<script type="text/javascript"> 
function display_c() {
    var refresh=1000; // Refresh rate in milli seconds
    mytime=setTimeout('display_ct()',refresh)
}
function display_ct() {
    var date = moment().format('dddd, LL, LTS');
    document.getElementById('date').innerHTML = date;
    display_c();
}
</script>


<script src={{ asset("assets/plugins/jquery/jquery.min.js") }}></script>
<script src={{ asset("assets/plugins/slimscrollbar/jquery.slimscroll.min.js") }}></script>
<script src={{ asset("assets/plugins/jekyll-search.min.js") }}></script>
<script src={{ asset("assets/plugins/charts/Chart.min.js") }}></script>
<script src={{ asset("assets/plugins/ladda/spin.min.js") }}></script>
<script src={{ asset("assets/plugins/ladda/ladda.min.js") }}></script>
<script src={{ asset("assets/plugins/daterangepicker/moment.min.js") }}></script>
<script src={{ asset("assets/plugins/daterangepicker/daterangepicker.js") }}></script>
<script src={{ asset("assets/plugins/select2/js/select2.full.min.js") }}></script>
<script src={{ asset("assets/plugins/jquery-mask-input/jquery.mask.min.js") }}></script>
<script src={{ asset("assets/plugins/toastr/toastr.min.js") }}></script>
<script src={{ asset("assets/js/sleek.bundle.js") }}></script>

<!-- =================================== NOTIFICATIONS =========================================== -->
<script>
$(document).ready(function() {
    /* UNRESPONDED COUNT */
    function pendingCount() {
        $.ajax({
            type: "GET",
            url: "{{ url('station/incident-reports/unresponded/count') }}",
            dataType: "JSON",
            success: function(result) {
                if(result == 0) {
                    $('#unresponded-count-sidebar').hide();
                } else {
                    $('#unresponded-count-sidebar').show().html(result);
                }
            },
            error: function(error) {
                console.log(error);
            },
        });
    };
    pendingCount();
    setInterval(pendingCount, 5000);
    /* END UNRESPONDED COUNT */

    function notifHeader() {
        $.ajax({
            url: "{{ url('station/notifications/header') }}",
            type: "GET",
            dataType: "JSON",
            success: function(result) {
                $('#notifications-view').html(result);
            },
            error: function(error) {
                console.log(error);
            }
        }); 
    }
    notifHeader();
    setInterval(notifHeader, 5000);
    function notifCount() {
        $.ajax({
            url: "{{ url('station/notifications/count') }}",
            success: function(result) {
                if(result == 0) {
                    $('#notification-count-badge').hide();
                    $('#notification-count-dropdown').html('No');
                    $('#notification-mdi').removeClass('mdi-spin');
                } else {
                    $('#notification-count-badge').show().html(result);
                    $('#notification-count-dropdown').html(result);
                    $('#notification-mdi').addClass('mdi-spin');
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
    notifCount();
    setInterval(notifCount, 5000);

    function notifSidebar() {
        $.ajax({
            type: "GET",
            url: "{{url('station/notifications/sidebar')}}",
            dataType: "JSON",
            success: function(result) {
                if(result.view2) {
                    $('#notifications-sidebar').html(result.view2);
                } else {
                    $('#notifications-sidebar').html(result.view);
                }
            },
            error: function(error) {
                console.log(error);
            }
        })
    }
    notifSidebar();
    setInterval(notifSidebar, 5000);

    $('#notifications-dropdown').click(function() {
        $('#notification-count-badge').hide();
        notifHeader();
        $.ajax({
            url: "{{url('station/notifications/status/read')}}",
        })
    })

    /* VIEW ANNOUNCEMENT */
    $('#notification-announcement-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        
        $('#notification-announcement-modal .modal-dialog').addClass('modal-dialog-centered');
        $('#notification-announcement-form').hide();
        $('#notification-post-content').hide();
        $('#notification-post-image').hide();
        $('#notification-announcement-loader').show();

        $.ajax({
            type: "GET",
            url: "{{ url('/admin/notifications/view/announcement/') }}"+"/"+id,
            dataType: "JSON",
            success: function(data) {
                if(data.image == "TBD") {
                    $('#notification-post-content').show();
                    $('#notification-announcement-subject').val(data.subject);
                    $('#notification-announcement-message').val(data.message);
                    if(data.from_type == "App\\Admin") {
                        $('#notification-announcement-from').html(data.from.admin_name);
                    } else {
                        $('#notification-announcement-from').html(data.from.station_name);
                    }
                } else {
                    $('#notification-post-image').show();
                    $('#notification-announcement-image').attr('src', '{{asset("/")}}'+data.image);
                    if(data.from_type == "App\\Station") {
                        $('#notification-announcement-from').html(data.from.station_name);
                    } else {
                        $('#notification-announcement-from').html(data.from.admin_name);
                    }
                }
            },
            complete: function() {
                $('#notification-announcement-modal .modal-dialog').removeClass('modal-dialog-centered');
                $('#notification-announcement-loader').hide();
                $('#notification-announcement-form').show();
            },
            error: function(error) {
                console.log(error);
            }
        })
    })
    /* END VIEW ANNOUNCEMENT */
});
</script>
<!-- =============================== END NOTIFICATIONS =========================================== -->







<!--
HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
-->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->