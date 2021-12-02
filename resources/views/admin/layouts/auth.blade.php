@extends('admin.layouts.index')
@section('admin_main')
    <body class="auth-body-bg">

    <div>
        <div class="container-fluid p-0">
            <div class="row g-0">

                <div class="col-xl-9">
                    <div class="auth-full-bg pt-lg-5 p-4" style="background: url({{ !empty(hwa_setting('auth_bg')) ? hwa_image_url('system', hwa_setting('auth_bg')) : 'assets/images/bg-auth.png' }}) no-repeat center; background-size: cover; height: 100%;"></div>
                </div>
                <!-- end col -->
                <div class="col-xl-3">
                    <div class="auth-full-page-content p-md-5 p-4">
                        <div class="w-100">

                            <div class="d-flex flex-column h-100">
                                <div class="mb-4 mb-md-5">
                                    <a href="{{ route('admin.home') }}" class="d-block auth-logo">
                                        <img src="assets/images/logo-light.png" alt="" height="25" class="auth-logo-dark">
                                    </a>
                                </div>

                                @yield('admin_auth_content')

                                <div class="mt-4 mt-md-5 text-center">
                                    <p class="mb-0">Copyright © {{ date('Y') }} by <a href="{{ route('admin.home') }}"><b>{{ hwa_app_name() }}</b></a>.</p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container-fluid -->
    </div>

    @include('admin.includes.script')

    </body>
@endsection
