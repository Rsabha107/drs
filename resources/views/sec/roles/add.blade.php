@extends('drs.layout.admin_template')
@section('main')

    <div class="border-bottom mb-7 mx-n3 px-2 mx-lg-n6 px-lg-6">
        <div class="row">
            <div class="col-xl-8">
                <div class="d-sm-flex justify-content-between">
                    <h2 class="mb-4">Add Role</h2>
                    <div class="d-flex mb-3">
                        <a class="btn btn-phoenix-danger me-2 px-6" href="{{ route('sec.roles.list') }}">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <form action="{{ route ('sec.roles.create') }}" class="row g-3 mb-9 needs-validation" novalidate="" method="post">
                @csrf

                <div class="col-sm-6 col-md-9">
                    <div class="form-floating">
                        <input name="name" class="form-control" id="floatingInputEventName" type="text"
                            placeholder="" required>
                        <label for="floatingInputEventName">Role Name</label>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-start mt-6">
                    <button class="btn btn-phoenix-secondary action" type="submit" value="save">Save</button>
                    <a class="btn btn-phoenix-danger me-2 px-6" href="{{ route('sec.roles.list') }}">Cancel</a>

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
