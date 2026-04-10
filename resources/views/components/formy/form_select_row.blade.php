<div class="{{ $class }}">
    <label class="{{ $classLabel }}" for="{{ $elementId }}">{{ $label }}</label>
    <div class="col-sm-8">
        <select class="form-select" 
            name="{{ $name }}" 
            id="{{ $elementId }}"
            style="{{ $style }}"
            {{ $required }} >
            <option value="">Select {{ $label }}...</option>
            @foreach ($forLoopCollection as $key =>$item)
                <option value="{{ $item->$itemIdForeach }}">
                    {{ $item->$itemTitleForeach }}
                </option>
            @endforeach
        </select>
    </div>
</div>


    