@props([
    'label' => 'Match Number',
    'name' => 'match_number',
    'id' => 'match_number',
    'placeholder' => 'Select Match Number',
    'col' => 'col-sm-12 col-md-12 mb-3',
    'disabled' => true, // 👈 default disabled
])

<div class="{{ $col }}">
    <label for="{{ $id }}" class="ms-2 mb-1">{{ $label }}</label>

    <select
        name="{{ $name }}"
        id="{{ $id }}"
        class="form-select"
        {{ $attributes }}
        @if($disabled) disabled @endif
    >
        <option value="" selected>{{ $placeholder }}</option>
    </select>
</div>