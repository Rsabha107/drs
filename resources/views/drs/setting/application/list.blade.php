@extends('drs.layout.admin_template')
@section('main')


<!-- ===============================================-->
<!--    Main Content-->
<!-- ===============================================-->

{{-- <div class="content"> --}}
    {{-- <div class="container-fluid"> --}}
        <x-formy.bread_crumb_insert_button
            activeBread='Application Setting'
            bsTargetName="create_setting_modal"
            bsTitle='Add Setting'
        />
        <x-setting.application-card />
    {{-- </div> --}}

    @include('drs.setting.modals.application_setting_modals')

    <script>
        var label_update = '<?= get_label('update', 'Update') ?>';
        var label_delete = '<?= get_label('delete', 'Delete') ?>';
        var label_not_assigned = '<?= get_label('not_assigned', 'Not assigned') ?>';
        var label_duplicate = '<?= get_label('duplicate', 'Duplicate') ?>';
    </script>
    <script src="{{asset('assets/js/pages/drs/application_setting.js')}}"></script>
    @endsection

    @push('script')


    @endpush
