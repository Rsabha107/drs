@extends('drs.layout.template')
@section('main')
        <div class="container">
            <div class="row flex-center min-vh-100 py-5">
                <div class="col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-3"><a class="d-flex flex-center text-decoration-none mb-4" href="../../../index.html">
                        {{-- <div class="d-flex align-items-center fw-bolder fs-3 d-inline-block"><img src="../../../assets/img/icons/logo.png" alt="phoenix" width="58" />
                        </div> --}}
                    </a>
                    <div class="card shadow-sm">
                        <div class="card-body p-4 p-sm-5">
                            <div class="text-center mb-6">
                                <div class="text-center mb-7">
                                    <h3 class="text-body-highlight">YPI</h3>
                                    <p class="text-body-tertiary">Reset your password.</p>
                                </div>
                                @if (Session::has('message'))
                                <div class="alert alert-success" role="alert">
                                    {{ Session::get('message') }}
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
                                <form action="{{ route('reset.password.post') }}" method="POST" class="mt-5">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <input class="form-control mb-2" name="email" id="email" type="email" placeholder="Type your email" required>
                                    <input class="form-control mb-2" name="password" id="password" type="password" placeholder="Type new password" required autocomplete="new-password">
                                    <input class="form-control mb-4" name="password_confirmation" id="confirmPassword" type="password" placeholder="Cofirm new password" required autocomplete="new-password" />
                                    <button class="btn btn-primary w-100" type="submit">Set Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
               @endsection
@push('script')
@endpush