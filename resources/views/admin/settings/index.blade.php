@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Settings") }}</title>
    <meta content="{{ hwa_page_title("Settings") }}" name="description"/>
@endsection

@section('admin_style')
    @include('admin.includes.select2.style')
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
                            <li class="breadcrumb-item active" aria-current="page">Settings</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row d-flex justify-content-sm-center">
            <div class="col-sm-9">
                <form action="{{ route("{$path}.index") }}" class="form-horizontal" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-4">
                            <h4 class="title mt-3">General Information</h4>
                            <label class="mt-3 text-muted">Setting site information.</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="admin_title">Admin title:</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('admin_title') ? 'is-invalid' : '' }}"
                                               name="admin_title" id="admin_title"
                                               placeholder="Enter sender name"
                                               value="{{ old('admin_title') ?? hwa_setting('admin_title', hwa_app_name()) }}">
                                        @error('admin_title')
                                        <p class="text-danger mt-2">{{ $errors->first('admin_title') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="admin_email">Admin email:</label>
                                        <input type="email"
                                               class="form-control {{ $errors->has('admin_email') ? 'is-invalid' : '' }}"
                                               name="admin_email" id="admin_email"
                                               placeholder="Enter admin email"
                                               value="{{ old('admin_email') ?? hwa_setting('admin_email') }}">
                                        @error('admin_email')
                                        <p class="text-danger mt-2">{{ $errors->first('admin_email') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="time_zone">Timezone:</label>
                                        <select name="time_zone" id="time_zone"
                                                class="form-control select2">
                                            @foreach(hwa_timezone_list() as $timezoneKey => $timezoneValue)
                                                <option value="{{ $timezoneKey }}"
                                                        @if(hwa_setting('time_zone', config('app.time_zone')) == $timezoneKey) selected @endif>{{ $timezoneValue }}</option>
                                            @endforeach
                                        </select>
                                        @error('time_zone')
                                        <p class="text-danger mt-2">{{ $errors->first('time_zone') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <hr>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <h4 class="title mt-3">Admin appearance</h4>
                            <label class="mt-3 text-muted">Setting admin appearance such as editor, language...</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="favicon">Favicon:</label>
                                                <input type="file" class="dropify"
                                                       name="favicon" {{ hwa_setting('favicon') ? 'data-default-file=' . hwa_image_url("system", $setting['favicon']) : "" }}>
                                                @error('favicon')
                                                <p class="text-danger mt-2">{{ $errors->first('favicon') }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="admin_logo_small">Admin small logo:</label>
                                                <input type="file" class="dropify"
                                                       name="admin_logo_small" {{ hwa_setting('admin_logo_small') ? 'data-default-file=' . hwa_image_url("system", $setting['admin_logo_small']) : "" }}>
                                                @error('admin_logo_small')
                                                <p class="text-danger mt-2">{{ $errors->first('admin_logo_small') }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="admin_logo">Admin logo:</label>
                                                <input type="file" class="dropify"
                                                       name="admin_logo" {{ hwa_setting('admin_logo') ? 'data-default-file=' . hwa_image_url("system", $setting['admin_logo']) : "" }}>
                                                @error('admin_logo')
                                                <p class="text-danger mt-2">{{ $errors->first('admin_logo') }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="auth_bg">Login admin background image:</label>
                                                <input type="file" class="dropify"
                                                       name="auth_bg" {{ hwa_setting('auth_bg') ? 'data-default-file=' . hwa_image_url("system", $setting['auth_bg']) : "" }}>
                                                @error('auth_bg')
                                                <p class="text-danger mt-2">{{ $errors->first('auth_bg') }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <hr>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <h4 class="title mt-3">Captcha</h4>
                            <label class="mt-3 text-muted">Settings for Google Captcha.</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="enable_captcha">Enable Captcha?</label>
                                        <div>
                                            <div class="form-check mb-3 form-check-inline">
                                                <input class="form-check-input" type="radio" name="enable_captcha"
                                                       value="1" id="enable_captcha_1"
                                                       @if(hwa_setting('enable_captcha')) checked @endif>
                                                <label class="form-check-label" for="enable_captcha_1">Yes</label>
                                            </div>
                                            <div class="form-check mb-3 form-check-inline">
                                                <input class="form-check-input" type="radio" name="enable_captcha"
                                                       value="0" id="enable_captcha_2"
                                                       @if(!hwa_setting('enable_captcha')) checked @endif>
                                                <label class="form-check-label" for="enable_captcha_2">No</label>
                                            </div>
                                        </div>
                                        @error('enable_captcha')
                                        <p class="text-danger mt-2">{{ $errors->first('enable_captcha') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="captcha_type">Type:</label>
                                        <div>
                                            <div class="form-check mb-3 form-check-inline">
                                                <input class="form-check-input" type="radio" name="captcha_type"
                                                       value="v2" id="captcha_type_1"
                                                       @if(hwa_setting('captcha_type', 'v2') == 'v2') checked @endif>
                                                <label class="form-check-label" for="captcha_type_1">V2 (Verify requests with a challenge)</label>
                                            </div>
                                            <div class="form-check mb-3 form-check-inline">
                                                <input class="form-check-input" type="radio" name="captcha_type"
                                                       value="v3" id="captcha_type_2"
                                                       @if(hwa_setting('captcha_type', 'v2') == 'v3') checked @endif>
                                                <label class="form-check-label" for="captcha_type_2">V3 (Verify requests with a score)</label>
                                            </div>
                                        </div>
                                        @error('captcha_type')
                                        <p class="text-danger mt-2">{{ $errors->first('captcha_type') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="captcha_site_key">Captcha Site Key:</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('captcha_site_key') ? 'is-invalid' : '' }}"
                                               name="captcha_site_key" id="captcha_site_key"
                                               placeholder="Enter captcha site Key"
                                               value="{{ old('captcha_site_key') ?? hwa_setting('captcha_site_key') }}">
                                        @error('captcha_site_key')
                                        <p class="text-danger mt-2">{{ $errors->first('captcha_site_key') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="captcha_secret">Captcha Secret:</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('captcha_secret') ? 'is-invalid' : '' }}"
                                               name="captcha_secret" id="captcha_secret"
                                               placeholder="Enter captcha secret"
                                               value="{{ old('captcha_secret') ?? hwa_setting('captcha_secret') }}">
                                        @error('captcha_secret')
                                        <p class="text-danger mt-2">{{ $errors->first('captcha_secret') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <div class="mb-3 mt-3 text-center justify-content-center">
                                <button type="submit"
                                        class="btn btn-success waves-effect waves-light"><i
                                        class="bx bx-check-double font-size-16 align-middle me-2"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- End row -->
                </form>
            </div>
        </div>
        <!-- End row -->

    </div>
@endsection

@section('admin_script')
    @include('admin.includes.select2.script')
    @include('admin.includes.dropify.script')
@endsection
