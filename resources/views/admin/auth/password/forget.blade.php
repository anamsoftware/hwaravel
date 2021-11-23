@extends('admin.layouts.auth')
@section('admin_head')
    <title>{{ hwa_page_title('Recover password') }}</title>
    <meta content="{{ hwa_page_title('Recover password') }}" name="description"/>
@endsection

@section('admin_auth_content')
    <div class="my-auto">

        <div>
            <h5 class="text-primary">Reset Password</h5>
            <p class="text-muted">Re-Password with {{ hwa_app_name() }}.</p>
        </div>

        <div class="mt-4">
            <div class="alert alert-success text-center mb-4" role="alert">
                Enter Email and instructions will be sent to you!
            </div>
            <form action="{{ route("{$path}.password.forget") }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email: <span class="text-danger">*</span></label>
                    <input type="email" class="form-control {{ $errors->first('email') ? 'is-invalid' : '' }}"
                           id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="Enter email">
                    @error('email')
                    <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                    @enderror
                </div>

                <div class="mt-3 d-grid">
                    <button type="submit"
                            class="btn btn-primary waves-effect"><i
                            class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i> Reset
                    </button>
                </div>
            </form>
            <div class="mt-5 text-center">
                <p>Remember it? <a href="{{ route("{$path}.login") }}" class="fw-medium text-primary">Sign In here</a>
                </p>
            </div>
        </div>
    </div>
@endsection

