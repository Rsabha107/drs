@extends('drs.layout.template')
@section('main')
        <div class="container">
            <form method="POST" action="{{ route('login') }}" class="forms-sample" id="spinner-form">
                @csrf
                <div class="row flex-center min-vh-100 py-5">
                    <div class="col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-3">
                        <div class="card shadow-sm">
                            <div class="card-body p-4 p-sm-5">
                                {{-- <div class="text-center mb-4">
                                    <img src="{{asset('assets/img/icons/logo-placeholder.jpg')}}" alt="phoenix" width="58" />
                                </div> --}}
                                <div class="text-center mb-7">
                                    <h3 class="text-body-highlight">{{ config('settings.website_name') }}</h3>
                                    <p class="text-body-tertiary">Please sign in to your account.</p>
                                </div>
                                @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                <!-- <button class="btn btn-phoenix-secondary w-100 mb-3"><span class="fab fa-google text-danger me-2 fs-9"></span>Sign in with google</button>
                            <button class="btn btn-phoenix-secondary w-100"><span class="fab fa-facebook text-primary me-2 fs-9"></span>Sign in with facebook</button>
                            <div class="position-relative">
                            <hr class="bg-body-secondary mt-5 mb-4" />
                            <div class="divider-content-center">or use email</div>
                            </div> -->
                                <div class="mb-3 text-start">
                                    <label class="form-label" for="email">Email</label>
                                    <div class="form-icon-container">
                                        <input class="form-control form-icon-input" id="email" name="login" id="login" type="login" placeholder="name@example.com" /><span class="fas fa-user text-body fs-9 form-icon"></span>
                                    </div>
                                </div>
                                <div class="mb-3 text-start">
                                    <label class="form-label" for="password">Password</label>
                                    <div class="form-icon-container" data-password="data-password">
                                        <input class="form-control form-icon-input pe-6" name="password" id="password" type="password" placeholder="Password" data-password-input="data-password-input" /><span class="fas fa-key text-body fs-9 form-icon"></span>
                                        <div class="btn px-3 py-0 h-100 position-absolute top-0 end-0 fs-7 text-body-tertiary mt-1" data-password-toggle="data-password-toggle"><span class="uil uil-eye show"></span><span class="uil uil-eye-slash hide"></span></div>
                                    </div>
                                </div>
                                <div class="row flex-between-center mb-5">
                                    <div class="col-auto">

                                    </div>
                                    <div class="col-auto"><a class="fs-9 fw-semibold" href="{{route('auth.forgot')}}">Forgot Password?</a></div>
                                </div>
                                <button class="btn btn-primary w-100 mb-3">Sign In</button>
                                {{-- <div class="text-center"><a class="fs-9 fw-bold" href="{{ route('auth.signup') }}">Create an account</a></div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endsection
@push('script')
@endpush