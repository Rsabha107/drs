@extends('main.event.layout.event-add-layout')
@section('main')


<div class="content">


    <div class="card shadow-none border my-4" data-component-card="data-component-card">
        <div class="card-header p-4 border-bottom bg-body">
            <div class="row g-3 justify-content-between align-items-center">
                <div class="col-12 col-md">
                    <h4 class="text-body mb-0" data-anchor="data-anchor" id="form-grid-layout">Create Client<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#form-grid-layout" style="margin-left: 0.1875em; padding-right: 0.1875em; padding-left: 0.1875em;"></a></h4>
                </div>
                <div class="col col-md-auto">

                </div>
            </div>
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

        <div class="p-4 code-to-copy">
            <form class="row g-3 needs-validation form-submit-eventx" novalidate id="add_new_client" action="{{route('main.users.store')}}" method="POST">
            @csrf
                <!-- <form class="row g-3"> -->
                <div class="col-md-6">
                    <label class="form-label" for="inputEmail4">First name</label>
                    <input class="form-control" id="first_name" name="first_name" type="text" value="{{old('first_name')}}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="inputEmail4">Last name</label>
                    <input class="form-control" id="last_name" name="last_name" type="text" value="{{old('last_name')}}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="inputEmail4">Email</label>
                    <input class="form-control" id="inputEmail4" name="email" type="email" value="{{old('email')}}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="inputEmail4">Phone number</label>
                    <input class="form-control" id="phone_number" name="phone" type="phone" value="{{old('phone')}}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="inputPassword4">Password</label>
                    <input class="form-control" id="password" name="password" type="password" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="inputPassword4">Confirm password</label>
                    <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="inputAddress">Company</label>
                    <input class="form-control" id="company" name="company" type="text" placeholder="1234 Main St" value="{{old('company')}}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="inputAddress2">Address</label>
                    <input class="form-control" id="address" name="address" type="text" placeholder="Apartment, studio, or floor" value="{{old('address')}}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="inputAddress2">Country</label>
                    <input class="form-control" id="country" name="country" type="text" placeholder="Apartment, studio, or floor" value="{{old('country')}}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="inputCity">City</label>
                    <input class="form-control" id="inputCity" name="city" type="text" value="{{old('city')}}" required>
                </div>
                <div class="col-md-6">

                    <label class="form-label" for="inputState">State</label>

                    <select class="form-select" id="inputState" name="state" value="{{old('state')}}" required>
                        <option value="" selected="selected">Choose...</option>
                        <option>...</option>
                    </select>
                </div>
                <div class="col-md-6">

                    <label class="form-label" for="inputZip">Zip code</label>

                    <input class="form-control" id="inputZip" name="zipcode" type="text" value="{{old('zipcode')}}" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label" for="">
                        <?= get_label('require_email_verification', 'Require email verification?') ?>
                        <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="If 'Yes' is selected, user will receive a verification link via email. Please ensure that email settings are configured and operational."></i>
                    </label>
                    <div class="">
                        <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" id="require_ev_yes" name="require_ev" value="1" checked>
                            <label class="btn btn-outline-primary" for="require_ev_yes"><?= get_label('yes', 'Yes') ?></label>
                            <input type="radio" class="btn-check" id="require_ev_no" name="require_ev" value="0">
                            <label class="btn btn-outline-primary" for="require_ev_no"><?= get_label('no', 'No') ?></label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label" for=""><?= get_label('status', 'Status') ?> (<small class="text-muted mt-2">If Deactive, user won't be able to log in to their account</small>)</label>
                    <div class="">
                        <div class="btn-group btn-group d-flex justify-content-center" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" id="user_active" name="status" value="1">
                            <label class="btn btn-outline-primary" for="user_active"><?= get_label('active', 'Active') ?></label>
                            <input type="radio" class="btn-check" id="user_deactive" name="status" value="0" checked>
                            <label class="btn btn-outline-primary" for="user_deactive"><?= get_label('deactive', 'Deactive') ?></label>

                        </div>
                    </div>
                </div>
                <div class="mb-3 col-md-6">
                    <button class="btn btn-primary" type="submit">Create</button>
                </div>
        </div>
        </form>
    </div>
    <!-- </div> -->
    <!-- </div> -->

    @endsection

    @push('script')


    @endpush
