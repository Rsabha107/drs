@extends('drs.layout.admin_template')
@section('main')


<div class="border-bottom mb-7 mx-n3 px-2 mx-lg-n6 px-lg-6">
    <div class="row">
        <div class="col-xl-8">
            <div class="d-sm-flex justify-content-between">
                <h2 class="mb-4">Import Permission Role</h2>
                <div class="d-flex mb-3">
                    <a class="btn btn-phoenix-danger me-2 px-6" href="{{ route('sec.perm.list') }}">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-8">
        <form action="{{ route ('sec.perm.import.now') }}" class="row g-3 mb-9 needs-validation" novalidate="" method="post" enctype="multipart/form-data">
            @csrf

            <div class="col-sm-6 col-md-9">
                <div class="mb-3">

                    <label class="form-label">Excel file import (.xlsx)</label>

                    <input class="form-control" type="file" name="import_file" required>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-start mt-6">
                <button class="btn btn-phoenix-primary action" type="submit" value="save">Upload</button>
                <a class="btn btn-phoenix-danger me-2 px-6" href="{{ route('sec.perm.list') }}">Cancel</a>

                <!-- <button class="btn btn-primary action"  type="submit" value="publish">Publish</button> -->
            </div>
        </form>
    </div>
    <!-- </div> -->
</div>

<!-- ===============================================-->
<!--    End of Main Content-->
<!-- ===============================================-->

@endsection

@push('script')


@endpush