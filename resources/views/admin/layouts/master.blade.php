@extends('admin.layouts.index')
@section('admin_main')
    <body data-sidebar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

    @include('admin.includes.header')

    <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <div data-simplebar class="h-100">

                <!--- Side menu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">
                        @foreach(hwaCore()->getAdminMenu() as $menu)
                            <li>
                                <a href="{{ (!isset($menu['items']) && !empty($menu['route'])) ? route("admin.{$menu['route']}") : 'javascript:void(0);' }}"
                                   class="{{ isset($menu['items']) ? 'has-arrow' : '' }} waves-effect">
                                    <i class="bx {{ $menu['icon'] }}"></i>
                                    <span>{{ $menu['label'] }}</span>
                                </a>
                                @if(isset($menu['items']))
                                    <ul class="sub-menu" aria-expanded="false">
                                        @foreach($menu['items'] as $submenu)
                                            <li>
                                                <a href="{{ !empty($subMenu['route']) ? route("admin.{$subMenu['route']}") : 'javascript:void(0);' }}">{{ $submenu['label'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
            @yield('admin_content')
            <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            Copyright {{ date('Y') }} © to <a href="{{ route('admin.home') }}"><b>{{ hwa_app_name() }}</b></a>.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Loading in {{ round((microtime(true) - LARAVEL_START), 2) }}s
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    @include('admin.includes.script')
    </body>
@endsection
