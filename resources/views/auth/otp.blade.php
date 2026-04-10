@extends('drs.layout.template')
@section('main')
    <div class="container">
        <form method="POST" action="{{ route('auth.otp.post') }}" class="forms-sample needs-validation" novalidate id="spinner-form">
            @csrf
            <div class="row flex-center min-vh-100 py-5">
                <div class="col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-3">
                    <div class="card shadow-sm">
                        <div class="card-body p-4 p-sm-5">
                            <div class="text-center mb-3">
                                <h3 class="text-body-highlight">VMS verification code</h3>
                                <p class="text-body-tertiary mb-0">An email containing a 6-digit verification code has been
                                    sent to your email address</p>
                            </div>
                            {{-- @if ($remaining > 0)
                                <p>You have {{ $remaining }} attempts left.</p>
                            @else
                                <p class="text-danger">You are locked out. Try again later.</p>
                            @endif --}}
                            @if (session('remaining') !== null)
                                <p>You still have {{ session('remaining') }} attempt(s) left.</p>
                            @endif
                            @if (session('message'))
                                <div class="alert alert-{{ session('alert-type') }} alert-dismissible fade show"
                                    role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                    <p class="text-white fs-9 mb-0">{{ session('message') }}</p>
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
                            <!-- <button class="btn btn-phoenix-secondary w-100 mb-3"><span class="fab fa-google text-danger me-2 fs-9"></span>Sign in with google</button>
                                        <button class="btn btn-phoenix-secondary w-100"><span class="fab fa-facebook text-primary me-2 fs-9"></span>Sign in with facebook</button>
                                        <div class="position-relative">
                                        <hr class="bg-body-secondary mt-5 mb-4" />
                                        <div class="divider-content-center">or use email</div>
                                        </div> -->
                            <div class="mb-3 text-start">
                                <div class="form-icon-container">
                                    <input class="form-control pe-6 text-center" name="otp" id="otp" required
                                        type="text" placeholder="OTP" />
                                </div>
                            </div>
                            <button class="btn btn-primary w-100 mb-5" type="submit">Verify</button>
                            <div class="row flex-between-center mb-5">
                                <div class="col-auto">
                                    <a class="fs-9" href="{{ route('otp.resend.get') }}">Didn’t receive the code? </a>
                                </div>
                                <div class="col-auto">
                                    <a class="fs-9" href="{{ route('login') }}">Back to login page </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('script')
@endpush
