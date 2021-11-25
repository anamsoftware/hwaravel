@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Email") }}</title>
    <meta content="{{ hwa_page_title("Email") }}" name="description"/>
@endsection

@section('admin_style')

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
                            <li class="breadcrumb-item"><a href="{{ route("{$path}.index") }}">Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Email</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row d-flex justify-content-sm-center">
            <div class="col-sm-9">
                <form action="{{ route("{$path}.email") }}" class="form-horizontal" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-4">
                            <h4 class="title mt-3">Email settings</h4>
                            <label class="mt-3 text-muted">Email template using HTML & system variables.</label>
                        </div>
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="email_driver">Mailer:</label>
                                        <select name="email_driver" id="email_driver"
                                                class="form-control {{ $errors->has('email_driver') ? 'is-invalid' : '' }}">
                                            <option value="smtp">SMTP</option>
                                            <option value="mailgun">Mailgun</option>
                                            <option value="ses">SES</option>
                                        </select>
                                        @error('email_driver')
                                        <p class="text-danger mt-2">{{ $errors->first('email_driver') }}</p>
                                        @enderror
                                    </div>

                                    <div class="smtp" style="display: none;">
                                        <div class="mb-3">
                                            <label for="email_port">Port:</label>
                                            <input type="number"
                                                   class="form-control {{ $errors->has('email_port') ? 'is-invalid' : '' }}"
                                                   name="email_port" id="email_port" placeholder="Enter port"
                                                   value="{{ old('email_port', 587) }}">
                                            @error('email_port')
                                            <p class="text-danger mt-2">{{ $errors->first('email_port') }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email_host">Host:</label>
                                            <input type="text"
                                                   class="form-control {{ $errors->has('email_host') ? 'is-invalid' : '' }}"
                                                   name="email_host" id="email_host" placeholder="Ex: smtp.gmail.com"
                                                   value="{{ old('email_host', 'smtp.mailgun.org') }}">
                                            @error('email_host')
                                            <p class="text-danger mt-2">{{ $errors->first('email_host') }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email_username">Username:</label>
                                            <input type="text"
                                                   class="form-control {{ $errors->has('email_username') ? 'is-invalid' : '' }}"
                                                   name="email_username" id="email_username"
                                                   placeholder="Username to login to mail server"
                                                   value="{{ old('email_username') }}">
                                            @error('email_username')
                                            <p class="text-danger mt-2">{{ $errors->first('email_username') }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email_password">Password:</label>
                                            <div class="input-group auth-pass-inputgroup">
                                                <input type="password"
                                                       class="form-control {{ $errors->first('email_password') ? 'is-invalid' : '' }}"
                                                       name="email_password"
                                                       value="{{ old('email_password') }}"
                                                       placeholder="Password to login to mail server"
                                                       aria-label="Password" aria-describedby="password-addon">
                                                <button class="btn btn-light " type="button" id="password-addon"><i
                                                        class="mdi mdi-eye-outline"></i></button>
                                            </div>
                                            @error('email_password')
                                            <p class="text-danger mt-2">{{ $errors->first('email_password') }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email_encryption">Encryption:</label>
                                            <input type="text"
                                                   class="form-control {{ $errors->has('email_encryption') ? 'is-invalid' : '' }}"
                                                   name="email_encryption" id="email_encryption"
                                                   placeholder="Encryption: ssl or tls"
                                                   value="{{ old('email_encryption') }}">
                                            @error('email_encryption')
                                            <p class="text-danger mt-2">{{ $errors->first('email_encryption') }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- end smtp -->

                                    <div class="mailgun" style="display: none;">
                                        <div class="mb-3">
                                            <label for="email_mail_gun_domain">Domain:</label>
                                            <input type="text"
                                                   class="form-control {{ $errors->has('email_mail_gun_domain') ? 'is-invalid' : '' }}"
                                                   name="email_mail_gun_domain" id="email_mail_gun_domain"
                                                   placeholder="Enter domain"
                                                   value="{{ old('email_mail_gun_domain') }}">
                                            @error('email_mail_gun_domain')
                                            <p class="text-danger mt-2">{{ $errors->first('email_mail_gun_domain') }}</p>
                                            @enderror
                                        </div>

                                        @if(!hwa_demo_env())
                                            <div class="mb-3">
                                                <label for="email_mail_gun_secret">Secret:</label>
                                                <input type="text"
                                                       class="form-control {{ $errors->has('email_mail_gun_secret') ? 'is-invalid' : '' }}"
                                                       name="email_mail_gun_secret" id="email_mail_gun_secret"
                                                       placeholder="Enter secret"
                                                       value="{{ old('email_mail_gun_secret') }}">
                                                @error('email_mail_gun_secret')
                                                <p class="text-danger mt-2">{{ $errors->first('email_mail_gun_secret') }}</p>
                                                @enderror
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <label for="email_mail_gun_endpoint">Endpoint:</label>
                                            <input type="text"
                                                   class="form-control {{ $errors->has('email_mail_gun_endpoint') ? 'is-invalid' : '' }}"
                                                   name="email_mail_gun_endpoint" id="email_mail_gun_endpoint"
                                                   placeholder="Enter endpoint"
                                                   value="{{ old('email_mail_gun_endpoint', 'api.mailgun.net') }}">
                                            @error('email_mail_gun_endpoint')
                                            <p class="text-danger mt-2">{{ $errors->first('email_mail_gun_endpoint') }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- end mail gun -->

                                    <div class="ses" style="display: none;">
                                        <div class="mb-3">
                                            <label for="email_ses_key">Key:</label>
                                            <input type="text"
                                                   class="form-control {{ $errors->has('email_ses_key') ? 'is-invalid' : '' }}"
                                                   name="email_ses_key" id="email_ses_key"
                                                   placeholder="Enter key"
                                                   value="{{ old('email_ses_key') }}">
                                            @error('email_ses_key')
                                            <p class="text-danger mt-2">{{ $errors->first('email_ses_key') }}</p>
                                            @enderror
                                        </div>

                                        @if(!hwa_demo_env())
                                            <div class="mb-3">
                                                <label for="email_ses_secret">Secret:</label>
                                                <input type="text"
                                                       class="form-control {{ $errors->has('email_ses_secret') ? 'is-invalid' : '' }}"
                                                       name="email_ses_secret" id="email_ses_secret"
                                                       placeholder="Enter secret"
                                                       value="{{ old('email_ses_secret') }}">
                                                @error('email_ses_secret')
                                                <p class="text-danger mt-2">{{ $errors->first('email_ses_secret') }}</p>
                                                @enderror
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <label for="email_ses_region">Region:</label>
                                            <input type="text"
                                                   class="form-control {{ $errors->has('email_ses_region') ? 'is-invalid' : '' }}"
                                                   name="email_ses_region" id="email_ses_region"
                                                   placeholder="Enter region"
                                                   value="{{ old('email_ses_region', 'us-east-1') }}">
                                            @error('email_ses_region')
                                            <p class="text-danger mt-2">{{ $errors->first('email_ses_region') }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- end ses -->

                                    <div class="mb-3">
                                        <label for="email_from_name">Sender name:</label>
                                        <input type="text"
                                               class="form-control {{ $errors->has('email_from_name') ? 'is-invalid' : '' }}"
                                               name="email_from_name" id="email_from_name"
                                               placeholder="Enter sender name"
                                               value="{{ old('email_from_name', hwa_app_name()) }}">
                                        @error('email_from_name')
                                        <p class="text-danger mt-2">{{ $errors->first('email_from_name') }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email_from_address">Sender email:</label>
                                        <input type="email"
                                               class="form-control {{ $errors->has('email_from_address') ? 'is-invalid' : '' }}"
                                               name="email_from_address" id="email_from_address"
                                               placeholder="Enter sender email"
                                               value="{{ old('email_from_address', 'hello@example.com') }}">
                                        @error('email_from_address')
                                        <p class="text-danger mt-2">{{ $errors->first('email_from_address') }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-12">
                        @include('admin.includes.form_button')
                        <!-- End button -->
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
    <script type="text/javascript">
        $(document).ready(function () {
            let option = $("#email_driver").find("option:selected").val();
            if (option === 'smtp'){
                $(".smtp").show();
            } else if (option === 'mailgun'){
                $(".mailgun").show();
            } else if (option === 'ses'){
                $(".ses").show();
            }
        });

        $("#email_driver").change(function () {
            let option = $("#email_driver").find("option:selected").val();
            if (option === 'smtp'){
                $(".smtp").show();
                $(".mailgun").hide();
                $(".ses").hide();
            } else if (option === 'mailgun'){
                $(".smtp").hide();
                $(".mailgun").show();
                $(".ses").hide();
            } else if (option === 'ses'){
                $(".smtp").hide();
                $(".mailgun").hide();
                $(".ses").show();
            }
        });
    </script>
@endsection
