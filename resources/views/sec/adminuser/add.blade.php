@extends('drs.layout.admin_template')
@section('main')

    {{-- <div class="container-fluid bg-body-tertiary"> --}}
    {{-- <div class="bg-holder bg-auth-card-overlay" style="background-image:url(../../../assets/img/bg/37.png);">
    </div> --}}
    <!--/.bg-holder-->

    <div class="row flex-center position-relative min-vh-100 g-0 py-5">
        <div class="col-11 col-sm-10 col-xl-6">
            <div class="card border border-translucent auth-card">
                <div class="card-body pe-md-0">
                    <div class="row align-items-center gx-0 gy-7">
                        <div class="col mx-auto">
                            <div class="auth-form-box">
                                <div class="text-center mb-5"><a class="d-flex flex-center text-decoration-none mb-4"
                                        href="#">
                                    </a>
                                    <h3 class="text-body-highlight">Create User</h3>
                                    <p class="text-body-tertiary">Create account today</p>
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
                                <form method="POST" action="{{ route('sec.adminuser.create') }}"
                                    class="needs-validation validatedForm" novalidate>
                                    @csrf
                                    {{-- <div class="mb-3 text-start">
                                            <label class="form-label" for="name">User Name</label>
                                            <input class="form-control" name="username" id="user_name" type="text" placeholder="User Name" value="{{ old('user_name') }}" required>
                            </div> --}}
                                    <div class="mb-3 text-start">
                                        <label class="form-label" for="name">Name</label>
                                        <input class="form-control" id="name" name="name" type="text"
                                            placeholder="Name" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label class="form-label" for="email">Email address</label>
                                        <input class="form-control" id="email" name="email" type="email"
                                            placeholder="name@example.com" value="{{ old('email') }}" required>
                                    </div>
                                    <div class="mb-3 text-start">
                                        <label class="form-label" for="phone">Phone</label>
                                        <input class="form-control" id="phone" name="phone" type="phone"
                                            placeholder="phone number" value="{{ old('phone') }}" required>
                                    </div>
                                    {{-- <div class="mb-3 text-start">
                                        <label class="form-label" for="photo"><?= get_label('photo', 'photo') ?></label>
                                        <input class="form-control" id="profile_image" name="photo" type="file"
                                            placeholder="photo">
                                    </div> --}}
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="">
                                            <?= get_label('require_email_verification', 'Require email verification?') ?>
                                            <i class='bx bx-info-circle text-primary' data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="If 'Yes' is selected, user will receive a verification link via email. Please ensure that email settings are configured and operational."></i>
                                        </label>
                                        <div class="">
                                            <div class="btn-group btn-group d-flex justify-content-center" role="group"
                                                aria-label="Basic radio toggle button group">
                                                <input type="radio" class="btn-check" id="require_ev_yes"
                                                    name="require_ev" value="1" checked>
                                                <label class="btn btn-outline-primary"
                                                    for="require_ev_yes"><?= get_label('yes', 'Yes') ?></label>
                                                <input type="radio" class="btn-check" id="require_ev_no" name="require_ev"
                                                    value="0">
                                                <label class="btn btn-outline-primary"
                                                    for="require_ev_no"><?= get_label('no', 'No') ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for=""><?= get_label('status', 'Status') ?>
                                            (<small class="text-muted mt-2">If Deactive, user won't be able to log in to
                                                their account</small>)</label>
                                        <div class="">
                                            <div class="btn-group btn-group d-flex justify-content-center" role="group"
                                                aria-label="Basic radio toggle button group">
                                                <input type="radio" class="btn-check" id="user_active" name="status"
                                                    value="1">
                                                <label class="btn btn-outline-primary"
                                                    for="user_active"><?= get_label('active', 'Active') ?></label>
                                                <input type="radio" class="btn-check" id="user_deactive" name="status"
                                                    value="0" checked>
                                                <label class="btn btn-outline-primary"
                                                    for="user_deactive"><?= get_label('deactive', 'Deactive') ?></label>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-4">

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" id="userUser" type="radio" name="usertype"
                                                value="user" checked="checked" required />
                                            <label class="form-check-label" for="inlineRadio2">User</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" id="adminUser" type="radio"
                                                name="usertype" value="admin" required />
                                            <label class="form-check-label" for="inlineRadio2">Admin</label>
                                        </div>
                                    </div>
                                    <div class="col-12 gy-3 mb-3">
                                        <label class="form-label" for="inputAddress2">Events
                                            (multiple)</label>
                                        <select class="form-select js-select-event-assign-multiple"
                                            id="add_event_assigned_to" name="event_id[]" multiple="multiple"
                                            data-with="100%"
                                            data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                            <!-- <select name="assignment_to_id[]" class="form-select" data-choices="data-choices" size="1" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}' id="floatingSelectRating" required> -->
                                            @foreach ($events as $event)
                                                <option value="{{ $event->id }}">{{ $event->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="col-12 gy-3 mb-3">
                                        <label class="form-label" for="inputAddress2">Functional Area
                                            (multiple)</label>
                                        <select class="form-select js-select-fa-assign-multiple" id="add_fa_assigned_to"
                                            name="fa_id[]" multiple="multiple" data-with="100%"
                                            data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                            <!-- <select name="assignment_to_id[]" class="form-select" data-choices="data-choices" size="1" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}' id="floatingSelectRating" required> -->
                                            @foreach ($functional_areas as $functional_area)
                                                <option value="{{ $functional_area->id }}">
                                                    {{ $functional_area->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="col-12 gy-3 mb-3">
                                        <label class="form-label" for="inputAddress2">Venue (multiple)</label>
                                        <select class="form-select js-select-venue-assign-multiple" id="add_venue_assigned_to"
                                            name="venue_id[]" multiple="multiple" data-with="100%"
                                            data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                            <!-- <select name="assignment_to_id[]" class="form-select" data-choices="data-choices" size="1" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}' id="floatingSelectRating" required> -->
                                            @foreach ($venues as $venue)
                                                <option value="{{ $venue->id }}">
                                                    {{ $venue->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-xl-6">
                                            <label class="form-label" for="password">Password</label>
                                            <input class="form-control form-icon-input" name="password" id="password"
                                                type="password" placeholder="Password" required>
                                        </div>
                                        <div class="col-xl-6">
                                            <label class="form-label" for="password_confirmation">Confirm
                                                Password</label>
                                            <input class="form-control form-icon-input" type="password"
                                                id="password_confirmation" name="password_confirmation"
                                                placeholder="Confirm Password" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-9 mb-3">
                                        @foreach ($roles as $key => $item)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" id="inlineCheckbox{{ $item->id }}"
                                                    type="checkbox" name="roles[]" value="{{ $item->id }}">
                                                <label class="form-check-label"
                                                    for="inlineCheckbox{{ $item->id }}">{{ $item->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button class="btn btn-primary w-100 mb-3" type="submit">Create now</button>
                                    <div class="text-center"><a class="fs-9 fw-bold"
                                            href="{{ route('sec.adminuser.list') }}">Go back to list</a></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- </div> --}}
    </div>

    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->

@endsection

@push('script')
    <script src="{{ asset('assets/js/pages/sec/users.js') }}"></script>
    {{-- <script>
            $(document).ready(function() {
                console.log('fauser checked ')
                $("#WorkspaceSelect").show();
                $("#DepartmentSelect").show();
                $("#floatingSelectDepartment").prop('required', true);
                $("#floatingSelectWorkspace").prop('required', true);
                $("input[name=usertype]").change(function() {
                    console.log('usertype changing')
                    if ($("#userUser").is(':checked')) {
                        $("#WorkspaceSelect").show();
                        $("#DepartmentSelect").show();
                        $("#floatingSelectDepartment").prop('required', true);
                        $("#floatingSelectWorkspace").prop('required', true);

                    } else {
                        $("#DepartmentSelect").hide();
                        $("#WorkspaceSelect").hide();
                        $("#floatingSelectWorkspace").prop('required', false);
                        $("#floatingSelectDepartment").prop('required', false);
                    }
                });
            });
        </script> --}}
@endpush
