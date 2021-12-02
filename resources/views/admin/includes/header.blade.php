<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('admin.home') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ !empty(hwa_setting('admin_logo_small')) ? hwa_image_url('system', hwa_setting('admin_logo_small')) : 'assets/images/logo.png'}}" alt="" height="32">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ !empty(hwa_setting('admin_logo')) ? hwa_image_url('system', hwa_setting('admin_logo')) : 'assets/images/logo-light.png'}}" alt="" height="35">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>

        <div class="d-flex">
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user"
                         src="{{ (isset($adminLogin['avatar']) && !empty($adminLogin['avatar'])) ? hwa_image_url("users", $adminLogin['avatar']) : "assets/images/users/user.png" }}"
                         alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1">{{ $adminLogin['full_name'] ?? "System Admin" }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="{{ route('admin.auth.profile.index') }}"><i
                            class="bx bx-user font-size-16 align-middle me-1"></i> <span>Profile</span></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{ route('admin.auth.logout') }}"><i
                            class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span>Logout</span></a>
                </div>
            </div>
        </div>
    </div>
</header>
