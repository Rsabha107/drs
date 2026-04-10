@extends('drs.layout.admin_template')
@section('main')

<div class="d-flex justify-content-between m-2">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1">
                <li class="breadcrumb-item">
                    <a href="{{route('home')}}"><?= get_label('home', 'Home') ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?= get_label('functional_areas', 'Functional Areas') ?>
                </li>
            </ol>
        </nav>
    </div>
    <div>
        <x-button_insert_modal bstitle='Add Functional Area' bstarget="#create_functional_areas_modal" />
    </div>
</div>
<x-setting.functional-area-card :functional_areas="$functional_areas" />

@include('drs.setting.modals.functional_area_modals')
<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_not_assigned = '<?= get_label('not_assigned', 'Not assigned') ?>';
    var label_duplicate = '<?= get_label('duplicate', 'Duplicate') ?>';
</script>
<script src="{{asset('assets/js/pages/drs/functional_area.js')}}"></script>
@endsection

@push('script')
@endpush
