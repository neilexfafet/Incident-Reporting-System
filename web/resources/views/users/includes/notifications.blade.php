<!-- VIEW NOTIFICATION ANNOUNCEMENT MODAL -->
<div class="modal fade" id="notification-announcement-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormADD" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div id="notification-announcement-loader" class="col-sm-12" style="display: none;">@include('PNPadmin.includes.loader')</div>
        <div id="notification-announcement-form" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ANNOUNCEMENT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="notification-post-content">
                    <div class="form-row px-2 py-2">
                        <label class="text-dark font-size-16">Subject</label>
                        <input id="notification-announcement-subject" type="text" class="form-control" disabled>
                    </div>
                    <div class="form-row px-2 py-2">
                        <label class="text-dark font-size-16">Message</label>
                        <textarea id="notification-announcement-message" type="text" class="form-control" rows="5" disabled></textarea>
                    </div>
                </div>
                <div id="notification-post-image">
                    <div class="text-center">
                        <img id="notification-announcement-image" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div class="p-2">
                    <span>From:</span>&nbsp;<span class="text-dark font-weight-bold" id="notification-announcement-from"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!--END NOTIFICATION ANNOUNCEMENT MODAL -->

<div class="right-sidebar-2">
    <div class="right-sidebar-container-2" id="notification-toggle">
        <div class="slim-scroll-right-sidebar-2">
            <div class="right-sidebar-2-header">
                <h2>Notifications <i class="mdi mdi-bell"></i></h2>
                <div class="btn-close-right-sidebar-2">
                    <i class="mdi mdi-window-close"></i>
                </div>
            </div>
            <div class="right-sidebar-2-body" id="notifications-sidebar"></div>
        </div>
    </div>
</div>