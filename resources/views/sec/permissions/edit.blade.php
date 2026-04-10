@extends('drs.layout.admin_template')
@section('main')


<div class="border-bottom mb-7 mx-n3 px-2 mx-lg-n6 px-lg-6">
    <div class="row">
        <div class="col-xl-8">
            <div class="d-sm-flex justify-content-between">
                <h2 class="mb-4">Edit Role</h2>
                <div class="d-flex mb-3">
                    <a class="btn btn-phoenix-danger me-2 px-6" href="{{ route('sec.perm.list') }}">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-8">
        <form action="{{ route ('sec.perm.update') }}" class="row g-3 mb-9 needs-validation" novalidate="" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $permissions->id }}">
            <div class="col-sm-6 col-md-9">
                <div class="form-floating">
                    <input name="name" value="{{$permissions->name}}" class="form-control" id="floatingInputEventName" type="text" placeholder="" required>
                    <label for="floatingInputEventName">Perminssion Name</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-9">
                <div class="form-floating">
                    <select name="group_name" class="form-select" id="floatingSelectRating" required>
                        <option selected="selected" value="">Select</option>
                        @foreach ($groups as $key => $item )
                        @if ($permissions->group_name == $item->name )
                        <option value="{{ $item->name  }}" selected>
                            {{ $item->name }}
                        </option>
                        @else
                        <option value="{{ $item->name  }}">
                            {{ $item->name }}
                        </option>
                        @endif
                        @endforeach
                    </select>
                    <label for="floatingSelectRating">Group</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-9">
                <div class="form-floating">
                    <select name="active_flag" class="form-select" id="floatingSelectRating" required>
                        <option value="">Select</option>
                        @foreach ($status as $key => $item )
                        @if ($permissions->active_flag == $item->id )
                        <option value="{{ $item->id  }}" selected>
                            {{ $item->name }}
                        </option>
                        @else
                        <option value="{{ $item->id  }}">
                            {{ $item->name }}
                        </option>
                        @endif
                        @endforeach
                    </select>
                    <label for="floatingSelectRating">Status</label>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-end mt-6">
                <button class="btn btn-phoenix-secondary action" type="submit" value="save">Save</button>
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