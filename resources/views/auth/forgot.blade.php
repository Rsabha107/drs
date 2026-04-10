@extends('drs.layout.template')
@section('main')
    <div class="container">
        <div class="row flex-center min-vh-100 py-5">
            <div class="col-sm-10 col-md-8 col-lg-5 col-xxl-4">
                <div class="px-xxl-5">
                    <div class="text-center mb-6">
                        <h4 class="text-white fw-bolder">Forgot your password?</h4>
                        <p class="text-white fw-bold mb-5">Enter your email below and we will send <br class="d-sm-none" />you
                            a reset link</p>
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
                        <form method="POST" action="{{ route('forgot.password.post') }}"
                            class="d-flex align-items-center mb-5">
                            @csrf
                            <input class="form-control flex-1" name="email" id="email" type="email"
                                placeholder="Email" />
                            <button type="submit" class="btn btn-primary ms-2">Send<span
                                    class="fas fa-chevron-right ms-2"></span></button>
                        </form>
                        <!-- <a class="fs-9 fw-bold" href="#!">Still having problems?</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
@endpush
