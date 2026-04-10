@if ($edit)
<div class="{{ $class }}">
    <label class="form-label" for="{{ $elementId }}">{{ $label }}</label>
    <select class="form-select js-select-event-assign-multiple-{{ $elementId }}" id="{{ $elementId }}"
        name="{{ $name }}[]" multiple="multiple" data-with="100%" {{ $required }} style="{{ $style }}"
        data-placeholder="Select {{ $label }}...">
        <!-- <select name="assignment_to_id[]" class="form-select" data-choices="data-choices" size="1" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}' id="floatingSelectRating" required> -->
        @foreach ($forLoopCollection as $item)
            <option value="{{ $item->$itemIdForeach }}" {{ in_array($item->$itemIdForeach, $baseModel->$relation->pluck('id')->toArray()) ? 'selected' : '' }}>
                {{ $item->$itemTitleForeach }}
            </option>
        @endforeach
    </select>
</div>
@else 
<div class="{{ $class }}">
    <label class="form-label" for="{{ $elementId }}">{{ $label }}</label>
    <select class="form-select js-select-event-assign-multiple-{{ $elementId }}" id="{{ $elementId }}"
        name="{{ $name }}[]" multiple="multiple" data-with="100%" {{ $required }} style="{{ $style }}"
        data-placeholder="Select {{ $label }}...">
        {{-- <option value="0">All</option> --}}
        @foreach ($forLoopCollection as $item)
            <option value="{{ $item->$itemIdForeach }}">
                {{ $item->$itemTitleForeach }}
            </option>
        @endforeach
    </select>

</div>
@endif
