@extends('drs.customer.layout.template')
@section('main')
    <div class="px-3">
        <div class="row min-vh-100 flex-center p-5">
            <div class="col-12 col-xl-10 col-xxl-8">
                <div class="row justify-content-center align-items-center g-5">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-12 col-lg-6 text-center text-lg-start">
                        <h2 class="text-white fw-bolder mb-3">Choose an Event!</h2>
                        <p class="text-white mb-2 fw-bold">Please choose an event from the list below.</p>
                        @php
                            $user_events = auth()->user()->events;
                            $user_events = $user_events->where('active_flag', 1)->sortBy('name');
                            $user_venues = auth()->user()->venues;
                            $user_venues = $user_venues->where('active_flag', 1)->sortBy('title');
                        @endphp
                        <div data-list='{"valueNames":["title"]}'>
                            <form class="position-relative" action="{{ route('drs.customer.report.event.switch') }}"
                                method="POST" id="spinner-form">
                                @csrf
                                <select class="form-select mb-3" name="event_id" id="event_idx" required>
                                    <option value="" selected>Select ..</option>
                                    @foreach ($user_events as $event)
                                        <option value="{{ $event->id }}">{{ $event->name }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- <select class="form-select mb-3" name="venue_id" id="venue_id" required disabled>
                                    <option value="">Select Venue…</option>
                                </select> --}}
                                {{-- <select class="form-select mb-3" name="venue_id" required>
                                    <option value="" selected>Select ..</option>
                                    @foreach ($user_venues as $venue)
                                        <option value="{{ $venue->id }}">{{ $venue->title }}
                                        </option>
                                    @endforeach
                                </select> --}}
                                <button class="btn btn-subtle-primary w-100 mb-3" type="submit">Choose
                                    Event</button>
                            </form>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <a class="btn btn-sm btn btn-purple text-white" href="{{ route('drs.logout') }}">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/pages/drs/pick.js') }}"></script>
@endsection

@push('script')
@endpush
