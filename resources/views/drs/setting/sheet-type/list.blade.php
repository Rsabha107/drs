@extends('drs.layout.admin_template')
@section('main')
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->

    {{-- <div class="container-fluid"> --}}
    <div class="d-flex justify-content-between m-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('sheet_types', 'Sheet Types') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <x-button_insert_modal bstitle='Add Sheet Type' bstarget="#create_sheet_type_modal" />
            <!-- <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_sheet_type_modal"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_sheet_type', 'Create Sheet Type') ?>"><i class="bx bx-plus"></i></button></a> -->
        </div>
    </div>
    <x-setting.sheet-type-card />
    {{-- </div> --}}

    @include('drs.setting.modals.sheet_type_modals')
    <script src="{{ asset('assets/js/pages/drs/sheet_type_upload.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/pages/drs/sheet_type_dz_upload.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/pages/drs/sheet_type_upload_min.js') }}"></script> --}}

    <script src="{{ asset('assets/js/pages/drs/sheet_type.js') }}"></script>

    <script>
        var label_update = '<?= get_label('update', 'Update') ?>';
        var label_delete = '<?= get_label('delete', 'Delete') ?>';
        var label_not_assigned = '<?= get_label('not_assigned', 'Not assigned') ?>';
        var label_duplicate = '<?= get_label('duplicate', 'Duplicate') ?>';
        var label_please_wait = '<?= get_label('please_wait', 'Please wait') ?>';
    </script>
@endsection

@push('script')

    {{-- <script src="{{ asset('assets/js/pages/drs/sheet_type_upload.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/pages/drs/sheet_type_dz_upload.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/pages/drs/sheet_type_upload_min.js') }}"></script> --}}

    {{-- <script src="{{ asset('assets/js/pages/drs/sheet_type.js') }}"></script> --}}

    {{-- <script>
        // showing the offcanvas for the task creation
        $(document).ready(function() {
            console.log('ready');
            $('.dropify').dropify();

        });

    </script> --}}
@endpush
