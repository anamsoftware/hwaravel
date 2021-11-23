@extends('admin.layouts.auth')

@section('admin_head')
    <title>{{ hwa_page_title('Login') }}</title>
    <meta content="{{ hwa_page_title('Login') }}" name="description" />
@endsection

@section('admin_auth_content')
    <div class="my-auto">

        <div>
            <h5 class="text-primary">Welcome Back !</h5>
            <p class="text-muted">Sign in to continue to {{ hwa_app_name() }}.</p>
        </div>

        <div class="mt-4">
            <form action="{{ route("{$path}.login") }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Account: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control {{ $errors->first('email') ? 'is-invalid' : '' }}"
                           id="username" name="email"
                           value="{{ old('email') ?? (hwa_local_env() ? 'admin' : '') }}"
                           placeholder="Enter email/username">
                    @error('email')
                    <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="float-end">
                        <a href="{{ route("{$path}.password.forget") }}" class="text-muted">Forget password?</a>
                    </div>
                    <label class="form-label">Password: <span class="text-danger">*</span></label>
                    <div class="input-group auth-pass-inputgroup">
                        <input type="password" class="form-control {{ $errors->first('password') ? 'is-invalid' : '' }}"
                               name="password"
                               value="{{ old('password') ?? (hwa_local_env() ? 'admin123' : '') }}"
                               placeholder="Enter password" aria-label="Password" aria-describedby="password-addon">
                        <button class="btn btn-light " type="button" id="password-addon"><i
                                class="mdi mdi-eye-outline"></i></button>
                    </div>
                    @error('password')
                    <p class="text-danger mt-2">{{ $errors->first('password') }}</p>
                    @enderror
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-check"
                           name="remember_me" {{ old('remember_me') == 'on' ? 'checked' : (hwa_local_env() ? 'checked' : '') }}>
                    <label class="form-check-label" for="remember-check">
                        Remember me
                    </label>
                </div>

                <div class="mt-3 d-grid">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">Login</button>
                </div>
            </form>
            <div class="mt-5 text-center">
                <p>Do not have account? <a href="{{ route("{$path}.register") }}" class="fw-medium text-primary">Register now</a></p>
            </div>
        </div>
    </div>
@endsection
