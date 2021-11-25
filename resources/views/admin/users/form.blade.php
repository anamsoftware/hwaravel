@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title( isset($result) ? ($result['full_name'] ?? "Edit user") : "Add new user" ) }}</title>
    <meta content="{{ hwa_page_title( isset($result) ? ($result['full_name'] ?? "Edit user") : "Add new user") }}"
          name="description"/>
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
                            <li class="breadcrumb-item active" aria-current="page">Administration</li>
                            <li class="breadcrumb-item">
                                <a href="{{ route("{$path}.index") }}">Users</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ isset($result) ? ($result['full_name'] ?? "Edit user") : "Add new user" }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <form class="form-horizontal"
              action="{{ isset($result) ? route("{$path}.update", $result['id']) : route("{$path}.store") }}"
              method="post" enctype="multipart/form-data">
            @csrf
            @if(isset($result))
                @method('PUT')
            @endif
            <div class="row">
                <div class="col-sm-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="first_name">Firstname: <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                               name="first_name" id="first_name"
                                               placeholder="Enter first name"
                                               value="{{ old('first_name') ?? (isset($result['first_name']) ? $result['first_name'] : '') }}">
                                        @if($errors->has('first_name'))
                                            <p class="text-danger mt-2">{{ $errors->first('first_name') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="last_name">Lastname: <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                                               name="last_name" id="last_name"
                                               placeholder="Enter last name"
                                               value="{{ old('last_name') ?? (isset($result['last_name']) ? $result['last_name'] : '') }}">
                                        @if($errors->has('last_name'))
                                            <p class="text-danger mt-2">{{ $errors->first('last_name') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- End name -->

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="username">Username: <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                               name="username" id="username"
                                               placeholder="Enter username"
                                               value="{{ old('username') ?? (isset($result['username']) ? $result['username'] : '') }}">
                                        @if($errors->has('username'))
                                            <p class="text-danger mt-2">{{ $errors->first('username') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="phone">Phone:</label>
                                        <input type="number"
                                               class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                               name="phone" id="phone"
                                               placeholder="Enter phone number"
                                               value="{{ old('phone') ?? (isset($result['phone']) ? $result['phone'] : '') }}">
                                        @if($errors->has('phone'))
                                            <p class="text-danger mt-2">{{ $errors->first('phone') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- End username -->

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="email">Email: <span class="text-danger">*</span></label>
                                        <input type="email"
                                               class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                               name="email" id="email"
                                               placeholder="Enter email"
                                               value="{{ old('email') ?? (isset($result['email']) ? $result['email'] : '') }}">
                                        @if($errors->has('email'))
                                            <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="password">Password: @if(!isset($result))<span
                                                class="text-danger">*</span>@endif</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password"
                                                   class="form-control {{ $errors->first('password') ? 'is-invalid' : '' }}"
                                                   name="password"
                                                   value="{{ old('password') }}"
                                                   placeholder="Enter password"
                                                   aria-label="Password" aria-describedby="password-addon">
                                            <button class="btn btn-light " type="button" id="password-addon"><i
                                                    class="mdi mdi-eye-outline"></i></button>
                                        </div>
                                        @if($errors->has('password'))
                                            <p class="text-danger mt-2">{{ $errors->first('password') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- End username -->

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="gender">Gender:</label>
                                        <select name="gender" id="gender" class="form-control">
                                            <option value="">--- Choose gender ---</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : ((isset($result['gender']) && $result['gender'] == 'male') ? 'selected' : '') }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : ((isset($result['gender']) && $result['gender'] == 'female') ? 'selected' : '') }}>Female</option>
                                        </select>
                                        @if($errors->has('gender'))
                                            <p class="text-danger mt-2">{{ $errors->first('gender') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="active">Status: <span class="text-danger">*</span></label>
                                        <select name="active" id="active" class="form-control">
                                            <option
                                                value="1" {{ old('active') == '1' ? 'selected' : ((isset($result['active']) && $result['active'] == '1') ? 'selected' : '') }}>
                                                Activated
                                            </option>
                                            <option
                                                value="0" {{ old('active') == '0' ? 'selected' : ((isset($result['active']) && $result['active'] == '0') ? 'selected' : '') }}>
                                                Deactivated
                                            </option>
                                        </select>
                                        @if($errors->has('active'))
                                            <p class="text-danger mt-2">{{ $errors->first('active') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- End username -->
                        </div>
                    </div>
                </div>
                <!-- end col -->

                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="avatar">Avatar:</label>
                                <input type="file" class="dropify"
                                       name="avatar" {{ (isset($result['avatar']) && !empty($result['avatar'])) ? 'data-default-file=' . hwa_image_url("users", $result['avatar']) : "" }}>
                                @if($errors->has('avatar'))
                                    <p class="text-danger mt-2">{{ $errors->first('avatar') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>

            <div class="row">
            @include('admin.includes.form_button')
            <!-- End button -->
            </div>
            <!-- End row -->
        </form>
    </div>
@endsection

@section('admin_script')
    @include('admin.includes.dropify.script')
@endsection
