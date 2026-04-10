@extends('vapp.admin.layout.admin_template')
@section('main')

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->

    {{-- <div class="content"> --}}

    <div class="d-flex justify-content-between m-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('vapp.customer.users.profile') }}">
                            <?= get_label('profile', 'Profile') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ $user->name }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container">
        @if (session('message') && session('alert-type') == 'error')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Profile 1 - Bootstrap Brain Component -->
        <section class="bg-light py-3 py-md-5 py-xl-8">

            <div class="container">
                <div class="row gy-4 gy-lg-0">
                    <div class="col-12 col-lg-8 col-xl-9">
                        <div class="card widget-card border-light shadow-sm">
                            <div class="card-body p-4">
                                <ul class="nav nav-tabs" id="profileTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#profile-tab-pane" type="button" role="tab"
                                            aria-controls="profile-tab-pane" aria-selected="false">Profile</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="email-tab" data-bs-toggle="tab"
                                            data-bs-target="#email-tab-pane" type="button" role="tab"
                                            aria-controls="email-tab-pane" aria-selected="false">Emails</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="password-tab" data-bs-toggle="tab"
                                            data-bs-target="#password-tab-pane" type="button" role="tab"
                                            aria-controls="password-tab-pane" aria-selected="false">Password</button>
                                    </li>
                                </ul>
                                <div class="tab-content pt-4" id="profileTabContent">
                                    <div class="tab-pane fade show active" id="profile-tab-pane" role="tabpanel"
                                        aria-labelledby="profile-tab" tabindex="0">
                                        <form action="{{ route('vapp.admin.users.profile.update') }}" method="POST"
                                            enctype="multipart/form-data" class="row gy-3 gy-xxl-4 needs-validation" novalidate>
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <input type="hidden" name="old_photo" value="{{ $user->photo }}">
                                            <div class="row gy-4 gy-lg-0">
                                                <div class="col-12 col-lg-8 col-xl-4 mt-9">

                                                    <div class="text-center mb-3">
                                                        <div class="mb-3 text-start">
                                                            <input type="file" name="file_name" class="dropify"
                                                                data-height="200"
                                                                data-default-file="{{ !empty($user->photo) ? url('storage/upload/profile_images/' . $user->photo) : url('upload/default.png') }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-8 col-xl-7">
                                                    <div class="col-12 col-md-12">
                                                        <label for="inputFirstName" class="form-label">Name</label>
                                                        <input type="text" class="form-control" id="inputFirstName"
                                                            value="{{ $user->name }}" name="name" required>
                                                    </div>
                                                    <div class="col-12 col-md-12">
                                                        <label for="inputPhone" class="form-label">Phone</label>
                                                        <input type="tel" class="form-control" id="inputPhone"
                                                            value="{{ $user->phone }}" name="phone">
                                                    </div>
                                                    <div class="col-12 col-md-12">
                                                        <label for="inputEmail" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="inputEmail"
                                                            value="{{ $user->email }}" name="email" required>
                                                    </div>
                                                    <div class="col-12 col-md-12 mb-5">
                                                        <label for="inputAddress" class="form-label">Address</label>
                                                        <input type="text" class="form-control" id="inputAddress"
                                                            value="{{ $user->address }}" name="address">
                                                    </div>

                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-primary">Save
                                                            Changes</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="email-tab-pane" role="tabpanel"
                                        aria-labelledby="email-tab" tabindex="0">
                                        <form action="#!">
                                            <fieldset class="row gy-3 gy-md-0">
                                                <legend class="col-form-label col-12 col-md-3 col-xl-2">Email Alerts
                                                </legend>
                                                <div class="col-12 col-md-9 col-xl-10">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="emailChange">
                                                        <label class="form-check-label" for="emailChange">
                                                            Email Changed
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="passwordChange">
                                                        <label class="form-check-label" for="passwordChange">
                                                            Password Changed
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="weeklyNewsletter">
                                                        <label class="form-check-label" for="weeklyNewsletter">
                                                            Weekly Newsletter
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="productPromotions">
                                                        <label class="form-check-label" for="productPromotions">
                                                            Product Promotions
                                                        </label>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <div class="row">
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-primary mt-4">Save
                                                        Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="password-tab-pane" role="tabpanel"
                                        aria-labelledby="password-tab" tabindex="0">
                                        <form action="{{ route('vapp.admin.users.profile.password.update') }}" method="POST"
                                            enctype="multipart/form-data" class="row gy-3 gy-xxl-4 needs-validation" novalidate>
                                            @csrf
                                            <div class="row gy-3 gy-xxl-4">
                                                <div class="col-12">
                                                    <label for="currentPassword" class="form-label">Current
                                                        Password</label>
                                                    <input type="password" class="form-control" id="currentPassword" name="current_password">
                                                </div>
                                                <div class="col-12">
                                                    <label for="newPassword" class="form-label">New Password</label>
                                                    <input type="password" class="form-control" id="newPassword" name="password">
                                                </div>
                                                <div class="col-12">
                                                    <label for="confirmPassword" class="form-label">Confirm
                                                        Password</label>
                                                    <input type="password" class="form-control" id="confirmPassword" name="password_confirmation">
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-primary">Change
                                                        Password</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
    <script src="{{ asset('assets/js/pages/vapp/booking.js') }}"></script>

    <!-- Confirm Modal -->
    <!-- <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmModalLabel">Order Placed</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">Congratulations! Your order is placed.</div>
                                                <div id="jsonOutput"></div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" onclick="closeModal()">
                                                        Ok, close and reset
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
    @include('vapp.admin.partials.booking_modals')

@endsection

@push('script')
    <script>
        // showing the offcanvas for the task creation
        $(document).ready(function() {
            console.log('ready');
            $('.dropify').dropify();

        });
    </script>
@endpush
