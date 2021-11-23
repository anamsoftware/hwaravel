@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Profile") }}</title>
    <meta content="{{ hwa_page_title("Profile") }}" name="description"/>
@endsection

@section('admin_style')
    @include('admin.includes.dropify.style')
@endsection

@section('admin_content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><span><i
                                            class="bx bxs-home-circle"></i> Dashboard</span></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Account</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 text-center">
                            <img class="img-thumbnail rounded-circle avatar-xl" style="min-height: 200px; min-width: 200px;"
                                 src="{{ (isset($user['avatar']) && !empty($user['avatar'])) ? hwa_image_url("users", $user['avatar']) : "assets/images/users/user.png" }}"
                                 alt="{{ $user['full_name'] ?? "{$user['first_name']} {$user['last_name']}" }}">
                        </div>
                        <table class="table table-nowrap mb-0">
                            <tbody>
                            <tr>
                                <th scope="row">Full name:</th>
                                <td>{{ $user['full_name'] ?? "{$user['first_name']} {$user['last_name']}" }}</td>
                            </tr>
                            @if(isset($user['phone']) && !empty($user['phone']))
                                <tr>
                                    <th scope="row">Phone:</th>
                                    <td>{{ $user['phone'] }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th scope="row">E-mail:</th>
                                <td>{{ $user['email'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Status:</th>
                                <td>
                                    @if($user['active'] == 1)
                                        <span class='badge badge-pill badge-soft-success font-size-11' style='line-height: unset!important;'>Activated</span>
                                    @else
                                        <span class='badge badge-pill badge-soft-danger font-size-11' style='line-height: unset!important;'>Deactivated</span>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End information -->

            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center text-uppercase mb-3">Account Information</h4>
                        <form class="form-horizontal" action="{{ route("{$path}.index") }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="first_name">Firstname: <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                       name="first_name" id="first_name" placeholder="Enter firstname"
                                       value="{{ old('first_name') ?? (isset($user['first_name']) ? $user['first_name'] : '') }}">
                                @if($errors->has('first_name'))
                                    <p class="text-danger mt-2">{{ $errors->first('first_name') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="last_name">Lastname: <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                                       name="last_name" id="last_name" placeholder="Enter lastname"
                                       value="{{ old('last_name') ?? (isset($user['last_name']) ? $user['last_name'] : '') }}">
                                @if($errors->has('last_name'))
                                    <p class="text-danger mt-2">{{ $errors->first('last_name') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="username">Username: <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                       name="username" id="username" placeholder="Enter username"
                                       value="{{ old('username') ?? (isset($user['username']) ? $user['username'] : '') }}">
                                @if($errors->has('username'))
                                    <p class="text-danger mt-2">{{ $errors->first('username') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="email">Email: <span class="text-danger">*</span></label>
                                <input type="email"
                                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                       name="email" id="email" placeholder="Enter email"
                                       value="{{ old('email') ?? (isset($user['email']) ? $user['email'] : '') }}">
                                @if($errors->has('email'))
                                    <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="phone">Phone:</label>
                                <input type="number"
                                       class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                       name="phone" id="phone" placeholder="Enter phone"
                                       value="{{ old('phone') ?? (isset($user['phone']) ? $user['phone'] : '') }}">
                                @if($errors->has('phone'))
                                    <p class="text-danger mt-2">{{ $errors->first('phone') }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="avatar">Avatar:</label>
                                <input type="file" class="dropify"
                                       name="avatar" {{ (isset($user['avatar']) && !empty($user['avatar'])) ? 'data-default-file=' . hwa_image_url("users", $user['avatar']) : "" }}>
                                @if($errors->has('avatar'))
                                    <p class="text-danger mt-2">{{ $errors->first('avatar') }}</p>
                                @endif
                            </div>

                        @include('admin.includes.form_button')
                        <!-- End button -->
                        </form>
                    </div>
                </div>
            </div>
            <!-- End update information -->

            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center text-uppercase mb-3">Change password</h4>
                        <form class="form-horizontal" action="{{ route("{$path}.password") }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="old_password">Old password: <span class="text-danger">*</span></label>
                                <div class="input-group auth-pass-inputgroup">
                                    <input type="password"
                                           class="form-control {{ $errors->first('old_password') ? 'is-invalid' : '' }}"
                                           name="old_password"
                                           value="{{ old('old_password') }}"
                                           placeholder="Enter old password" aria-label="Password"
                                           aria-describedby="password-addon">
                                    <button class="btn btn-light " type="button" id="password-old-addon"><i
                                            class="mdi mdi-eye-outline"></i></button>
                                </div>
                                @error('old_password')
                                <p class="text-danger">{{ $errors->first('old_password') }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password">New password: <span class="text-danger">*</span></label>
                                <div class="input-group auth-pass-inputgroup">
                                    <input type="password"
                                           class="form-control {{ $errors->first('password') ? 'is-invalid' : '' }}"
                                           name="password"
                                           value="{{ old('password') }}"
                                           placeholder="Enter new password" aria-label="Password"
                                           aria-describedby="password-addon">
                                    <button class="btn btn-light " type="button" id="password-addon"><i
                                            class="mdi mdi-eye-outline"></i></button>
                                </div>
                                @error('password')
                                <p class="text-danger">{{ $errors->first('password') }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation">Confirm password: <span class="text-danger">*</span></label>
                                <div class="input-group auth-pass-inputgroup">
                                    <input type="password"
                                           class="form-control {{ $errors->first('password_confirmation') ? 'is-invalid' : '' }}"
                                           name="password_confirmation"
                                           value="{{ old('password_confirmation') }}"
                                           placeholder="Confirm new password" aria-label="Password"
                                           aria-describedby="password-addon">
                                    <button class="btn btn-light " type="button" id="password-confirmation-addon"><i
                                            class="mdi mdi-eye-outline"></i></button>
                                </div>
                                @error('password_confirmation')
                                <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                                @enderror
                            </div>

                            @include('admin.includes.form_button')
                            <!-- End button -->
                        </form>
                    </div>
                </div>
            </div>
            <!-- End update password -->
        </div>


    </div>
@endsection

@section('admin_script')
    @include('admin.includes.dropify.script')

    <script type="text/javascript">
        $("#password-confirmation-addon").on("click", function () {
            0 < $(this).siblings("input").length && ("password" == $(this).siblings("input").attr("type") ? $(this).siblings("input").attr("type", "input") : $(this).siblings("input").attr("type", "password"))
        });

        $("#password-old-addon").on("click", function () {
            0 < $(this).siblings("input").length && ("password" == $(this).siblings("input").attr("type") ? $(this).siblings("input").attr("type", "input") : $(this).siblings("input").attr("type", "password"))
        });
    </script>
@endsection
