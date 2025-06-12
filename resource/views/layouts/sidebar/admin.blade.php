      <!-- ========== Left Sidebar Start ========== -->
      <div class="left side-menu">
          <div class="slimscroll-menu" id="remove-scroll">

              <!--- Sidemenu -->
              <div id="sidebar-menu">
                  <!-- Left Menu Start -->
                  <ul class="metismenu" id="side-menu">
                      <li class="menu-title">Main</li>
                      <li class="">
                          <a href="{{ route('admin') }}" class="waves-effect {{ request()->is('admin') || request()->is('admin/*') ? 'mm active' : '' }}">
                              <i class="ti-home"></i><span class="badge badge-primary badge-pill float-right"></span>
                              <span> Dashboard </span>
                          </a>
                      </li>


                      <li>
                          <a href="javascript:void(0);" class="waves-effect"><i class="ti-user"></i><span> Employees
                                  <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                              </span></a>
                          <ul class="submenu">
                              <li>
                                  <a href="/employees" class="waves-effect {{ request()->is('employees') || request()->is('/employees/*') ? 'mm active' : '' }}"><i
                                          class="dripicons-view-apps"></i><span>Employees List</span></a>
                              </li>

                          </ul>
                      </li>
                      <li class="">
                          <a href="/schedule" class="waves-effect {{ request()->is('schedule') || request()->is('schedule/*') ? 'mm active' : '' }}">
                              <i class="ti-time"></i> <span> Schedule </span>
                          </a>
                      </li>
                      {{-- <li>
                          <a href="javascript:void(0);" class="waves-effect"><i class="ti-user"></i><span>Department<span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                              </span></a>
                          <ul class="submenu">
                              <li>
                                  <a href="/departments" class="waves-effect {{ request()->is('departments') || request()->is('/departments/*') ? 'mm active' : '' }}"><i
                                          class="dripicons-view-apps"></i><span>Department List</span></a>
                              </li>

                          </ul>
                      </li> --}}
                      {{-- <li>
                          <a href="javascript:void(0);" class="waves-effect"><i class="ti-user"></i><span>Role<span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                              </span></a>
                          <ul class="submenu">
                              <li>
                                  <a href="/role/roleView" class="waves-effect {{ request()->is('role') || request()->is('/role/*') ? 'mm active' : '' }}"><i
                                          class="dripicons-view-apps"></i><span>Roles
                                          List</span></a>
                              </li>

                          </ul>
                      </li> --}}
                      <li>
                          <a href="javascript:void(0);" class="waves-effect"><i class="ti-location-pin"></i><span>Location<span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                              </span></a>
                          <ul class="submenu">
                              <li>
                                  <a href="/location/locationView" class="waves-effect {{ request()->is('location') || request()->is('/location/*') ? 'mm active' : '' }}"><i
                                          class="dripicons-view-apps"></i><span>Location
                                          List</span></a>
                              </li>

                          </ul>
                      </li>

                      <li>
                          <a href="javascript:void(0);" class="waves-effect"><i class="ti-agenda"></i><span>Attendance<span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
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
                      {{-- <li class="">
                          <a href="/captureView" class="waves-effect {{ request()->is('check') || request()->is('check/*') ? 'mm active' : '' }}">
                              <i class="dripicons-to-do"></i> <span> Capture Attendance </span>
                          </a>
                      </li> --}}
                      <li>
                          <a href="javascript:void(0);" class="waves-effect"><i class="ti-gallery"></i><span> Department
                                  <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span>
                              </span></a>
                          <ul class="submenu">
                              <li>
                                  <a href="/department/departmentView" class="waves-effect {{ request()->is('department') || request()->is('/department/*') ? 'mm active' : '' }}"><i
                                          class="dripicons-view-apps"></i><span>Department List</span></a>
                              </li>

                          </ul>
                      </li>
                      <li class="">
                          <a href="/attendance" class="waves-effect {{ request()->is('attendance') || request()->is('attendance/*') ? 'mm active' : '' }}">
                              <i class="ti-calendar"></i> <span> Attendance Record </span>
                          </a>
                          <a href="/timeoffListView" class="waves-effect {{ request()->is('attendance') || request()->is('attendance/*') ? 'mm active' : '' }}">
                              <i class="dripicons-to-do"></i> <span> Time Off Record</span>
                          </a>

                          <a href="/employeHistoryView" class="waves-effect {{ request()->is('history') || request()->is('history/*') ? 'mm active' : '' }}">
                              <i class="dripicons-to-do"></i> <span> Employee History</span>
                          </a>
                      </li>

                  </ul>

              </div>
              <!-- Sidebar -->
              <div class="clearfix"></div>

          </div>
          <!-- Sidebar -left -->

      </div>
      <!-- Left Sidebar End -->
