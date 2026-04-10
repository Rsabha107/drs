@extends('drs.layout.admin_template')
@section('main')

<div class="d-flex justify-content-between m-2">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}"><?= get_label('home', 'Home') ?></a>
                </li>
                <li class="breadcrumb-item"><a href="#">
                        <?= get_label('booking', 'Booking') ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?= get_label('send_invitation', 'Send Invitation') ?>
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="container">

    <div class="card shadow-none border my-4 col-md-8" style="margin:0 auto;" data-component-card="data-component-card">
        @if (session('message'))
        <div class="alert">{{ session('message') }}</div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger alert alert-danger alert-dismissible fade show">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card-header p-4 border-bottom bg-body">
            <div class="row g-3 justify-content-between align-items-center">
                <div class="col-12 col-md">
                    <h4 class="text-body mb-0">Invite New User</h4>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="p-4 code-to-copy">
                <form class="row g-3  px-3 needs-validation" action="{{ route('drs.admin.users.invite.send') }}"
                    id="spinner-form" novalidate method="POST">
                    @csrf

                    <x-formy.form_input_left_label floating="0" name="name" elementId="add_name"
                        classLabel="col-sm-3 col-form-label-sm" label="Name" inputType="text"
                        inputValue="{{ old('name') }}" class="row mt-2" inputWrappingClass="col-sm-8"
                        required="required" disabled="" inputPlaceholder="Enter name" inputAttributes="" />

                    <x-formy.form_input_left_label floating="0" name="email" elementId="add_email"
                        classLabel="col-sm-3 col-form-label-sm" label="Email" inputType="text"
                        inputValue="{{ old('email') }}" class="row mt-2" inputWrappingClass="col-sm-8"
                        required="required" disabled="" inputPlaceholder="Enter email" inputAttributes="" />

                    <x-formy.form_select_row name="event_id" itemIdForeach="id"
                        itemTitleForeach="name" elementId="add_event_id" selectedValue="{{ old('event_id') }}"
                        classLabel="col-sm-3 col-form-label-sm" label="Event"
                        :forLoopCollection="$events" class="row mb-3 mt-2" style="margin:0 auto;" required="required"
                        addDynamicButton="0" dynamicModal="#add_event_id_modal" />
                    {{-- <x-formy.form_select_row name="functional_area_id" itemIdForeach="id"
                        itemTitleForeach="title" elementId="add_functional_area_id" selectedValue="{{ old('functional_area_id') }}"
                        classLabel="col-sm-3 col-form-label-sm" label="Functional Area"
                        :forLoopCollection="$functional_areas" class="row mb-3 mt-2" style="margin:0 auto;" required="required"
                        addDynamicButton="0" dynamicModal="#add_functional_area_id_modal" /> --}}
                    <!-- <div class="invisible">.</div> -->
                    <div class="col-12 d-flex justify-content-end mt-6">
                        <button class="btn btn-primary" type="submit">Send Invite</button>
                    </div>
                    <!-- <button class="btn btn-primary" type="submit">Submit</button> -->
                </form>
            </div>
        </div>
        <!-- <br /> -->
        <!-- &nbsp; -->
    </div>
</div>
@endsection