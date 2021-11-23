@extends('admin.layouts.auth')

@section('admin_head')
    <title>{{ hwa_page_title('Register') }}</title>
    <meta content="{{ hwa_page_title('Register') }}" name="description" />
@endsection

@section('admin_auth_content')
    <div class="my-auto">
        <div>
            <h5 class="text-primary">Register Free</h5>
            <p class="text-muted">Get your free {{ hwa_app_name() }} account now.</p>
        </div>

        <div class="mt-4">
            <form action="{{ route("{$path}.register") }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="first_name" class="form-label">Firstname: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control {{ $errors->first('first_name') ? 'is-invalid' : '' }}" id="first_name" name="first_name"
                           value="{{ old('first_name') }}"
                           placeholder="Enter firstname">
                    @error('first_name')
                    <p class="text-danger mt-2">{{ $errors->first('first_name') }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="last_name" class="form-label">Lastname: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control {{ $errors->first('last_name') ? 'is-invalid' : '' }}" id="last_name" name="last_name"
                           value="{{ old('last_name') }}"
                           placeholder="Enter lastname">
                    @error('last_name')
                    <p class="text-danger mt-2">{{ $errors->first('last_name') }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control {{ $errors->first('username') ? 'is-invalid' : '' }}" id="username" name="username"
                           value="{{ old('username') }}"
                           placeholder="Enter username">
                    @error('username')
                    <p class="text-danger mt-2">{{ $errors->first('username') }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email: <span class="text-danger">*</span></label>
                    <input type="email" class="form-control {{ $errors->first('email') ? 'is-invalid' : '' }}" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="Enter email">
                    @error('email')
                    <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password: <span class="text-danger">*</span></label>
                    <div class="input-group auth-pass-inputgroup">
                        <input type="password" class="form-control {{ $errors->first('email') ? 'is-invalid' : '' }}" name="password"
                               value="{{ old('password') }}"
                               placeholder="Enter password" aria-label="Password" aria-describedby="password-addon">
                        <button class="btn btn-light " type="button" id="password-addon"><i
                                class="mdi mdi-eye-outline"></i></button>
                    </div>
                    @error('password')
                    <p class="text-danger mt-2">{{ $errors->first('password') }}</p>
                    @enderror
                </div>

                <div>
                    <p class="mb-0">By registering you agree to the {{ hwa_app_name() }} Terms of Use.</p>
                </div>

                <div class="mt-3 d-grid">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">Register</button>
                </div>

            </form>
            <div class="mt-5 text-center">
                <p>Have account yet? <a href="{{ route("{$path}.login") }}" class="fw-medium text-primary">Login now</a></p>
            </div>
        </div>
    </div>
@endsection
