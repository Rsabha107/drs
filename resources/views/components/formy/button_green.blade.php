<a href="{{$url}}">
    <button class="btn btn-subtle-success px-3 px-sm-5 me-2" id="{{ $btnId }}" @if($disabled) disabled @endif>
        <span class="fa-solid fa-plus me-sm-2"></span>
        <span class="d-none d-sm-inline">{{ $title }}</span>
    </button>
</a>