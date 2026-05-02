@extends('drs.layout.admin_template')
@section('main')

    <form method="POST" action="{{ route('admin.signup.ms.store') }}" class="forms-sample">
        @csrf
        <div class="row flex-center py-5">
            <div class="col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4 p-sm-5">
                        <div class="text-center mb-7">
                            <h3 class="text-body-highlight">{{ config('settings.website_name') }} (MDS)
                            </h3>
                            <p class="text-body-tertiary">Register</p>
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

                        <div class="col mb-3">
                            <div class="form-floating">
                                <input class="form-control" id="add_name" name="name" type="text" placeholder="Name"
                                    value="{{ old('name', request('name')) }}">
                                <label for="add_name">Name</label>
                            </div>
                        </div>
                        <div class="col mb-3">
                            <div class="form-floating">
                                <input class="form-control" id="add_email" name="email" type="text" placeholder="Email"
                                    value="{{ old('email', request('email')) }}">
                                <label for="add_email">Email</label>
                            </div>
                        </div>
                        {{-- <x-formy.form_input class="col mb-3" floating="1" inputValue="{{ old('phone', request('phone')) }}" name="phone"
                            elementId="add_phone" inputType="text" inputAttributes="" label="Phone" required="required"
                            disabled="0" /> --}}

                        <div class="col-12 gy-3 mb-3">
                            <label class="form-label" for="inputAddress2">Events
                                (multiple)</label>
                            <select class="form-select js-select-event-assign-multiple" id="add_event_assigned_to"
                                name="event_id[]" multiple="multiple" data-with="100%"
                                data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if (config('settings.venue_user_management'))
                            <div class="col-12 gy-3 mb-3">
                                <label class="form-label" for="inputAddress2">Venue (multiple)</label>
                                    (multiple)</label>
                                <select class="form-select js-select-venue-assign-multiple" id="add_venue_assigned_to"
                                    name="venue_id[]" multiple="multiple" data-with="100%"
                                    data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                    @foreach ($venues as $venue)
                                        <option value="{{ $venue->id }}">
                                            {{ $venue->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        @if (config('settings.functional_area_user_management'))
                            <div class="col-12 gy-3 mb-3">
                                <label class="form-label" for="inputAddress2">Functional Area
                                    (multiple)</label>
                                <select class="form-select js-select-fa-assign-multiple" id="add_fa_assigned_to"
                                    name="fa_id[]" multiple="multiple" data-with="100%"
                                    data-placeholder="<?= get_label('type_to_search', 'Type to search') ?>">
                                    @foreach ($fas as $functional_area)
                                        <option value="{{ $functional_area->id }}">
                                            {{ $functional_area->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-sm-6 col-md-9 mb-3">
                            @foreach ($roles as $key => $item)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="inlineCheckbox{{ $item->id }}" type="checkbox"
                                        name="roles[]" value="{{ $item->id }}">
                                    <label class="form-check-label"
                                        for="inlineCheckbox{{ $item->id }}">{{ $item->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <button class="btn btn-primary w-100 mb-3">Register</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    {{-- <script src="{{ asset('assets/js/pages/sec/users.js') }}"></script> --}}
    <script>
        $(document).ready(function() {
            $('.js-select-event-assign-multiple').select2();
            $('.js-select-fa-assign-multiple').select2();
            $('.js-select-venue-assign-multiple').select2();
            // $('.js-select-role-assign-multiple').select2();
        });
    </script>
@endpush
