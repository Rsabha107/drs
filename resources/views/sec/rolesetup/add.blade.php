@extends('drs.layout.admin_template')
@section('main')



    <div class="border-bottom mb-7 mx-n3 px-2 mx-lg-n6 px-lg-6">
        <div class="row">
            <div class="col-xl-8">
                <div class="d-sm-flex justify-content-between">
                    <h2 class="mb-4 text-white">Add Roles in Permission</h2>
                    <!-- <div class="d-flex mb-3">
                        <a class="btn btn-phoenix-danger me-2 px-6" href="{{ route('sec.roles.list') }}">Cancel</a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8">
            <form action="{{ route ('sec.rolesetup.create') }}" class="row g-3 mb-9 needs-validation" novalidate="" method="post">
                @csrf

                <div class="col-sm-6 col-md-9">
                    <div class="form-floating">
                        <select name="role_id" class="form-select" id="floatingSelectRating" required>
                            <option selected="selected" value="">Select</option>
                            @foreach ($roles as $key => $role )
                            @if (Request::old('id') == $role->id )
                            <option value="{{ $role->id  }}" selected>
                                {{ $role->name }}
                            </option>
                            @else
                            <option value="{{ $role->id  }}">
                                {{ $role->name }}
                            </option>
                            @endif
                            @endforeach
                        </select>
                        <label for="floatingSelectRating">Role Name</label>
                    </div>
                    <hr>

                    <div class="col-sm-6 col-md-9">
                        <div class="form-check">
                            <input class="form-check-input" id="flexCheckDefaultmain" type="checkbox" value="">
                            <label class="form-check-label text-white" for="flexCheckDefaultmain">All Permissions</label>
                        </div>
                    </div>
                    <hr>
                    @foreach ($permission_groups as $group)
                    <div class="row">
                        <div class="col-3">
                            <div class="col-sm-6 col-md-9">
                                <div class="form-check">

                                    <input class="form-check-input" id="flexCheckDefault" type="checkbox" value="" />

                                    <label class="form-check-label text-white" for="flexCheckDefault">{{$group->group_name}}</label>
                                </div>
                            </div>

                        </div>
                        <div class="col-9">

                            @php
                            $permissions = App\Models\User::getpermissionByGroupName($group->group_name);
                            @endphp

                            @foreach ($permissions as $permission)
                            <div class="col-sm-6 col-md-9">
                                <div class="form-check">
                                    <input class="form-check-input" id="flexCheckDefault{{$permission->id}}" type="checkbox" name="permissions[]" value="{{$permission->id}}">
                                    <label class="form-check-label text-white" for="flexCheckDefault{{$permission->id}}">{{$permission->name}}</label>
                                </div>
                            </div>
                            @endforeach
                            <br>
                        </div>
                    </div>
                    @endforeach

                    <div class="col-12 d-flex justify-content-start mt-6">
                        <button class="btn btn-phoenix-secondary action me-3" type="submit" value="save">Save</button>
                        <a class="btn btn-phoenix-danger me-2 px-6" href="{{ route('sec.rolesetup.list') }}">Cancel</a>

                        <!-- <button class="btn btn-primary action"  type="submit" value="publish">Publish</button> -->
                    </div>
            </form>
        </div>
    </div>
    <!-- </div> -->
</div>

<!-- ===============================================-->
<!--    End of Main Content-->
<!-- ===============================================-->

@endsection

@push('script')

<script type="text/javascript">

    $('#flexCheckDefaultmain').click(function(){

        if ($(this).is(':checked')) {
            $('input[type= checkbox').prop('checked', true);
        } else {
            $('input[type= checkbox').prop('checked', false);
        }

    });

</script>


@endpush
