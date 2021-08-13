
    <aside class="left-sidebar bg-sidebar">
        <div id="sidebar" class="sidebar">
            <div class="app-brand">
                <a title="Sleek Dashboard">
                <img class="brand-icon" src="{{ asset('assets\img\pnpseal.png') }}" width="40">
                <span class="brand-name text-truncate">PNP Dashboard</span>
                </a>
            </div>
            <div class="sidebar-scrollbar">
                <ul class="nav sidebar-inner" id="sidebar-menu">
                    <li class="sub @if(Request::is('admin/dashboard')) active @endif">
                        <a class="sidenav-item-link" href="{{ url('admin/dashboard') }}">
                            <i class="mdi mdi-view-dashboard-outline"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="has-sub @if(Request::is('admin/post/news'))
                                            active expand 
                                        @elseif(Request::is('admin/post/announcements'))
                                            active expand
                                        @endif">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#news-and-announcements">
                            <i class="mdi mdi-radio-tower @if(Request::is('admin/post/news')) 
                                                        text-success 
                                                    @elseif(Request::is('admin/post/announcements'))
                                                        text-success
                                                    @endif"></i>
                            <span class="nav-text">News and<br> Announcements</span><b class="caret"></b>
                        </a>
                        <ul class="collapse @if(Request::is('admin/post/news')) 
                                            show 
                                        @elseif(Request::is('admin/post/announcements'))
                                            show
                                        @endif" id="news-and-announcements" data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                <li @if(Request::is('admin/post/news')) class="active" @endif>
                                    <a class="sidenav-item-link" href="{{ url('admin/post/news') }}">
                                    <span class="nav-text">News</span>
                                    
                                    </a>
                                </li>
                                <li @if(Request::is('admin/post/announcements')) class="active" @endif>
                                    <a class="sidenav-item-link" href="{{ url('admin/post/announcements') }}">
                                    <span class="nav-text">Announcements</span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                    </li>

                    <li  class="sub @if(Request::is('admin/officers')) active @endif">
                        <a class="sidenav-item-link" href="{{ url('admin/officers') }}">
                            <i class="mdi mdi-account"></i>
                            <span class="nav-text">Police Officers</span>
                        </a>
                    </li>

                    <li  class="sub @if(Request::is('admin/station')) active @endif">
                        <a class="sidenav-item-link" href="{{ url('admin/station') }}">
                            <i class="mdi mdi-security"></i>
                            <span class="nav-text">Stations</span>
                        </a>
                    </li>

                    <!-- <li  class="sub @if(Request::is('admin/notifications')) active @endif">
                        <a class="sidenav-item-link" href="{{ url('admin/notifications') }}">
                            <i class="mdi mdi-bell" id="notification-count-sidebar-mdi"></i>
                            <span class="nav-text">Notifications</span>
                            <span class="badge badge-warning text-dark" id="notification-count-sidebar"></span>
                        </a>
                    </li> -->

                    <!-- <li  class="sub @if(Request::is('admin/incidents')) active @endif">
                        <a class="sidenav-item-link" href="{{ url('admin/incidents') }}">
                            <i class="mdi mdi-gavel"></i>
                            <span class="nav-text">Incidents/Crimes</span>
                            <span class="badge badge-warning text-dark"></span>
                        </a>
                    </li> -->

                    <li class="has-sub @if(Request::is('admin/incidents'))
                                        active expand 
                                    @elseif(Request::is('admin/incident-reports'))
                                        active expand
                                    @endif">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#incidents-and-crimes">
                            <i class="mdi mdi-gavel"></i>
                            <span class="nav-text">Incidents/Crimes</span><b class="caret"></b>
                        </a>
                        <ul class="collapse @if(Request::is('admin/incidents'))
                                        show 
                                    @elseif(Request::is('admin/incident-reports'))
                                        show
                                    @endif" id="incidents-and-crimes" data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                <li @if(Request::is('admin/incidents')) class="active" @endif>
                                    <a class="sidenav-item-link" href="{{ url('admin/incidents') }}">
                                    <span class="nav-text">Crimes</span>
                                    
                                    </a>
                                </li>
                                <li @if(Request::is('admin/incident-reports')) class="active" @endif>
                                    <a class="sidenav-item-link" href="{{ url('admin/incident-reports') }}">
                                    <span class="nav-text">Incident Reports</span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                    </li>

                    <li  class="sub @if(Request::is('admin/statistical-reports')) active @endif">
                        <a class="sidenav-item-link" href="{{ url('admin/statistical-reports') }}">
                            <i class="mdi mdi-chart-areaspline"></i>
                            <span class="nav-text">Reports</span>
                        </a>
                    </li>

                    <li  class="sub @if(Request::is('admin/account-logs')) active @endif">
                        <a class="sidenav-item-link" href="{{ url('admin/account-logs') }}">
                            <i class="mdi mdi-view-list"></i>
                            <span class="nav-text">Account Logs</span>
                        </a>
                    </li>

                <li  class="has-sub @if(Request::is('admin/trash/officers')) 
                                        active expand 
                                    @elseif(Request::is('admin/trash/news')) 
                                        active expand 
                                    @elseif(Request::is('admin/trash/announcements')) 
                                        active expand 
                                    @elseif(Request::is('admin/trash/crimes')) 
                                        active expand
                                    @elseif(Request::is('admin/trash/accounts')) 
                                        active expand
                                    @elseif(Request::is('admin/trash/blocked-users')) 
                                        active expand
                                    @endif">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#remove-data">
                        <i class="mdi mdi-trash-can-outline @if(Request::is('admin/trash/officers'))
                                                            text-danger 
                                                        @elseif(Request::is('admin/trash/news')) 
                                                            text-danger 
                                                        @elseif(Request::is('admin/trash/announcements')) 
                                                            text-danger 
                                                        @elseif(Request::is('admin/trash/crimes')) 
                                                            text-danger
                                                        @elseif(Request::is('admin/trash/accounts')) 
                                                            text-danger
                                                        @elseif(Request::is('admin/trash/blocked-users')) 
                                                            text-danger
                                                        @endif"></i>
                        <span class="nav-text">Archive</span><b class="caret"></b>
                    </a>
                    <ul class="collapse @if(Request::is('admin/trash/officers')) 
                                        show 
                                    @elseif(Request::is('admin/trash/news')) 
                                        show 
                                    @elseif(Request::is('admin/trash/announcements')) 
                                        show 
                                    @elseif(Request::is('admin/trash/crimes')) 
                                        show
                                    @elseif(Request::is('admin/trash/accounts')) 
                                        show
                                    @elseif(Request::is('admin/trash/blocked-users')) 
                                        show
                                    @endif" id="remove-data" data-parent="#sidebar-menu">
                        <div class="sub-menu">
                            <li @if(Request::is('admin/trash/officers')) class="active" @endif>
                                <a class="sidenav-item-link" href="{{ url('admin/trash/officers') }}">
                                <span class="nav-text">Officers</span>
                                <span class="badge badge-danger">REMOVED</span>
                                </a>
                            </li>
                            <li @if(Request::is('admin/trash/news')) class="active" @endif>
                                <a class="sidenav-item-link" href="{{ url('admin/trash/news') }}">
                                <span class="nav-text">News</span>
                                <span class="badge badge-danger">REMOVED</span>
                                </a>
                            </li>
                            <li @if(Request::is('admin/trash/announcements')) class="active" @endif>
                                <a class="sidenav-item-link" href="{{ url('admin/trash/announcements') }}">
                                <span class="nav-text">Announcements</span>
                                <span class="badge badge-danger">REMOVED</span>
                                </a>
                            </li>
                            <li @if(Request::is('admin/trash/crimes')) class="active" @endif>
                                <a class="sidenav-item-link" href="{{ url('admin/trash/crimes') }}">
                                <span class="nav-text">Crimes</span>
                                <span class="badge badge-danger">REMOVED</span>
                                </a>
                            </li>
                            <li @if(Request::is('admin/trash/accounts')) class="active" @endif>
                                <a class="sidenav-item-link" href="{{ url('admin/trash/accounts') }}">
                                <span class="nav-text">Accounts</span>
                                <span class="badge badge-danger">REMOVED</span>
                                </a>
                            </li>
                            <li @if(Request::is('admin/trash/blocked-users')) class="active" @endif>
                                <a class="sidenav-item-link" href="{{ url('admin/trash/blocked-users') }}">
                                <span class="nav-text">Blocked Users</span>
                                @if(count(App\User::all()->where('status', 'blocked')) != 0)
                                <span class="badge badge-danger">{{count(App\User::all()->where('status', 'blocked'))}}</span>
                                @endif
                                </a>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
            
        <!-- <div class="sidebar-footer">
            <hr class="separator mb-0" />
            <div class="sidebar-footer-content">
            <span>Philippine Standard Time</span><br>
            <span id="date"></span>
            </div>
        </div> -->
                    
    </aside>
