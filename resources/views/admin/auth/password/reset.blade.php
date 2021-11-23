@extends('admin.layouts.auth')
@section('admin_head')
    <title>{{ hwa_page_title("Reset Password") }}</title>
    <meta content="{{ hwa_page_title("Reset Password") }}" name="description"/>
@endsection

@section('admin_auth_content')
    <div class="my-auto">
        <div>
            <h5 class="text-primary">Reset Password</h5>
            <p class="text-muted">Reset {{ hwa_app_name() }} password here.</p>
        </div>

        <div class="mt-4">
            <form action="{{ route("{$path}.password.reset", ['token' => $passwordReset['token']]) }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email: <span class="text-danger">*</span></label>
                    <input type="email" class="form-control {{ $errors->first('email') ? 'is-invalid' : '' }}" id="email" name="email"
                           value="{{ $passwordReset['email'] }}" disabled>
                    @error('email')
                    <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password: <span class="text-danger">*</span></label>
                    <div class="input-group auth-pass-inputgroup">
                        <input type="password" class="form-control {{ $errors->first('password') ? 'is-invalid' : '' }}" name="password"
                               value="{{ old('password') }}"
                               placeholder="Enter new password" aria-label="Password" aria-describedby="password-addon">
                        <button class="btn btn-light " type="button" id="password-addon"><i
                                class="mdi mdi-eye-outline"></i></button>
                    </div>
                    @error('password')
                    <p class="text-danger mt-2">{{ $errors->first('password') }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation">Confirm password: <span class="text-danger">*</span></label>
                    <div class="input-group auth-pass-inputgroup">
                        <input type="password" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                               value="{{ old('password_confirmation') }}" id="password_confirmation"
                               name="password_confirmation" placeholder="Confirm new password">
                        <button class="btn btn-light " type="button" id="password-confirmation-addon"><i
                                class="mdi mdi-eye-outline"></i></button>
                    </div>
                    @error('password_confirmation')
                    <p class="text-danger mt-2">{{ $errors->first('password_confirmation') }}</p>
                    @enderror
                </div>

                <div class="mt-3 d-grid">
                    <button class="btn btn-success waves-effect waves-light" type="submit">
                        <i class="bx bx-check-double font-size-16 align-middle me-2"></i> Save
                    </button>
                </div>
            </form>

            <div class="mt-5 text-center">
                <p>Remember it? <a href="{{ route("{$path}.login") }}" class="fw-medium text-primary"> Login now</a></p>
            </div>
        </div>
    </div>
@endsection

@section('admin_script')
    <script type="text/javascript">
        $("#password-confirmation-addon").on("click", function () {
            0 < $(this).siblings("input").length && ("password" == $(this).siblings("input").attr("type") ? $(this).siblings("input").attr("type", "input") : $(this).siblings("input").attr("type", "password"))
        });
    </script>
@endsection

