@if($floating)
<div class="{{ $class }}">
    <div class="form-floating">
        <input name="{{ $name }}" class="form-control" id="{{ $elementId }}"
            type="{{ $inputType }}" placeholder="{{ $label }}" value="{{ $inputValue }}" {{ $required }} {{ $disabled }} {{ $inputAttributes }}/>
        <label for="{{ $elementId }}">{{ $label }}</label>
    </div>
</div>
@else
<div class="{{ $class }}">
    <label class="{{ $classLabel }}" for="{{ $elementId }}">{{ $label }}</label>
    <div class="{{ $inputWrappingClass }}">
        <input class="form-control" id="{{ $elementId }}" name="{{ $name }}" value="{{ $inputValue }}" 
            type="{{ $inputType }}" placeholder="{{ $label }}" {{ $required }} {{ $disabled }} />
    </div>
</div>
@endif