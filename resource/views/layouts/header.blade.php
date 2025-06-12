   <!-- Top Bar Start -->
   <div class="topbar">

       <!-- LOGO -->
       <div class="topbar-left">
           <a href="/" class="logo">
               <span>
                   <h1 style="color: white; ">AMS</h1>
               </span>
               <i>
                   <h1>A</h1>
               </i>
           </a>
       </div>

       <nav class="navbar-custom">
           <ul class="navbar-right d-flex list-inline float-right mb-0">
               <li class="dropdown notification-list">
                   <div class="container">
                       <div class="dropdown notification-list nav-pro-img">
                           <a class="container dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                               aria-expanded="false">
                               <img src="/assets/images/profile1.jpg" alt="user" class="rounded-circle">
                           </a>

                           <div class="dropdown-menu dropdown-menu-right profile-dropdown" style="min-width: 20rem">
                               <!-- item-->
                               <div class="dropdown-item">
                                   Name:<div class="text-break">{{ Auth::user()->name ?? '' }}</div>
                               </div>
                               <div class="dropdown-item">
                                   Branch:<div class="text-break">{{ Auth::user()->location->location_name ?? '' }}</div>
                               </div>

                               <div class="dropdown-divider d-md-none"></div>

                               <a class="dropdown-item text-danger d-md-none" href="/changePasswordView">
                                   <i class="mdi mdi-lock text-danger"></i> {{ __('Change Password') }}
                               </a>
                               <a class="dropdown-item text-danger d-md-none" href="/logout">
                                   <i class="mdi mdi-power text-danger"></i> {{ __('Logout') }}
                               </a>

                               <div class="dropdown-divider"></div>

                               <a class="dropdown-item text-danger d-none d-md-block" href="/changePasswordView">
                                   <i class="mdi mdi-lock text-danger"></i> {{ __('Change Password') }}
                               </a>
                               <a class="dropdown-item text-danger d-none d-md-block" href="/logout">
                                   <i class="mdi mdi-power text-danger"></i> {{ __('Logout') }}
                               </a>
                           </div>
                       </div>

                   </div>
               </li>

           </ul>

           <ul class="list-inline menu-left mb-0">
               <li class="float-left">
                   <button class="button-menu-mobile open-left waves-effect">
                       <i class="mdi mdi-menu"></i>
                   </button>
               </li>
           </ul>

       </nav>

   </div>
   <!-- Top Bar End -->
