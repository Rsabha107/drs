@extends('drs.layout.template')
@section('main')
<div class="container">
    <form method="POST" action="{{ route('login') }}" class="forms-sample" id="spinner-form">
        @csrf
        <div class="row flex-center min-vh-100 py-5">
            <div class="col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-3">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-sm-5">
                        @if(session('message'))
                        <div class="alert alert-{{ session('alert-type') == 'error' ? 'danger' : session('alert-type') }} alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                        @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                        @endif
                        @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="text-center mb-7">
                            <h3 class="text-body-highlight">{{ config('settings.website_name') }}</h3>
                            <p class="text-body-tertiary">Please sign in to your account.</p>
                        </div>
                        <a href="{{ route('auth.microsoft') }}"
                            class="btn btn-phoenix-secondary w-100 mb-3 d-flex align-items-center justify-content-center gap-2"
                            id="ms-login-link">
                            <span id="ms-spinner" class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true" style="display:none;"></span>
                            <img src="{{ asset('assets/img/sc_logo_a.jpeg') }}" alt="Microsoft" width="20"
                                height="20">
                            <span>Sign in with your SC/LOC account</span>
                        </a>
                        {{-- <a href="{{ route('auth.microsoft') }}" id="ms-login-link">
                        <button type="button" class="btn btn-phoenix-secondary w-100 mb-3" id="ms-login-btn">
                            <span id="ms-spinner" class="spinner-border spinner-border-sm me-2" role="status"
                                aria-hidden="true" style="display:none;"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" class="me-2"
                                viewBox="0 0 21 21">
                                <rect x="1" y="1" width="9" height="9" fill="#f25022"></rect>
                                <rect x="1" y="11" width="9" height="9" fill="#00a4ef"></rect>
                                <rect x="11" y="1" width="9" height="9" fill="#7fba00"></rect>
                                <rect x="11" y="11" width="9" height="9" fill="#ffb900"></rect>
                            </svg>
                            Sign in with your SC/LOC account
                        </button>
                        </a> --}}
                        <div class="position-relative">
                            <hr class="bg-body-secondary mt-5 mb-4">
                            <div class="divider-content-center">or use email</div>
                        </div>
                        <div class="mb-3 text-start">
                            <label class="form-label" for="email">Email</label>
                            <div class="form-icon-container">
                                <input class="form-control form-icon-input" id="email" name="login" id="login"
                                    type="login" placeholder="name@example.com" /><span
                                    class="fas fa-user text-body fs-9 form-icon"></span>
                            </div>
                        </div>
                        <div class="mb-2 text-start">
                            <label class="form-label" for="password">Password</label>
                            <div class="form-icon-container" data-password="data-password">
                                <input class="form-control form-icon-input pe-6" name="password" id="password"
                                    type="password" placeholder="Password"
                                    data-password-input="data-password-input" /><span
                                    class="fas fa-key text-body fs-9 form-icon"></span>
                                <div class="btn px-3 py-0 h-100 position-absolute top-0 end-0 fs-7 text-body-tertiary mt-1"
                                    data-password-toggle="data-password-toggle"><span
                                        class="uil uil-eye show"></span><span class="uil uil-eye-slash hide"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row flex-between-center">
                            <div class="col-auto">

                            </div>
                            <div class="col-auto mb-5"><a class="fs-9 fw-semibold"
                                    href="{{ route('auth.forgot') }}">Forgot Password?</a></div>
                        </div>
                        {{-- <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <br />
                            <a href="{{ route('auth.google') }}">
                        <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png">
                        </a>
                    </div>
                </div> --}}
                {{-- <div class="row mb-0">
                                <div class="col-md-8 offset-md-4 mb-3">
                                    <br />
                                    <a href="{{ route('auth.microsoft') }}">
                <img src="{{ asset('assets/img/ms-symbollockup_signin_light.png') }}"
                    style="width: 200px;">
                </a>
            </div>
        </div> --}}
        <button class="btn btn-primary w-100 mb-3">Sign In</button>
        {{-- <div class="text-center"><a class="fs-9 fw-bold" href="{{ route('auth.signup') }}">Create an account</a>
</div> --}}
</div>
</div>
</div>
</div>
</form>
</div>
@endsection
@push('script')
@endpush