@if ($floating)
    <div class="{{ $class }}">
        <div class="input-group">
            <div class="form-floating">
                <select name="{{ $name }}" id="{{ $elementId }}"
                    class="form-select @error('guest_type_id') is-invalid @enderror" {{ $required }}
                    style="{{ $style }}" aria-label=".form-select-lg example">
                    <option selected="selected" value="">Select {{ $label }}...</option>
                    @foreach ($forLoopCollection as $key => $item)
                        {{-- {{ logger('selectedValue: '.$selectedValue) }}
                        {{ logger('itemIdForeach: '.$$item->$itemIdForeach) }} --}}
                        <option value="{{ $item->$itemIdForeach }}" @if ($item->$itemIdForeach == $selectedValue) selected @endif>{{ $item->$itemTitleForeach }}</option>
                    @endforeach
                </select>
                <!-- <div class="invalid-feedback">
                                            Please select event.
                                        </div> -->
                <label for="{{ $elementId }}">{{ $label }}</label>
            </div>
            @if ($addDynamicButton)
                <button type="button" class="btn btn-phoenix-primary px-3" data-bs-toggle="modal"
                    data-bs-target="{{ $dynamicModal }}">
                    <i class="fas fa-plus-circle text-success"></i>
                </button>
            @endif
        </div>
    </div>
@else
    <div class="{{ $class }}" style="{{ $style }}">
        <label class="form-label" for="{{ $elementId }}">{{ $label }}</label>
        <select class="form-select @error('guest_type_id') is-invalid @enderror" 
            name="{{ $name }}" 
            id="{{ $elementId }}" {{ $required }}
            style="{{ $style }}">
            <option value="">Select {{ $label }}...</option>
            @foreach ($forLoopCollection as $key => $item)
                <option value="{{ $item->$itemIdForeach }}"  @if ($item->$itemIdForeach == $selectedValue) selected @endif>
                    {{ $item->$itemTitleForeach }}
                </option>
            @endforeach
        </select>
    </div>
@endif
