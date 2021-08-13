<!--
          ====================================
          ——— LEFT SIDEBAR 
          =====================================
        -->
        <aside class="left-sidebar bg-sidebar">
          <div id="sidebar" class="sidebar">
            <!-- Aplication Brand -->
            <div class="app-brand">
              <a href="javascript:void(0);" title="Sleek Dashboard">
                <img
                  class="brand-icon"
                  src={{ asset('assets\img\pnpseal.png') }}
                  width="40"
                  height="40"
                >
                <span class="brand-name text-truncate">Station Dashboard</span>
              </a>
            </div>
            <!-- begin sidebar scrollbar -->
            <div class="sidebar-scrollbar">

              <!-- sidebar menu -->
              <ul class="nav sidebar-inner" id="sidebar-menu">
                
                  <li class="sub @if(Request::is('station/dashboard')) active @endif">
                    <a class="sidenav-item-link" href="{{ url('station/dashboard') }}">
                      <i class="mdi mdi-view-dashboard-outline"></i>
                      <span class="nav-text">Dashboard</span>
                    </a>
                  </li>

                  <li  class="sub @if(Request::is('station/incident-reports')) active @endif">
                    <a class="sidenav-item-link" href="{{ url('station/incident-reports') }}">
                      <i class="mdi mdi-alert-circle-outline text-warning"></i>
                      <span class="nav-text">Incident Reports</span>
                      <span class="badge badge-warning text-dark" id="unresponded-count-sidebar"></span>
                    </a>
                  </li>

                  <li  class="has-sub @if(Request::is('station/news'))
                                          active expand
                                      @elseif(Request::is('station/announcements'))
                                          active expand
                                      @endif">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#news-and-announcements">
                      <i class="mdi mdi-radio-tower @if(Request::is('station/news')) 
                                                        text-success
                                                    @elseif(Request::is('station/announcements'))
                                                        text-success
                                                    @endif"></i>
                      <span class="nav-text">News and<br> Announcements</span><b class="caret"></b>
                    </a>
                    <ul class="collapse @if(Request::is('station/news'))
                                            show
                                        @elseif(Request::is('station/announcements'))
                                            show
                                        @endif" id="news-and-announcements" data-parent="#sidebar-menu">
                      <div class="sub-menu">
                        <li @if(Request::is('station/news')) class="active" @endif>
                          <a class="sidenav-item-link" href="{{ url('station/news') }}">
                            <span class="nav-text">News</span>
                            
                          </a>
                        </li>
                        <li @if(Request::is('station/announcements')) class="active" @endif>
                          <a class="sidenav-item-link" href="{{ url('station/announcements') }}">
                            <span class="nav-text">Announcements</span>
                          </a>
                        </li>
                      </div>
                    </ul>
                  </li>

                  <li  class="sub @if(Request::is('station/officers')) active @endif">
                    <a class="sidenav-item-link" href="{{ url('station/officers') }}">
                      <i class="mdi mdi-account"></i>
                      <span class="nav-text">Police Officers</span>
                      <span class="badge badge-warning text-dark"></span>
                    </a>
                  </li>

                  <li  class="sub @if(Request::is('station/statistical-reports')) active @endif">
                    <a class="sidenav-item-link" href="{{ url('station/statistical-reports') }}">
                      <i class="mdi mdi-chart-areaspline"></i>
                      <span class="nav-text">Reports</span>
                    </a>
                  </li>

                  <!-- <li  class="sub @if(Request::is('station/notifications')) active @endif">
                    <a class="sidenav-item-link" href="{{ url('station/notifications') }}">
                      <i class="mdi mdi-bell" id="notification-count-sidebar-mdi"></i>
                      <span class="nav-text">Notifications</span>
                      <span class="badge badge-warning text-dark" id="notification-count-sidebar"></span>
                    </a>
                  </li> -->

                  <!-- <li  class="sub">
                    <a class="sidenav-item-link" href="javascript:(0);">
                      <i class="mdi mdi-account"></i>
                      <span class="nav-text">Stations</span>
                    </a>
                  </li> -->
                          
                            
          </div>
        </aside>
