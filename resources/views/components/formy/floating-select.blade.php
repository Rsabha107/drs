@if ($floating)
    <div class="form-floating form-floating-advance-select mb-3">
        <select id="{{ $id }}" name="{{ $multiple ? $id . '[]' : $id }}" class="form-select"
            data-choices="data-choices"
            data-options='{"removeItemButton":{{ $multiple ? 'true' : 'false' }},"placeholder":true}'
            @if ($multiple) multiple @endif @if ($required) required @endif>
            <option selected="selected" value="">Select {{ $label }}...</option>
            @foreach ($options as $value => $item)
                <option value="{{ $item->$itemIdForeach }}" @if ($item->$itemIdForeach == $selectedValue) selected @endif>
                    {{ $item->$itemTitleForeach }}</option>
            @endforeach
        </select>

        <label for="{{ $id }}">{{ $label }}</label>
    </div>

@else
{{-- @php
    // Priority: old input → passed value → null
    $currentValue = old($id);
@endphp --}}
 <div class="mb-3 choices-lg">
    <label for="{{ $id }}" class="ms-2 mb-1">{{ $label }}</label>
    <select id="{{ $id }}" name="{{ $id }}" class="form-select form-select-lg @error($id) is-invalid @enderror"
        data-choices="data-choices" data-options='{"removeItemButton":true,"placeholder":true}'
        @if ($required) required @endif>
        <option value="">Select {{ $label }}...</option>

        @foreach ($options as $value => $item)
            <option value="{{ $item->$itemIdForeach }}" @if ($item->$itemIdForeach == $selectedValue) selected @endif>
                    {{ $item->$itemTitleForeach }}</option>
        @endforeach
    </select>

    @error($id)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
@endif
