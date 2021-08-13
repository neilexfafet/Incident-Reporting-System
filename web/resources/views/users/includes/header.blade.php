<!-- Header -->
<header class="main-header " id="header">
            <nav class="navbar navbar-static-top navbar-expand-lg">
              <!-- Sidebar toggle button -->
              <button id="sidebar-toggler" class="sidebar-toggle">
                <span class="sr-only">Toggle navigation</span>
              </button>
              
              <div class="search-form d-none d-lg-inline-block">
              <!--TIMESTAMP HERE-->
              <span class="text-dark">Philippine Standard Time</span><br>
              <span class="text-dark" id="date"></span>
              </div>

              <div class="navbar-right">
                <ul class="nav navbar-nav">
                  <li class="dropdown notifications-menu">
                    <button class="dropdown-toggle" data-toggle="dropdown" id="notifications-dropdown">
                      <span class="mdi mdi-bell mdi-24px mdi-rotate-315" id="notification-mdi" style="opacity: 70%;"></span>
                        <span class="d-none d-lg-inline-block pl-2">Notifications</span>
                        <span id="notification-count-badge" class="badge badge-pill badge-primary" style="display:none;">
                      </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                      <li class="dropdown-header">Notification(s)</li>
                      <li id="notifications-view"><li>
                      <li class="dropdown-footer">
                        <a class="right-sidebar-in right-sidebar-2-menu"> View All </a>
                      </li>
                    </ul>
                  </li>
                  <!-- User Account -->
                  <li class="dropdown user-menu">
                    <button href="javascript:void(0);" class="dropdown-toggle nav-link" data-toggle="dropdown">
                      <img @if(Auth::guard('user')->user()->image == "TBD") 
                                src={{ asset("uploads/user.jpg") }} 
                              @else 
                                src={{ asset(Auth::guard('user')->user()->image) }} 
                              @endif class="user-image" alt="User Image" />
                      <span class="d-none d-lg-inline-block">{{ Auth::guard('user')->user()->first_name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                      <!-- User image -->
                      <li class="dropdown-header">
                        <img @if(Auth::guard('user')->user()->image == "TBD") 
                                src={{ asset("uploads/user.jpg") }} 
                              @else 
                                src={{ asset(Auth::guard('user')->user()->image) }} 
                              @endif  class="img-circle" alt="User Image" />
                        <div class="d-inline-block">
                        {{ Auth::guard('user')->user()->first_name }} {{ Auth::guard('user')->user()->last_name }} <small class="pt-1">{{ Auth::guard('user')->user()->email }}</small>
                        </div>
                      </li>
                      
                      <li>
                        <a href="{{url('user/profile')}}">
                          <i class="mdi mdi-settings text-dark"></i> Account Settings
                        </a>
                      </li>
                      <li class="dropdown-footer">
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#logout"> <i class="mdi mdi-logout text-dark"></i> Logout </a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>
            </nav>
            
</header>

      <!-- LOGOUT MODAL -->
      <div class="modal fade" id="logout" tabindex="-1" role="dialog" aria-labelledby="exampleLogout" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-dark" id="formlogout">Logout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{ url('user/logout') }}" method="POST">
                  @csrf
                  <span class="text-dark font-size-15">Are you sure you want to Logout?</span>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="ladda-button btn btn-primary" data-style="expand-right">
                <span class="ladda-label">Logout</span>
                <span class="ladda-spinner"></span>
              </div>
              </form>
            </div>
          </div>
        </div>
      <!-- END LOGOUT MODAL -->

