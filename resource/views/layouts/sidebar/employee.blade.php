<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li class="menu-title">Main</li>
                <li class="">
                    <a href="{{ route('employee') }}" class="waves-effect {{ request()->is('employee') || request()->is('employee/*') ? 'mm active' : '' }}">
                        <i class="ti-home"></i><span class="badge badge-primary badge-pill float-right"></span> <span> Dashboard </span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-user"></i><span>Attendance<span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                        </span></a>
                    <ul class="submenu">
                        <li>
                            <a href="/captureView" class="waves-effect {{ request()->is('check') || request()->is('check/*') ? 'mm active' : '' }}">
                                <i class="dripicons-to-do"></i> <span> Capture Attendance </span>
                            </a>
                        </li>
                        <li>
                            <a href="/captureOutsideView" class="waves-effect {{ request()->is('check') || request()->is('check/*') ? 'mm active' : '' }}">
                                <i class="dripicons-to-do"></i> <span> Capture Outside Activity </span>
                            </a>
                        </li>
                        <li>
                            <a href="/timeOffView" class="waves-effect {{ request()->is('check') || request()->is('check/*') ? 'mm active' : '' }}">
                                <i class="dripicons-to-do"></i> <span> Time Off </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="">
                    <a href="/attendance/attendanceList/{{ Auth::user()->id }}" class="waves-effect {{ request()->is('attendance') || request()->is('attendance/*') ? 'mm active' : '' }}">
                        <i class="dripicons-to-do"></i> <span> Attendance Record</span>
                    </a>
                    <a href="/timeoffListView" class="waves-effect {{ request()->is('attendance') || request()->is('attendance/*') ? 'mm active' : '' }}">
                        <i class="dripicons-to-do"></i> <span> Time Off Record</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
