@if($floating)
<div class="{{ $class }}">
    <div class="form-floating">
        <textarea name="{{ $name }}" class="form-control" id="{{ $elementId }}"
            placeholder="{{ $label }}" style="height: 100px" {{ $required }} {{ $disabled }} >{{ $inputValue }}</textarea>
        <label for="{{ $elementId }}">{{ $label }}</label>
    </div>
</div>
@else
<div class="{{ $class }}">
    <label class="{{ $classLabel }}" for="{{ $elementId }}">{{ $label }}</label>
    {{-- <div class="{{ $inputWrappingClass }}"> --}}
        <textarea class="form-control" id="{{ $elementId }}" name="{{ $name }}"
            placeholder="{{ $label }}" {{ $required }} {{ $disabled }}>{{ $inputValue }}</textarea>
    {{-- </div> --}}
</div>
@endif
