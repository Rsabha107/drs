@if ($floating)
<div class="{{ $class }}">
    <div class="flatpickr-input-container">
        <div class="form-floating">
            <input class="form-control datetimepicker"
                type="{{ $inputType }}" placeholder="dd/mm/yyyy" placeholder="{{ $label }}"
                name="{{ $name }}"
                id="{{ $elementId }}"
                value="{{ $inputValue }}"
                data-options='{"disableMobile":true,"dateFormat":"d/m/Y"}' {{ $required }} />
            <div class="invalid-feedback">
                Please enter booking date.
            </div>
            <label class="ps-6" for="{{ $elementId }}">{{ $label }}</label><span
                class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span>
        </div>
    </div>
</div>
@else
<div class="{{ $class }}">
    <label  for="{{ $elementId }}" class="ms-2 mb-1">{{ $label }} <span class="text-danger">*</span></label>
    <input class="form-control datetimepicker flatpickr-input active"
        name="{{ $name }}"
        id="{{ $elementId }}"
        type="{{ $inputType }}"
        value="{{ $inputValue }}"
        placeholder="dd/mm/yyyy"
        data-options="{&quot;disableMobile&quot;:true,&quot;dateFormat&quot;:&quot;d/m/Y&quot;}" {{ $required }}>
</div>
@endif